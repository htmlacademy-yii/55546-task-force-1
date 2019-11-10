<?php

namespace app\User;

class User
{
    private $id;
    private $login;
    private $email;
    private $password;
    private $dateRegistration;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
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
