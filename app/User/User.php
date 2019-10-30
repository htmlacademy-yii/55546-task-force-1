<?php

namespace app\User;

class User
{
    private $id = null;
    private $login = null;
    private $email = null;
    private $password = null;
    private $date_registration = null;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->login = $data['id'] ?? null;
        $this->email = $data['id'] ?? null;
        $this->password = $data['id'] ?? null;
        $this->date_registration = $data['id'] ?? null;
    }

    public function getReviews()
    {
        return [];
        /*
            todo: получить список отзывов по данному пользователю
        */
    }

    public function getDataProfile()
    {
        return [];
        /*
            todo: получить данные для профиля пользователя
        */
    }

    public function getDataSettings()
    {
        return [];
        /*
            todo: получить данные настроек пользователя
        */
    }

    public function getChat()
    {
        return [];
        /*
            todo: получить чат активный для данного пользователя
        */
    }

    public function getTasks()
    {
        return [];
        /*
            todo: получить список задач созданных данным пользователем
        */
    }

    public static function getDataExecutorsList()
    {
        return [];
        /*
            todo: получить данные для списка исполнителей
        */
    }

}
