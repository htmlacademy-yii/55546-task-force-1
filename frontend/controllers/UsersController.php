<?php
namespace frontend\controllers;

use common\models\User;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
        return $this->render('index', [
//            'users' => User::findAll([])
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view');
    }
}
