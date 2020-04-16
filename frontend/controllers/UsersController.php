<?php

namespace frontend\controllers;

use app\models\Category;
use app\models\ExecutorSearchForm;
use app\models\FavoriteExecutor;
use common\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\Response;

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
    public function actionIndex(string $sort = ''): string
    {
        $query = User::find()
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

        $model = new ExecutorSearchForm();
        if (Yii::$app->request->get('ExecutorSearchForm')
            && $model->load(Yii::$app->request->get())
            && $model->validate()
        ) {
            $model->applyFilters($query);
        }

        $model->applySort($query, $sort);

        return $this->render('index', [
            'model' => $model,
            'selectedSort' => $sort,
            'additionallyList' => ExecutorSearchForm::ADDITIONALLY_LIST,
            'sortList' => User::SORT_TYPE_LIST,
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]),
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
    public function actionView(int $id): string
    {
        $user = User::findOne($id);
        if (!$user || !$user->getIsExecutor()) {
            throw new NotFoundHttpException('Исполнитель не найден!');
        }

        $user->userData->updateCounters(['views' => 1]);

        return $this->render('view', [
            'user' => $user,
            'isOwner' => $user->getIsOwnerProfile(Yii::$app->user->id),
            'isCustomer' => Yii::$app->user->identity->getIsCustomer($user->id),
            'isFavorite' => Yii::$app->user->identity->getIsFavorite($user->id),
        ]);
    }

    /**
     * Действие для добавления исполнителя в избранное
     *
     * @param int $userId идентификатор исполнитлея
     *
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSelectFavorite(int $userId): Response
    {
        FavoriteExecutor::toggleUserFavorite(Yii::$app->user->identity->id,
            $userId);

        return $this->redirect(User::getUserUrl($userId));
    }
}
