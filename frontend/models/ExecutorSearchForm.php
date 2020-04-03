<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\Query;
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
     * @param string $sort
     *
     * @return string
     */
    public function getSortQuery(string $sort): string
    {
        return ArrayHelper::getValue([
            User::SORT_TYPE_RATING => 'user_data.rating DESC',
            User::SORT_TYPE_ORDERS => '(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id) DESC',
            User::SORT_TYPE_POPULARITY => 'user_data.views DESC',
        ], $sort, 'user.date_registration DESC');
    }
}
