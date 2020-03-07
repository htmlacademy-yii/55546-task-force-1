<?php
namespace frontend\controllers;

use app\models\Category;
use app\models\ExecutorSearchForm;
use app\models\FavoriteExecutor;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
        $model = new ExecutorSearchForm();

        return $this->render('index', [
            'model' => $model,
            'executors' => User::findAll(['role' => User::ROLE_EXECUTOR]),
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
