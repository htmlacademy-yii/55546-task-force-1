<?php
namespace frontend\controllers;

use app\models\Category;
use app\models\ExecutorSearchForm;
use app\models\FavoriteExecutor;
use common\models\User;
use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
        $model = new ExecutorSearchForm();
//        $query = (new Query())
//            ->select("
//                user.*,
//                user.id as uid,
//                user_data.*,
//                category.*,
//                (JSON_OBJECT('title', category.title, 'code', category.code)) as res")
//            ->from('user')
//            ->where(['user.role' => User::ROLE_EXECUTOR])
//            ->leftJoin('user_data', 'user.id = user_data.user_id')
//            ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
//            ->leftJoin('category', 'user_specialization.category_id = category.id')
//            ->groupBy('uid');

        $query = (new Query())
            ->select('
                user.id,
                user.login,
                user.last_activity,
                user_data.avatar,
                user_data.rating,
                user_data.description,
                category.title,
                category.code
                ')
            ->from('user')
            ->where(['user.role' => User::ROLE_EXECUTOR])
            ->leftJoin('user_data', 'user.id = user_data.user_id')
            ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
            ->leftJoin('category', 'user_specialization.category_id = category.id');
//            ->groupBy('user.id');
        $provider = new ActiveDataProvider([
            'query' => $query,
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



//        $executors = User::find()->joinWith(['userData'])->where(['role' => User::ROLE_EXECUTOR]);
//        $model = new ExecutorSearchForm();
//        if(Yii::$app->request->isPost) {
////            DebugHelper::debug(Yii::$app->request->post());
//            $model->load(Yii::$app->request->post());
//            $model->applyFilters($executors);
//        }
//
//        $provider = new ActiveDataProvider([
//            'query' => $executors,
//            'pagination' => [
//                'pageSize' => 5,
//            ],
//            'sort' => [
//                'attributes' => [
//                    'rating' => [
//                        'asc' => ['userData.rating' => SORT_ASC],
//                        'desc' => ['userData.rating' => SORT_DESC],
//                        'default' => SORT_DESC,
//                        'label' => 'Рейтинг',
//                    ]
//                ],
//                'defaultOrder' => [
//                    'userData.rating' => SORT_DESC
//                ]
//            ],
//        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $provider,
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
        ]);
    }

    public function actionView(int $id)
    {
        $user = User::findOne($id);
        if(!$user) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        return $this->render('view', [
            'user' => $user,
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

        return $this->redirect("/users/view/{$userId}");
    }
}
