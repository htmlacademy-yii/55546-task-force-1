<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Класс для работы с моделью формы фильтрации заданий
 *
 * Class TasksFilter
 *
 * @package app\models
 */
class TasksFilter extends Model
{
    /** @var array массив со списком категорий */
    public $categories = [];
    /** @var string строка с флагом - без исполнителя */
    public $isNoExecutor;
    /** @var string строка с флагом - удалённая работа */
    public $isTelework;
    /** @var string строка с именем задания */
    public $title;
    /** @var string строка с периодом времени */
    public $time;

    /** @var string строка с периодом за всё время */
    private const PERIOD_ALL = 'all';
    /** @var string строка с периодом за день */
    private const PERIOD_DAY = 'day';
    /** @var string строка с периодом за неделю */
    private const PERIOD_WEEK = 'week';
    /** @var string строка с периодом за месяц */
    private const PERIOD_MONTH = 'month';

    /** @var array массив со списком всех доступных периодов */
    public const PERIOD_LIST
        = [
            self::PERIOD_ALL => 'За всё время',
            self::PERIOD_DAY => 'За день',
            self::PERIOD_WEEK => 'За неделю',
            self::PERIOD_MONTH => 'За месяц',
        ];

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['isNoExecutor', 'isTelework'], 'boolean'],
            [
                'categories',
                'filter',
                'filter' => function ($categories) {
                    return $categories ? array_map(function ($item) {
                        return (int)$item;
                    }, $categories) : [];
                },
            ],
            [
                'categories',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'allowArray' => true,
            ],
            [
                'time',
                'in',
                'range' => [
                    self::PERIOD_ALL,
                    self::PERIOD_DAY,
                    self::PERIOD_WEEK,
                    self::PERIOD_MONTH,
                ],
            ],
            ['title', 'string', 'length' => [0, 255]],
        ];
    }

    /**
     * Указание списка имён для атрибутов формы
     *
     * @return array список имён для атрибутов формы
     */
    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категори',
            'isNoExecutor' => 'Без исполнителя',
            'isTelework' => 'Удаленная работа',
            'title' => 'Поиск по названию',
            'time' => 'Период',
        ];
    }

    /**
     * Применение фильтра для списка заданий
     *
     * @param ActiveQuery $taskQuery ссылка на объект
     */
    public function applyFilters(ActiveQuery &$taskQuery): void
    {
        if (!empty($this->categories)) {
            $taskQuery->andWhere(['category_id' => $this->categories]);
        }

        if ($this->isNoExecutor) {
            $taskQuery->andWhere(['executor_id' => null]);
        }

        if (!$this->isTelework) {
            $taskQuery->andWhere([
                'city_id' => Yii::$app->session->get('city') ??
                    Yii::$app->user->identity->city_id,
            ]);
        }

        if (isset($this->title)) {
            $taskQuery->andWhere(['like', 'title', $this->title]);
        }

        if (isset($this->time) && $this->time !== self::PERIOD_ALL) {
            $taskQuery->andWhere("date_start > CURRENT_TIMESTAMP() - INTERVAL 1 $this->time");
        }
    }
}
