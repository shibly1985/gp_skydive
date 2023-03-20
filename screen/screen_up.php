<?php
    define('SCREEN_LOGIN'               ,'user');
    define('SCREEN_PASS'                ,'pass');
    if(isset($_GET['signout'])){
        $_SESSION['screen_login']='';
        $general->redirect('?screen');
    }
    if(isset($_SESSION['screen_login']) && $_SESSION['screen_login']==SCREEN_LOGIN){
        if(isset($_GET['up'])){
            function arrayInt2Time($array){
                $general=new General();
                $next=array();
                foreach($array as $k=>$a){
                    $next[]=array('i'=>$k,'t'=>$general->makeTimeAvgI($a));
                }
                return $next;
            }
            $ugID=intval($_GET['up']);
            $ug=$db->allGroups(' and ugID='.$ugID);
            if(!empty($ug)){
                $g=$ug[0];
                $jArray=array('status'=>1);
                $query="select count(c.comment_id) as total from ".$general->table(13)." c where c.replyed=0;";
                $total=$db->fetchQuery($query);
                $jArray['commentQueue']=$total[0]['total'];;
                $query="select count(c.post_id) as total from ".$general->table(12)." c where c.replyed=0;";
                $total=$db->fetchQuery($query);
                $jArray['wallPostQue']=$total[0]['total'];
                $query="select count(c.comment_id) as total from ".$general->table(14)." c where c.replyed=0;";
                $total=$db->fetchQuery($query);
                $jArray['wallPostQue']+=$total[0]['total'];
                if(PROJECT!='gp'){
                    $query="select count(c.sender_id) as total from ".$general->table(16)." c where c.replyed=0;";
                    $total=$db->fetchQuery($query);
                    $jArray['msgQue']=$total[0]['total'];
                }
                else{
                    $jArray['msgQue']=0;    
                }
                $users=$db->selectAll($general->table(17),'where ugID='.$ugID);
                if(!empty($users)){
                    $general->arrayIndexChange($users,'uID');
                    $agInfo=array();
                    $sGroup=array();

                    $sGroup[]=array('id'=>$ugID,'title'=>$g['ugTitle']);
                    $agInfo[$ugID]=array('a'=>0,'s'=>0,'i'=>0,'b'=>0);
                    $jArray['groups']=$sGroup;
                    $query="
                    select * from ".$general->table(18)." 
                    where ulsStatus=1 and ulsValidity>".TIME." and uID in(".implode(',',array_keys($users)).")
                    ";
                    $aUsers=$db->fetchQuery($query);
                    if(!empty($aUsers)){
                        $general->arrayIndexChange($aUsers,'uID');
                        foreach($aUsers as $a){
                            //$jArray[__LINE__.'_'.rand(99,999)]=$users[$a['uID']['ugID']];
                            if(isset($agInfo[$ugID])){
                                $agInfo[$ugID]['a']+=1;
                            }
                        }


                        $jArray['activeUser']=count($aUsers);
                    }
                }
                if(!empty($aUsers)){
                    $aUsers=$db->fetchQuery("select * from ".$general->table(37)." where service > ".strtotime('-5 minute')." and uID in(".implode(',',array_keys($aUsers)).")");
                    $caht=array();
                    $cart=array();
                    $waht=array();
                    $wart=array();
                    $activeUsers=array();
                    if(!empty($aUsers)){
                        $general->arrayIndexChange($aUsers,'uID');
                        $db->runQuery('delete from '.$general->table(60).' where dcTime<'.strtotime('-15 minute'));
                        foreach($aUsers as $a){
                            $activeUsers[$a['uID']]=$users[$a['uID']]['uFullName'];
                            $or=$db->getRowData($general->table(60),'where uID='.$a['uID']);
                            if(empty($or)){
                                $aht=$sReport->ahtNart(TODAY_TIME,TIME,$a['uID']);
                                $caht[$a['uID']]=$aht['ahti'];
                                $cart[$a['uID']]=$aht['arti'];
                                $aht=$sReport->ahtNartWall(TODAY_TIME,TIME,$a['uID']);
                                $waht[$a['uID']]=$aht['ahti'];
                                $wart[$a['uID']]=$aht['arti'];
                                $data=array(
                                    'uID'=>$a['uID'],
                                    'dcTime'=>TIME,
                                    'caht'  => intval($caht[$a['uID']]),
                                    'cart'  => intval($cart[$a['uID']]),
                                    'waht'  => intval($waht[$a['uID']]),
                                    'wart'  => intval($wart[$a['uID']]),
                                );
                                $db->insert($general->table(60),$data);
                            }
                            else{
                                //$jArray[__LINE__]=1;
                                $caht[$a['uID']]=$or['caht'];
                                $cart[$a['uID']]=$or['cart'];
                                $waht[$a['uID']]=$or['waht'];
                                $wart[$a['uID']]=$or['wart'];
                            }
                            //$jArray[__LINE__.'_'.rand(99,999)]=$users[$a['uID']['ugID']];
                            if(isset($agInfo[$users[$a['uID']]['ugID']])){
                                $agInfo[$users[$a['uID']]['ugID']]['s']+=1;
                            }
                        }
                        asort($caht);asort($cart);asort($waht);asort($wart);
                        $jArray['min']=array('h'=>arrayInt2Time($caht),'r'=>arrayInt2Time($cart),'wh'=>arrayInt2Time($waht),'wr'=>arrayInt2Time($wart));
                        arsort($caht);arsort($cart);arsort($waht);arsort($wart);
                        $jArray['max']=array('h'=>arrayInt2Time($caht),'r'=>arrayInt2Time($cart),'wh'=>arrayInt2Time($waht),'wr'=>arrayInt2Time($wart));
                        $jArray['activeUser']=count($aUsers);
                    }
                }
                $lfInfo=array(
                    'c'=>array('l'=>0,'f'=>0),
                    'w'=>array('l'=>0,'f'=>0)
                );
                if(!empty($activeUsers)){
                    foreach($activeUsers as $k=>$v){
                        if($users[$k]['uCommentFlow']==WALL_POST_COMMENT_FLOW_FIFO){
                            $lfInfo['c']['f']++;
                        }
                        else{
                            $lfInfo['c']['l']++;
                        }
                        if($users[$k]['uWallpostFlow']==WALL_POST_COMMENT_FLOW_FIFO){
                            $lfInfo['w']['f']++;
                        }
                        else{
                            $lfInfo['w']['l']++;
                        }
                    }
                }
                $jArray['lf']=$lfInfo;
                $jArray['activeUsers']=$activeUsers;
                foreach($agInfo as $k=>$a){
                    $agInfo[$k]['i']=$a['a']-$a['s'];
                }
                $query="
                select * from ".$general->table(43)."
                where btReturnTime=0 and btTime>".strtotime('-1 hour');
                $aUsers=$db->fetchQuery($query);
                $activeUsers=0;
                $bUsers=array();
                if(!empty($aUsers)){
                    $general->arrayIndexChange($aUsers,'uID');
                    foreach($aUsers as $a){
                        //$jArray[__LINE__][]=$a;
                        if(isset($agInfo[$users[$a['uID']]['ugID']])){
                            $bUsers[]=$users[$a['uID']]['uFullName'];
                            $agInfo[$users[$a['uID']]['ugID']]['b']+=1;
                        }
                    }
                }
                $jArray['brakeusers']=$bUsers;
                $b=$agInfo;$agInfo=array();foreach($b as $k=>$a){$a['id']=$k;$agInfo[]=$a;}
                $jArray['agInfo']=$agInfo;
                $jArray['date']=date('d:M:Y');
                $jArray['time']=date('h:i:s A');
                $general->jsonHeader($jArray);
            }
        }
        else{
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Display</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/style.css">
                <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/font-awesome.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/bootstrap.min.css">
                <script src="<?php echo URL;?>/screen/js/bootstrap.min.js"></script>
                <script src="<?php echo URL;?>/vendors/jquery/dist/jquery.min.js"></script>
                <script type="">
                    function t(t){console.log(t);}
                    $(document).ready(function(){
                        screenReload();
                    });
                    function setUserInfo(name,time,setId,serial){
                        $('#ahtArtRow .SL').html(serial);
                        $('#ahtArtRow .max_name').html(name);
                        $('#ahtArtRow .time').html(time);
                        var maxAhtBody=$('#ahtArtRow').html();
                        $('#'+setId).append(maxAhtBody);
                    }
                    function screenReload(){
                        var groups=$('#groups').val();
                        $.post({url:'<?php echo URL;?>/?screen&up='+groups,success:function(data){
                            if(data.status==1){
                                $('#time').html(data.time);
                                $('#date').html(data.date);
                                $('#commentQueue').html(data.commentQueue);
                                $('#wallPostQue').html(data.wallPostQue);
                                <?php
                                    if(OPERATION_MESSAGE_ALLWO==true){
                                    ?>
                                    $('#msgQue').html(data.msgQue);
                                    <?php
                                    }
                                ?>
                                $('#groupStatus').html('');
                                $(data.groups).each(function(a,b){
                                    $('#groupStatusHtml .groupTitle').html(b.title);
                                    var groupStatusHtml=$('#groupStatusHtml').html();
                                    $('#groupStatus').append('<tbody id="stg_'+b.id+'">'+groupStatusHtml+'</tbody>');
                                });
                                $(data.agInfo).each(function(a,b){
                                    //t('#stg_'+b.id+' .agAvailable');
                                    $('#stg_'+b.id+' .agAvailable').html(b.a);
                                    $('#stg_'+b.id+' .agService').html(b.s);
                                    $('#stg_'+b.id+' .agIdol').html(b.i);
                                    $('#stg_'+b.id+' .agBreak').html(b.b);
                                });
                                $('#maxAhtBody').html('');
                                $('#minArtBody').html('');
                                $('#wmaxAhtBody').html('');
                                $('#wminArtBody').html('');
                                var h=1;
                                var wh=1;
                                var r=1;
                                var wr=1;
                                $.each(data.max,function(a,b){
                                    if(a=='h'){
                                        $.each(b,function(c,d){
                                            setUserInfo(data.activeUsers[d.i],d.t,'maxAhtBody',h);h++;
                                        });
                                    }
                                    if(a=='wh'){
                                        $.each(b,function(c,d){
                                            setUserInfo(data.activeUsers[d.i],d.t,'wmaxAhtBody',wh);wh++;
                                        });
                                    }
                                });
                                $.each(data.min,function(a,b){
                                    if(a=='r'){
                                        $.each(b,function(c,d){
                                            setUserInfo(data.activeUsers[d.i],d.t,'minArtBody',r);r++;
                                        });
                                    }
                                    if(a=='wr'){
                                        $.each(b,function(c,d){
                                            setUserInfo(data.activeUsers[d.i],d.t,'wminArtBody',wr);wr++;

                                        });
                                    }
                                });
                                $('.cf').html(data.lf.c.f);
                                $('.cl').html(data.lf.c.l);
                                $('.wf').html(data.lf.w.f);
                                $('.wl').html(data.lf.w.l);
                                var brakString='';
                                $.each(data.brakeusers,function(a,b){
                                    brakString+=b+'<br>';
                                });
                                $('#brkAgnts').html(brakString)
                                
                                setTimeout(screenReload,10000);
                            }
                        }});
                    }
                </script>
            </head>
            <body>
                <div class="container-fluid">
                    <div class="row date">
                        <div class="col-md-3">
                            <select class="form-control option" id="groups">
                                <?php
                                    $groups=$db->allGroups();
                                    foreach($groups as $g){
                                    ?>
                                    <option value="<?php echo $g['ugID'];?>" <?php echo $g['ugID']==12?'selected':'';?>><?php echo $g['ugTitle'];?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <h4 id="date"></h4>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <h4 id="time"></h4>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <a href="?screen&signout">Logout</a>
                        </div>
                    </div>
                    <!--................................................-->
                    <div class="row gp">
                        <div class="col-md-4">
                            <table border="2" bordercolor="#86C653" style="display: none;">
                                <tbody id="groupStatusHtml">
                                    <tr>
                                        <td rowspan="4" class="gp_title groupTitle"></td>
                                        <td class="gp_name">Available Agents</td>
                                        <td class="gp_num agAvailable">0</td>
                                    </tr>
                                    <tr>
                                        <td class="gp_name">Service Agents</td>
                                        <td class="gp_num agService">0</td>
                                    </tr>
                                    <tr>
                                        <td class="gp_name">Idle Agents</td>
                                        <td class="gp_num agIdol">0</td>
                                    </tr>
                                    <tr>
                                        <td class="gp_name">Break Agents</td>
                                        <td class="gp_num agBreak">0</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="2" bordercolor="#86C653" id="groupStatus"></table>
                        </div>
                        <div class="col-md-4">
                            <table border="1" width="100%">
                                <tr>
                                    <th colspan="4"  class="queue_title">Total Queue</th>
                                </tr>
                                <tr>
                                    <td class="queue_name">Comment Queue </td>
                                    <td class="queue_num" id="commentQueue">-</td>
                                </tr>
                                <tr>
                                    <td class="queue_name">Wall Queue </td>
                                    <td class="queue_num"  id="wallPostQue"></td>
                                </tr>
                                <?php
                                    if(OPERATION_MESSAGE_ALLWO==true){
                                    ?>
                                    <tr>
                                        <td class="queue_name">Message Queue</td>
                                        <td class="queue_num" id="msgQue"></td>
                                    </tr>
                                    <?php
                                    }
                                ?>
                                <tr>
                                <td class="queue_name">Break Agents</td>
                                <td class="queue_num" id="brkAgnts"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">

                            <div class="LIFO_FIFO">
                                <p>LIFO/FIFO Status:</p>
                                <table border="1" width="100%">
                                    <tr>
                                        <th class="name_title"></th>
                                        <th class="lifo_title">LIFO</th>
                                        <th class="fifo_title">FIFO</th>
                                    </tr>
                                    <tr>
                                        <td class="fifo_name">comment</td>
                                        <td class="lifo_num cl">0</td>
                                        <td class="fifo_num cf">0</td>
                                    </tr>
                                    <tr>
                                        <td class="fifo_name">Wall</td>
                                        <td class="lifo_num wl">0</td>
                                        <td class="fifo_num wf">0</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--................................................-->

                    <!--................................................-->
                    <!--................................................-->
                    <div class="row comments">
                        <div class="Comment_title">
                            <div class="col-md-12">
                                <h3>Comment</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table border="1" bordercolor="#00A8E7"  style="display: none;">
                                <tbody id="ahtArtRow">
                                    <tr>
                                        <td class="SL">1</td>
                                        <td class="max_name"></td>
                                        <td class="time"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" bordercolor="#00A8E7">
                                <thead>
                                    <tr>
                                        <td colspan="3" class="title">Maximum AHT live:</td>
                                    </tr>
                                </thead>
                                <tbody id="maxAhtBody"></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table border="1" bordercolor="#00A8E7">
                                <thead>
                                    <tr>
                                        <td colspan="3" class="title">Minimum ART live:</td>
                                    </tr>
                                </thead>
                                <tbody id="minArtBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <!--................................................-->
                    <!--................................................-->
                    <div class="row comments">
                        <div class="Comment_title">
                            <div class="col-md-12">
                                <h3>Wall</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table border="1" bordercolor="#00A8E7"  style="display: none;">
                                <tbody id="wahtArtRow">
                                    <tr>
                                        <td class="SL">1</td>
                                        <td class="max_name"></td>
                                        <td class="time"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" bordercolor="#00A8E7">
                                <thead>
                                    <tr>
                                        <td colspan="3" class="title">Maximum AHT live:</td>
                                    </tr>
                                </thead>
                                <tbody id="wmaxAhtBody"></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table border="1" bordercolor="#00A8E7">
                                <thead>
                                    <tr>
                                        <td colspan="3" class="title">Minimum ART live:</td>
                                    </tr>
                                </thead>
                                <tbody id="wminArtBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <!--................................................-->
                </div>
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                    ga('create', 'UA-96322175-1', 'auto');
                    ga('send', 'pageview');

                </script>
            </body>
        </html>
        <?php
        }
    }
    else{
        include("login.php");
    }
?>