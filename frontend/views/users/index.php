<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="#" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="/img/man-glasses.jpg" width="65" height="65"></a>
                <span>17 заданий</span>
                <span>6 отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular">Астахов Павел</a></p>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b>4.25</b>
                <p class="user__search-content">
                    Сложно сказать, почему элементы политического процесса лишь
                    добавляют фракционных разногласий и рассмотрены исключительно
                    в разрезе маркетинговых и финансовых предпосылок.
                </p>
            </div>
            <span class="new-task__time">Был на сайте 25 минут назад</span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <a href="#" class="link-regular">Ремонт</a>
            <a href="#" class="link-regular">Курьер</a>
            <a href="#" class="link-regular">Оператор ПК</a>
        </div>
    </div>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="/img/user-man2.jpg" width="65" height="65"></a>
                <span>6 заданий</span>
                <span>3 отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular">Миронов Алексей</a></p>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b>4.25</b>
                <p class="user__search-content">
                    Как принято считать, акционеры крупнейших компаний формируют глобальную
                    экономическую сеть и при этом - рассмотрены исключительно в разрезе
                    маркетинговых и финансовых предпосылок
                </p>
            </div>
            <span class="new-task__time">Был на сайте час назад</span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <a href="#" class="link-regular">Ремонт</a>
            <a href="#" class="link-regular">Курьер</a>
            <a href="#" class="link-regular">Оператор ПК</a>
        </div>
    </div>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="/img/user-man.jpg" width="65" height="65"></a>
                <span>2 заданий</span>
                <span>1 отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular">Крючков Василий</a></p>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b>4.25</b>
                <p class="user__search-content">
                    Разнообразный и богатый опыт говорит нам, что существующая теория способствует
                    подготовке и реализации форм воздействия. Безусловно, укрепление и развитие
                    внутренней структуры представляет собой интересный эксперимент
                </p>
            </div>
            <span class="new-task__time">Был на сайте минуту назад</span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <a href="#" class="link-regular">Ремонт</a>
            <a href="#" class="link-regular">Курьер</a>
            <a href="#" class="link-regular">Оператор ПК</a>
        </div>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <form class="search-task__form" name="users" method="post" action="#">
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <input class="visually-hidden checkbox__input" id="101" type="checkbox" name="" value="" checked disabled>
                <label for="101">Курьерские услуги </label>
                <input class="visually-hidden checkbox__input" id="102" type="checkbox" name="" value="" checked>
                <label  for="102">Грузоперевозки </label>
                <input class="visually-hidden checkbox__input" id="103" type="checkbox" name="" value="">
                <label  for="103">Переводы </label>
                <input class="visually-hidden checkbox__input" id="104" type="checkbox" name="" value="">
                <label  for="104">Строительство и ремонт </label>
                <input class="visually-hidden checkbox__input" id="105" type="checkbox" name="" value="">
                <label  for="105">Выгул животных </label>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>
                <label for="106">Сейчас свободен</label>
                <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>
                <label for="107">Сейчас онлайн</label>
                <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>
                <label for="108">Есть отзывы</label>
                <input class="visually-hidden checkbox__input" id="109" type="checkbox" name="" value="" checked>
                <label for="109">В избранном</label>
            </fieldset>
            <label class="search-task__name" for="110">Поиск по имени</label>
            <input class="input-middle input" id="110" type="search" name="q" placeholder="">
            <button class="button" type="submit">Искать</button>
        </form>
    </div>
</section>
