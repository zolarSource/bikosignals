<?


$pipeline_id = '22329643'; //id ответственного по сделке, контакту, компании
$lead_name = ''; //Название добавляемой сделки
$lead_status_id = '17882395'; //id этапа продаж, куда помещать сделку
$contact_name =   $_REQUEST['name'];//Название добавляемого контакта
$contact_phone =  $_REQUEST['phone']; //Телефон контакта
$contact_email =  $_REQUEST['email']; //Емейл контакта
$action =  $_REQUEST['action'];// Вид формы отправки и тип действия
$tags_lead = '';
if ($action == 'vip'){
    $tags_lead = 'VIP, Заявка с сайта';
    $lead_name = 'VIP пакет';
}elseif ($action == 'business'){
    $tags_lead = 'Business, Заявка с сайта';
    $lead_name = 'Business пакет';
}elseif ($action == 'standart'){
    $tags_lead = 'Standart, Заявка с сайта';
    $lead_name = 'Standart пакет';
}

//АВТОРИЗАЦИЯ
$user=array(
    'USER_LOGIN'=>'bishko95@gmail.com', #Ваш логин (электронная почта)
    'USER_HASH'=>'99a02a1c36ad8a8d8a0f6ea79f252e185b1fe589' #Хэш для доступа к API (смотрите в профиле пользователя)
);
$subdomain='bikotrade';
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
curl_setopt($curl,CURLOPT_HEADER,0);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
curl_close($curl);  #Завершаем сеанс cURL
$Response=json_decode($out,true);
if($code !== 200){
    echo 'Ошибка! <br>';
    echo '<pre>'; print_r($Response); echo '</pre>';
    exit;
}
$user_id = $Response['response']['user']['id'];

$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current'; #$subdomain уже объявляли выше
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
$Response=json_decode($out,true);
//echo '<pre>'; print_r($Response); echo '</pre>';exit;
$contacts=$Response['response']['account']['custom_fields']['contacts'];
//ФОРМИРУЕМ МАССИВ С ЗАПОЛНЕННЫМИ ПОЛЯМИ КОНТАКТА
//Стандартные поля амо:
$sFields = [];
foreach ($contacts as $contact){
    if($contact['code'] == 'PHONE')
        $sFields['PHONE'] = $contact['id'];

    if($contact['code'] == 'EMAIL')
        $sFields['EMAIL'] = $contact['id'];
}
//ДОБАВЛЯЕМ СДЕЛКУ

$leads['add']=array(
    array(
        'name' => $lead_name,
        'status_id' => $lead_status_id, //id статуса
        'tags' => $tags_lead,
        'responsible_user_id' => $user_id, //id ответственного по сделке
        'pipeline_id' => $pipeline_id
    )
);
$link='https://'.$subdomain.'.amocrm.ru/api/v2/leads';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,'https://'.$subdomain.'.amocrm.ru/api/v2/leads');
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

$Response=json_decode($out,true);
if($code !== 200){
    echo 'Ошибка! <br>';
    echo '<pre>'; print_r($Response); echo '</pre>';
    exit;
}
$lead_id = '';
if(is_array($Response['_embedded']['items']))
    $lead_id = $Response['_embedded']['items'][0]['id']; //id новой сделки
//ДОБАВЛЯЕМ СДЕЛКУ - КОНЕЦ
//ДОБАВЛЕНИЕ КОНТАКТА
$contact = array(
    'name' => $contact_name,
    'tags' => $tags_lead,
    'linked_leads_id' => array($lead_id), //id сделки
    'responsible_user_id' => $user_id, //id ответственного
    'custom_fields'=>array(
        array(
            'id' => $sFields['PHONE'],
            'values' => array(
                array(
                    'value' => $contact_phone,
                    'enum' => 'WORK'
                )
            )
        ),
        array(
            'id' => $sFields['EMAIL'],
            'values' => array(
                array(
                    'value' => $contact_email,
                    'enum' => 'WORK'
                )
            )
        )
    )
);
$set['request']['contacts']['add'][]=$contact;
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
$Response=json_decode($out,true);
if($code !== 200){
    echo 'Ошибка! <br>';
    echo '<pre>'; print_r($Response); echo '</pre>';
    exit;
}else{

    if ($action == 'vip'){
        header('HTTP/1.1 200 OK');
        header("Location: https://new.bikosignals.com/pay_form_vip.html");
    }elseif ($action == 'business'){
        header('HTTP/1.1 200 OK');
        header("Location: https://new.bikosignals.com/pay_form_business.html");
    }elseif ($action == 'standart'){
        header('HTTP/1.1 200 OK');
        header("Location: https://new.bikosignals.com/pay_form_standart.html");
    }

}