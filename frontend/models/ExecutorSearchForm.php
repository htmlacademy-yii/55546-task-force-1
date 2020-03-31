<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\Query;

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
    public $additionally = [];
    /** @var string строка с названием задачи */
    public $name;

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
            [['categories', 'additionally', 'name'], 'safe'],
        ];
    }

    /**
     * Применение фильтра для списка исполнителей
     *
     * @param Query $query ссылка на объект
     */
    public function applyFilters(Query &$query): void
    {
        if (!empty($this->name)) {
            $query->andWhere(['like', 'user.login', $this->name]);

            return;
        }

        if (!empty($this->categories)) {
            $categories = array_map(function ($item) {
                return (int)$item;
            }, $this->categories);
            $query->andWhere("(SELECT COUNT(*) FROM user_specialization us 
                WHERE us.user_id = user.id AND us.category_id IN (".implode(',',
                    $categories)."))");
        }

        if ($this->additionally) {
            if (in_array('now-free', $this->additionally)) {
                $query->andWhere("(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id AND task.status = :status) = 0",
                    [':status' => Task::STATUS_EXECUTION]);
            }
            if (in_array('now-online', $this->additionally)) {
                $query->andWhere("user.last_activity > CURRENT_TIMESTAMP() - INTERVAL 30 minute");
            }
            if (in_array('there-are-reviews', $this->additionally)) {
                $query->andWhere("(SELECT COUNT(*) FROM review WHERE review.executor_id = user.id) > 0");
            }
            if (in_array('in-favorites', $this->additionally)) {
                $query->andWhere(['user.id' => Yii::$app->user->identity->favoriteExecutorsId]);
            }
        }
    }

    /**
     * Применение сортировки к списку исполнителей
     *
     * @param Query  $query ссылка на объект
     * @param string $sort  строка с типом сортировки
     */
    public function applySort(Query &$query, string $sort): void
    {
        if (empty($sort)) {
            $query->orderBy('user.date_registration DESC');
        } elseif ($sort === User::SORT_TYPE_RATING) {
            $query->orderBy('user_data.rating DESC');
        } elseif ($sort === User::SORT_TYPE_ORDERS) {
            $query->orderBy('(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id) DESC');
        } elseif ($sort === User::SORT_TYPE_POPULARITY) {
            $query->orderBy('user_data.views DESC');
        }
    }
}
