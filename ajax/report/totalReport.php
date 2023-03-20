<?php       
    $start  = strtotime($_POST['st']);
    $end    = strtotime($_POST['en']);$end=strtotime('+1 day',$end);
    $groups = intval($_POST['groups']);
    $agents = intval($_POST['agents']);
    $keyword= $_POST['keyword'];
    $type   = $_POST['type'];
    
?>
