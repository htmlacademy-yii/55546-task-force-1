<?php
namespace frontend\controllers;

use app\models\Category;
use app\models\ExecutorSearchForm;
use app\models\FavoriteExecutor;
use app\models\Review;
use app\models\Task;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * Контроллер для работы с исполнителями
 *
 * Class UsersController
 *
 * @package frontend\controllers
 */
class UsersController extends SecuredController
{
    /**
     * Действие для страницы списка исполнителей
     *
     * @param string $sort параметр сортировки для списка исполнителей
     *
     * @return string шаблон с данными страницы
     */
    public function actionIndex(string $sort = '')
    {
        $query = (new Query())->select([
            'user.id',
            'user.login',
            'user.last_activity',
            'user_data.avatar',
            'user_data.rating',
            'user_data.description',
            'CONCAT("[",GROUP_CONCAT(JSON_OBJECT("title", category.title, "id", category.id) SEPARATOR ","),"]") as specializations',
            '(SELECT COUNT(*) FROM review WHERE review.executor_id = user.id) as reviews_count',
            '(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id || task.author_id = user.id) as tasks_count',
        ])->from('user')
            ->where(['user.role' => User::ROLE_EXECUTOR, 'user_settings.is_hidden_profile' => false])
            ->leftJoin('user_data', 'user.id = user_data.user_id')
            ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
            ->leftJoin('category', 'user_specialization.category_id = category.id')
            ->leftJoin('user_settings', 'user.id = user_settings.user_id');

        $model = new ExecutorSearchForm();
        if(Yii::$app->request->get('ExecutorSearchForm') && $model->load(Yii::$app->request->get())) {
            $model->applyFilters($query);
        }
        $model->applySort($query, $sort);

        $provider = new ActiveDataProvider([
            'query' => $query->groupBy([
                'user.id',
                'user_data.rating',
                'user_data.avatar',
                'user_data.description',
                'user_data.views',
            ]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $provider,
            'categories' => Category::getCategoriesArray(),
        ]);
    }

    /**
     * Действие для страницы профиля исполнителя
     *
     * @param int $id идентификатор исполнителя
     *
     * @return string шаблон с данными страницы
     * @throws NotFoundHttpException ошибка при попытке найти несуществующего исполнителя
     */
    public function actionView(int $id)
    {
        $user = User::findOne($id);
        if(!$user || $user->role !== User::ROLE_EXECUTOR) {
            throw new NotFoundHttpException("Исполнитель не найден!");
        }

        $user->userData->updateCounters(['views' => 1]);
        return $this->render('view', [
            'user' => $user,
            'reviewsCount' => Review::find()->where(['executor_id' => $user->id])->count(),
            'completedTasksCount' => Task::find()->where(['executor_id' => $user->id])
                ->andWhere(["!=", 'status', Task::STATUS_EXECUTION])
                ->count(),
            'isCustomer' => Task::find()->where([
                'status' => Task::STATUS_EXECUTION,
                'executor_id' => $user->id,
                'author_id' => Yii::$app->user->identity->id,
            ])->exists(),
            'isFavorite' => FavoriteExecutor::find()->where([
                'client_id' => Yii::$app->user->identity->id,
                'executor_id' => $user->id
            ])->exists(),
        ]);
    }

    /**
     * Действие для добавления исполнителя в избранное
     *
     * @param int $userId идентификатор исполнитлея
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSelectFavorite(int $userId)
    {
        $params = [
            'client_id' => Yii::$app->user->identity->id,
            'executor_id' => $userId
        ];

        $data = FavoriteExecutor::findOne($params);
        if($data) {
            $data->delete();
        } else {
            (new FavoriteExecutor($params))->save();
        }

        return $this->redirect(User::getUserUrl($userId));
    }
}
