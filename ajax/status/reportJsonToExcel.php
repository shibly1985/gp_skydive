<?php
    $rq=json_decode(stripslashes($_POST['reportJsonToExcel']),true);
    //    echo print_r($rq);
    //    echo (urldecode($_POST['reportJsonToExcel']));
    //    exit;
    include 'PHPExcel.php';
    $ex = new PHPExcel();
    $ex->getProperties()->setCreator("Abdus Salam");
    $ex->getProperties()->setLastModifiedBy("Abdus Salam");
    $ex->getProperties()->setTitle("Optimal IT LTD http://optimalbd.com/");
    $ex->getProperties()->setSubject("http://optimalbd.com/");
    $ex->getProperties()->setDescription("Final Report file");
    $ex->setActiveSheetIndex(0);
    $head=$rq['title'];
    $keys=array();
    $row=1;
    $i=1;
    foreach($head as $d){
        $h=$d['title'];
        $keys[]=$d['key'];
        $h=str_ireplace('\n'," - ",$h);
        if(isset($d['w'])){
            $ex->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setAutoSize(false);
            $ex->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setWidth($d['w']);
        }
        $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,$h);
        $ex
        ->getActiveSheet()
        ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i))
        ->getAlignment()
        ->setWrapText(true);
        $i++;
    }
    $row++;     

    foreach($rq['data'] as $b){
        $i=1;
        foreach($keys as $k){
            if(isset($b[$k])){
                $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,$b[$k]);
            }
            else{
                $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,'-');
            }
            $i++;
        }
        $row++;     
    }      



    $ex->setNameAndHeader('text');
    $objWriter = PHPExcel_IOFactory::createWriter($ex, 'Excel2007');
    //$objWriter->save('php://output');exit;
    if(isset($rq['name'])){
        $path='report_excel/'.$rq['name'].'_'.TIME.'.xlsx';
    }
    else{
        $path='report_excel/report_'.TIME.'.xlsx';
    }
    $objWriter->save($path);
    //exit;
    $jArray['status']=1;
    $jArray['link']=URL.'/'.$path;
    $general->jsonHeader($jArray);
?>
