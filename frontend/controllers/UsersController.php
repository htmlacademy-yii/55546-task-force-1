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
        $query = (new Query())->select([
            'user.id',
            'user.login',
            'user.last_activity',
            'user_data.avatar',
            'user_data.rating',
            'user_data.description',
            'CONCAT("[",GROUP_CONCAT(JSON_OBJECT("title", category.title, "id", category.id) SEPARATOR ","),"]") as specializations'
        ])
            ->from('user')
            ->where(['user.role' => User::ROLE_EXECUTOR, 'user_settings.is_hidden_profile' => false])
            ->leftJoin('user_data', 'user.id = user_data.user_id')
            ->leftJoin('user_specialization', 'user.id = user_specialization.user_id')
            ->leftJoin('category', 'user_specialization.category_id = category.id')
            ->leftJoin('user_settings', 'user.id = user_settings.user_id')
            ->groupBy([
                'user.id',
                'user_data.rating',
                'user_data.avatar',
                'user_data.description',
            ]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => [
                    'rating' => [
                        'asc' => ['rating' => SORT_ASC],
                        'desc' => ['rating' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label' => 'Рейтинг',
                    ]
                ],
                'defaultOrder' => [
                    'rating' => SORT_DESC
                ]
            ],
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


    public function actionTest()
    {
        return $this->render('_test');
    }
}
