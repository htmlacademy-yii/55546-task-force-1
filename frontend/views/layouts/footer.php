<?php

use yii\helpers\Html;

?>
<footer class="page-footer">
    <div class="main-container page-footer__container">
        <div class="page-footer__info">
            <p class="page-footer__info-copyright">
                © 2019, ООО «ТаскФорс»
                Все права защищены
            </p>
            <p class="page-footer__info-use">
                «TaskForce» — это сервис для поиска исполнителей на разовые
                задачи.
                mail@taskforce.com
            </p>
        </div>
        <div class="page-footer__links">
            <ul class="links__list">
                <li class="links__item">
                    <?= Html::a('Задания', '/tasks'); ?>
                </li>
                <li class="links__item">
                    <?= Html::a('Мой профиль',
                        '/users/view/'.($user ? $user->id : '')); ?>
                </li>
                <li class="links__item">
                    <?= Html::a('Исполнители', '/users'); ?>
                </li>
                <li class="links__item">
                    <?= Html::a('Регистрация', '/site/signup'); ?>
                </li>
                <li class="links__item">
                    <?= Html::a('Создать задание', '/tasks/create'); ?>
                </li>
                <li class="links__item">
                    <?= Html::a('Справка', '/'); ?>
                </li>
            </ul>
        </div>
        <div class="page-footer__copyright">
            <?= Html::a('<img class="copyright-logo"
                         src="/img/academy-logo.png"
                         width="185" height="63"
                         alt="Логотип HTML Academy">',
                'https://htmlacademy.ru') ?>
        </div>
    </div>
</footer>
