<?php
    define('SHWO_ALL_QUERY','No');//when not No then show all query
    //        define('SHWO_ALL_QUERY','Yes');//when not No then show all query

    include 'class.db.connection.php';
    class DB
    {
        private $general;
        private $con;

        function __construct(){
            $this->general  = new general();
            $this->con=$GLOBALS['connection'];
        }
        public function allUsers($query=''){
            return $this->selectAll($this->general->table(17),'where ugID!='.SUPERADMIN_USER.' '.$query,'uID,ugID,uFullName,uLoginName');
        }
        public function allGroups($query=''){
            return $this->selectAll($this->general->table(22),'where ugID!='.SUPERADMIN_USER.' and isActive=1 '.$query);
        }
        public function update($table, $data, $where,$echo='No'){
            $count = count($data);$where_count = count($where);$sql = "UPDATE `".$table."` SET";
            $start = 0;
            foreach ($data as $k => $v){$start = $start + 1;if($start == $count){ $sql .= " `".$k."` = '".$this->esc($v)."'"; } else { $sql .= " `".$k."` = '".$this->esc($v)."', "; }
            }
            $sql .= " WHERE ";
            if($where_count == 1){ foreach($where as $m => $n) { $sql .= "`".$m."` = '".$this->esc($n)."'"; } }
            else{$x=0;foreach($where as $m=>$n){
                $x = $x + 1;if($x == $where_count){$sql.= "`".$m."`='".$this->esc($n)."'";}else{$sql.= "`".$m."`='".$this->esc($n)."' and ";}}}
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){echo $sql."<br>";}
            $update = mysqli_query($this->con,$sql);
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){echo mysqli_error($this->con);}
            if($update){
                if($echo=='array'){return $sql.' err='.mysqli_error($this->con);}
                else{return true;}
            }else{
                if($echo=='array'){return $sql.' err='.mysqli_error($this->con);}
                else{return false;}
            }
        }
        public function insert($table,$data,$getId='',$echo='No'){
            $count = count($data);
            $sql = "INSERT INTO `".$table."` (";
            $start=0;
            foreach($data as $k=>$v){$start=$start+1;if($start==$count){$sql.= "`".$k."`";}else{$sql.="`".$k."`,";}}
            $sql.= ") value (";
            $number=0;
            foreach($data as $k=>$v){
                $number=$number+1;if($number==$count){
                    $sql.="'".$this->esc($v)."'";
                }else{
                    $sql.="'".$this->esc($v)."', ";
            }}
            $sql.= ")";if($echo!='No'||SHWO_ALL_QUERY!='No'){if($echo!='array'){echo $sql.'<br>';}}
            $insert=mysqli_query($this->con,$sql);
            if($echo!='No'||SHWO_ALL_QUERY!='No'){
                if($echo!='array'){echo mysqli_error($this->con).'<br>';}
            }
            if($insert){
                if($getId=='getId'){ return mysqli_insert_id($this->con);}
                else{
                    if($echo!='array'){return true;}else{return $sql.'  err= '.mysqli_error($this->con);}
                }
            }else{
                if($echo!='array'){return false;}else{return $sql.'  err= '.mysqli_error($this->con);}
            }
        }
        public function lastError(){
            return mysqli_error($this->con);
        }
        public function insertEnc($table,$data,$getId='',$echo='No'){
            $count = count($data);
            $sql = "INSERT INTO `".$table."` (";
            $start=0;
            foreach($data as $k=>$v){$start=$start+1;if($start==$count){$sql.= "`".$k."`";}else{$sql.="`".$k."`,";}}
            $sql.= ") value (";
            $number=0;
            foreach($data as $k=>$v){
                if(!is_array($v)){
                    $sql.="'".$this->esc($v)."'";
                }else{
                    $sql.="AES_ENCRYPT('".$this->esc($v['t'])."','".AES_KEY."')";
                }
                $number=$number+1;
                if($number!=$count){$sql.=',';}
            }
            $sql.= ")";if($echo!='No'||SHWO_ALL_QUERY!='No'){if($echo!='array'){echo $sql.'<br>';}}
            $insert=mysqli_query($this->con,$sql);
            if($echo!='No'||SHWO_ALL_QUERY!='No'){
                if($echo!='array'){echo mysqli_error($this->con).'<br>';}
            }
            if($insert){
                if($getId=='getId'){ return mysqli_insert_id($this->con);}
                else{
                    if($echo!='array'){return true;}else{return $sql.'  err= '.mysqli_error($this->con);}
                }
            }else{
                if($echo!='array'){return false;}else{return $sql.'  err= '.mysqli_error($this->con);}
            }
        }
        public function delete($table, $where,$echo = 'No'){
            $where_count = count($where);
            $sql = "DELETE FROM `".$table."` WHERE ";
            if($where_count == 1){foreach($where as $m => $n){$sql .= "`".$m."` = '".$this->esc($n)."'";}}
            else{$x = 0;foreach($where as $m => $n){$x = $x + 1;if($x == $where_count){$sql .= "`".$m."` = '".$this->esc($n)."'";} else {$sql .= "`".$m."` = '".$this->esc($n)."' and ";}}}
            if($echo != 'No'||SHWO_ALL_QUERY!='No'){echo $sql.'<br>'; }
            $delete = mysqli_query($this->con,$sql);if($delete)return true;else return false;
        }
        public function esc($string){
            if(SHOW_ERROR_LINE=='Yes'){
                if(is_array($string)){
                    $backtrace = debug_backtrace();
                    textFileWrite(date('d-m-Y h:i:s').' - '.json_encode($backtrace),'fb_error.txt');
                }
            }
            return mysqli_real_escape_string($this->con,$string);}
        public function get_data($tableName, $where, $wherevalue, $rowname,$echo = 'No'){
            if($echo != 'No'||SHWO_ALL_QUERY!='No'){
                echo "SELECT * FROM $tableName WHERE $where = '$wherevalue'<br>";
            }
            $sql = mysqli_query($this->con,"SELECT * FROM $tableName WHERE $where = '$wherevalue'");
            $row = mysqli_fetch_assoc($sql);
            return $row["$rowname"];
        }
        public function getData($tableName, $where, $rowname,$echo = 'No'){
            if($echo != 'No'||SHWO_ALL_QUERY!='No'){
                echo "SELECT * FROM $tableName $where '<br>";
            }
            $sql = mysqli_query($this->con,"SELECT * FROM $tableName $where");
            $row = mysqli_fetch_assoc($sql);
            return $row["$rowname"];
        }
        public function get_rowData($tableName, $where, $wherevalue,$echo='No'){
            $query="SELECT * FROM $tableName WHERE $where = '$wherevalue'";
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){
                echo $query."<br>";   
            }
            $sql = mysqli_query($this->con,$query);
            $row = mysqli_fetch_assoc($sql);
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '.json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){
                echo mysqli_error($this->con);
            }
            if($echo=='array'){
                $row['arrayData']=$query.' err='.mysqli_error($this->con);
            }
            return $row;

        }
        public function getRowData($tableName,$where,$echo='No'){
            $query="SELECT * FROM $tableName $where limit 1";
            $sql = mysqli_query($this->con,$query);
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){
                echo $query."<br>";
            }
            $row = mysqli_fetch_assoc($sql);
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '.json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){echo mysqli_error($this->con);}
            if($echo=='array'){
                $row['arrayData']=$query.' err='.mysqli_error($this->con);
            }
            //            if(mysqli_error($this->con)!=''){echo $query.' $err='. mysqli_error($this->con);}
            return $row;
        }
        public function getRowDataWithColumn($tableName,$where,$column,$echo='No',&$jArray=array()){
            $query="SELECT $column FROM $tableName $where limit 1";
            $sql = mysqli_query($this->con,$query);
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){
                echo $query."<br>";
            }
            $row = mysqli_fetch_assoc($sql);
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '.json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            if(($echo != 'No'&&$echo != 'array')||SHWO_ALL_QUERY!='No'){echo mysqli_error($this->con);}
            if($echo=='array'){
                $jArray[__LINE__]=$query.' err='.mysqli_error($this->con);
            }
            //            if(mysqli_error($this->con)!=''){echo $query.' $err='. mysqli_error($this->con);}
            return $row;
        }
        public function fetchQuery($query,$echo = 'No'){
            if($echo != 'No'||SHWO_ALL_QUERY!='No'){echo '<pre>'.$query.'</pre>';}
            $result = array();
            $all = mysqli_query($this->con,$query);
            while($table= mysqli_fetch_assoc($all)){$result[] = $table;}
            if($echo != 'No'||SHWO_ALL_QUERY!='No'){echo mysqli_error($this->con).'<br>';}
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '.json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            return $result;
        }
        public function runQuery($query,$echo = 'No',&$jArray=array()){
            if(($echo != 'No'||SHWO_ALL_QUERY!='No')&&$echo!='array'){echo '<pre>'.$query.'</pre>';}
            $all = mysqli_query($this->con,$query);
            if($echo=='array'){$jArray[__LINE__][]=$query.' error='.mysqli_error($this->con);}
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                if($echo=='array'){
                    $jArray[__LINE__]=$backtrace;
                }
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '. json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            if($echo=='array'){
                $jArray[__LINE__]=$query.' error='.mysqli_error($this->con);
            }

            if(($echo != 'No'||SHWO_ALL_QUERY!='No')&&$echo!='array'){echo '<pre>'.mysqli_error($this->con).'</pre>';}
            else{return $all;}
        }
        public function selectAll($table, $where='', $fields='*', $echo = 'No',&$jArray=array()){
            $result = array();
            $data = $this->esc($table);
            if($fields == ''){$fields = '*';}
            //            if($echo == 'No'){
            $query = "SELECT ".$fields." FROM $data $where";
            if($echo=='array'){
                $jArray[__LINE__][]=$query;
            }
            else{
                if($echo != 'No'||SHWO_ALL_QUERY!='No'){echo '<pre>'.$query.'</pre>';}
            }
            $all = mysqli_query($this->con,$query);
            while($table= mysqli_fetch_assoc($all)){$result[] = $table;}
            if(mysqli_error($this->con)!=''){
                $backtrace = debug_backtrace();
                textFileWrite(array('backtrace'=>date('d-m-Y h:i:s').' - '.json_encode($backtrace),'query'=>$query,'SQL Query error. '.mysqli_error($this->con)));
            }
            //if(mysqli_error($this->con)!=''){echo $query.' $err='. mysqli_error($this->con);}
            if($echo=='array'){
                $jArray[__LINE__][]=mysqli_error($this->con);
            }
            else{
                if($echo != 'No'||SHWO_ALL_QUERY!='No'){echo mysqli_error($this->con).'<br>';}
            }
            return $result;
        }
        public function check_available($table, $where ){
            $total = $this->selectAll($table,$where);$count = count($total);
            //echo "SELECT * FROM $table   $where<br>"; print_r($total); echo '<br>'.$count;
            if($count>0){return false;}else{return true;}
        }
        public function statusChance($table, $id, $whereFild, $where){
            // $tbl = 'tbl'.$table;

            mysqli_query($this->con,"UPDATE `$table` SET `status` = $id WHERE `$whereFild` = $where");
        }
        public function permissionSetForModule($ugID,$cmId,$st){
            if($st==1){
                $data = array('cmId'=>$cmId,'ugID'=>$ugID);
                $this->delete($this->general->table(29),$data);
                $insert = $this->insert($this->general->table(29),$data);
            }
            else{
                $where = array('cmId'=>$cmId,'ugID'=>$ugID);
                $this->delete($this->general->table(29),$where);
            }
        }
        public function permissionSetForPermission($ugID,$perID,$st){
            if($st==1){
                $data = array('perID'=>$perID,'ugID'=>$ugID);
                $this->delete($this->general->table(21),$data);
                $insert = $this->insert($this->general->table(21),$data);
            }
            else{
                $where = array('perID'=>$perID,'ugID'=>$ugID);
                $this->delete($this->general->table(21),$where);
            }
        }
        public function groupInfoByID($ugID,$echo='No'){ return $this->get_rowData($this->general->table(22),'ugID',$ugID,$echo);}
        public function login($userLogin){
            $thisUser = $this->get_rowData($this->general->table(5),'username',$userLogin);
            if(empty($thisUser)){
                unset($_SESSION['halfuser']);
                header('location: '.$this->general->stting->url);
                exit();

            }
        }
        public function userNameByID($uID){ return $this->get_data($this->general->table(17),'uID',$uID,'uFullName');}
        public function userInfoByID($uID){ return $this->get_rowData($this->general->table(17),'uID',$uID);}
        public function orderIncrement($tbl,$tIndex,$where=''){
            $order = mysqli_fetch_array(mysqli_query($this->con,"select max(".$tIndex.") from ".$tbl.' '.$where));
            //            echo "select max(".$tIndex.") from ".$tbl.' '.$where;
            $cOrder = intval($order[0])+1;
            return $cOrder;
        }
        public function dragNdropOrder($table,$no,$where=''){
            $id         = $this->site->tableOrdArray[$no]['id'];
            $order      = $this->site->tableOrdArray[$no]['order'];
            $title      = $this->site->tableOrdArray[$no]['title'];
            $mainArray  = $this->selectAll($this->general->table($table),$where.' order by '.$order);
        ?>
        <!--<script type="text/javascript" src="<?=URL?>/js/jquery-ui-1.7.1.custom.min.js"></script>-->
        <style type="text/css">
            #contentWrap {
                width: 700px;
                margin: 0 auto;
                height: auto;
                overflow: hidden;
            }
            #contentTop {
                width: 600px;
                padding: 10px;
                margin-left: 30px;
            }
            #contentLeft {
                float: left;
                width:100%;
            }
            #contentLeft li {
                background: url("images/left-meny.png") repeat-y scroll left top rgba(0, 0, 0, 0);
                border: 1px solid #CCCCCC;
                color: #0E0E0E;
                list-style: none outside none;
                margin: 0 0 4px;
                padding: 10px 0 10px 18px;
            }
            #contentLeft li:hover{
                cursor: move;
            }    
        </style>
        <script type="text/javascript">
            $(document).ready(function(){                    
                $(function() {
                    $("#contentLeft ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
                        var order = $(this).sortable("serialize") + '&hs_ord=ord&actn=<?=$table?>&trg=<?=$no?>'; 
                        $.post("ajax/operation.php", order, function(theResponse){
                            //                            alert(theResponse);
                        });                                                              
                        }                                  
                    });
                });

            });    
        </script>
        <div id="contentWrap">
            <div id="contentLeft">
                <ul>
                    <?php
                        foreach($mainArray as $h){
                        ?>
                        <li id="recordsArray_<?=$h[$id]?>"><?=$h[$order] . " ) " . $h[$title]?></li>
                        <?php } ?>
                </ul>
            </div>

        </div>
        <?php
        }
        public function logOut($lData){
            $data   = array('ulsEndTime'=>TIME,'ulsStatus'=>0);
            $where  = array('ulsID'=> $lData['ulsID']);
            $update = $this->update($this->general->table(18),$data,$where);
            if(defined('UID')){
                $this->runQuery("update ".$this->general->table(16)." set assignTo=0,assignTime=0 where replyed=0 and assignTo=".UID);
                $this->runQuery("update ".$this->general->table(67)." set assignTo=0,assignTime=0 where replyed=0 and assignTo=".UID);
                $this->runQuery("update ".$this->general->table(62)." set uID=".UID);
                $where = array('uID'=>UID);
                $this->delete($this->general->table(5),$where);
                $this->delete($this->general->table(36),$where);
                $this->delete($this->general->table(10),$where);
                $this->delete($this->general->table(44),$where);

            }
            unset($_SESSION[LOGIN_SESSION_NAME]);
        }
        /**
         * Set the block dimensions accounting for page breaks and page/column fitting
         * @param $table (string) database table name
         * @param $inputName (string) select tag name and id
         * @param $columnID (string) table colum id which set in option value
         * @param $columnTitle (string) table colum title which show in option
         * @param $currentValue (string) value will be selected
         * @param $inputClassName (string) select tag class name
         * @param $required (string) deafult No. Possible value are  No,y
         * @param $script (string) deafult n. Which embed in select tag Possible value any string 
         * @param $haveSelect (string) deafult ''. have option select. Possible value any '',n. when !=n or !='' then first select option of that text
         * @return no return
         */
        public function dropdownInput
        ($table,$inputName,$columnID,$columnTitle,$currentValue='',$inputClassName='',$required='No',$script='n',$haveSelect='')
        {
        ?>
        <select name="<?=$inputName?>" id="<?=$inputName?>" class="<?=$inputClassName?>" <?=($required=='y')?'required':''?> <?=($script!='n')?$script:''?> >
            <?php
                if($haveSelect!='n'){
                    if($haveSelect==''){
                    ?>
                    <option value="">Select</option>
                    <?php
                    }
                    else{
                    ?>
                    <option value=""><?=$haveSelect?></option>
                    <?php
                    }
                }
                $inputs= $this->selectAll($this->general->table($table),'where IsActive=1 order by '.$columnTitle);
                foreach($inputs as $i){
                ?>
                <option value="<?=$i[$columnID]?>" <?=$this->general->selected($currentValue,$i[$columnID])?>><?=$i[$columnTitle]?></option>
                <?php
                }
            ?>
        </select>
        <?php
        }
        public function dropdownInputB
        ($table,$inputName,$columnID,$columnTitle,$currentValue='',$inputClassName='',$required='No',$script='n',$haveSelect='')
        {
        ?>
        <select name="<?=$inputName?>" id="<?=$inputName?>" class="<?=$inputClassName?>" <?=($required=='y')?'required':''?> <?=($script!='n')?$script:''?> >
            <?php
                if($haveSelect!='n'){
                    if($haveSelect==''){
                    ?>
                    <option value="">All Brand</option>
                    <?php
                    }
                    else{
                    ?>
                    <option value=""><?=$haveSelect?></option>
                    <?php
                    }
                }
                $inputs= $this->selectAll($this->general->table($table),'where IsActive=1 order by '.$columnTitle);
                foreach($inputs as $i){
                ?>
                <option value="<?=$i[$columnID]?>" <?=$this->general->selected($currentValue,$i[$columnID])?>><?=$i[$columnTitle]?></option>
                <?php
                }
            ?>
        </select>
        <?php
        }
        public function settingsValue($key){
            $settings = $this->get_data($this->general->table(3),'ssKey',$key,'ssVal');return $settings;
        }
        public function settingsValues($keys){
            $settings = $this->selectAll($this->general->table(3),"where ssKey in('".implode("','",$keys)."') order by field(sskey,'".implode("','",$keys)."')");
            $this->general->arrayIndexChange($settings,'ssKey');
            return $settings;
        }
        public function settingsUpdate($value,$key,$echo='No'){
            $data = array('ssVal' => $value);
            $where = array('ssKey' => $key);
            return $this->update($this->general->table(3),$data,$where,$echo);
        }
        public function checkRole($perId,$type='p',$bID='all'){
            if(USER_ROLE_TYPE==SUPERADMIN_USER){return true;}
            else{
                return false;
                if($type=='m'){
                    $md = $this->get_data($this->general->table(1),'cmID',$perId,'IsActive');
                    if($md==1){
                        $p = $this->getRowData($this->general->table(29),'where uID='.UID.' and cmID='.$perId);
                        if(!empty($p)){return true;}
                        else{return false;}
                    }else{return false;}
                }
                else{
                    $cmId = $this->get_data($this->general->table(19),'perID',$perId,'cmId');
                    if(!empty($cmId)){
                        $p = $this->getRowData($this->general->table(21),'where uID='.UID.' and perID='.$perId);
                        if(!empty($p)){return true;}else{return false;}
                    }else{return true;}
                }
            }
        }
        /**
         * This method return user access.<br />
         * @param $perID (int) permission number which provide module permission page.
         * @param $bID (string) branch id or for all branch. Possible values are:any or empty string (For any branch),Int number (Specic branch),all (for check all branch),bID: For return allowed branch id</li></ul>
         * @return bool whern $bID!=bID else array(list id of branch)
         */
        function permission($perID){
            if(UGID==SUPERADMIN_USER)return true;
            $p=$this->getRowData($this->general->table(21),'where perID='.$perID.' and ugID='.UGID);
            if(!empty($p)){return true;}else{return false;}
        }
        /**
         * This method return user access.<br />
         * @param $perID (int) module number which provide module permission page.
         * @param $bID (string) branch id or for all branch. Possible values are:1:-"any" or "empty" string: For Any branch, 2:-"Int number" for Specic branch,3:-"bID": For return allowed branch id,4:-"all" for all branch
         * @return bool whern $bID!=bID else array(list id of branch)
         */
        public function modulePermission($cmID){
            $md = $this->get_data($this->general->table(1),'cmID',$cmID,'isActive');
            if($md==1){
                if(UGID==SUPERADMIN_USER)return true;
                $p = $this->getRowData($this->general->table(29),'where ugID='.UGID.' and cmID='.$cmID);
                if(!empty($p)){return true;}
                else{return false;}
            }else{return false;}
        }
        public function maxID($table,$id){
            $mi=$this->selectAll($table,'','max('.$id.') as maxID');
            return intval(@$mi[0]['maxID']);
        }
        public function transactionStart(){mysqli_query($this->con,'SET AUTOCOMMIT=0;');mysqli_query($this->con,'START TRANSACTION;');return true;}
        public function reportCacheGet($key){
            $r=$this->get_rowData($this->general->table(40),'rcKey',$key);
            if(!empty($r)){
                if($r['rcValidity']<TIME){return false;}
                else{return $r['rcValue'];}
            }else{return false;}
        }
        public function reportCacheSet($key,$value,$expeir=0){
            /*$data=array(
            'rcKey'     => rand(0,9999).$key,
            'rcValidity'=> 0,
            'rcValue'   => json_encode(debug_backtrace())
            );
            $this->insert($this->general->table(40),$data);*/
            $expeir=intval($expeir);if($expeir<1)$expeir=strtotime('+1 hour',TIME);
            $r=$this->get_rowData($this->general->table(40),'rcKey',$key);
            if(empty($r)){
                $data=array(
                    'rcKey'     => $key,
                    'rcValidity'    => $expeir,
                    'rcValue'   => $value
                );
                return $this->insert($this->general->table(40),$data);
            }
            else{
                $data=array(
                    'rcValidity'    => $expeir,
                    'rcValue'   => $value
                );
                $where=array('rcKey'=>$key);
                return $this->update($this->general->table(40),$data,$where);
            }
        }
    }
?>