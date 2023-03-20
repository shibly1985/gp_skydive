<?php
    define('TIME'                               ,time());
    define('SHOW_ERROR_LINE'                    ,'Yes');//when "Yes" then show error line

    define('LOGIN_SESSION_STRING'               ,'s!g@y#_4'.PROJECT.'d$iqX^a7');
    define('LOGIN_SESSION_NAME'                 ,'0d()q*v5e'.PROJECT.'^_5s%k4y');

    define('SITE_NAME'                          ,'Sky Dive');
    define('DEVELOPER_PASSWORD'                 ,'ha#aba@a,la^95');
    if(PROJECT=='gpc'){
        define('AES_KEY'                            ,'89-Es^A28dUs$dfsPf%dfaEs^a28dUs$df)df%dfEFs^a28dUs$dfsd*/dvaEs^a28d9s$d&sdf%OfaEs^a28dUs$d+sdf%dfaEs^a28dks$d@sdf%dfaEs^a28dUs$d#sdf%dfaEs^a28XUs$dfsdf%df8_gp');   
    }
    elseif(PROJECT=='tmmc'){
        define('AES_KEY'                            ,'89-Es^A28dUs$dfsPf%dfaEs^a28dUs$df)df%dfEFs^a28dUs$dfsd*/dvaEs^a28d9s$d&sdf%OfaEs^a28dUs$d+sdf%dfaEs^a28dks$d@sdf%dfaEs^a28dUs$d#sdf%dfaEs^a28XUs$dfsdf%df8_tmm');   
    }
    elseif(PROJECT=='local'){
        define('AES_KEY'                            ,'89-Es^A28dUs$dfsPf%dfaEs^a28dUs$df)df%dfEFs^a28dUs$dfsd*/dvaEs^a28d9s$d&sdf%OfaEs^a28dUs$d+sdf%dfaEs^a28dks$d@sdf%dfaEs^a28dUs$d#sdf%dfaEs^a28XUs$dfsdf%df8_tmm');   
    }
    else{
        define('AES_KEY'                            ,'89-Es^A28dUs$dfsPf%dfaEs^a28dUs$df)df%dfEFs^a28dUs$dfsd*/dvaEs^a28d9s$d&sdf%OfaEs^a28dUs$d+sdf%dfaEs^a28dks$d@sdf%dfaEs^a28dUs$d#sdf%dfaEs^a28XUs$dfsdf%df8_'.PROJECT);
    }
    //echo PROJECT;
    define('SUPERADMIN_USER'                    ,1);
    define('MODULE_URL'                         ,'module');
    define('PAGE_INATION_CURRENT_PAGE_NAME'     ,'current_page');
    define('INCLUDE_FOLDER'                     ,'include_files');
    define('NUMERIC_INPUT'                      ,'onkeypress="return isNumberKey(event)"');
    define('ROOT_PATH'                          ,__DIR__);
    define('INVOICE_FONT_NAME'                  ,'');
    define('TASK_STATUS_ABANDON'                ,101);
    define('TASK_STATUS_AGREED'                 ,7);
    define('TASK_STATUS_DECLINE'                ,13);
    define('TASK_STATUS_DELIVERED'              ,100);
    define('SALSE_USER'                         ,4);
    define('ASSIGN_TYPE_AUTO'                   ,'1');
    define('ASSIGN_TYPE_MANUAL'                 ,'0');
    define('ASSIGN_TYPE_HIGHBREED'              ,'2');
    define('WALL_POST_COMMENT_FLOW_LIFO'        ,1);
    define('WALL_POST_COMMENT_FLOW_FIFO'        ,0);

    define('POST_TYPE_STATUS'                   ,1);//page post
    define('POST_TYPE_PHOTO'                    ,2);
    define('POST_TYPE_POST'                     ,3);//wall post / visitor post 
    define('POST_TYPE_VIDEO'                    ,4);
    define('POST_TYPE_LINK'                     ,5);
    define('POST_TYPE_NOTE'                     ,6);

    define('SCENTIMENT_TYPE_POSITIVE'           ,1);
    define('SCENTIMENT_TYPE_NUTRAL'             ,2);
    define('SCENTIMENT_TYPE_NEGETIVE'           ,3);

    define('COMMENT_ID'                         ,'comment_id');
    define('OUTBOX_MAX_RETRY'                   ,5);  


    define('OPERATION_COMMENT_ALLWO'            ,true);
    define('OPERATION_WALL_ALLWO'               ,true);
    define('OPERATION_MESSAGE_ALLWO'            ,true);
    /*if(PROJECT=='gp'){
    define('OPERATION_MESSAGE_ALLWO'            ,false);   
    }
    else{
    define('OPERATION_MESSAGE_ALLWO'            ,true);    
    }*/



    /*define('SENT_TYPE_COMMENTS'                 ,1);
    define('SENT_TYPE_WALL_POST'                ,2);
    define('SENT_TYPE_MESSAGE'                  ,3);*/



    $ttm = strtotime('today');
    $ytm = strtotime('yesterday');
    $tmtm = strtotime('tomorrow');
    //$lwtm = $ttm-(84000*7);
    define('YESTERDAY_TIME',$ytm);
    define("TODAY_TIME",$ttm);
    define("TOMORROW_TIME",$tmtm);
    //define("PREVIOUS_WEEK_TIME", $lwtm);
    $colorCodes=array(
        0   => "#DB2724",
        1   => "#26B99A",
        2   => "#2A3F54",
        3   => "#68A155",
        4   => "#4C9ED9",
        5   => "#CDB4A6",
        6   => "#55AB30",
        7   => "#B7950D",
        8   => "#F4A756",
        9   => "#C0AFD1",
        10  => "#4D9A26",
        11  => "#FF6920",
        12  => "#F3E48E",
        13  => "#B9B9B9");
    include_once 'system/define_permissions.php';
    $pSlug='';
    $pUrl = URL;


    if(isset($_SESSION[LOGIN_SESSION_NAME])||(isset($_POST['STOKEN'])&&isset($_GET['ajax']))){
        if(isset($_POST['STOKEN'])){
            $userPut=$_POST['STOKEN'];
        }
        else{
            $userPut    = $_SESSION[LOGIN_SESSION_NAME];    
        }

        $lSessino   = base64_decode($userPut);
        $lSessino   = explode(md5(LOGIN_SESSION_STRING),$lSessino);
        $lSessino   = $lSessino[1];
        $lData   = $db->get_rowData($general->table(18),'ulsString',$lSessino);
        if(!empty($lData)){
            if($lData['ulsStatus']==1){
                if($lData['ulsValidity']>=TIME){
                    $userData = $db->get_rowData($general->table(17),'uID',$lData['uID']);
                    if(!empty($userData)){
                        $sessionLife=intval($db->settingsValue('sessionLifeTime'));if($sessionLife<2)$sessionLife=2;if($sessionLife>59)$sessionLife=59;
                        $data=array();
                        if($_SERVER['SERVER_NAME']==LOCAL_SERVER_NAME){$sessionLife=59;}
                        $logoutTime = strtotime("+".$sessionLife." min", TIME);
                        $data['ulsValidity']=$logoutTime;
                        $ulsaData=array(
                            'active'=> TIME,
                            'ulsID' => $lData['ulsID'],
                            'uID'   => $lData['uID']
                        );
                        if(
                            isset($_POST['nextPicup'])
                            ||isset($_POST['newLikeByAgent'])
                            ||isset($_POST['newCommentByAgent'])
                            ||isset($_POST['newBulkReply'])
                            ||isset($_POST['removePostComment'])
                            ||isset($_POST['newLikeByAgent'])
                            ||isset($_POST['newLikeByAgent'])
                            ||isset($_POST['newLikeByAgent'])
                            ||isset($_POST['logoutForBreak'])
                            ||isset($_GET['logout'])
                            ||isset($_GET[MODULE_URL])
                        ){
                            $data['ulsLastActivity']=TIME;
                            if(
                                !isset($_GET[MODULE_URL])
                                &&!isset($_POST['logoutForBreak'])
                                &&!isset($_GET['logout'])
                            ){//other activity checked before
                                $ulsaData['service']=TIME;
                                $data['ulsLastService']=TIME;
                                if($lData['ulsLastService']!=0){
                                    $last5min=strtotime('-5 minute',TIME);
                                    $service=0;
                                    if($lData['ulsLastService']>$last5min){
                                        $service=TIME-$lData['ulsLastService'];
                                    }
                                    else{
                                        $next2Min=strtotime('+2 minute',$lData['ulsLastService']);
                                        $service=$next2Min-$lData['ulsLastService'];
                                    }

                                    $data['ulsService']=$lData['ulsService']+$service;
                                }
                                else{
                                    //just start service
                                }
                            }
                            if(!isset($_POST['topQueueUpdate'])
                                &&!isset($_POST['dashboardGraph'])
                            ){
                                $insert=$db->insert($general->table(37),$ulsaData);
                            }
                        }
                        if(!isset($_POST['topQueueUpdate'])
                            &&!isset($_POST['dashboardGraph'])
                        ){
                            $where = array('ulsID'=>$lData['ulsID']);
                            $update = $db->update($general->table(18),$data,$where);
                        }
                        define('UID',$userData['uID']);
                        define('UGID',$userData['ugID']);
                        $logdIn=1;
                    }else{$db->logOut($lData);SetMessage(47);}
                }
                else{
                    SetMessage(48);
                    $db->logOut($lData);
                }
            }else{$db->logOut($lData);SetMessage(47);}
        }else{$db->logOut($lData);SetMessage(47);}
    }
    if(isset($userData)){
        define('MODULE_URLN','lUser_'.PROJECT.'='.$general->make_url($userData['uLoginName']).'&'.MODULE_URL);
        define('URL_INFO','
            <input type="hidden" name="lUser_'.PROJECT.'" value="'.$general->make_url($userData['uLoginName']).'">
            <input type="hidden" name="'.MODULE_URL.'" value="'.@$_GET[MODULE_URL].'">
        ');
    }
    else{
        define('MODULE_URLN','lUser_'.PROJECT.'=0&'.MODULE_URL);
        define('URL_INFO','
            <input type="hidden" name="'.MODULE_URL.'" value="'.@$_GET[MODULE_URL].'">
        ');
    }
    $data=$db->settingsValues(array('page_id','appid','appsecret','access_token','page_name'));
    if(!empty($data)){
        if(1){
            define('PAGE_NAME',$data['page_name']['ssVal']);
            define('PAGE_ID',$data['page_id']['ssVal']);
            define('APPID',$data['appid']['ssVal']);
            define('APPSECRET',$data['appsecret']['ssVal']);
            define('ACCESS_TOKEN',$data['access_token']['ssVal']);
        }
		
        else{
            define('PAGE_NAME',$data['page_name']['ssVal']);
            define('PAGE_ID',$data['page_id']['ssVal']);
            define('APPID',$data['appid']['ssVal']);
            define('APPSECRET',$data['appsecret']['ssVal'].'dd');
            define('ACCESS_TOKEN',$data['access_token']['ssVal'].'a');
        }
    }
?>
