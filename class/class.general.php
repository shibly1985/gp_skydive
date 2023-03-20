<?php
    class General
    {

        private $con;
        function __construct(){
            $this->con=$GLOBALS['connection'];
        } 

        public function table($no){
            $tbl_1  = 'cor_module';                     $tbl_2  = 'companyinfo';
            $tbl_3  = 'site_settings';                  $tbl_4  = 'post_status';
            $tbl_5  = 'assignment_wall';                $tbl_6  = 'assign_group';                   
            $tbl_7  = 'senders';                        $tbl_8  = 'comments_wall_like';
            $tbl_9  = 'messages';                       $tbl_10 = 'assignment_comments_status';
            $tbl_11 = 'wrapup';                         $tbl_12 = 'post_wall';
            $tbl_13 = 'comments_status';                $tbl_14 = 'comments_wall';
            $tbl_15 = 'comments_status_delete';         $tbl_16 = 'messages_sender';
            $tbl_17 = 'useraccount';                    $tbl_18 = 'user_login_session';
            $tbl_19 = 'permissions';                    $tbl_20 = 'baned_sender';
            $tbl_21 = 'role_permission';                $tbl_22 = 'usergroup';
            $tbl_23 = 'msg_template';                   $tbl_24 = 'fav_msg_template';
            $tbl_25 = 'post_wall_delete';               $tbl_26 = 'comments_status_delete';
            $tbl_27 = 'comments_status_like';           $tbl_28 = 'post_wall_like';
            $tbl_29 = 'cor_module_permissions';         $tbl_30 = 'post_status_photos'; 
            $tbl_31 = 'comments_status_hide';           $tbl_32 = 'comments_wall_hide'; 
            $tbl_33 = 'senders';                        $tbl_34 = 'wrapup_category'; 
            $tbl_35 = 'post_wall_hide';                 $tbl_36 = 'assignment_comments_wall'; 
            $tbl_37 = 'user_login_session_activity';    $tbl_38 = 'user_login_session_activity_summery'; 
            $tbl_39 = 'comments_wall_delete';           $tbl_40 = 'report_cach';
            $tbl_41 = 'queue_comments_status';          $tbl_42 = 'queue_comments_wall'; 
            $tbl_43 = 'user_break_time_tracker';        $tbl_44 = 'assigned_comment_status'; 
            $tbl_45 = 'comments_status_hour_report_1';  $tbl_46 = 'comments_status_hour_report_2'; 
            $tbl_47 = 'post_wall_report_1';             $tbl_48 = 'post_wall_report_2'; 
            $tbl_49 = 'comments_wall_report_1';         $tbl_50 = 'comments_wall_report_2'; 
            $tbl_51 = 'comment_hourly_report';          $tbl_52 = 'dashboard_report'; 
            $tbl_53 = 'user_break_reason';              $tbl_54 = 'assignment_comments_status_tracker';
            $tbl_55 = 'assignment_comments_wall_tracker';$tbl_56 = 'assignment_wall_tracker';
            $tbl_57 = 'assigned_wall';                  $tbl_58 = 'bulk_send_comment_status';
            $tbl_59 = 'cron_log';                       $tbl_60 = 'display_cach';
            $tbl_61 = 'attachment_file';                $tbl_62 = 'assigned_message';
            $tbl_63 = 'no_rep_comments_status';         $tbl_64 = 'no_rep_post_wall';
            $tbl_65 = 'no_rep_comments_wall';           $tbl_66 = 'no_rep_messages';
            $tbl_67 = 'no_rep_messages_sender';         $tbl_68 = '';


            $tbl = 'tbl_'.$no;
            return ${$tbl};
        }
        public function arrayIndexChange(&$array,$arrayIndex){
            $return = array();
            if(is_array($array)){
                foreach($array as $a){
                    $return[$a[$arrayIndex]]=$a;
                }
            }
            $array=$return;
            return $return;
        }
        public function ArrayTowDimensionalKeySearch($array,$value){
            $r='false';
            foreach($array as $k=>$v){
                if(in_array($value,$v)){$r=$k;break;}
            }
            return $r;
        }
        public function arraySortMaxtToMinWithKey(&$array){
            $d=$array;
            rsort($d);
            $n=array();
            foreach($d as $b){
                while($c=array_search ($b, $array)){
                    unset($array[$c]);
                    $n[$c]=$b;
                }
            }
            $array=$n;
        }
        //array_sort_by_column($allPurchases, 'ccTitle',SORT_DESC);
        public function arraySortByColumn(&$arr, $col, $dir = SORT_ASC) {
            $sort_col = array();
            foreach ($arr as $key=> $row){$sort_col[$key]=$row[$col];}
            array_multisort($sort_col, $dir, $arr);
        }
        public function arrayValueIntval(&$array){
            $intArray = array();
            foreach($array as $k=>$a){
                if(!is_array($a)){$intArray[$k]=intval($a);}
                else{$this->arrayValueIntval($a);}
            }
            $array=$intArray;
        }
        public function breadcrumb($data,$extraHtml=''){
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="btn-group btn-breadcrumb">
                <a class="btn btn-default" href="<?=URL?>">Home</a>
                <?php
                    foreach($data as $d=>$key){
                        if($d == 1){
                        ?>
                        <span class="active"><?=$key?></span>
                        <?php
                        }
                        else{
                        ?>
                        <a class="btn btn-default" href="<?=$d?>"><?=$key?></a>
                        <?php
                        }
                    }    
                ?>
            </div>
            <div style="float: right;"><?=$extraHtml?></div>
        </div>

        <?php             
        }
        public function make_url($requestUrl){
            return $url=strtolower(preg_replace('#[^\w-]#',"",str_ireplace(' ', '_',trim($requestUrl))));
        }
        /*public function hourDropdown($selectedTime){
        ?>
        <option value="1" <?=$this->selected(date("G",$selectedTime),1)?>>1 AM</option>
        <option value="2" <?=$this->selected(date("G",$selectedTime),2)?>>2 AM</option>
        <option value="3" <?=$this->selected(date("G",$selectedTime),3)?>>3 AM</option>
        <option value="4" <?=$this->selected(date("G",$selectedTime),4)?>>4 AM</option>
        <option value="5" <?=$this->selected(date("G",$selectedTime),5)?>>5 AM</option>
        <option value="6" <?=$this->selected(date("G",$selectedTime),6)?>>6 AM</option>
        <option value="7" <?=$this->selected(date("G",$selectedTime),7)?>>7 AM</option>
        <option value="8" <?=$this->selected(date("G",$selectedTime),8)?>>8 AM</option>
        <option value="9" <?=$this->selected(date("G",$selectedTime),9)?>>9 AM</option>
        <option value="10" <?=$this->selected(date("G",$selectedTime),10)?>>10 AM</option>
        <option value="11" <?=$this->selected(date("G",$selectedTime),11)?>>11 AM</option>
        <option value="12" <?=$this->selected(date("G",$selectedTime),12)?>>12 PM</option>
        <option value="13" <?=$this->selected(date("G",$selectedTime),13)?>>1 PM</option>
        <option value="14" <?=$this->selected(date("G",$selectedTime),14)?>>2 PM</option>
        <option value="15" <?=$this->selected(date("G",$selectedTime),15)?>>3 PM</option>
        <option value="16" <?=$this->selected(date("G",$selectedTime),16)?>>4 PM</option>
        <option value="17" <?=$this->selected(date("G",$selectedTime),17)?>>5 PM</option>
        <option value="18" <?=$this->selected(date("G",$selectedTime),18)?>>6 PM</option>
        <option value="19" <?=$this->selected(date("G",$selectedTime),19)?>>7 PM</option>
        <option value="20" <?=$this->selected(date("G",$selectedTime),20)?>>8 PM</option>
        <option value="21" <?=$this->selected(date("G",$selectedTime),21)?>>9 PM</option>
        <option value="22" <?=$this->selected(date("G",$selectedTime),22)?>>10 PM</option>
        <option value="23" <?=$this->selected(date("G",$selectedTime),23)?>>11 PM</option>
        <option value="0" <?=$this->selected(date("G",$selectedTime),0)?>>12 AM</option>
        <?php
        }
        public function minuteDropdown($selectedTime){
        for($i=0;$i<=59;$i++){ ?><option value="<?=$i?>" <?=$this->selected(intval(date('i',$selectedTime)),$i)?>><?=str_pad($i,2,0,STR_PAD_LEFT)?></option><?php }
        }*/
        public function make_future_timestamp($day,$timestamp){return strtotime("+$day day", $timestamp);}
        public function timestampDiffInArray($t1,$t2,$inSecond=false,$echo='No'){
            if($t1==0||$t2==0)return 0;
            $avg=array('m'=>0,'d'=>0,'h'=>0,'i'=>0,'s'=>0);
            $d1=date('Y-m-d H:i:s',$t1);
            $d2=date('Y-m-d H:i:s',$t2);
            if($echo!='No'){
                echo $d1;echo'<br>';  
                echo $d2;echo'<br>';  
            }
            $datetime1 = date_create($d1);
            $datetime2 = date_create($d2);
            $interval = date_diff( $datetime1,$datetime2);
            $day=$interval->format('%a');
            if($day>0){
                $month=$interval->format('%m');
                if($month>0){
                    $avg['m']=$interval->format('%m');
                }
                $avg['d']=$interval->format('%d');
            }
            $avg['h']=$interval->format('%h');
            $avg['i']=$interval->format('%i');
            $avg['s']=$interval->format('%s');
            if($inSecond===true){
                if($avg['m']!=0){$avg['d']+=$avg['m']*30;$avg['m']=0;}
                if($avg['d']!=0){$avg['h']+=$avg['d']*24;$avg['d']=0;}
                if($avg['h']!=0){$avg['i']+=$avg['h']*60;$avg['h']=0;}
                if($avg['i']!=0){$avg['s']+=$avg['i']*60;$avg['i']=0;}
                $avg=$avg['s'];
            }
            return $avg;
        }
        public function timestampDiff($t1,$t2,$echo='No'){
            $avg=array();
            $d1=date('Y-m-d H:i:s',$t1);
            $d2=date('Y-m-d H:i:s',$t2);
            if($echo!='No'){
                echo $d1;echo'<br>';  
                echo $d2;echo'<br>';  
            }
            $datetime1 = date_create($d1);
            $datetime2 = date_create($d2);
            $interval = date_diff( $datetime1,$datetime2);
            $day=$interval->format('%a');
            if($day>0){
                $month=$interval->format('%m');
                if($month>0){
                    $avg['month']=$interval->format('%m');
                }
                $avg['day']=$interval->format('%d');
            }
            $h=$interval->format('%H');
            $i=$interval->format('%I');
            $s=$interval->format('%S');
            $r='';
            foreach($avg as $k=>$v){
                $r.=$v.' '.ucwords($k);
                if($v>1)$r.='s';
                $r.=' ';
            }
            $r.= ' '.$h.':'.$i.':'.$s;
            return $r;
        }
        public function make_date($timestamp,$st='') {
            if($timestamp==''||$timestamp==0){return '';}
            else{
                if($st){
                    if($st== 'time'){
                        return $date=date('d-m-Y h:i:s A', $timestamp);
                    }
                    elseif($st == 'y_m_d'){
                        return $date=date('y-m-d', $timestamp);
                    }
                    elseif($st == 'm_d_y'){
                        return $date=date('m/d/Y', $timestamp);
                    }
                    elseif($st == 'd/m/y'){
                        return $date=date('m/d/Y', $timestamp);
                    }
                    elseif($st == 'd_m_y'){
                        return $date=date('d-m-Y', $timestamp);
                    }
                    elseif($st == 't'){
                        return $date=date('h:i:s', $timestamp);
                    }
                    elseif($st == 'tam'){
                        return $date=date('h:i A', $timestamp);
                    }
                    else{
                        return $date=date('d-m-Y', $timestamp);
                    }
                }
                else{
                    return $date=date('d-M-Y', $timestamp);
                }
            }
        }
        public function make_date_difference($start, $end){
            $diff=$start - $end;
            //return $diff;
            return round($diff/86400);
        }
        public function makeTimeAvgI($int){

            $r='';
            $day=floor($int/86400);
            if($day>0){
                $r=$day.' day ';
                $int=$int%86400;
            }
            $hour=floor($int/3600);
            if($hour>0){
                $hs=1;
                $r.=str_pad($hour,2,0,STR_PAD_LEFT).':';
                $int=$int%3600;
            }
            else{
                $r.='00:';
            }
            $min=floor($int/60);
            if($min>0){
                //if(isset($hs))$r.=':';
                $r.=str_pad($min,2,0,STR_PAD_LEFT).':';
                $ms=1;
                $int=$int%60;
            }
            else{
                $r.='00:';
            }

            if($int>0){
                $sec=$int%60;
                $r.=str_pad($sec,2,0,STR_PAD_LEFT);
            }
            else{
                $r.='00';
            }
            return $r;
        }
        public function makeTimeAvg($int){
            $avg=array();
            $day=round($int/86400);
            if($day>0){
                $avg['day']=$day;
                $int=$int%86400;
            }
            $hour=round($int/3600);
            if($hour>0){
                $avg['hour']=$hour;
                $int=$int%3600;
            }
            $min=round($int/60);
            if($min>0){
                $avg['min']=$min;
                $int=$int%60;
            }
            if($int>0){
                $avg['sec']=$int%60;
            }
            $r='';
            foreach($avg as $k=>$v){
                $r.=$v.' '.ucwords($k);
                if($v>1)$r.='s';
                $r.=' ';
            }
            if($r==''){$r=$int.' Sec';}
            return $r;
        }
        public function timestampDate($startTime,$endTime){
            if($startTime<$endTime){

            }
            else{return false;}
        }
        public function makeTimestampToDate($time){
            $time   = date('d-m-Y', $time);
            $time   = strtotime($time);
            return $this->make_future_timestamp(1,$time);
        }
        public function checked($valu1,$value2='myNameisSalam'){
            //  var_dump($valu1);var_dump($value2);
            if($value2 === 'myNameisSalam'){
                //   echo __LINE__;
                if($valu1 == 1){
                    $return = 'checked="checked"';
                }
                else{
                    $return = '';
                }
            }
            else{
                //  echo __LINE__;
                if(!is_array($valu1)){
                    if($valu1 == $value2){
                        $return = 'checked="checked"';
                        // echo $valu1.'-'.$value2;
                    }
                    else{
                        // echo $valu1.'--'.$value2;
                        $return = '';
                    }
                }
                else{
                    if(in_array($value2,$valu1)){
                        $return = 'checked="checked"';
                    }
                    else{
                        $return = '';
                    } 
                }
            }
            return $return;
        }
        public function selected($valu1,$value2){
            if(!is_array($valu1)){
                if($valu1 == $value2){
                    $return = 'selected="selected"';
                }
                else{
                    $return = '';
                }
            }
            else{
                if(in_array($value2,$valu1)){
                    $return = 'selected="selected"';
                }
                else{
                    $return = '';
                }
            }
            return $return;

        }
        public function content_show($content,$rn='no'){
            $content = html_entity_decode(stripcslashes($content));
            if($rn=='br'){
                $content = str_ireplace("\r\n",'<br>',$content);
            }
            elseif($rn=='n'){
                return  $content = str_ireplace('\n',"\n",$content);
            }
            $content=preg_replace('/\s+/', ' ', $content);
            return $content;
            // my mind out why return there. but it's work.
            $cont =
            str_ireplace('\r\n','',
                str_ireplace('\\','',
                    str_ireplace('"&quot;','"',
                        str_ireplace('&quot;"','"',
                            str_ireplace('\"','',$content)))));

            $cont =
            str_ireplace('"&quot;','"',
                str_ireplace('&quot;"','"',$cont));
            $cont=preg_replace('/\s+/', '', $cont);
            return $cont;
        }
        public function arrayContentShow(&$v) {
            $data = $v;
            $output = array();
            if(is_array($data)) {foreach($data as $k=>$d) {$output[$k] = $this->arrayContentShow($d);}}
            else{$output = $this->content_show($data);}
            $v = $output;
            return $v;
        }
        public function arrayTabRemove(&$v) {
            $data = $v;
            $output = array();
            if(is_array($data)) {foreach($data as $k=>$d) {$output[$k] = $this->arrayTabRemove($d);}}
            else{
                $output=preg_replace('/\s+/', ' ',$data);
            }
            $v = $output;
            return $v;
        }
        public function redirect($url,$message=''){
            if($message!=''){
                if(!is_array($message)){$a=func_get_args();$n=func_num_args();$m=array();for($i=1;$i<$n;$i++){$m[]=$a[$i];}}else{$m=$message;}
                SetMessage($m);
            }
            header('location:'.$url);exit();
        }
        public function pagination_init_customQuery($query,$rowPerPage, $currentPage){ 
            $numbers         = mysqli_query($this->con,$query);
            $numRows         = mysqli_num_rows($numbers);
            $a['total']      = $numRows;
            $a['TotalPage']  =ceil($numRows / $rowPerPage);
            $a['currentPage']=intval($currentPage);

            if ($a['currentPage'] > $a['TotalPage'])
            {
                $a['currentPage']=$a['TotalPage'];
            }

            if ($a['currentPage'] < 1 || !$a['currentPage'])
            {
                $a['currentPage']=1;
            }

            $a['start']=($a['currentPage'] - 1) * $rowPerPage;
            $a['limit']=" LIMIT " . $a['start'] . ", " . $rowPerPage;

            if ($a['start'] < 1)
            {
                $a['start']=1;
            }
            else
            {
                $a['start']=$a['start'] + 1;
            }

            return $a;
        }
        public function pagination_init($table,$rowPerPage, $currentPage, $where = ''){ 
            $query           = "SELECT COUNT(*) FROM `$table` $where";
            $numbers         = mysqli_query($this->con,$query);
            $totalrows       = mysqli_fetch_row($numbers);
            $numRows         = $totalrows[0];
            $a['total']      = $numRows;
            $a['TotalPage']  =ceil($numRows / $rowPerPage);
            $a['currentPage']=intval($currentPage);

            if ($a['currentPage'] > $a['TotalPage'])
            {
                $a['currentPage']=$a['TotalPage'];
            }

            if ($a['currentPage'] < 1 || !$a['currentPage'])
            {
                $a['currentPage']=1;
            }

            $a['start']=($a['currentPage'] - 1) * $rowPerPage;
            $a['limit']=" LIMIT " . $a['start'] . ", " . $rowPerPage;

            if ($a['start'] < 1)
            {
                $a['start']=1;
            }
            else
            {
                $a['start']=$a['start'] + 1;
            }

            return $a;
        }
        public function pagination($currentPage,$totalPage, $pageLink,$class=''){
            if(is_array($pageLink)){
                $link   = $pageLink[0];
                $link2  = '?'.$pageLink[1];
            }
            else{
                $link = $pageLink;
                $link2 = '';
            }
            if($class == ''){
                $class = 'btn-box';
            }

            if ($currentPage > 1){
                $prvPage=$currentPage - 1;
                echo "<li><a class='".$class."' href='".$link."1".$link2."'> First </a></li> <li><a class='".$class."' href='$link$prvPage$link2'> Prev </a></li> ";
            }

            $range=3;

            for ($x=($currentPage - $range); $x < (($currentPage + $range) + 1); $x++){
                if (($x > 0) && ($x <= $totalPage)){
                    if ($x == $currentPage){
                        echo " <li><a class='".$class." active'>$x</a></li>  ";
                    }
                    else{
                        echo "<li><a class='".$class."' href='$link$x$link2'>$x</a></li>";
                    }
                }
            }

            if ($currentPage != $totalPage){
                $nextPage=$currentPage + 1;
                echo "<li><a class='".$class."' href='$link$nextPage$link2'> Next </a></li> <li><a class='".$class."' href='$link$totalPage$link2'> Last </a></li>";
            }
        }
        public function paginationAjax($currentPage,$totalPage, $script){
            $class='btn';
            if ($currentPage > 1){
                $prvPage=$currentPage - 1;
                $s=str_ireplace('SET_PAGE_NAME',1,$script);
                echo '
                <li>
                <span>
                <a class="'.$class.'" href="javascript:void();" onclick="'.$s.'"> First </a>
                </span>
                </li>';
                $s=str_ireplace('SET_PAGE_NAME',$prvPage,$script);
                echo '
                <li>
                <span>
                <a class="'.$class.'" href="javascript:void();"  onclick="'.$s.'"> Prev </a>
                </span>
                </li> ';
            }

            $range=3;

            for ($x=($currentPage - $range); $x < (($currentPage + $range) + 1); $x++){

                if (($x > 0) && ($x <= $totalPage)){
                    if ($x == $currentPage){
                        echo '<li class=current><span>'.$x.'</span></li>';
                    }
                    else{
                        $s=str_ireplace('SET_PAGE_NAME',$x,$script);
                        echo '<li><a class="'.$class.'" href="javascript:void()" onclick="'.$s.'">'.$x.'</a></li>';
                    }
                }
            }

            if ($currentPage < $totalPage){
                $nextPage=$currentPage + 1;
                $s=str_ireplace('SET_PAGE_NAME',$nextPage,$script);
                echo '<li><a class="'.$class.'" href="javascript:void();" onclick="'.$s.'"> Next </a></li>';
                $s=str_ireplace('SET_PAGE_NAME',$totalPage,$script);
                echo '<li><a class="'.$class.'" href="javascript:void();" onclick="'.$s.'"> Last </a></li>';
            }
        }
        public function word_limit($text, $limit=70){
            $explode = explode(' ',$text);
            $string  = '';
            if(count($explode)>$limit){
                $dots = '...';
                if(count($explode) <= $limit){
                    $dots = '';
                }
                for($i=0;$i<$limit;$i++){
                    $string .= $explode[$i]." ";
                }

                return $string.$dots;
            }
            else{
                return $text;
            }

        }
        public function messageSplit($text,$attachment=''){
            $length=640;
            $m=array();
            if(strlen($text)>$length){
                $v=explode(' ',$text);
                $tm='';
                $i=0;
                foreach($v as $d){
                    $tm=trim($m[$i].' '.$d);
                    if(strlen($tm)>$length){
                        $i++;
                        $m[$i]=trim($m[$i].' '.$d);
                    }
                    else{
                        $m[$i]=trim($m[$i].' '.$d);    
                    }

                }   
            }
            else{
                if($text!=''){
                    $m[]=$text;
                }
            }
            if(is_array($attachment)){$m[]=$attachment;}
            return $m;
        }
        public function onclickChangeBTN($id,$checkest=''){
        ?>
        <input type="checkbox"
            class="check_box" <?=$checkest?>
            onclick="actinact('<?=$id?>',this.checked);"
            id="act_<?=$id?>"
            name="act_<?=$id?>">
        <label for="act_<?=$id?>"></label>
        <?php
        }
        public function onclickChangeJavaScript($tbl,$name){
        ?>
        <script type="text/javascript">
            function actinact(pId,chekValue){
                var stch = '<?=$tbl?>';
                var name = '<?=$name?>';
                if(chekValue==true){var ch=1;}else{var ch=0;}
                if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
                else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
                xmlhttp.onreadystatechange=function(){
                    if (xmlhttp.readyState==4 && xmlhttp.status==200){}
                }
                xmlhttp.open("GET","?ajax=1&stch="+stch+"&ch_id="+pId+'&action='+ch+'&name='+name,true);
                xmlhttp.send();
            }
        </script>
        <?php
        }
        public function remove_html($s, $l,$e='&hellip;',$isHTML = true){
            $s = trim($s);
            $e = (strlen(strip_tags($s)) > $l) ? $e : '';
            $i = 0;
            $tags = array();
            if($isHTML) {
                preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach($m as $o) {
                    if($o[0][1] - $i >= $l) {
                        break;                  
                    }
                    $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                    if($t[0] != '/') {
                        $tags[] = $t;                   
                    }
                    elseif(end($tags) == substr($t, 1)) {
                        array_pop($tags);                   
                    }
                    $i += $o[1][1] - $o[0][1];
                }
            }
            $output = substr($s, 0, $l = min(strlen($s), $l + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . $e;
            return $output;
        }
        public function percentage($percent,$percenNumber){$percent=floatval($percent);$percenNumber=floatval($percenNumber);$b=$percent*$percenNumber;$a=$b/100;return $a;}
        public function percentageFrom($percenNumber,$from){
            $percent=floatval($from);
            $percenNumber=floatval($percenNumber);
            $b=$percent/$percenNumber;
            $a=$b*100;return $a;}
        public function sorting($currentSby,$cOrd,$sby,$mUrl='No'){
            $url = '&sby='.$sby.'&ord=';
            if($currentSby == ''){
                if($mUrl!='No'){$url=$mUrl.$url;}
                $url .= 'a';
            }
            else{
                if($mUrl != 'No'){
                    if($currentSby==$sby){if($cOrd=='a'){$url.='d';}else{$url.= 'a';}}else{$url.= 'a';}
                    $url=$mUrl.$url;
                }
                else{$url.= $cOrd;}
            }
            return $url;
        }
        public function sortingFunction($fName,$sby,$cSby,$cOrd){
            if($sby==$cSby){if($cOrd=='a'){$ord='d';}else{$ord='a';}}else{$ord='a';}
            $r=$fName."('".$sby."','".$ord."')";
            return $r;
        }
        public function JSONsortingFunction($sby,$cSby,$cOrd){
            if($sby==$cSby){if($cOrd=='a'){$ord='d';}else{$ord='a';}}else{$ord='a';}
            return '[{"sb":"'.$sby.'","or":"'.$ord.'"}]';
        }
        public function sortingTh($txt,$fName,$sby,$cSby,$cOrd,$class=''){

        ?><th <?=($class=='')?'':'class="'.$class.'"'?>><a href="javascript:void();" onclick="<?php echo $this->sortingFunction($fName,$sby,$cSby,$cOrd)?>"><?=$txt?></a></th><?php
        }
        public function utf8ize(&$array) {
            $intArray = array();
            foreach($array as $k=>$a){
                if(!is_array($a)){$intArray[$k]=utf8_encode($a);}
                else{$this->utf8ize($a);}
            }
            $array=$intArray;
            /*echo __LINE__;echo'<br>'; 
            if (is_array($arr)) { 
            echo __LINE__;echo'<br>';
            foreach ($arr as $k => $v) {
            echo __LINE__;echo'<br>';
            $arr[$k] = $this->utf8ize($v);
            echo __LINE__;echo'<br>';
            }
            } else{
            echo utf8_encode($arr);
            return utf8_encode($arr);
            }*/
        }
        public function printArray($array){echo'<pre>';print_r($array);echo'</pre>';}
        public function imageWidthReset($originalW,$originalH,$maxW){
            $ratio = $originalW / $originalH;
            $targetWidth = $maxH = min($maxW, max($originalW, $originalH));
            if ($ratio < 1) {$targetWidth = $maxH * $ratio;}
            else {$maxH = $targetWidth / $ratio;}
            return array($targetWidth,$maxH);
        }
        function intToUnicode($input){
            $numbers=array('০'=>0,'১'=>1,'২'=>2,'৩'=>3,'৪'=>4,'৫'=>5,'৬'=>6,'৭'=>7,'৮'=>8,'৯'=>9);
            $rt='';
            foreach($numbers as $k=>$n){
                $input=str_ireplace($v,$k,$input);
            }
            return $input;
        }
        function unicode2Int($input){
            $numbers=array('০'=>0,'১'=>1,'২'=>2,'৩'=>3,'৪'=>4,'৫'=>5,'৬'=>6,'৭'=>7,'৮'=>8,'৯'=>9);
            $rt='';
            for($i=0;$i<strlen($input);$i+=3){
                if(strlen($input)>=$i+3){
                    $st=@$input[$i].$input[$i+1].$input[$i+2];
                    if(array_key_exists($st,$numbers)){$rt.=$numbers[$st];}
                    else{$rt.=$st;}
                }else{$rt.=$input[$i];}
            }
            return $rt;
        }
        function searchOrder($sby,$ord,$sOrder,$defultOrd=''){
            if($ord!=''){if($ord=='a'){$orderBy='asc';}else{$orderBy='desc';}}else{$orderBy='asc';}
            if(array_key_exists($sby,$sOrder)){
                $searchOrder=' order by '.$sOrder[$sby];
            }else{
                if($defultOrd==''){
                    $searchOrder='';
                }
                else{
                    $searchOrder=' order by '.$defultOrd;
                }
            }
            if($searchOrder!='')$searchOrder.=' '.$orderBy;
            return $searchOrder;
        }
        public function search_result_show($searchValue,$result){
            return str_ireplace($searchValue,'<b>'.$searchValue.'</b>',$result);
        }
        public function searchQueryCreate($index,$column){
            $return=array();
            if(isset($_GET[$index])){
                $search=$_GET[$index];
                if(!empty($search)){
                    if(is_array($column)){
                        if($column['t']=='i'){
                            $return['q'] = $column['col']." = '".$search."'";
                        }else{
                            $return['q'] = $column." like '%".$search."%'";
                        }
                    }
                    else{
                        $return['q'] = $column." like '%".$search."%'";
                    }
                    $return['u'] = array($index,$search);
                }else{$return['q']='';$return['u']='';}
            }else{$return['q']='';$return['u']='';}
            return $return;
        }
        public function mEnc($text){return array('t'=>$text);}
        public function mDec($columName,$asColumn=''){
            if($asColumn==''){$asColumn=$columName;}
            return "AES_DECRYPT(".$columName.",'".AES_KEY."') as ".$asColumn;}
        public function dateDiff($time1, $time2, $precision = 10) {
            //            echo $time1.'-'.$time2;
            // If not numeric then convert texts to unix timestamps
            /*if (!is_int($time1)) {
            $time1 = strtotime($time1);
            }
            if (!is_int($time2)) {
            $time2 = strtotime($time2);
            }*/

            // If time1 is bigger than time2
            // Then swap time1 and time2
            if ($time1 > $time2) {
                $ttime = $time1;
                $time1 = $time2;
                $time2 = $ttime;
            }
            // Set up intervals and diffs arrays
            $intervals = array('year','month','day','hour','minute','second');        
            $diffs = array();

            // Loop thru all intervals
            foreach ($intervals as $interval) {                                            
                // Create temp time from time1 and interval
                $ttime = strtotime('+1 ' . $interval, $time1);
                // Set initial values
                $add = 1;
                $looped = 0;
                // Loop until temp time is smaller than time2
                //                echo '*'.$time2.'-'.$ttime.'*';
                while ($time2 >= $ttime) {
                    // Create new temp time from time1 and interval
                    $add++;
                    $ttime = strtotime("+" . $add . " " . $interval, $time1);
                    $looped++;
                }

                $time1 = strtotime("+" . $looped . " " . $interval, $time1);
                $diffs[$interval] = $looped;
            }
            $count = 0;
            $times = array();
            // Loop thru all diffs
            foreach ($diffs as $interval => $value) {
                // Break if we have needed precission
                if ($count >= $precision) {
                    break;
                }
                // Add value and interval 
                // if value is bigger than 0
                if ($value > 0) {
                    // Add s if value is not 1
                    if ($value != 1) {
                        $interval .= "s";
                    }
                    // Add value and interval to times array
                    $times[] = $value . " " . $interval;
                    $count++;
                }
            }

            // Return string with times
            return implode(", ", $times);
        }

        public function average_time($time1) {
            //            echo $time1.'-'.$time2;
            // If not numeric then convert texts to unix timestamps
            /*if (!is_int($time1)) {
            $time1 = strtotime($time1);
            }
            if (!is_int($time2)) {
            $time2 = strtotime($time2);
            }*/

            // If time1 is bigger than time2
            // Then swap time1 and time2
            if ($time1 > $time2) {
                $ttime = $time1;
                $time1 = $time2;
                $time2 = $ttime;
            }
            // Set up intervals and diffs arrays
            $intervals = array('year','month','day','hour','minute','second');        
            $diffs = array();

            // Loop thru all intervals
            foreach ($intervals as $interval) {                                            
                // Create temp time from time1 and interval
                $ttime = strtotime('+1 ' . $interval, $time1);
                // Set initial values
                $add = 1;
                $looped = 0;
                // Loop until temp time is smaller than time2
                //                echo '*'.$time2.'-'.$ttime.'*';
                while ($time2 >= $ttime) {
                    // Create new temp time from time1 and interval
                    $add++;
                    $ttime = strtotime("+" . $add . " " . $interval, $time1);
                    $looped++;
                }

                $time1 = strtotime("+" . $looped . " " . $interval, $time1);
                $diffs[$interval] = $looped;
            }
            $count = 0;
            $times = array();
            // Loop thru all diffs
            foreach ($diffs as $interval => $value) {
                // Break if we have needed precission
                if ($count >= $precision) {
                    break;
                }
                // Add value and interval 
                // if value is bigger than 0
                if ($value > 0) {
                    // Add s if value is not 1
                    if ($value != 1) {
                        $interval .= "s";
                    }
                    // Add value and interval to times array
                    $times[] = $value . " " . $interval;
                    $count++;
                }
            }

            // Return string with times
            return implode(", ", $times);
        }

        public function convert_number_to_words($number){
            //            $number=str_ireplace(',','',$number);
            $hyphen      = '-';
            $conjunction = ' and ';
            $separator   = ', ';
            $negative    = 'negative ';
            $decimal     = ' point ';
            $dictionary  = array(
                0                   => 'zero',
                1                   => 'one',
                2                   => 'two',
                3                   => 'three',
                4                   => 'four',
                5                   => 'five',
                6                   => 'six',
                7                   => 'seven',
                8                   => 'eight',
                9                   => 'nine',
                10                  => 'ten',
                11                  => 'eleven',
                12                  => 'twelve',
                13                  => 'thirteen',
                14                  => 'fourteen',
                15                  => 'fifteen',
                16                  => 'sixteen',
                17                  => 'seventeen',
                18                  => 'eighteen',
                19                  => 'nineteen',
                20                  => 'twenty',
                30                  => 'thirty',
                40                  => 'fourty',
                50                  => 'fifty',
                60                  => 'sixty',
                70                  => 'seventy',
                80                  => 'eighty',
                90                  => 'ninety',
                100                 => 'hundred',
                1000                => 'thousand',
                1000000             => 'million',
                1000000000          => 'billion',
                1000000000000       => 'trillion',
                1000000000000000    => 'quadrillion',
                1000000000000000000 => 'quintillion'
            );

            if (!is_numeric($number)) {return false;}

            if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
                // overflow
                trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                    E_USER_WARNING
                );
                return false;
            }

            if ($number < 0) {
                return $negative . $this->convert_number_to_words(abs($number));
            }

            $string = $fraction = null;

            $d = explode('.',$number);if(isset($d[1])){if(intval($d[1])==0){$number=$d[0];}}

            if (strpos($number, '.') !== false) {
                list($number, $fraction) = explode('.', $number);
            }

            switch (true) {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens   = ((int) ($number / 10)) * 10;
                    $units  = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                    break;
                case $number < 1000:
                    $hundreds  = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction .$this->convert_number_to_words($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= $this->convert_number_to_words($remainder);
                    }
                    break;
            }

            if (null !== $fraction && is_numeric($fraction)) {
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $dictionary[$number];
                }
                $string .= implode(' ', $words);
            }
            $string=str_ireplace('Only.','',$string);
            if($string!='')$string.=' Only.';
            $string=str_ireplace('  ',' ',$string);

            return ucfirst($string);
        }
        public function tableScrolScript($clasName='',$tf='f'){
            if($clasName=='')$clasName='table_fixed_header';
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.<?=$clasName?>').fixedHeaderTable({footer: true });
                $('.<?=$clasName?>').fixedHeaderTable('show', 700);
            });
        </script>
        <?php
        }
        public function taskCurrentStatus($tStatus){
            $db=new DB();
            $ts=$db->get_rowData($this->table(15),'tsID',$tStatus);
            if(!empty($ts)){
                $tStatus=$ts['tsTitle'];
            }else{
                $tStatus='Unknown';
            }

            return $tStatus;
        }
        public function getRatio($kotorJonno,$kotoDiba,$akonKotoAse){return (($kotoDiba*$akonKotoAse)/$kotorJonno);}
        public  function imageRation($originalWidth,$originalHeight,$targetWidth,$targetHeight){
            $ratio = $originalWidth / $originalHeight;
            if ($ratio < 1) {
                $targetWidth = $targetHeight * $ratio;
            } else {
                $targetHeight = $targetWidth / $ratio;
            }
            return array($targetWidth,$targetHeight);
        }
        public function arrayUserInfoAdd(&$array){
            $array['createdBy']=UID;
            $array['createdOn']=TIME;
            $array['modifiedBy']=UID;
            $array['modifiedOn']=TIME;
        }
        public function comments_usarname($names){
            $v ='v2.3';
            $fb = new Facebook\Facebook([
                'app_id' => APPID,
                'app_secret' => APPSECRET,
                'default_graph_version' => $v,
            ]);
            $data=array();
            if(!empty($names)){
                $response = $fb->get('/?fields=name&ids='.implode(',',$names));
                $i=0;
                foreach($response->getDecodedBody() as $d){
                    $data['name'][$i]['id']=$d['id'];
                    $data['name'][$i]['name']=$d['name'];
                    $i++;
                }

            }
            return $data;
        }

        function fileToVariable($path) {
            $pageContent = '';
            include($path);
            $pageContent = ob_get_contents();
            ob_end_clean();
            return $pageContent;
        }
        function jsonHeader($jArray=array()){header('Content-Type: application/json');if(!empty($jArray)){echo json_encode($jArray);exit();}}
        function makeGraphArray($graphData){
            $fulData=array();
            foreach($graphData as $id=>$g){
                if(isset($g['data'])){
                    $fulData[$id]['title']=$g['title'];
                    $wrapusData=$g['data'];
                    $t=array();
                    foreach($wrapusData as $w){
                        foreach($w as $k=>$b){
                            $t[$k][]=$b;
                        }
                    }

                    foreach($t as $k=>$b){
                        $fulData[$id]['serises'][$k]=$b;
                    }
                    $fulData[$id]['legend']=array_keys($t);
                    $fulData[$id]['xData']=array_keys($wrapusData);
                }
            }
            return $fulData;
        }
        function makeGraph($graphData){
            foreach($graphData as $id=>$g){
                if(isset($g['data'])){
                    $wrapusData=$g['data'];
                    $t=array();
                    foreach($wrapusData as $w){
                        foreach($w as $k=>$b){
                            $t[$k][]=$b;
                        }
                    }
                ?>
                <script type="text/javascript">
                    var graphData={
                        serises : [
                            <?php
                                foreach($t as $k=>$b){
                                ?>{
                                    name: '<?php echo $k;?>',
                                    type: 'line',
                                    smooth: true,
                                    data: [<?php echo "'".implode("','",$b)."'";?>]
                                },
                                <?php
                                }
                        ?>],
                        xData:[<?php echo '"'.implode('","',array_keys($wrapusData)).'"';?>]
                    }

                    var echartLine = echarts.init(document.getElementById('<?php echo $id;?>'));
                    echartLine.setOption({
                        title: {
                            text: '<?php echo $g['title'];?>'
                        },
                        tooltip: {
                            trigger: 'line'
                        },
                        legend: {
                            x: 60,
                            y: 30,
                            data: [<?php echo '"'.implode('","',array_keys($t)).'"';?>]
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                magicType: {
                                    show: true,
                                    title: {line: 'Line',bar: 'Bar',stack: 'Stack',tiled: 'Tiled'},
                                    type: ['line', 'bar', 'stack', 'tiled']
                                },restore: {
                                    show: true,title: "Restore"},
                                saveAsImage: {show: true,title: "Save Image"}
                            }
                        },
                        calculable: true,
                        xAxis: [{type: 'category',
                            boundaryGap: false,
                            data: graphData.xData
                        }],
                        yAxis: [{type: 'value'}],
                        series:graphData.serises
                    });
                </script>
                <?php
                }
            }
        }
        function getScentimentName($s){
            if($s==SCENTIMENT_TYPE_POSITIVE){$r='Positive';}
            else if($s==SCENTIMENT_TYPE_NEGETIVE){$r='Negetive';}
                else if($s==SCENTIMENT_TYPE_NUTRAL){$r='Nutral';}
                    else{$r='';}
            return $r;
        }
        function showQuery(){
            $echo='No';if(isset($_GET['showq']))$echo='a';return $echo;
        }
    }
?>