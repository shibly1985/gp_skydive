<?php
    if(0){
        echo '<h1>Under Maintenance. We are comming back within 05:00:00 PM (GMT+6)</h1>';
    }
    else{
        ob_start();
        include_once("class/class.db.php");
        include_once("class/class.general.php");
        include_once("class/class.social.php");
        include_once("class/class.social2.php");
        include_once("class/class.report.php");
        include_once("class/class.report2.php");
        include_once("class/messages.php");
        require_once(dirname(__FILE__) . '/vendor/autoload.php');
        $db     = new DB();
        $general= new General();
        $social = new social();
        $sReport= new socialReport();
        //$crm    = new CRM();
        include("init.php");
        //    echo $general->make_date(time(),'time');
        $thisPageTitle = 'Dashboard';
        if(isset($_GET['logout'])){
            if(isset($logdIn)){
                $db->logOut($lData);
                $general->redirect(URL,49);
            }
            else{
                $general->redirect(URL);
            }
        }
        elseif(isset($_GET['ajax'])){include 'ajax/ajax_request.php';}
        elseif(isset($_GET['screen'])){include 'screen/screen_up.php';}
        elseif(!isset($logdIn)){
            $thisPageTitle = 'Login';
            //        include(INCLUDE_FOLDER."/header.php");
            include(INCLUDE_FOLDER."/login.php");
            //        include(INCLUDE_FOLDER."/footer.php");
        }

        else{
            $include1=INCLUDE_FOLDER."/dashboard.php";
            if(isset($_GET[MODULE_URL])){
                $rModule = $db->get_rowData($general->table(1),'cmSlug',$_GET[MODULE_URL]);
                if(!empty($rModule)){
                    if($rModule['isActive']==1){
                        if($db->modulePermission($rModule['cmId'])==true){
                            $pUrl = '?'.MODULE_URLN.'='.$rModule['cmSlug'];
                            $pSlug = $rModule['cmSlug'];
                            $thisPageTitle = $rModule['cmTitle'];
                            $include1 = $rModule['cmFolder'].'/'.$rModule['cmPageName'];
                        }else{/*$general->redirect(URL,52,$rModule['cmTitle']);*/}
                    }
                    else{
                        SetMessage(131);
                    }
                }
            }
            include(INCLUDE_FOLDER."/header.php");
            include($include1);
            //        if($pSlug!='request' && $pSlug!='messages' && $pSlug!='replies' && $pSlug!='chat'){
            include(INCLUDE_FOLDER."/footer.php");
            //        }
        }
        mysqli_close($GLOBALS['connection']);
    }
?>
