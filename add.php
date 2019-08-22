<?
include("components/auth.php");

function add_lead() {
    $leads['add'] = array(
        array(
            'name' => $_POST['lead'],
            'sale' => $_POST['sale'],
        ),
    );

    $subdomain = 'xhuman80';
    #Формируем ссылку для запроса
    $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/leads';
    /* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о
    работе с этой
    библиотекой Вы можете прочитать в мануале. */
    $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($leads));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    /* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code = (int) $code;
    $errors = array(
        301 => 'Moved permanently',
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    );
    try
    {
        #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
        if ($code != 200 && $code != 204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
        }
    } catch (Exception $E) {
        die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
    }
}

function add_contact() {
    $contacts['add'] = array(
        array(
            'name' => $_POST['name'],
            'leads_id' => $_POST['leads_id']
        ), 
    );
    
    $subdomain = 'xhuman80';
    /* Теперь подготовим данные, необходимые для запроса к серверу */
    #Формируем ссылку для запроса
    $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts';
    /* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о
    работе с этой
    библиотекой Вы можете прочитать в мануале. */
    $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($contacts));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    /* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code = (int) $code;
    $errors = array(
        301 => 'Moved permanently',
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    );
    try
    {
        #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
        if ($code != 200 && $code != 204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
        }
    } catch (Exception $E) {
        die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <style>
        input[type=text] {
            margin-left:10px;
        }
    </style>
    <form method="POST">
         <label for="lead">Введите название сделки</label><input type="text" name="lead" id="lead"/><br><br>
         <label for="sale">Введите бюджет сделки</label><input type="text" name="sale" id="sale"/><br><br>
        <input type="submit" value="Добавить сделку" name="button_lead"/>
    </form>
    <? if (isset($_POST['button_lead'])) {
        add_lead();
    }
    ?><br>
    <form method="POST">
        <label for="name">Введите имя контакта</label><input type="text" name="name" id="name"/><br><br>
        <label for="leads_id">Введите id сделки, к которой хотите привязать контакт</label><input type="text" name="leads_id" id="leads_id"/><br><br>
        <input type="submit" value="Добавить контакт" name="button_contact"/>
    </form>
    <? if (isset($_POST['button_contact'])) {
        add_contact();
    }
    ?>
</body>
</html>
