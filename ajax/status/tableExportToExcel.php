<?php
    if(isset($_POST['tableExportToExcel'])){
        $file=$_POST['name'].".xls";
        $test=$_POST['tableExportToExcel'];
        //print_r($_POST);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $test;

    }
?>
