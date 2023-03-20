<?php
    $newFile=$_FILES['uploadFile'];
    $jArray[__LINE__]=__LINE__;
    if(isset($newFile["name"])){
    $jArray[__LINE__]=__LINE__;
        $fileName   = $newFile["name"];
        if(!empty($fileName)){
    $jArray[__LINE__]=__LINE__;
            $tempImage  = $newFile['tmp_name']; 
            $extension=explode('.', $fileName);
            $extension=end($extension);
            $extension=strtolower($extension);
            if($extension=='jpeg'||$extension=='jpg'||$extension=='png'){
    $jArray[__LINE__]=__LINE__;
                if($newFile['size']<1024*2000){
                    $imageName = 'at_'.time().'_'.rand(10,9999).'.'.$extension;
                    $destination= ROOT_PATH.'/attachments/'.UID.'/';
    $jArray[__LINE__]=__LINE__;
                    if(!is_dir($destination)){
    $jArray[__LINE__]=__LINE__;
                        mkdir($destination);
                        fopen($destination."index.html", "w");
                    }
                    $move=move_uploaded_file($tempImage,$destination.$imageName);
                    
    $jArray[__LINE__]=$destination.$imageName;
                    if($move){
    $jArray[__LINE__]=__LINE__;
                        $data=array(
                            'uID'       => UID,
                            'afFile'    => $imageName,
                            'afOrder'   => TIME,
                            'afType'    => 'image',
                            'createdBy' => UID,
                            'createdOn' => TIME
                        );
                        $afID=$db->insert($general->table(61),$data,'getId');
                        $jArray['fileName']=$imageName;
                        $jArray['fileId']=$afID;
                        $jArray['status']=1;
                    }
                }else{
    $jArray[__LINE__]=__LINE__;
                    SetMessage(227);$error=__LINE__; }
            }else{$error=__LINE__;SetMessage(4,'Please upload only following type file: jpeg,jpg,png');}
        }
    }
    $jArray['m']=show_msg('yes');
    if(isset($error)){
        $jArray[__LINE__]=$error;
    }
    $general->jsonHeader($jArray);
?>
