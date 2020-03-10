<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

class ExecutorSearchForm extends Model
{
    public $categories = [];
    public $additionally = [];
    public $name;

    public function attributeLabels()
    {
        return [
            'name' => 'Поиск по имени',
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'additionally', 'name'], 'safe']
        ];
    }

    public function applyFilters(Query &$query): void
    {
        if(!empty($this->name)) { // имя
            $query->andWhere(['like', 'user.login', $this->name]);
            return;
        }

        if(!empty($this->categories)) { // категории
            $query->andWhere("(SELECT COUNT(*) FROM user_specialization us 
                WHERE us.user_id = user.id AND us.category_id IN (" . implode(',', $this->categories) . "))");
        }

        if ($this->additionally) {
            if (in_array('now-free', $this->additionally)) { // сейчас свободен
                //«Сейчас свободен» — добавляет к условию фильтрации показ исполнителей, для которых сейчас нет назначенных активных заданий
                $query->andWhere("(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id AND task.status = '" . Task::STATUS_EXECUTION . "') = 0");
            }
            if (in_array('now-online', $this->additionally)) { // сейчас онлайн
                //«Сейчас онлайн» — добавляет к условию фильтрации показ исполнителей, время последней активности которых было не больше получаса назад
                $query->andWhere("user.last_activity > CURRENT_TIMESTAMP() - INTERVAL 30 minute");
            }
            if (in_array('there-are-reviews', $this->additionally)) { // есть отзывы
                //«Есть отзывы» — добавляет к условию фильтрации показ исполнителей с отзывами
                $query->andWhere("(SELECT COUNT(*) FROM review WHERE review.executor_id = user.id) > 0");
            }
            if (in_array('in-favorites', $this->additionally)) { // в избранном
                //«В избранном» — добавляет к условию фильтрации показ пользователей, которые были добавлены в избранное
                $query->andWhere(['user.id' => Yii::$app->user->identity->favoriteExecutorsId]);
            }
        }
    }
}
