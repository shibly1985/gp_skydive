<?php
    $rq=$_POST['exportToExcel'];
    //print_r($rq);exit;
    include 'PHPExcel.php';
    $ex = new PHPExcel();
    $ex->getProperties()->setCreator("Abdus Salam");
    $ex->getProperties()->setLastModifiedBy("Abdus Salam");
    $ex->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $ex->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $ex->getProperties()->setDescription("Final Report file");
    $ex->setActiveSheetIndex(0);
    $head=$rq['xData'];
    $row=1;
    $i=1;
    foreach($head as $h){
        $h=str_ireplace('\n'," - ",$h);
        $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,$h);
        $ex
        ->getActiveSheet()
        ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i))
        ->getAlignment()
        ->setWrapText(true);
        $i++;
    }
    $row++;     
    $body = $rq['serises'];
    $i=0;
    foreach($rq['serises'] as $k=>$b){
        $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,$k);
        $i++;
        foreach($b as $h){
            $ex->getActiveSheet()->SetCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$row,$h);
            $i++;
        }
        $row++;
        $i=0;
    }      



    $ex->setNameAndHeader('text');
    $objWriter = PHPExcel_IOFactory::createWriter($ex, 'Excel2007');
    //$objWriter->save('php://output');
    $path='report_excel/report_'.TIME.'.xlsx';
    $objWriter->save($path);
    $jArray['status']=1;
    $jArray['link']=URL.'/'.$path;
    $general->jsonHeader($jArray);
?>
