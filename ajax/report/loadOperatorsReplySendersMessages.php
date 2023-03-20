<?php
    $ids=$_POST['loadOperatorsReplySendersMessages'];
    $jArray['rq']=$ids;
    $rt=array();
    if(!empty($ids)){
        $jArray['status']=1;
        foreach($ids as $rq){
            $rt[$rq['id']]['j']='';
            $rt[$rq['id']]['t']='';
            $rt[$rq['id']]['s']='';
            $rrq=explode('_',$rq['inf']);
            $sender_id=$rrq[0];
            $targetSeq=$rrq[1];
            $rpMsg=array();
            $query="select targetSeq from ".$general->table(9)." where sender_id='".$sender_id."' and sendType=2 and targetSeq<".$targetSeq." order by targetSeq desc limit 1";
            $a=$db->fetchQuery($query);
            if(!empty($a)){
                $old=$a[0]['targetSeq'];
                $query="select mid,".$general->mDec('text').",url,sendTime from ".$general->table(9)." where sender_id='".$sender_id."' and sendType=1 and seq>".$old." and seq<=".$targetSeq." order by targetSeq desc";
                //$query="select mid,text,url,sendTime from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq>".$old." and seq<=".$ad['targetSeq']." order by targetSeq desc";
                $a=$db->fetchQuery($query);
                if(!empty($a)){
                    foreach($a as $ms){
                        //$rpMsg[]=$ms['text'];
                        $rpMsg[]=array('i'=>$ms['mid'],'t'=>$ms['text'],'u'=>$ms['url'],'sendTime'=>$ms['sendTime']);
                    }
                }

            }
            else{
                $query="select mid,".$general->mDec('text').",sendTime,wuID,scentiment from ".$general->table(9)." where sender_id='".$sender_id."' and sendType=1 and seq=".$targetSeq." order by targetSeq desc";
                //                                    $query="select mid,text,sendTime,wuID,scentiment from ".$general->table($tbl)." where sender_id='".$ad['sender_id']."' and sendType=1 and seq=".$ad['targetSeq']." order by targetSeq desc";
                $a=$db->fetchQuery($query);
                if(!empty($a)){
                    foreach($a as $ms){
                        $rpMsg[]=array('t'=>$ms['text'],'sendTime'=>$ms['sendTime']);
                    }
                }
            }
            $m='';
            if(!empty($rpMsg)){
                $sr=1;
                //$general->printArray($rpMsg);
                foreach($rpMsg as $ms){
                    if(count($rpMsg)==1){
                        //$general->printArray($ms);
                        $m.=$ms['t'];
                    }
                    else{
                        if($sr==1){
                            $m.=$sr."-> ".$ms['t'];
                        }
                        else{
                            $m.="\n".$sr."-> ".$ms['t'];
                        }
                    }
                    $sr++;
                }
                $rt[$rq['id']]['j']=$m;
            }
            $m='';
            foreach($rpMsg as $ms){
                //$m.=$ms['i'].'<br>'; 
                $m.='<div class="eachmsg">'.$ms['t'].'<br>';
                if($ms['u']!=''){
                    $mu=json_decode($ms['u'],true);
                    if(!empty($mu)){
                        foreach($mu as $mmi){
                            if($mmi['type']=='image'){
                                $m.='<img src="'.$mmi['url'].'" style="max-width:200px;"><br>';
                            }
                            else{
                                $m.=$mmi['type'].'<br>'; 
                            }
                        }
                    }
                    else{
                        $m.='<img src="'.$ms['u'].'" style="max-width:200px;"><br>';
                    }
                }
                $m.= date('d-m-y h:i:s A',$ms['sendTime']);
                $m.='</div>';
            }
            $rt[$rq['id']]['t']=$m;
            $rt[$rq['id']]['s']=$general->word_limit($m,10);

        }
    }
    $jArray['rt']=$rt;
    $general->jsonHeader($jArray);
?>