<?php

namespace frontend\models\User;

class User
{
    private $id;
    private $login;
    private $email;
    private $password;
    private $dateRegistration;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getReviews(): array
    {
        return [];
        /*
            todo: получить список отзывов по данному пользователю
        */
    }

    public function getDataProfile(): array
    {
        return [];
        /*
            todo: получить данные для профиля пользователя
        */
    }

    public function getDataSettings(): array
    {
        return [];
        /*
            todo: получить данные настроек пользователя
        */
    }

    public function getChat(): array
    {
        return [];
        /*
            todo: получить чат активный для данного пользователя
        */
    }

    public function getTasks(): array
    {
        return [];
        /*
            todo: получить список задач созданных данным пользователем
        */
    }

    public static function getDataExecutorsList(): array
    {
        return [];
        /*
            todo: получить данные для списка исполнителей
        */
    }
}
