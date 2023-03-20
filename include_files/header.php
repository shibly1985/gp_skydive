<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $thisPageTitle;?></title>
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!--    <link rel="stylesheet" type="text/css" href="css/jquery-ui-datepekar.css"> Please use date range picker-->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!--    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet"> Please use date range picker-->
    <!--<link href="css/custom.css" rel="stylesheet">-->
    <?php
        $cssFile='custom.css';
        $cssFile='skydive_custom.css';
        if(file_exists('css/'.PROJECT.'_custom.css')){
            $cssFile=PROJECT.'_custom.css';
        }
    ?>
    <link href="css/<?php echo $cssFile ;?>" rel="stylesheet">
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <script src="vendors/nprogress/nprogress.js"></script>
    <link href="vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    <script src="vendors/pnotify/dist/pnotify.js"></script>
    <script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <!-- bootstrap-daterangepicker -->
    <!--<script src="js/datepicker/bootstrap-datetimepicker.js"></script> Please use date range picker-->
    <script type="text/javascript">$(document).ready(function(){$(".hideit").click(function(){$(this).hide(600);});});</script>
    <script src="vendors/iCheck/icheck.min.js"></script>
    <?php
        if(isset($logdIn)){
        ?>
        <link rel="stylesheet" type="text/css" href="css/daterangepicker.css">
        <script src="js/moment/moment.min.js"></script>
        <script src="js/datepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="js/jx.js"></script>
        <script type="text/javascript" src="js/select2.js"></script>
        <link rel="stylesheet" type="text/css" href="css/select2.css">
        <!--        <script type="text/javascript" src="js/jquery-ui-datepekar.js"></script> Please use date range picker-->
        <script type="text/javascript">
            var LOGDIN=1;
            var SITE_URL='<?php echo URL?>';
            var URL2='<?php echo URL2?>';
            var URL3='<?php echo URL3?>';
            var PROJECT='<?php echo PROJECT?>';
            <?php
                if(isset($_SESSION[LOGIN_SESSION_NAME])){
                ?>
                var STOKEN='<?php echo $_SESSION[LOGIN_SESSION_NAME];?>';
                <?php
                }
                else{
                ?>
                var STOKEN='';
                <?php
                }
            ?>
            var PAGE_NAME='<?php echo PAGE_NAME?>';
            var PAGEID='<?php echo PAGE_ID?>';
            var UID='<?php echo UID?>';
            var thisPageTitle='<?php echo $thisPageTitle;?>';
            var POST_TYPE_STATUS='<?php echo POST_TYPE_STATUS;?>';
            var POST_TYPE_PHOTO='<?php echo POST_TYPE_PHOTO;?>';
            var POST_TYPE_POST='<?php echo POST_TYPE_POST;?>';
            var POST_TYPE_VIDEO='<?php echo POST_TYPE_VIDEO;?>';
            lastTime='<?php echo date('YmdHis',TIME);?>';
        </script>
        <script type="text/javascript" src="js/sky.js"></script>
        <script>
        var serviceTime = parse_int('<?php echo $social->getAgentTodayActivity(UID);?>');
        </script>
        <script type="text/javascript" src="js/salam.js"></script>
        <script type="text/javascript" src="js/shamim.js"></script>
        <script>
            $(document).ready(function() {
                select2();
                <?php if(isset($_GET['flush'])){ ?>ajUrl+='&flush=1';<?php }?>
                countTimer();

                $('.daterangepicker').daterangepicker({
                    singleDatePicker: true,
                    locale:{format:'DD-MM-YYYY'},
                    calender_style: "picker_3",
                    showDropdowns: true
                    }, function(start, end, label) {
                        console.log(start.toISOString(), end.toISOString(), label);
                });

                /*
                Please use date range picker
                $('#form_datetimepicker').datetimepicker({
                daysOfWeekDisabled: [0, 6]
                });
                $('#to_datetimepicker').datetimepicker({
                daysOfWeekDisabled: [0, 6]

                });*/
            });

            $(function() {
                $('#accordion .panel').hover(function() {
                    $(this).find(".accordion-toggle .indicator").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                    $(this).find(".panel-collapse").collapse("show");
                    }, function() {
                        var $collapse = $(this).find(".panel-collapse");
                        $(this).find(".accordion-toggle .indicator").addClass("glyphicon-chevron-down").removeClass("glyphicon-chevron-up");
                        setTimeout(function(){
                            $collapse.collapse("hide");
                            },400);
                });
            })



        </script>
        <script src="js/custom.js"></script>
        <?php
        }
    ?>
</head>
<?php
    $nt='md';
    $smArray=array(142,155,152);
    if(isset($rModule)){if(in_array($rModule['cmId'],$smArray))$nt='sm';}
?>
<body class="nav-<?php echo $nt;?>">
<div class="container body">
<div class="main_container">
<?php
    include("include_files/left_column.php"); 
    include("include_files/top_nav.php");
?>
<div class="right_col" role="main">
<div class="row">