<?php
    header('Content-Type: text/html; charset=utf-8');
    define('DB_SERVER', 'localhost');
	
    if(__DIR__=='/var/www/sslbd/gp/class'){//It's use for same server
        define('PROJECT','gp');
    }
    elseif(__DIR__=='/var/www/sslbd/tmm/class'){
        define('PROJECT','tmm');
    }
    elseif(__DIR__=='/var/www/sslbd/tmmc/class'){
        define('PROJECT','tmmc');
    }
    elseif(__DIR__=='/var/www/sslbd/pizzainn/class'){
        define('PROJECT','pizzainn');
    }
    elseif(__DIR__=='/var/www/sslbd/seemo/class'){
        define('PROJECT','seemo');
    }
    else if(__DIR__=='/home/cicilica/public_html/pizzainn/class'){
        header('location:https://skydivebd.net/pizzainn');exit;
    }
    else if(__DIR__=='/home/cicilica/public_html/demo/class'){
        define('PROJECT','cicilica_demo');
    }
    elseif(__DIR__=='/home/skydivebd/public_html/skydive/class'){
        define('PROJECT','skydive');
    }
    elseif(__DIR__=='/var/www/sslbd/gpc/class'){
        define('PROJECT','gpc');
    }
    elseif(__DIR__=='/home/skydivebd/public_html/sk/class'){
        define('PROJECT','sk');
    }
    elseif(__DIR__=='/var/www/sslbd/ittips/class'){ 
        define('PROJECT','ittips');
    }
    else{
        //echo __DIR__;echo'<br>'; 
        define('PROJECT','local');
    }
    if(!isset($_GET['sh_er'])&&PROJECT!='local'){
        error_reporting(0);
    }
     if(isset($_SERVER['SERVER_NAME'])){
        if(preg_match('/^192/', $_SERVER['SERVER_NAME'])){define('LOCAL_SERVER_NAME',$_SERVER['SERVER_NAME']);}
        else{define('LOCAL_SERVER_NAME','localhost');}
    }
    else{define('LOCAL_SERVER_NAME','localhost');}
    
    if(PROJECT=='tmm'||PROJECT=='tmmc'){
        date_default_timezone_set('Asia/Yangon');
    }
    else{
        date_default_timezone_set('Asia/Dhaka');  
    }

    if(isset($_SERVER['SERVER_NAME'])){
        if($_SERVER['SERVER_NAME'] ==LOCAL_SERVER_NAME){
            define('URL' ,'http://'.LOCAL_SERVER_NAME.'/oitl/sky_dive');
        }
        else{
            if(PROJECT=='gp'){
                define('URL','https://skydivebd.net/gp');
                define('URL2','https://skydivebd.net/gp');
                define('URL3','https://skydivebd.net/gp');
            }
            elseif(PROJECT=='tmm'){
                define('URL','https://skydivebd.net/tmm');
                define('URL2','http://cn1.skydivebd.net/tmm');
                define('URL3','http://cn1.skydivebd.net/tmm');
            }
            else if(PROJECT=='pizzainn'){
                define('URL','https://skydivebd.net/pizzainn');
                define('URL2','http://cn1.skydivebd.net/pizzainn');
                define('URL3','http://cn1.skydivebd.net/pizzainn');
            }
            elseif(PROJECT=='seemo'){
                define('URL','https://skydivebd.net/seemo');
                define('URL2','http://cn1.skydivebd.net/seemo');
                define('URL3','http://cn1.skydivebd.net/seemo');
            }
            elseif(PROJECT=='tmmc'){
                define('URL','https://skydivebd.net/tmmc');
                define('URL2','http://cn1.skydivebd.net/tmmc');
                define('URL3','http://cn1.skydivebd.net/tmmc');
            }
            elseif(PROJECT=='skydive'){
                define('URL','https://skydivebd.com/skydive');
                define('URL2','http://cn1.skydivebd.com/skydive');
                define('URL3','http://cn1.skydivebd.com/skydive');
            }
            elseif(PROJECT=='sk'){
                define('URL','https://skydivebd.com/sk');
            }
            elseif(PROJECT=='gpc'){
                define('URL','https://skydivebd.net/gpc');
            }
            else if(PROJECT=='cicilica_demo'){
                define('URL','https://cicilica.com/demo');
            }
            elseif(PROJECT=='ittips'){
                define('URL','https://skydivebd.net/ittips');
            }
            else{
                textFileWrite('Invalid request line '.__LINE__);

                die('Project '.PROJECT.'Invalid request '.__LINE__);

            }
        }
    }
    else{
        if(PROJECT=='gp'){
            define('URL','https://skydivebd.net/gp');
        }
        elseif(PROJECT=='tmm'){
            define('URL','https://skydivebd.net/tmm');
        }
        else if(PROJECT=='pizzainn'){
            define('URL','https://skydivebd.net/pizzainn');
        }
        elseif(PROJECT=='seemo'){
            define('URL','https://skydivebd.net/seemo');
        }
        elseif(PROJECT=='tmmc'){
            define('URL','https://skydivebd.net/tmmc');
        }
        elseif(PROJECT=='skydive'){
            define('URL','https://skydivebd.com/skydive');
        }
        elseif(PROJECT=='skydive'){
            define('URL','https://skydivebd.com/sk');
        }
        elseif(PROJECT=='ittips'){
            define('URL','https://skydivebd.net/ittips');
        }
        else{
            textFileWrite('Invalid request line '.__LINE__);
            die('Project '.PROJECT.'Invalid request '.__LINE__);

        }
    }

    if(isset($_SERVER['SERVER_NAME'])){
        session_start();
    }
    if(@$_SERVER['SERVER_NAME'] ==LOCAL_SERVER_NAME){
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        //define('DB_DATABASE', 'sky_dive');
        define('DB_DATABASE', 'sky_dive');
    }
    else{
        if(PROJECT=='gp'){
            define('DB_USERNAME', 'root');
            define('DB_PASSWORD', '');
            define('DB_DATABASE', 'gp');
        }
        elseif(PROJECT=='tmm'){
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'tmm');
        }
        elseif(PROJECT=='tmmc'){
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'tmmc');
        }
        elseif(PROJECT=='seemo'){
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'seemo');
        }
        else if(PROJECT=='pizzainn'){
            /*define('DB_USERNAME', 'cicilica_abdus');
            define('DB_PASSWORD', 'KS=U{h7B9CB7');
            define('DB_DATABASE', 'cicilica_pizzainn');*/
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'pizzainn');
        }
        else if(PROJECT=='cicilica_demo'){
            define('DB_USERNAME', 'cicilica_abdus');
            define('DB_PASSWORD', 'KS=U{h7B9CB7');
            define('DB_DATABASE', 'cicilica_demo');
        }
        elseif(PROJECT=='skydive'){
            define('DB_USERNAME', 'skydiveb_gpuser');
            define('DB_PASSWORD', 'LGTc[1(XZ0T+');
            define('DB_DATABASE', 'skydiveb_skydive');
            //            define('DB_DATABASE', 'skydiveb_tmm');
        }
        elseif(PROJECT=='gpc'){
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'gpc');
        }
        elseif(PROJECT=='ittips'){
            define('DB_USERNAME', 'sslbd');
            define('DB_PASSWORD', 'cloudly#sslbd');
            define('DB_DATABASE', 'ittips');
        }
        elseif(PROJECT=='sk'){
            define('DB_USERNAME', 'skydiveb_gpuser');
            define('DB_PASSWORD', 'LGTc[1(XZ0T+');
            define('DB_DATABASE', 'skydiveb_sk');
        }
        else{
            //textFileWrite('Invalid request line '.__LINE__);
            echo __DIR__;echo'<br>';echo'<br>';
            echo PROJECT;echo'<br>';   
            die('Invalid request');
        }
    } 
    //echo DB_USERNAME;exit();
    //    $GLOBALS['connection'];
    $GLOBALS['connection'] = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE) or die('Oops connection error 1');
    //    mysqli_set_charset($GLOBALS['connection'],'utf8');
    mysqli_set_charset($GLOBALS['connection'],'utf8mb4');
    $_POST = sanitize($_POST);
    $_GET = sanitize($_GET);

    //

    function cleanInput($input){
        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }
    function sanitize($input) {

        $output = array();
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = sanitize($val);
            }
        }
        else {
            if(get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input  = cleanInput($input);
            $output = mysqli_real_escape_string($GLOBALS['connection'],trim($input));
            $output = preg_replace('/\s+/', ' ',$output);
        }
        return $output;
    }
    function textFileWrite($data,$fileName="error.txt"){
        $handle = fopen($fileName, 'a');
        if(is_array($data)){
            foreach($data as $key=>$p){
                $new_data = $key.'->'.$p."\n";
                fwrite($handle, $new_data);
            }
        }
        else{
            if($fileName=='replyed.txt'){
                $data=date('d H:I:s A ').$data;
            }
            fwrite($handle, $data."\n");
        }
        fclose($handle);
    }
    //    textFileWrite(__DIR__);
?>