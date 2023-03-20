
<?php
    class socialReport{
        public $db;
        public $general;
        public $social;
        function __construct(){
            $this->db       = new DB();
            $this->general  = new General();
            $this->social   = new social();
        }
        function ahtNart($from,$to,$uID=0,$echo='No'){
            //$echo='d';
            /*$query="
            SELECT
            count(c.replyed) as tr,
            sum(cc.replyTime) as sm,
            sum(cc.assignTime) as st,
            sum(cc.created_time) as ac,
            ((sum(cc.replyTime)-sum(cc.assignTime))/count(c.replyed)) as aht,
            ((sum(cc.replyTime)-sum(cc.created_time))/count(c.replyed)) as art
            FROM ".$this->general->table(13)." c
            left join ".$this->general->table(13)." cc on cc.comment_id=c.target_c_id
            WHERE
            c.replyed=1 and c.sender_id=".PAGE_ID." and cc.assignTime!=0 and
            c.created_time between ".$from." AND ".$to."
            ";*/
            $query="
            SELECT
            c.comment_id,c.replyTime,c.assignTime,c.created_time            
            FROM ".$this->general->table(13)." c
            WHERE
            c.replyed=1 and c.sender_id!=".PAGE_ID." and c.assignTime!=0 and
            c.replyTime between ".$from." AND ".$to."
            ";
            $uids=$uID;
            if(intval($uID)!=0){
                if(!is_array($uID)){
                    $query.=' and c.replyBy='.$uID;
                }
                else{
                    $uids=implode('-',$uID);
                    $query.=" and c.replyBy in (".implode(',',$uID).") ";
                }
            }
            $cacheKey='ahtart_'.$from.'_'.$to.'_'.$uids;
            $c=$this->db->reportCacheGet($cacheKey);
            $mwID='e';
            if($c!=false&&!isset($_GET['flush'])){
                $b=json_decode($c,true);
                $b['c']=1;
            }
            else{

                if($echo!='No'){
                    $this->general->printArray($cacheKey);
                    $this->general->printArray($query);
                }
                $t=0;$mwt=0;$aht=0;$art=0;
                $all = mysqli_query($GLOBALS['connection'],$query);
                while($b= mysqli_fetch_assoc($all)){
                    $t++;
                    $aht+=$this->general->timestampDiffInArray($b['replyTime'],$b['assignTime'],true);
                    $d=$this->general->timestampDiffInArray($b['created_time'],$b['replyTime'],true);
                    $art+=$d;
                    if($mwt<$d){$mwt=$d;$mwID=$b['comment_id'];}
                }

                if($aht>0&&$t>0){$aht=floor($aht/$t);}
                if($art>0&&$t>0){$art=floor($art/$t);}


                $b=array();
                $b['ahti']=$aht;
                $b['arti']=$art;
                $b['aht']=$this->general->makeTimeAvgI($aht);
                $b['art']=$this->general->makeTimeAvgI($art);
                $b['mwt']=$this->general->makeTimeAvgI($mwt);
                $b['mwID']=$mwID;
                $b['f']=$this->general->make_date($from,'time');
                $b['t']=$this->general->make_date($to,'time');   

                $expTime=strtotime('+ 1 hour');
                if($to<strtotime('-3 hour')){
                    $expTime=strtotime('+ 6 month');    
                }
                $this->db->reportCacheSet($cacheKey,json_encode($b),$expTime);
            }
            return $b;
        }

        function ahtNartWall($from,$to,$uID=0,$echo='No',&$jArray=array()){
            $query="
            select 
            c.comment_id,c.replyTime,c.assignTime,c.created_time
            FROM ".$this->general->table(14)." c
            WHERE
            c.replyed=1 and c.sender_id!=".PAGE_ID." and c.assignTime!=0 and
            c.replyTime between ".$from." AND ".$to."
            ";
            $query2="
            select 
            c.post_id,c.replyTime,c.assignTime,c.created_time
            FROM ".$this->general->table(12)." c
            WHERE
            c.replyed=1 and c.assignTime!=0 and
            c.replyTime between ".$from." AND ".$to."
            ";
            $uids=$uID;
            if($uID!=0){
                if(!is_array($uID)){
                    $query.=' and c.replyBy='.$uID;
                    $query2.=' and c.replyBy='.$uID;
                }
                else{
                    $uids=implode('-',$uID);
                    $query.=" and c.replyBy in (".implode(',',$uID).") ";
                    $query2.=" and c.replyBy in (".implode(',',$uID).") ";
                }
            }
            if($echo!='No'&&$echo!='array'){
                echo 'F: '.$this->general->make_date($from,'time').'<br>';
                echo 'T: '.$this->general->make_date($to,'time').'<br>';
                $this->general->printArray($query);
                $this->general->printArray($query2);
            }
            else if($echo=='array'){
                $jArray[__LINE__][]=array(
                'f'=>$this->general->make_date($from,'time'),
                't'=>$this->general->make_date($to,'time'),
                'q'=>$query,
                'q2'=>$query2
                );
            }
            $cacheKey='ahtartw_'.$from.'_'.$to.'_'.$uids;
            $c=$this->db->reportCacheGet($cacheKey);
                $mwID='e';
            if($c!=false&&!isset($_GET['flush'])){
                $b=json_decode($c,true);
                $b['c']=1;
            }
            else{
                $t=0;$mwt=0;$aht=0;$art=0;//mwt=maximum wait time
                $all = mysqli_query($GLOBALS['connection'],$query);
                while($b= mysqli_fetch_assoc($all)){
                    $t++;
                    $aht+=$this->general->timestampDiffInArray($b['replyTime'],$b['assignTime'],true);
                    $d=$this->general->timestampDiffInArray($b['created_time'],$b['replyTime'],true);
                    $art+=$d;
                    if($mwt<$d){
                        $mwt=$d;
                        $mwID=$b['comment_id'];
                    }
                }
                $all = mysqli_query($GLOBALS['connection'],$query2);
                if(mysqli_error($GLOBALS['connection'])!=''){
                    echo $query2;
                    echo "\n\n";
                    echo mysqli_error($GLOBALS['connection']);
                }
                while($b= mysqli_fetch_assoc($all)){
                    $t++;
                    $aht+=$this->general->timestampDiffInArray($b['replyTime'],$b['assignTime'],true);
                    $d=$this->general->timestampDiffInArray($b['created_time'],$b['replyTime'],true);
                    $art+=$d;
                    if($mwt<$d){
                        $mwt=$d;
                        $mwID=$b['post_id'];
                    }
                }

                $b=array();
                if($aht>0&&$t>0){$aht=floor($aht/$t);}
                if($art>0&&$t>0){$art=floor($art/$t);}
                $b['ahti']=$aht;
                $b['arti']=$art;
                $b['aht']=$this->general->makeTimeAvgI($aht);
                $b['art']=$this->general->makeTimeAvgI($art);
                $b['mwt']=$this->general->makeTimeAvgI($mwt);
                $b['mwID']=$mwID;
                $b['f']=$this->general->make_date($from,'time');
                $b['t']=$this->general->make_date($to,'time');   
                $expTime=strtotime('+ 1 hour');
                if($to<strtotime('-3 hour')){
                    $expTime=strtotime('+ 6 month');    
                }
                $this->db->reportCacheSet($cacheKey,json_encode($b),$expTime);
            }
            $jArray[__LINE__][]=$b;
            return $b;
        }
        function ahtNartMsg($from,$to,$uID=0,$echo='No'){
            $query="
            SELECT m.replyTime,m.assignTime,m.sendTime as created_time FROM ".$this->general->table(9)." m where m.sendType=1 and m.replyTime between ".$from." and ".$to."
            ";
            $uids=$uID;
            if(intval($uID)!=0){
                if(!is_array($uID)){
                    $query.=' and m.replyBy='.$uID;
                }
                else{
                    $uids=implode('-',$uID);
                    $query.=" and m.replyBy in (".implode(',',$uID).") ";
                }
            }
            $query.=" group by m.sender_id,m.replyBy,m.replyTime";

            $cacheKey='ahtartm_'.$from.'_'.$to.'_'.$uids;
            $c=$this->db->reportCacheGet($cacheKey);
            if($c!=false&&!isset($_GET['flush'])){
                $b=json_decode($c,true);
                $b['c']=1;
            }
            else{
                if($echo!='No'){
                    $this->general->printArray($cacheKey);
                    $this->general->printArray($query);
                }
                $t=0;$mwt=0;$aht=0;$art=0;
                $all = mysqli_query($GLOBALS['connection'],$query);
                /*echo '<br>';
                echo mysqli_num_rows($all);
                echo'<br>';echo'<br>';  */
                while($b= mysqli_fetch_assoc($all)){
                    //$this->general->printArray($b);
                    $t++;
                    $aht+=$this->general->timestampDiffInArray($b['replyTime'],$b['assignTime'],true);
                    $d=$this->general->timestampDiffInArray($b['created_time'],$b['replyTime'],true);
                    //echo $aht.' - '.$d;echo'<br>'; 
                    $art+=$d;
                    if($mwt<$d)$mwt=$d;
                }

                if($aht>0&&$t>0){$aht=floor($aht/$t);}
                if($art>0&&$t>0){$art=floor($art/$t);}


                $b=array();
                $b['ahti']=$aht;
                $b['arti']=$art;
                $b['aht']=$this->general->makeTimeAvgI($aht);
                $b['art']=$this->general->makeTimeAvgI($art);
                $b['mwt']=$this->general->makeTimeAvgI($mwt);
                $b['f']=$this->general->make_date($from,'time');
                $b['t']=$this->general->make_date($to,'time');   

                $expTime=strtotime('+ 1 hour');
                if($to<strtotime('-3 hour')){
                    $expTime=strtotime('+ 6 month');    
                }
                $this->db->reportCacheSet($cacheKey,json_encode($b),$expTime);
            }
            return $b;

        }
        /*function dashboardSummery($from,$to){
        //এটা আর কোথায় ও ব্যাবহার করা হয় না।
        $r=new socialReport2();
        return $r->dashboardSummery($from,$to);
        }*/
        function commentsAdminActivity($from,$to,$uID=0,$nearReply='',$replicaUse=true,$echo='No',&$jArray=array()){
            if($echo!='No'){
                if($echo=='array'){
                    $jArray[__LINE__][]= 'F: '.$this->general->make_date($from,'time').' ('.$from.')';
                    $jArray[__LINE__][]= 'T: '.$this->general->make_date($to,'time').' ('.$to.')';    
                }
                else{
                    echo '<br>F: '.$this->general->make_date($from,'time').' ('.$from.')<br>';
                    echo 'T: '.$this->general->make_date($to,'time').' ('.$to.')<br>';
                }
            }
            $reportTable=13;
            if(($from<=strtotime('-24 hour',$to)||$to<strtotime('-3 hour'))&&$replicaUse!==false){$reportTable=$this->db->settingsValue('commentReportTable');}
            $doneNcom = '';
            if($nearReply==''){
                if($uID!=0){
                    if(!is_array($uID)){
                        $doneNcom .= ' and replyBy='.$uID;
                    }
                    else{
                        $doneNcom.=" and replyBy in (".implode(',',$uID).") ";
                    }
                }

                //direct send from GP
                $com=$this->db->selectAll($this->general->table($reportTable)
                    ,' WHERE replyBy!=0 and sender_id!='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom
                    ,' count(comment_id) as total',$echo,$jArray);//এখান থেকে replyed!=3 বাদ দেওয়া হয়েছে  এবং replyBy!=0 লাগানো হয়েছে 
                //user comment remove by my system
                //$dcom = $this->db->selectAll($this->general->table($reportTable),' WHERE replyed=3 and sender_id='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                // $hcom = $this->db->selectAll($this->general->table(31),' WHERE  hideTime between '.$from. ' AND '.$to.$delNhide,' count(hideTime) as total');
                // user comment done by my Agent


                //$docom    =$this->db->selectAll($this->general->table($reportTable),' WHERE isDone=1 and replyed!=3 and replyTime between '.$from.' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                //$outbxcom = $this->db->selectAll($this->general->table(41),' WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to.$doneNcom,'count(scqID) as total',$echo);

            }
            else{
                $eq='';
                if($uID!=0){
                    if(!is_array($uID)){
                        $doneNcom .= ' and replyBy='.$uID;
                        $eq.= ' and r.replyBy='.$uID;
                    }
                    else{
                        $doneNcom.=" and replyBy in (".implode(',',$uID).") ";
                        $eq.=" and r.replyBy in (".implode(',',$uID).") ";
                    }
                }
                //$nearReplyQuery='';
                if(!is_array($nearReply)){
                    $doneNcom.=" and (replyTime-created_time)<=".$nearReply;//.' and created_time > '.$from;
                    //$nearReplyQuery.=" and (r.replyTime-c.created_time)<=".$nearReply.' and c.created_time > '.$from;
                }
                else{
                    if($nearReply[1]!=0){
                        $doneNcom.=" and (replyTime-created_time) between ".$nearReply[0].' and '.$nearReply[1];
                        //$nearReplyQuery.=" and (r.replyTime-c.created_time) between ".$nearReply[0].' and '.$nearReply[1];    
                    }
                    else{
                        $doneNcom.=" and (replyTime-created_time) >=".$nearReply[0];    
                        //$nearReplyQuery.=" and (r.replyTime-c.created_time) >= ".$nearReply[0];
                    }
                    /*if(isset($nearReply[2])){
                    if($nearReply[2]!='all'){
                    $doneNcom.=" and created_time >= ".$from;
                    //$nearReplyQuery.=' and c.created_time >= '.$from;    
                    }
                    }
                    else{
                    $doneNcom.=" and created_time >= ".$from;
                    //$nearReplyQuery.=' and c.created_time >= '.$from;    
                    }*/
                }


                //direct send from GP
                /*$com    =$this->db->fetchQuery("
                SELECT COUNT(r.comment_id) AS total
                FROM ".$this->general->table($reportTable)." r
                LEFT JOIN ".$this->general->table($reportTable)." c ON c.comment_id=r.target_c_id
                WHERE 
                r.replyed!=3
                AND r.sender_id=".PAGE_ID." 
                AND r.replyTime BETWEEN ".$from." AND ".$to
                .$eq
                .$nearReplyQuery
                ,$echo);*/
                $com=$this->db->selectAll($this->general->table($reportTable)
                    ,' WHERE replyBy!=0 and sender_id!='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom
                    ,' count(comment_id) as total',$echo,$jArray);//এখান থেকে replyed!=3 বাদ দেওয়া হয়েছে  এবং replyBy!=0 লাগানো হয়েছে 
                //user comment remove by my system
                /*$dcom    =$this->db->fetchQuery("
                SELECT COUNT(r.comment_id) AS total
                FROM ".$this->general->table($reportTable)." r
                LEFT JOIN ".$this->general->table($reportTable)." c ON c.comment_id=r.target_c_id
                WHERE 
                r.replyed=3 and
                r.sender_id=".PAGE_ID." AND r.replyTime BETWEEN ".$from." AND ".$to." AND c.created_time >".$from,$echo);*/

                //$dcom = $this->db->selectAll($this->general->table($reportTable),' WHERE replyed=3 and sender_id='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                // user comment done by my Agent
                //$docom    =$this->db->selectAll($this->general->table($reportTable),' WHERE isDone=1 and replyed!=3 and replyTime between '.$from.' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                //$outbxcom = $this->db->selectAll($this->general->table(41),' WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to.$doneNcom,'count(scqID) as total',$echo);

            }

            $t= $com[0]['total'];/*+$dcom[0]['total']+$docom[0]['total']+$outbxcom[0]['total'];*/
            if($echo!='No'){
                if($echo=='array'){
                    $jArray[__LINE__][]= $com[0]['total'];
                }
                else{
                    echo '<pre>'.$com[0]['total'];/*.' | '.$dcom[0]['total'].' | '.$docom[0]['total'].' | '.$outbxcom[0]['total'];*/echo'</pre>'; 
                }
            }
            //textFileWrite(array(__LINE__=> $this->general->make_date($from,'time').' - '.$this->general->make_date($to).' - UID'.$uID.' t '. $t));
            //}
            return intval($t);
        }
        function commentsUserActivity($from,$to,$replicaUse=true,$echo='No',&$jArray=array()){
            if($echo!='No'){
                if($echo=='array'){
                    $jArray[__LINE__][]= 'F: '.$this->general->make_date($from,'time').' ('.$from.')';
                    $jArray[__LINE__][]= 'T: '.$this->general->make_date($to,'time').' ('.$to.')';    
                }
                else{
                    echo '<br>F: '.$this->general->make_date($from,'time').' ('.$from.')<br>';
                    echo 'T: '.$this->general->make_date($to,'time').' ('.$to.')<br>';
                }
            }

            $reportTable=13;
            if(($from<=strtotime('-24 hour',$to)||$to<strtotime('-3 hour'))&&$replicaUse!==false){$reportTable=$this->db->settingsValue('commentReportTable');}
            $com    =$this->db->selectAll($this->general->table($reportTable),' WHERE sender_id!='.PAGE_ID.' and created_time between '.$from. ' AND '.$to.' and (replyBy!=0 or replyed!=3)',' count(comment_id) as t',$echo,$jArray);
            if($echo=='array'){
                $jArray[__LINE__][]= $com[0]['t'];
            }
            return intval($com[0]['t']);
        }
        function wallAdminActivity($from,$to,$uID=0,$nearReply='',$replicaUse=true,$echo="No"){
            if($echo!='No'){
                echo '<br>F: '.$this->general->make_date($from,'time').' ('.$from.')<br>';
                echo 'T: '.$this->general->make_date($to,'time').' ('.$to.')<br>';
            }
            $wallCommentReportTable=14;$postWallReportTable=12;
            if(($from<=strtotime('-24 hour',$to)||$to<strtotime('-3 hour'))&&$replicaUse!==false){
                //if($from<=strtotime('-24 hour',$to)||$to<strtotime('-3 hour')||$replicaUse===false){
                $wallCommentReportTable=$this->db->settingsValue('commentWallReportTable');
                $postWallReportTable=$this->db->settingsValue('postWallReportTable');
            }

            $doneNcom='';
            if($nearReply==''){
                if($uID!=0){
                    if(!is_array($uID)){
                        $doneNcom .= ' and replyBy='.$uID;
                    }
                    else{
                        $doneNcom.=" and replyBy in (".implode(',',$uID).") ";
                    }
                }
                /*$com    =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE sender_id='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                $docom  =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE isDone=1 and  replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                //$dpost  = $this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE replyed=3 and sender_id='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);
                $dopost  = $this->db->selectAll($this->general->table(12),' WHERE isDone=1 and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);
                $outbxcom = $this->db->selectAll($this->general->table(42),' WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to.$doneNcom,'count(scqID) as total',$echo);*/
                /*$com    =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE replyBy!=0 and sender_id!='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                //$docom  =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE isDone=1 and  replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);
                //$dpost  = $this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE replyed=3 and sender_id='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);
                $dpost  = $this->db->selectAll($this->general->table(12),' WHERE replyBy!=0 and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);
                //$dopost  = $this->db->selectAll($this->general->table(12),' WHERE isDone=1 and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);
                $dopost[0]['total']=0;
                $outbxcom = $this->db->selectAll($this->general->table(42),' WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to.$doneNcom,'count(scqID) as total',$echo);*/
            }
            else{
                $eq='';
                if($uID!=0){
                    if(!is_array($uID)){
                        $doneNcom .= ' and replyBy='.$uID;
                        $eq .= ' and r.replyBy='.$uID;
                    }
                    else{
                        $doneNcom.=" and replyBy in (".implode(',',$uID).") ";
                        $eq.=" and r.replyBy in (".implode(',',$uID).") ";
                    }
                }

                //$nearReplyQuery='';
                if(!is_array($nearReply)){//calculate with crete time
                    $doneNcom.=" and (replyTime-created_time)<=".$nearReply.' and created_time > '.$from;
                    //$nearReplyQuery.=" and (r.replyTime-c.created_time)<=".$nearReply.' and c.created_time > '.$from;
                }
                else{
                    if($nearReply[1]!=0){
                        $doneNcom.=" and (replyTime-created_time) between ".$nearReply[0].' and '.$nearReply[1];
                        //$nearReplyQuery.=" and (r.replyTime-c.created_time) between ".$nearReply[0].' and '.$nearReply[1];
                    }
                    else{
                        $doneNcom.=" and (replyTime-created_time) between ".$nearReply[0].' and '.$nearReply[1];;
                        //$nearReplyQuery.=" and (r.replyTime-c.created_time) >= ".$nearReply[0];
                    }
                    if(isset($nearReply[2])){
                        if($nearReply[2]!='all'){
                            $doneNcom.=" and created_time >= ".$from;
                            //$nearReplyQuery.=' and c.created_time >= '.$from;    
                        }
                    }
                    else{
                        $doneNcom.=" and created_time >= ".$from;
                        //$nearReplyQuery.=' and c.created_time >= '.$from;    
                    }
                }   
            }
            $com    =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE replyBy!=0 and sender_id!='.PAGE_ID.' and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(comment_id) as total',$echo);

            $dpost  = $this->db->selectAll($this->general->table($postWallReportTable),' WHERE replyBy!=0 and replyTime between '.$from. ' AND '.$to.$doneNcom,' count(post_id) as total',$echo);

            //$outbxcom = $this->db->selectAll($this->general->table(42),' WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to.$doneNcom,'count(scqID) as total',$echo);

            $t =    $com[0]['total']+      $dpost[0]['total'];/*+     $outbxcom[0]['total'];*/
            if($echo!='No'){
                echo '<br>'. $com[0]['total'].'|'.   $dpost[0]['total'];/*.'|'. $outbxcom[0]['total'].'<br>';*/
            }
            return $t; 
        }
        function wallPosts($from,$to,$replicaUse=true,$echo="No"){
            if($echo!='No'){
                echo '<br>F: '.$this->general->make_date($from,'time').' ('.$from.')<br>';
                echo 'T: '.$this->general->make_date($to,'time').' ('.$to.')<br>';
            }

            $wallCommentReportTable=14;$postWallReportTable=12;
            if(($from<=strtotime('-24 hour',$to)||$to<strtotime('-3 hour'))&&$replicaUse!==false){
                $wallCommentReportTable=$this->db->settingsValue('commentWallReportTable');
                $postWallReportTable=$this->db->settingsValue('postWallReportTable');
            }
            $post    =$this->db->selectAll($this->general->table($postWallReportTable),' WHERE  created_time between '.$from. ' AND '.$to,' count(created_time) as total',$echo);
            $postDelete= $this->db->selectAll($this->general->table($postWallReportTable),' WHERE  created_time between '.$from. ' AND '.$to.' and replyBy=0 and replyed=3',' count(created_time) as total',$echo);
            //$dpost    =$this->db->selectAll($this->general->table(25),' WHERE  created_time between '.$from. ' AND '.$to,' count(created_time) as total');
            $com  =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE sender_id!='.PAGE_ID.' and created_time between '.$from. ' AND '.$to,' count(created_time) as total',$echo);
            $comDelete  =$this->db->selectAll($this->general->table($wallCommentReportTable),' WHERE sender_id!='.PAGE_ID.' and created_time between '.$from. ' AND '.$to.' and replyBy=0 and replyed=3',' count(created_time) as total',$echo);
            //$hcom = $this->db->selectAll($this->general->table(32),' WHERE  hideTime between '.$from. ' AND '.$to,' count(hideTime) as total');
            $total= intval($post[0]['total']+$com[0]['total'])-intval($postDelete[0]['total']+$comDelete[0]['total']);
            if($echo!='No'){
                echo '<br>'.$post[0]['total'].'|'.$postDelete[0]['total'].'|'.$com[0]['total'].'|'.$postDelete[0]['total'].'|'.$comDelete[0]['total'];
            }
            return $total;
        }
        function arrayReportTable($reporData){
        ?>
        Total :<?php echo count($reporData['data']);?> <a style="display: none;" id="exportBtn" class="btn btn-default">Export</a>
        <table class="table table-striped table-bordered fixtWidthReport">
            <tr>
                <?php
                    foreach($reporData['title'] as $t){
                        $hw='';
                        if(isset($t['hw'])){
                            $hw=' style="width: '.$t['hw'].'%"';
                        }

                    ?><td <?php echo $hw;?>><?php echo $t['title'];?></td><?php
                    }
                ?>
            </tr>
            <?php
                $i=0;
                foreach($reporData['data'] as $r){
                    if($i<300){
                        $i++;
                    ?><tr><?php
                        foreach($reporData['title'] as $t){
                        ?><td><?php echo $r[$t['key']];?></td><?php
                    } ?></tr><?php
                    }
                    else{
                    ?><tr><td colspan="<?php echo count($reporData['title']);?>"><b style="color: red;">Please export for more data</b></td></tr><?php
                        break;
                    }
                }
            ?>
        </table>
        <script>
            <?php echo 'reportHead='.json_encode($reporData).';';?> 
            $(document).ready(function(){
                $('#exportBtn').show();
                $("#exportBtn").click(function(){reportJsonToExcel(reportHead);});
            });
        </script>
        <?php
        }
        function messageUniqueSender($from,$to){
            $cacheKey='msgUniSener'.$from.'_'.$to;
            $c=$this->db->reportCacheGet($cacheKey);
            if($c!=false&&!isset($_GET['flush'])){
                return $c;
            }
            else{
                $query="select count(a.t) as t from (SELECT  count(mid) as t FROM ".$this->general->table(9)."  WHERE sendType=1 and sendTime between ".$from." AND ".$to." group by sender_id) a";
                $total=$this->db->fetchQuery($query);
                $c=$total[0]['t'];
                $expTime=strtotime('+ 1 hour');
                if($to<strtotime('-3 hour')){
                    $expTime=strtotime('+ 6 month');    
                }
                $this->db->reportCacheSet($cacheKey,$c,$expTime);
                return $c;
            }

        }
        function messageUniqueSenderReply($from,$to){
            $cacheKey='msgUniReply'.$from.'_'.$to;
            $c=$this->db->reportCacheGet($cacheKey);
            if($c!=false&&!isset($_GET['flush'])&&0){
                return $c;
            }
            else{
                $query="select count(a.t) as t from (SELECT  count(mid) as t FROM ".$this->general->table(9)."  WHERE sendType=1 and replyTime between ".$from." AND ".$to." group by sender_id) a";
                //echo $query;exit;
                $total=$this->db->fetchQuery($query);
                $c=$total[0]['t'];
                $expTime=strtotime('+ 1 hour');
                if($to<strtotime('-3 hour')){
                    $expTime=strtotime('+ 6 month');    
                }
                $this->db->reportCacheSet($cacheKey,$c,$expTime);
                return $c;
            }

        }
        function messageAdminActivity($from,$to,$uID=0,$echo='No',&$jArray=array()){
            $uids=$uID;
            $q=array();
            
            $q[]='replyTime between '.$from. ' AND '.$to;
            if($uID!=0){
                if(!is_array($uID)){
                    $q[]='replyBy='.$uID;
                }
                else{
                    $uids=implode('-',$uID);
                    $q[]="replyBy in (".implode(',',$uID).")";
                }
            }
            $cacheKey='msgAdminActivity'.$from.'_'.$to.'_'.$uids;
            $c=$this->db->reportCacheGet($cacheKey);
            if($c!=false&&!isset($_GET['flush'])){
                $t=$c;
            }
            else{
                $dQuery='where '.implode(' and ',$q);
                $q[]='sendType=2';//এডমিন যেগুলো সেন্ড করবে সেগুলো ডান হবে না।
                $query='where '.implode(' and ',$q);
                if($echo!='No'&&$echo!='array'){
                    $this->general->printArray($cacheKey);
                    $this->general->printArray($query);
                }
                $com =$this->db->selectAll($this->general->table(9),$query,'count(mid) as total',$echo,$jArray);
                $d=0;if(!empty($com)){$d=intval($com[0]['total']);}

                $com =$this->db->selectAll($this->general->table(9),$dQuery.' and isDone=1',' count(mid) as total',$echo,$jArray);
                $d2=0;if(!empty($com)){$d2=intval($com[0]['total']);}
                $t=$d+$d2;
            }
            return $t;

        }        
        function messageIn($from,$to,$uID=0,$echo='No'){
            $uids=$uID;
            if($uID!=0){
                if(!is_array($uID)){
                }
                else{
                    $uids=implode('-',$uID);
                }
            }
            $cacheKey='msgIn'.$from.'_'.$to.'_'.$uids;
            $c=$this->db->reportCacheGet($cacheKey);
            if($c!=false&&!isset($_GET['flush'])){
                $d=$c;
            }
            else{
                $com    =$this->db->selectAll($this->general->table(9),' WHERE sendType=1 and sendTime between '.$from. ' AND '.$to,' count(mid) as total');
                $d=0;if(!empty($com)){$d=intval($com[0]['total']);}
            }
            return $d;
        }


    }
?>