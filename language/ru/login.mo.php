<?php

/*
#############################################################################
#  Filename: login.mo
#  Project: SuperNova.WS
#  Website: http://www.supernova.ws
#  Description: Massive Multiplayer Online Browser Space Startegy Game
#
#  Copyright © 2009 Gorlum for Project "SuperNova.WS"
#  Copyright © 2009 MSW
#############################################################################
*/

/**
*
* @package language
* @system [Russian]
* @version 36d1
*
*/

/**
* DO NOT CHANGE
*/

if (!defined('INSIDE')) die();


$lang = array_merge($lang, array(
  'Login' => 'Логин',
  'User_name' => 'Имя:',
//  'Password' => 'Пароль:',
//  'neededpass' => 'Пароль',
  'Please_Login' => 'Пожалуйста <a href="login.php" target="_main">войдите...</a>',
  'Please_Wait' => 'Подождите',
  'Remember_me' => 'Запомнить меня',
  'Register' => 'Информация о ошибке',
  'Login_Error' => 'Ошибка',
  'PleaseWait' => 'Подождите',
  'PasswordLost' => 'Восстановить забытый пароль',
  'Login_Ok' => 'Успешное подключение, <a href="./"><blink>перенаправление...</blink></a><br><center><img src="design/images/progressbar.gif"></center>',
  'Login_FailPassword' => 'Неверное имя и/или пароль<br /><a href="login.php" target="_top">Назад</a>',
  'Login_FailUser' => 'Такого игрока не существует.<br><a href=login.php>Назад</a>',
  'log_univ' => 'Добро пожаловать в нашу Вселенную!',
  'log_reg' => 'Регистрация',
  'log_reg_main' => 'Регистрируйся сейчас!',
  'log_menu' => 'Меню',
  'log_stat_menu' => 'Статистика',
  'log_enter' => 'Войти',
  'log_news' => 'Новости сервера',
  'log_cred' => 'О сервере',
  'log_faq' => 'FAQ по игре',
  'log_forums' => 'Форум',
  'log_contacts' => 'Администрация',
  'log_desc' => '<strong>Сверхновая — это онлайновая мультиплеерная космическая браузерная стратегия.</strong> Тысячи игроков выступают одновременно против друг друга. Для игры Вам нужен лишь обычный браузер.',
  'log_toreg' => 'Зарегистрируйся сейчас!',
  'log_online' => 'Игроков Онлайн',
  'log_lastreg' => 'Новичок',
  'log_numbreg' => 'Всего аккаунтов',
  'log_welcome' => 'Добро пожаловать в',
  'vacation_mode' => 'Вы в отпуске<br> отключить режим отпуска можно через ',
  'hours' => ' часов',
  'vacations' => 'Режим отпуска',
  'log_rules' => 'Правила игры',
  'log_banned' => 'Список забаненных',
  'log_see_you' => 'Надеемся вас снова увидеть на просторах нашей Вселенной. Удачи!<br><a href="login.php">Перейти на страницу входа в игру</a>',
  'log_session_closed' => 'Сессия закрыта.',
  'registry' => 'Регистрация',
  'form' => 'Форма регистрации',
  'Undefined' => '- неопределённый -',
  'Male' => 'Мужской',
  'Female' => 'Женский',
  'Multiverse' => 'XNova',
  'E-Mail' => 'Адрес e-Mail',
  'MainPlanet' => 'Имя главной планеты',
  'GameName' => 'Имя',
  'Sex' => 'Пол',
  'accept' => 'Я согласен с правилами',
  'reg_i_agree' => 'Я ознакомился и согласен с',
  'reg_with_rules' => 'правилами игры',
  'signup' => 'Зарегистрироваться',
  'Languese' => 'Язык',
  'log_reg_text0' => 'Перед регистрацией ознакомьтесь с',
  'log_reg_text1' => 'Регистрация означает, что вы полность прочли и согласились со всеми пунктами правил. Если вы не согласны хоть с каким-то пунктом правил - пожалуйста, не регестрируйтесь.',
  'mail_title' => "Ваша регистрация на сервере %1\$s игры Сверхновая",
  'mail_welcome' => "Подтверждение регистрации для %3\$s\r\n\r\n
  Это письмо содержит Ваши регистрационные данные на сервере %1\$s игры Сверхновая\r\n
  Сохраните эти данные в безопасном месте\r\n\r\n
  Адрес сервера: %2\$s\r\n
  Ваш логин: %3\$s\r\n
  Ваш пароль: %4\$s\r\n\r\n
  Спасибо за регистрацию на нашем сервере! Желаем Вам удачи в игре!\r\n
  Администрация сервера %1\$s %2\$s\r\n\r\n
  Сервер работает на свободном движке 'Project SuperNova.WS'. Зажги свою Сверхновую http://supernova.ws/",
  'thanksforregistry' => 'Поздравляем вас с успешной регистрацией! Теперь вы можете <a href=overview.php><u>начать игру!</u></a>',
  'welcome_to_universe' => 'Добро пожаловать в OGame!!!',
  'please_click_url' => 'Для того чтобы использовать аккаунт, вы должны активировать его нажав на эту ссылку',
  'regards' => 'Удачи!',
  'error_lang' => 'Этот язык не поддерживается!<br />',
  'error_mail' => 'Неверный E-Mail !<br />',
  'error_planet' => 'Другая планета уже имеет то же название !<br />',
  'error_hplanetnum' => 'Название планеты должно быть написано ТОЛЬКО латинскими буквами !<br />',
  'error_character' => 'Неверное имя !<br />',
  'error_charalpha' => 'Вы можете использовать ТОЛЬКО латинские буквы !<br />',
  'error_password' => 'Пароль должен состоять как минимум из 4 знаков !<br />',
  'error_rgt' => 'Вы должны согласиться с правилами !<br />',
  'error_userexist' => 'Такое имя уже используется !<br />',
  'error_emailexist' => 'Такой e-mail уже используется !<br />',
  'error_sex' => 'Ошибка в выборе пола !<br />',
  'error_mailsend' => 'Ошибка в отправлении электронной почты, ваш пароль: ',
  'reg_welldone' => 'Регистрация завершена! Ваш пароль отправлен на указанный при регистрации почтовый ящик. Вот он же еще раз на всякий случай:<br>',
  'error_captcha' => 'Неверный графический код !<br/>',
  'error_v' => 'Повторить еще раз !<br />',
  'log_login_page' => 'Войти в игру',
  'log_reg_already' => 'Уже есть регистрация? Воспользутесь ссылкой ',
  'log_reg_already_lost' => 'Не помните пароль? Воспользутесь ссылкой ',
  'log_lost_header' => 'Восстановление пароля',
  'log_lost_description1' => 'Введите e-mail, на который зарегестрирован Ваш аккаунт. На него будет отправлена письмо с кодом подтверждения для сброса пароля',
  'log_lost_send_mail' => 'Отправить письмо',
  'log_lost_code' => 'Код подтверждения',
  'log_lost_description2' => 'Если у Вас есть код подтверждения - введите его ниже и нажмите кнопку "Сбросить пароль". На Ваш e-mail будет отправлено письмо с новым паролем',
  'log_lost_reset_pass' => 'Сбросить пароль',
  'log_lost_sent_code' => 'На указанный емейл отправлено письмо с дальнейшими инструкциями по сбросу пароля',
  'log_lost_sent_pass' => 'Так же на Ваш емейл отправлено письмо с новым паролем',
  'log_lost_email_title' => 'Сверхновая, Вселенная %s: Сброс пароля',
  'log_lost_email_code' => 'Кто-то (может быть Вы) запросил сброс пароля во Вселенной %4 игры Сверхновая. Если Вы не запрашивали сброс пароля - просто проигнорируйте это письмо.Для сброса пароля перейдите по адресу %1$s?confirm=%2$s или введите код подтверждения "%2$s" (БЕЗ ДВОЙНЫХ КАВЫЧЕК!) на странице %1$sЭтот код будет действителен до %3$s. После указанного для сброса пароля Вам нужно будет запросить новый код подтверждения',
  'log_lost_email_pass' => 'Вы сбросили пароль на сервере %s игры \'Сверхновая\'. В следующей строке указан Ваш новый пароль:<br />%s<br />Запомните его!',
  'log_lost_err_email' => 'Указанный емейл не зарегестрирован в базе данных. Это может означать одно из нижеперечисленного:<br>Вы ошиблись при вводе емейла. Вернитесь на предыдущую страницу и попробуйте еще раз<br>Ваш аккаунт был удален из-за неактивности. Зарегестрируйтесь заново<br>Вы пытаетесь зайти в неправильную игровую Вселенную. Внимательно проверьте название текущей Вселенной и в случае ошибки зайдите на правильную Вселенную',
  'log_lost_err_sending' => 'Ошибка отправки сообщения по указанному емейлу. Сообщите об ошибке Администратору',
  'log_lost_err_code' => 'Указанный код подтверждения не зарегестрирован в базе данных. Это может означать одно из нижеперечисленного:<br>Вы ошиблись при вводе кода подтверждения. Вернитесь на предыдущую страницу и внимательно введите код<br>Вы пытаетесь ввести код подтверждения не в той Вселенной, для которой он был сгенерирован. Внимательно проверьте название текущей Вселенной и в случае ошибки зайдите на правильную Вселенную<br>Ваш аккаунт был удален из-за неактивности. Зарегестрируйтесь заново<br>Истек срок действия кода подтверждения. Проверьте дату действия кода в письме. Если она прошла, запросите новый код подтверждения',
  'log_lost_err_admin' => 'Члены Команды сервера (модераторы, операторы, администраторы итд) не могут использовать функцию сброса пароля. Обратитесь к Администратору сервера для смены пароля',
  'log_lost_err_change' => 'Ошибка смены пароля в базе данных. Сообщите об ошибке Администратору',
));

?>
