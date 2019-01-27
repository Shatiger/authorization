<?php

function date_to_string($date) {
    if(strtotime($date) == 0) {
        return 'не определена';
    }
    $regday = date('d #m# Y', strtotime($date));
    $regday = str_replace("#01#", "января", $regday);
    $regday = str_replace("#02#", "февраля", $regday);
    $regday = str_replace("#03#", "марта", $regday);
    $regday = str_replace("#04#", "апреля", $regday);
    $regday = str_replace("#05#", "мая", $regday);
    $regday = str_replace("#06#", "июня", $regday);
    $regday = str_replace("#07#", "июля", $regday);
    $regday = str_replace("#08#", "августа", $regday);
    $regday = str_replace("#09#", "сентября", $regday);
    $regday = str_replace("#10#", "октября", $regday);
    $regday = str_replace("#11#", "ноября", $regday);
    $regday = str_replace("#12#", "декабря", $regday);
    return $regday;
}

session_start();

if(empty($_SESSION['login_session'])) {
    header("Location: login.php");
} else {
    require("./config/db.php");
    $login = $_SESSION['login_session'];
    $sql = "SELECT * FROM users WHERE login='$login'";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    $user['regday'] = date_to_string($user['regday']);
    $user['bday'] = date_to_string($uset['bday']);
    switch ($user['sex']) {
        case 1:
            $user['sex'] = 'женский';
            break;
        case 2:
            $user['sex'] = 'мужской';
            break;
        case 0:
        default:
            $user['sex'] = 'не определен';
            break;
    }
    if(empty($user['name'])) {
        $user['name'] = 'не определено';
    }
    if(empty($user['city'])) {
        $user['city'] = 'не определено';
    }
    if(empty($user['info'])) {
        $user['info'] = 'отсутствует';
    }
    if(empty($user['photo'])) {
        $user['photo'] = '/assets/img/default.png';
    }
}

?>
<!DOCTYPE html>
<html lang="ru"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Профиль</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/font-awesome.css">
</head>
<body>

<div _ngcontent-c1="" class="system">
    <app-header _ngcontent-c1="" _nghost-c2=""><header _ngcontent-c2="" class="header">
            <div _ngcontent-c2="" class="header__logo">СООБЩНИК</div>
            <ul _ngcontent-c2="" class="header__links">
                <li _ngcontent-c2="" class="header__link_item">
                    <a _ngcontent-c2="" href="#">Миссии</a>
                </li>
                <li _ngcontent-c2="" class="header__link_item">
                    <a _ngcontent-c2="" href="#">А давай!</a>
                </li>
                <li _ngcontent-c2="" class="header__link_item">
                    <a _ngcontent-c2="">Вопросы и ответы</a>
                </li>
            </ul>
            <div _ngcontent-c2="" class="header__controls">
                <i _ngcontent-c2="" class="fa fa-star header__control-icon" tabindex="0"></i>
                <i _ngcontent-c2="" class="fa fa-music header__control-icon"></i>
                <i _ngcontent-c2="" class="fa fa-cog header__control-icon"></i>
                <a href="/logout.php"><i _ngcontent-c2="" class="fa fa-sign-out header__control-icon"></i></a>
            </div>
        </header>
    </app-header>
    <app-leftbar _ngcontent-c1="" _nghost-c3=""><div _ngcontent-c3="" class="leftbar">
            <div _ngcontent-c3="" class="leftbar__avatar-block">
                <div _ngcontent-c3="" class="leftbar__avatar">
                    <img _ngcontent-c3="" class="leftbar__avatar-img" src="<?php echo $user['photo']?>">
                </div>
                <div _ngcontent-c3="" class="leftbar__avatar-stars">
                    <i _ngcontent-c3="" class="fa fa-star leftbar__avatar-star leftbar__avatar-star--filled"></i>
                    <i _ngcontent-c3="" class="fa fa-star leftbar__avatar-star leftbar__avatar-star--filled"></i>
                    <i _ngcontent-c3="" class="fa fa-star leftbar__avatar-star leftbar__avatar-star--filled"></i>
                    <i _ngcontent-c3="" class="fa fa-star leftbar__avatar-star"></i>
                    <i _ngcontent-c3="" class="fa fa-star leftbar__avatar-star"></i>
                </div>
            </div>

            <app-leftbar-menu _ngcontent-c3="" _nghost-c5=""><ul _ngcontent-c5="" class="leftnav">
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="/index.php">Профиль</a>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="#">Дневник</a>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="#">Мои миссии</a>
                        <ul _ngcontent-c5="" class="leftnav__submenu">
                            <li _ngcontent-c5="" class="leftnav__submenu-item"><a _ngcontent-c5="" href="#">Текущие миссии</a></li>
                            <li _ngcontent-c5="" class="leftnav__submenu-item"><a _ngcontent-c5="" href="#">Выполненные миссии</a></li>
                            <li _ngcontent-c5="" class="leftnav__submenu-item"><a _ngcontent-c5="" href="#">Прерванные миссии</a></li>
                        </ul>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="#">Сообщники</a>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link leftnav__link--message">
                        <a _ngcontent-c5="" href="#">Чаты</a>
                        <span _ngcontent-c5="" class="leftnav__link-count">50</span>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="#">Фотоотчеты</a>
                    </li>
                    <li _ngcontent-c5="" class="leftnav__link">
                        <a _ngcontent-c5="" href="#">Единомышленники</a>
                    </li>
                </ul>
            </app-leftbar-menu>


            <div _ngcontent-c3="" class="leftbar__footer">
                <p _ngcontent-c3="">Поделиться в соцсетях:</p>
                <div _ngcontent-c3="" class="leftbar__footer-social">
                    <i _ngcontent-c3=""></i>
                    <i _ngcontent-c3=""></i>
                    <i _ngcontent-c3=""></i>
                    <i _ngcontent-c3=""></i>
                    <i _ngcontent-c3=""></i>
                </div>
            </div>
        </div>
    </app-leftbar>

    <div _ngcontent-c1="" class="system__content system__content--has-rightbar">

        <router-outlet _ngcontent-c1=""></router-outlet><app-profile _nghost-c15=""><div _ngcontent-c15="" class="page__title">
                Профиль
            </div>


            <div _ngcontent-c15="" class="page__block p">
                <div _ngcontent-c15="" class="p__t-icons">
                    <a _ngcontent-c15="" href="#"><i _ngcontent-c15="" class="fa fa-cog"></i></a>
                </div>
                <div _ngcontent-c15="" class="p__left">
                    <img _ngcontent-c15="" class="p__img" src="<?php echo $user['photo']?>">
                    <div _ngcontent-c15="" class="p__b-icons">
                        <i _ngcontent-c15="" class="fa fa-envelope"></i>
                        <i _ngcontent-c15="" class="fa fa-phone"></i>
                        <i _ngcontent-c15="" class="fa fa-video-camera"></i>
                        <i _ngcontent-c15="" class="fa fa-paperclip"></i>
                    </div>
                    <div _ngcontent-c15="" class="p__missions">
                        <strong _ngcontent-c15="">Миссии пользователя:</strong>
                        <a _ngcontent-c15="" class="text-primary" href="#">Бросить курить</a>,
                        <a _ngcontent-c15="" class="text-primary" href="#">Контроль веса</a>,
                        <a _ngcontent-c15="" class="text-primary" href="#">Читать книги</a>,
                        <a _ngcontent-c15="" class="text-primary" href="#">Благотворительность</a>,
                        <a _ngcontent-c15="" class="text-primary" href="#">Управление эмоциями</a>
                    </div>
                </div>
                <div _ngcontent-c15="" class="p__right">
                    <div _ngcontent-c15="" class="p__name"><?php echo $user['login']?></div>
                    <div _ngcontent-c15="" class="p__stars">
                        <i _ngcontent-c15="" class="fa fa-star p__star p__star--filled"></i>
                        <i _ngcontent-c15="" class="fa fa-star p__star p__star--filled"></i>
                        <i _ngcontent-c15="" class="fa fa-star p__star"></i>
                        <i _ngcontent-c15="" class="fa fa-star p__star"></i>
                        <i _ngcontent-c15="" class="fa fa-star p__star"></i>
                    </div>
                    <div _ngcontent-c15="" class="p__status">
                    </div>
                    <ul _ngcontent-c15="" class="p__info">
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Имя:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['name']?></div>
                        </li>
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Пол:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['sex']?></div>
                        </li>
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Дата рождения:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['bday']?></div>
                        </li>
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Дата регистрации:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['regday']?></div>
                        </li>
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Город:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['city']?></div>
                        </li>
                        <li _ngcontent-c15="" class="p__row">
                            <div _ngcontent-c15="" class="p__label">Информация:</div>
                            <div _ngcontent-c15="" class="p__value"><?php echo $user['info']?></div>
                        </li>
                    </ul>
                </div>
            </div>


            <div _ngcontent-c15="" class="page__subtitle page__subtitle--has-link">
                <span _ngcontent-c15=""><a href="#">Все заметки</a></span>
                <div>
                    <a _ngcontent-c15="" class="text-primary page__subtitle-link" id="my_notes" data-user="4">Мои заметки</a>
                    <a _ngcontent-c15="" class="text-primary page__subtitle-link" id="add_note">Новая заметка</a>
                    <input type="text" name="search">
                    <i _ngcontent-c6="" class="fa fa-search"></i>
                </div>
            </div>
            <div id="comments">
                <div id="form_add_note">
                    <form name="form_add_note" method="post" action="/user/main#">
                        <textarea cols="80" rows="3" name="text" id="text" placeholder="Комментарий"></textarea>
                        <p>Прикрепить файл: <input id="file" type="file" name="file"></p>
                        <p>Требования к файлу: разрешается только jpg, gif, png, mp3, максимальный размер 10 МБ.</p>
                        <input type="hidden" value="0" name="id" id="id">
                        <input type="hidden" value="4" name="article_id" id="article_id">
                        <input type="hidden" value="0" name="parent_id" id="parent_id">
                        <div>
                            <input type="button" value="Сохранить" class="btn save">
                            <input type="button" value="Отменить" class="btn cancel">
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </app-profile>

    </div>

    <!----><app-rightbar _ngcontent-c1="" _nghost-c4=""><div _ngcontent-c4="" class="rb">
            <app-useful-links _ngcontent-c4="" _nghost-c8=""><div _ngcontent-c8="" class="ufl">
                    <div _ngcontent-c8="" class="ufl__header">Полезные ссылки</div>
                    <div _ngcontent-c8="" class="ufl__separator"></div>
                    <ul _ngcontent-c8="" class="ufl__list">
                        <li _ngcontent-c8="" class="ufl__list-item"><a _ngcontent-c8="">useful very mach</a></li>
                        <li _ngcontent-c8="" class="ufl__list-item"><a _ngcontent-c8="">useful very mach</a></li>
                        <li _ngcontent-c8="" class="ufl__list-item"><a _ngcontent-c8="">useful very mach</a></li>
                        <li _ngcontent-c8="" class="ufl__list-item"><a _ngcontent-c8="">useful very mach</a></li>
                    </ul>
                </div>
            </app-useful-links>
            <app-interesting-pages _ngcontent-c4="" _nghost-c9=""><div _ngcontent-c9="" class="inps">
                    <div _ngcontent-c9="" class="inps__header">Интересные страницы</div>
                    <div _ngcontent-c9="" class="inps__separator"></div>
                    <ul _ngcontent-c9="" class="inps__list">
                        <li _ngcontent-c9="" class="inps__list-item">
                            <a _ngcontent-c9="" class="inps__link">
                                <img _ngcontent-c9="" class="inps__img" src="/assets/img/572978_1000_1_340.jpg">
                                <div _ngcontent-c9="" class="inps__info">
                                    <h5 _ngcontent-c9="" class="inps__title">Последняя сигарета</h5>
                                    <span _ngcontent-c9="" class="inps__members">153 участника</span>
                                </div>
                            </a>
                        </li>
                        <li _ngcontent-c9="" class="inps__list-item">
                            <a _ngcontent-c9="" class="inps__link">
                                <img _ngcontent-c9="" class="inps__img" src="/assets/img/572978_1000_1_340.jpg">
                                <div _ngcontent-c9="" class="inps__info">
                                    <h5 _ngcontent-c9="" class="inps__title">Последняя сигарета</h5>
                                    <span _ngcontent-c9="" class="inps__members">153 участника</span>
                                </div>
                            </a>
                        </li>
                        <li _ngcontent-c9="" class="inps__list-item">
                            <a _ngcontent-c9="" class="inps__link">
                                <img _ngcontent-c9="" class="inps__img" src="/assets/img/572978_1000_1_340.jpg">
                                <div _ngcontent-c9="" class="inps__info">
                                    <h5 _ngcontent-c9="" class="inps__title">Последняя сигарета</h5>
                                    <span _ngcontent-c9="" class="inps__members">153 участника</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <button _ngcontent-c9="" class="btn inps__create btn-block btn-sm">Создать страницу</button>
                </div>
            </app-interesting-pages>
            <app-missions-photoreport _ngcontent-c4="" _nghost-c10=""><div _ngcontent-c10="" class="mpr">
                    <div _ngcontent-c10="" class="mpr__header">
                        Фотоотсчеты миссий
                    </div>

                    <div _ngcontent-c10="" class="mpr__separator"></div>

                    <ul _ngcontent-c10="" class="mpr__list">
                        <li _ngcontent-c10="" class="mpr__list-item">
                            <img _ngcontent-c10="" class="mpr__img" src="/assets/img/572978_1000_1_340.jpg">
                            <h4 _ngcontent-c10="" class="mpr__title">
                                Контроль веса
                            </h4>

                            <span _ngcontent-c10="" class="mpr__records">
        12 записей
      </span>
                        </li>
                        <li _ngcontent-c10="" class="mpr__list-item">
                            <img _ngcontent-c10="" class="mpr__img" src="/assets/img/572978_1000_1_340.jpg">
                            <h4 _ngcontent-c10="" class="mpr__title">
                                Контроль веса
                            </h4>

                            <span _ngcontent-c10="" class="mpr__records">
        12 записей
      </span>
                        </li>
                    </ul>
                </div>
            </app-missions-photoreport>
            <app-dont-giveup _ngcontent-c4="" _nghost-c11=""><div _ngcontent-c11="" class="dgu">
                    <h2 _ngcontent-c11="" class="dgu__title">
                        Не сдавайся!
                    </h2>

                    <div _ngcontent-c11="" class="dgu__separator"></div>

                    <p _ngcontent-c11="" class="regular">Истории твоего успеха помогают позитивно мыслить, менять жизнь к лучшему</p>
                    <p _ngcontent-c11="" class="regular">Сила и смелость заразительны, когда ты достигаешь успеха, ты помогаешь себе и другим.</p>
                </div>
            </app-dont-giveup>
            <app-adavai-right _ngcontent-c4="" _nghost-c12=""><div _ngcontent-c12="" class="adr">

                    <div _ngcontent-c12="" class="adr__switch">
                        <button _ngcontent-c12="" class="adr__btn">Участник</button>
                        <button _ngcontent-c12="" class="adr__btn adr__btn--active">Организатор</button>
                    </div>


                    <!---->


                    <!----><div _ngcontent-c12="">
                        <h2 _ngcontent-c12="" class="adr__title">
                            Создать адавайку
                        </h2>


                        <div _ngcontent-c12="" class="form-group">
                            <label _ngcontent-c12="" for="theme">Тема:</label>
                            <select _ngcontent-c12="" class="form-control" id="theme">
                                <option _ngcontent-c12="" value="wq">Satisfaction</option>
                            </select>
                        </div>

                        <div _ngcontent-c12="" class="form-group">
                            <label _ngcontent-c12="" for="city">Город:</label>
                            <input _ngcontent-c12="" class="form-control" id="city">
                        </div>

                        <div _ngcontent-c12="" class="form-group">
                            <label _ngcontent-c12="" for="datetime">Дата и время:</label>
                            <div _ngcontent-c12="" class="form-control-icon form-control-icon--right">
                                <input _ngcontent-c12="" class="form-control" id="datetime">
                                <i _ngcontent-c12="" class="fa fa-calendar"></i>
                            </div>
                        </div>


                        <div _ngcontent-c12="" class="form-group">
                            <label _ngcontent-c12="" for="members">Город:</label>
                            <input _ngcontent-c12="" class="form-control" id="members">
                        </div>

                        <div _ngcontent-c12="" class="form-group adr__checkbox">
                            <span _ngcontent-c12="">Возрастные ограничения</span>
                            <label _ngcontent-c12="" class="checkbox">
                                <input _ngcontent-c12="" type="checkbox">
                                <i _ngcontent-c12=""></i>
                            </label>
                        </div>

                        <div _ngcontent-c12="" class="form-group">
                            <label _ngcontent-c12="" for="description">Описание:</label>
                            <textarea _ngcontent-c12="" class="adr__textarea form-control" id="description"></textarea>
                        </div>

                        <button _ngcontent-c12="" class="btn btn-block btn-sm" disabled="">Создать Адавайку</button>

                    </div>

                </div>
            </app-adavai-right>
            <app-quick-chats _ngcontent-c4="" _nghost-c13=""><!----><div _ngcontent-c13="" class="qc">
                    <h2 _ngcontent-c13="" class="qc__title">
                        Чаты миссий
                    </h2>

                    <div _ngcontent-c13="" class="qc__separator"></div>

                    <!----><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
        153 участника
      </span>
                        </div>
                    </div><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
        153 участника
      </span>
                        </div>
                    </div>
                </div><div _ngcontent-c13="" class="qc">
                    <h2 _ngcontent-c13="" class="qc__title">
                        Чаты миссий
                    </h2>

                    <div _ngcontent-c13="" class="qc__separator"></div>

                    <!----><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
        153 участника
      </span>
                        </div>
                    </div><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
        153 участника
      </span>
                        </div>
                    </div>
                </div><div _ngcontent-c13="" class="qc">
                    <h2 _ngcontent-c13="" class="qc__title">
                        Чаты миссий
                    </h2>

                    <div _ngcontent-c13="" class="qc__separator"></div>

                    <!----><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
        153 участника
      </span>
                        </div>
                    </div><div _ngcontent-c13="" class="qc__item">
                        <img _ngcontent-c13="" class="qc__img" src="/assets/img/maxresdefault.jpg">
                        <div _ngcontent-c13="">
                            <h4 _ngcontent-c13="" class="qc__name">
                                Бросить курить
                            </h4>
                            <span _ngcontent-c13="" class="qc__members">
                            153 участника
                            </span>
                        </div>
                    </div>
                </div>
            </app-quick-chats>
        </div>
    </app-rightbar>
</div>

<script type="text/javascript" src="/assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/assets/js/functions.js"></script>
<script type="text/javascript" src="/assets/js/validator.js"></script>

</body>
</html>
