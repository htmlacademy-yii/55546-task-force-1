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
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class UsersController extends SecuredController
{
    public function actionIndex($sort = null)
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
        ])
            ->from('user')
            ->where(['user.role' => User::ROLE_EXECUTOR, 'user_settings.is_hidden_profile' => false])
            ->leftJoin('user_data', 'user.id = user_data.user_id')
            ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
            ->leftJoin('category', 'user_specialization.category_id = category.id')
            ->leftJoin('user_settings', 'user.id = user_settings.user_id');

        $model = new ExecutorSearchForm();
        if(Yii::$app->request->get('ExecutorSearchForm') && $model->load(Yii::$app->request->get())) {
            $model->applyFilters($query);
        }

        if(!$sort) {
            $query->orderBy('user.date_registration DESC');
        } elseif ($sort === User::SORT_TYPE_RATING) {
            $query->orderBy('user_data.rating DESC');
        } elseif ($sort === User::SORT_TYPE_ORDERS) {
            $query->orderBy('(SELECT COUNT(*) FROM task WHERE task.executor_id = user.id) DESC');
        } elseif ($sort === User::SORT_TYPE_POPULARITY) {
            $query->orderBy('user_data.views DESC');
        }

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
//            'sort' => [
//                'attributes' => [
//                    'rating' => [
//                        'asc' => ['rating' => SORT_ASC],
//                        'desc' => ['rating' => SORT_DESC],
//                        'default' => SORT_ASC,
//                        'label' => 'Рейтинг',
//                    ]
//                ],
//                'defaultOrder' => [
//                    'rating' => SORT_DESC
//                ]
//            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $provider,
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
        ]);
    }

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

    public function actionSelectFavorite($userId)
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
