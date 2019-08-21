<?
require_once "components/auth.php";
$subdomain = 'xhuman80'; #Наш аккаунт - поддомен
/* Формируем ссылку для запроса */
$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/leads';
/* Заметим, что в ссылке можно передавать и другие параметры, которые влияют на выходной результат (смотрите документацию
выше).
Следовательно, мы можем заменить ссылку, приведённую выше на одну из следующих, либо скомбинировать параметры так, как Вам
необходимо. */
$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/leads?filter/active/';
/* Следующий запрос вернёт список сделок, у которых есть почта 'test@mail.com' */
/* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о
работе с этой
библиотекой Вы можете прочитать в мануале. */
$curl = curl_init();
/* Устанавливаем необходимые опции для сеанса cURL */
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, $link);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
/* Вы также можете передать дополнительный HTTP-заголовок IF-MODIFIED-SINCE, в котором указывается дата в формате D, d M Y
H:i:s. При
передаче этого заголовка будут возвращены сделки, изменённые позже этой даты. */
//curl_setopt($curl, CURLOPT_HTTPHEADER, array('IF-MODIFIED-SINCE: Mon, 01 Aug 2013 07:07:23'));
/* Выполняем запрос к серверу. */
$out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
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
    /* Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке */
    if ($code != 200 && $code != 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
    }
} catch (Exception $E) {
    die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
}
/*
Данные получаем в формате JSON, поэтому, для получения читаемых данных,
нам придётся перевести ответ в формат, понятный PHP
 */
$Response = json_decode($out, true);
$Response = $Response['_embedded']['items'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<style>
body {
    padding:200px;
}
td {
    border:1px solid silver;
    padding:5px;
}
.head td {
    background-color:lightblue;
}
</style>
<table>
<tr class="head">
<td>ID</td>
<td>Название</td>
<td>Бюджет</td>
</tr>
<?foreach ($Response as $item):?>
<tr>
<td><?=$item['id']?></td>
<td><?=$item['name']?></td>
<td><?=$item['sale']?> рублей</td>
</tr>
<?endforeach;?>
</table>
</body>
</html>
