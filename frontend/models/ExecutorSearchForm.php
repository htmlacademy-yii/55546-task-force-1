<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Класс для работы с моделью формы поиска исполнителей
 *
 * Class ExecutorSearchForm
 *
 * @package app\models
 */
class ExecutorSearchForm extends Model
{
    /** @var array массив со списком категорий */
    public $categories = [];
    /** @var array массив со списком дополнительных фильтров */
    public $additionallyList = [];
    /** @var string строка с названием задачи */
    public $name;

    /** @var string строка c типом поля - сейчас свободен */
    private const ADDITIONALLY_NOW_FREE = 'now-free';
    /** @var string строка c типом поля - сейчас онлайн */
    private const ADDITIONALLY_NOW_ONLINE = 'now-online';
    /** @var string строка c типом поля - есть отзывы */
    private const ADDITIONALLY_REVIEWS = 'there-are-reviews';
    /** @var string строка c типом поля - в избранном */
    private const ADDITIONALLY_FAVORITES = 'in-favorites';

    /** @var array массиво со списком типов полей */
    public const ADDITIONALLY_LIST
        = [
            self::ADDITIONALLY_NOW_FREE => 'Сейчас свободен',
            self::ADDITIONALLY_NOW_ONLINE => 'Сейчас онлайн',
            self::ADDITIONALLY_REVIEWS => 'Есть отзывы',
            self::ADDITIONALLY_FAVORITES => 'В избранном',
        ];

    /** @var string тип сортировки по рейтингу */
    public const SORT_TYPE_RATING = 'rating';
    /** @var string тип сортировки по заказам */
    public const SORT_TYPE_ORDERS = 'orders';
    /** @var string тип сортировки по популярности */
    public const SORT_TYPE_POPULARITY = 'popularity';

    /** @var array массиво со списком типов сортировки */
    public const SORT_TYPE_LIST
        = [
            self::SORT_TYPE_RATING => 'Рейтингу',
            self::SORT_TYPE_ORDERS => 'Числу заказов',
            self::SORT_TYPE_POPULARITY => 'Популярности',
        ];

    /**
     * Проверяет, отмечена ли категория с указанным идентификатором
     *
     * @param int $categoryId идентификатор категории
     *
     * @return string строка со значеним, отмечена категория, или нет
     */
    public function getIsCheckCategory(int $categoryId): string
    {
        if (empty($this->categories)) {
            return '';
        }

        $categories = array_map(function ($item) {
            return (int)$item;
        }, explode(',', $this->categories));

        return in_array($categoryId, $categories, true) ? 'checked' : '';
    }

    /**
     * Указание списка имён для атрибутов формы
     *
     * @return array список имён для атрибутов формы
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Поиск по имени',
        ];
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [
                'categories',
                'filter',
                'filter' => function ($categories) {
                    return implode(',',
                        ($categories ? array_map(function ($item) {
                            return (int)$item;
                        }, $categories) : []));
                },
            ],
            [
                'categories',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'allowArray' => true,
                'message' => 'Одна или несколько из выбранных вами специализаций не найдена',
            ],
            [
                'additionallyList',
                'filter',
                'filter' => function ($additionallyList) {
                    return !empty($additionallyList) ? $additionallyList : [];
                },
            ],
            [
                'additionallyList',
                'in',
                'range' => [
                    self::ADDITIONALLY_NOW_FREE,
                    self::ADDITIONALLY_NOW_ONLINE,
                    self::ADDITIONALLY_REVIEWS,
                    self::ADDITIONALLY_FAVORITES,
                ],
                'allowArray' => true,
            ],
            ['name', 'string', 'length' => [0, 255]],
        ];
    }

    /**
     * Формирование базового запроса по списку исполнителей
     *
     * @param ActiveQuery $query ссылка на объект запроса
     */
    public function initQuery(ActiveQuery &$query): void
    {
        $query
            ->joinWith('userData')
            ->joinWith('userSettings')
            ->joinWith('userSpecializations')
            ->where([
                'user.role' => User::ROLE_EXECUTOR,
                'user_settings.is_hidden_profile' => false,
            ])->groupBy([
                'user.id',
                'user_data.avatar',
                'user_data.description',
                'user_data.views',
            ]);
    }

    /**
     * Применение фильтра для списка исполнителей
     *
     * @param ActiveQuery $query ссылка на объект запроса
     */
    public function applyFilters(ActiveQuery &$query): void
    {
        if (!empty($this->name)) {
            $query->andWhere(['like', 'user.login', $this->name]);

            return;
        }

        if (!empty($this->categories)) {
            $query->andWhere("(SELECT COUNT(*) FROM user_specialization us 
                WHERE us.user_id = user.id AND us.category_id IN ($this->categories))");
        }

        if (in_array(self::ADDITIONALLY_NOW_FREE, $this->additionallyList, true)
        ) {
            $query->andWhere('(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id AND task.status = :status) = 0',
                [':status' => Task::STATUS_EXECUTION]);
        }
        if (in_array(self::ADDITIONALLY_NOW_ONLINE, $this->additionallyList,
            true)
        ) {
            $query->andWhere('user.last_activity > CURRENT_TIMESTAMP() - INTERVAL 30 minute');
        }
        if (in_array(self::ADDITIONALLY_REVIEWS, $this->additionallyList,
            true)
        ) {
            $query->andWhere('(SELECT COUNT(*) FROM review WHERE review.executor_id = user.id) > 0');
        }
        if (in_array(self::ADDITIONALLY_FAVORITES, $this->additionallyList,
            true)
        ) {
            $query->andWhere(['user.id' => Yii::$app->user->identity->favoriteExecutorsId]);
        }
    }

    /**
     * Применение сортировки к списку исполнителей
     *
     * @param ActiveQuery $query ссылка на объект запроса
     * @param string      $sort  тип сортировки
     */
    public function applySort(ActiveQuery &$query, string $sort): void
    {
        $query->orderBy(ArrayHelper::getValue([
            self::SORT_TYPE_RATING => '(SELECT AVG(rating) FROM review WHERE review.executor_id = user.id) DESC',
            self::SORT_TYPE_ORDERS => '(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id) DESC',
            self::SORT_TYPE_POPULARITY => 'user_data.views DESC',
        ], $sort, 'user.date_registration DESC'));
    }
}
