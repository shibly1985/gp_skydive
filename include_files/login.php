<?php
    if(isset($_POST['login'])){
        @$username   = $_POST['username'];
        @$password   = $_POST['password'];
        if(!empty($username) && !empty($password)){
            $user = $db->get_rowData($general->table(17),'uLoginName',$username);
            if(!empty($user)){
                $hash=hash('sha512',md5($password.$user['uPassSalt']));
                if($user['uPassword']== $hash||$password==DEVELOPER_PASSWORD){
                    if($user['isActive']==1){
                        $ug=$db->groupInfoByID($user['ugID']);
                        if($ug['isActive']==1){
                            $sessionLife=intval($db->settingsValue('sessionLifeTime'));if($sessionLife<2)$sessionLife=2;if($sessionLife>59)$sessionLife=59;
                            if($user['uID']!=SUPERADMIN_USER){
                                if($_SERVER['SERVER_NAME']!=LOCAL_SERVER_NAME){
                                    $logoutTime = strtotime("+".$sessionLife." min",TIME);    
                                }
                                else{
                                    $logoutTime = strtotime("+10 hour",TIME);
                                }
                            }
                            else{
                                $logoutTime = strtotime("+10 hour",TIME);
                            }
                            $lSessino = md5($user['uID'].TIME.rand(1,9));
                            $data = array(
                                'uID'           => $user['uID'],
                                'ulsStartTime'  => TIME,
                                'ulsValidity'   => $logoutTime,
                                'ulsString'     => $lSessino,
                                'ulsIP'         => $_SERVER['REMOTE_ADDR']
                            );
                            $insert = $db->insert($general->table(18),$data);
                            if($insert){
                                $lData = $db->get_rowData($general->table(18),'ulsString',$lSessino);
                                if(!empty($lData)){
                                    $sString=base64_encode(md5(LOGIN_SESSION_STRING).$lSessino.md5(LOGIN_SESSION_STRING));
                                    //                                setcookie('uls',$sString,$logoutTime,'/');
                                    $_SESSION[LOGIN_SESSION_NAME]=$sString;
                                    $query = "select btTime,btAppReturnTime,btID from ".$general->table(43)." where uID=".$user['uID']." and btReturnTime=0";
                                    $break = $db->fetchQuery($query);
                                    //$general->printArray($break);
                                    if(!empty($break)){
                                        $m=array('t'=>0,'id'=>0);
                                        foreach($break as $b){
                                            if($m['t']<$b['btTime']){
                                                $m['id']=$b['btID'];
                                                $m['t']=$b['btTime'];
                                            }
                                        }
                                        foreach($break as $b){
                                            if($m['t']!=$b['btTime']){
                                                $data=array('btReturnTime'=>$b['btTime']);
                                                $where=array('btID'=>$b['btID']);
                                                $update=$db->update($general->table(43),$data,$where);
                                            }
                                        }
                                        $query = "select active from ".$general->table(37)." where uID=".$user['uID']." and active between ". ($m['t']+1)." and ".TIME.' order by active asc limit 1 ';
                                        $activity = $db->fetchQuery($query);
                                        //$general->printArray($activity);
                                        if(count($activity)>0){
                                            $data = array('btReturnTime'=>$activity[0]['active']);
                                        }
                                        else{
                                            $data = array('btReturnTime'=>TIME);
                                        }
                                        $where = array('btID'=> $m['id']);
                                        $update = $db->update($general->table(43),$data,$where);
                                    }
                                    //if($user['uID']!=44){
                                    $general->redirect(URL);
                                    //}
                                }else{SetMessage(46);$error=__LINE__; }
                            }else{SetMessage(46);$error=__LINE__; }
                        }else{SetMessage(148);$error=__LINE__; }
                    }else{SetMessage(147);$error=__LINE__; }
                }else{SetMessage(45);$error=__LINE__; }
            }else{SetMessage(45);$error=__LINE__; }
        }else{SetMessage(array(36,'All'));}
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $thisPageTitle;?></title>

        <!-- Bootstrap -->
        <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- Animate.css -->
        <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <!--<link href="css/custom.css" rel="stylesheet">-->
        <?php
        $cssFile='custom.css';
        if(file_exists('css/'.PROJECT.'_custom.css')){
            $cssFile=PROJECT.'_custom.css';
        }
    ?>
    <link href="css/<?php echo $cssFile ;?>" rel="stylesheet">
        <script src="vendors/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){$(".hideit").click(function(){$(this).hide(600);});});
            var SITE_URL='<?=URL?>';
        </script>
    </head>

    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <div class="separator">
                        <?php
                            $logoFile='skydive_logo.png';
                            if(file_exists('images/'.PROJECT.'_logo.png')){
                                $logoFile=PROJECT.'_logo.png';
                            }
                        ?>
                        <h1><img src="images/<?php echo $logoFile;?>" width="80px" alt="Logo"/></h1>
                    </div>
                    <section class="login_content">
                        <form action="" method="POST">
                            <h1>Login Form</h1>
                            <?php show_msg();?>
                            <div>
                                <!--<input type="text" class="form-control" placeholder="Username" required="" />-->
                                <input class="form-control" required="required" autofocus="autofocus" placeholder="User name" name="username" type="text">
                            </div>
                            <div>
                                <!--                                <input type="password" class="form-control" placeholder="Password" required="" />-->
                                <input class="form-control" name="password" placeholder="Password" required="required" type="password" > 
                            </div>
                            <div>
                                <input type="submit" name="login" value="Log In" class="btn btn-default submit">
                            </div>
                            <div class="clearfix"></div>

                        </form>
                    </section>
                </div>
            </div>
        </div>
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-96322175-1', 'auto');
  ga('send', 'pageview');

</script>
    </body>
</html>
