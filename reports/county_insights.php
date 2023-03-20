<?php
    require_once ROOT_PATH . '/vendor/autoload.php';
    $v ='v2.5';

    $fb = new Facebook\Facebook([
        'app_id' => APPID,
        'app_secret' => APPSECRET,
        'default_graph_version' => $v,
    ]);

    $d=$fb->get('/422353074597576/insights/page_fans_country', 
        array(
            'access_token' => ACCESS_TOKEN,
        )
    );

    var_dump($d);
?>