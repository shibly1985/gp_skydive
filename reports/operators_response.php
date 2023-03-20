<?php

    if(date('i')<10&&PROJECT=='gp'){
        echo '<h1>Generating Please wait till '.date('h:10:00 A').'</h1>';
    }
    else{
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><a href="<?php echo $pUrl;?>"><?php echo $rModule['cmTitle'];?></a></h2>
                <div class="clearfix"></div>
            </div>
            <?php
                if(isset($_GET['show'])){
                    $date_range=$_GET['date_range'];
                }
                else{
                    $dr = date('d-m-Y 00:00:00');
                    $dr2= date('d-m-Y 00:00:00');
                    $date_range=$dr.'__'.$dr2;
                }

                $dates=explode('__',$date_range);
                $from_date=strtotime($dates[0]);
                $from=$from_date;
                $to=strtotime($dates[1]);
                if(date('H:i',$to)=='00:00'){
                    $to=strtotime('+1 day',$to);
                    $to=strtotime('-1 second',$to);
                }
                $diff=$to-$from;
                $dateRangeVal=date('d-m-Y h:i A',$from_date).'__'.date('d-m-Y h:i A',$to);
                $dateRangeShow=date('d-m-Y h:i A',$from_date).' - '.date('d-m-Y h:i A',$to);
                $st_date = $dates[0];
                $ed_date = $dates[1];
                $uID=0;if(isset($_GET['ag'])){$uID=intval($_GET['ag']);}
                $ugID=0;
                if(isset($_GET['ug'])){
                    $ugID    = intval($_GET['ug']);
                }
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#reportrange').daterangepicker({
                        timePicker: true,
                        opens: "right",
                        autoApply: true,
                        timePicker24Hour: true,
                        startDate: "<?php echo date('m/d/Y',$from_date);?>",
                        endDate: "<?php echo date('m/d/Y',$to);?>"
                        }, 
                        function(start, end) {
                            $('#reportrange span').html(start.format('DD-MM-YYYY hh:mm A') + ' - ' + end.format('DD-MM-YYYY hh:mm A'));
                            $('#date_range').val(start.format('DD-MM-YYYY HH:mm')+'__'+end.format('DD-MM-YYYY HH:mm'));
                    });
                });
            </script>
            <form method="GET" class="form-inline form_inline" action="">
                <?php echo URL_INFO;?>
                <div class="form-group">
                    <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                    <div id="reportrange" class="pull-right sky_datepicker" >
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                    </div>
                </div>
                <div class="form-group">
                    <select name="ug" class="form-control select2">
                        <option value="">All Groups</option>
                        <?php
                            $groups=$db->allGroups('order by ugTitle asc');
                            foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="ag" class="form-control select2">
                        <option value="">All User</option>
                        <?php
                            $users=$db->allUsers('order by uFullName asc');
                            foreach($users as $u){?><option value="<?php echo $u['uID'];?>" <?php echo $general->selected($u['uID'],@$uID);?>><?php echo $u['uFullName'];?></option><?php } ?>
                    </select>
                </div>
                <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
            </form>

        </div>
    </div>
    <?php
        if($diff>82000||$to<strtotime('-3 hour')){
            $reportTable=$db->settingsValue('commentReportTable');
            $wallCommentReportTable=$db->settingsValue('commentWallReportTable');
        }
        else{
            $reportTable=13;
            $wallCommentReportTable=14;
        }

    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <?php
                $sq='';
                if($ugID!=0){
                    $sq=' and ugID='.$ugID;
                }
                elseif($uID!=0){$sq=' and uID='.$uID;}
                //var_dump($sq);
                $users=$db->allUsers($sq.' order by ugID,uFullName asc');
                //$users=$db->allUsers('order by ugID,uFullName asc');
            ?>
            <div class="x_content">
                <a class="btn btn-default" id="exportBtn" style="display: none;">Export</a>
                <table class="table table-striped table-bordered" style="text-align: right;">
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">Name</th>
                        <th colspan="9">Comments</th>
                        <th colspan="9">Wall Post</th>
                    </tr>
                    <tr>
                        <th>Replied</th>
                        <th>Like</th>
                        <th>Done</th>
                        <th>Hide</th>
                        <th>Del</th>
                        <th>Outbox</th>
                        <th>AHT</th>
                        <th>ART</th>
                        <th>Total Replied</th>
                        <th>Replied</th>
                        <th>Like</th>
                        <th>Done</th>
                        <th>Hide</th>
                        <th>Del</th>
                        <th>Outbox</th>
                        <th>AHT</th>
                        <th>ART</th>
                        <th>Total Replied</th>
                    </tr>
                    <?php
                        $i=1;
                        foreach($users as $u){
                        ?>
                        <tr id="us_<?php echo $u['uID'];?>" class="userReport">
                            <td>
                                <?php echo $i++; ?>
                                <input type="hidden" class="userid" value="<?php echo $u['uID'];?>">
                            </td>
                            <td class="uFullName"><?php echo $u['uFullName']; ?></td>
                            <td class="cReplyed">..</td>
                            <td class="cLike"></td>
                            <td class="cDone"></td>
                            <td class="cHide"></td>
                            <td class="cDelete"></td>
                            <td class="cOutbox"></td>
                            <td class="cAht"></td>
                            <td class="cArt"></td>
                            <td class="cRepliedT"></td>
                            <td class="wReplyed"></td>
                            <td class="wLike"></td>
                            <td class="wDone"></td>
                            <td class="wHide"></td>
                            <td class="wDelete"></td>
                            <td class="wOutbox"></td>
                            <td class="wAht"></td>
                            <td class="wArt"></td>
                            <td class="wRepliedT"></td>
                        </tr>
                        <?php
                        }
                    ?>
                    <tr id="total">
                        <td></td>
                        <td><b>Total</b></td>
                        <td class="cReplyed"></td>
                        <td class="cLike"></td>
                        <td class="cDone"></td>
                        <td class="cHide"></td>
                        <td class="cDelete"></td>
                        <td class="cOutbox"></td>
                        <td class="cAht"></td>
                        <td class="cArt"></td>
                        <td class="cRepliedT"></td>
                        <td class="wReplyed"></td>
                        <td class="wLike"></td>
                        <td class="wDone"></td>
                        <td class="wHide"></td>
                        <td class="wDelete"></td>
                        <td class="wOutbox"></td>
                        <td class="wAht"></td>
                        <td class="wArt"></td>
                        <td class="wRepliedT"></td>
                    </tr>
                </table>
                <?php
                    if(OPERATION_MESSAGE_ALLWO==true){
                    ?>
                    <table class="table table-striped table-bordered" style="text-align: right;">
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Name</th>
                            <th colspan="9">Message</th>
                        </tr>
                        <tr>
                            <th>Replied</th>
                            <th>Done</th>
                            <th>Outbox</th>
                            <th>AHT</th>
                            <th>ART</th>
                            <th>Total Replied</th>
                        </tr>
                        <?php
                            $i=1;
                            foreach($users as $u){
                            ?>
                            <tr id="usm_<?php echo $u['uID'];?>" class="userReportm">
                                <td>
                                    <?php echo $i++; ?>
                                    <input type="hidden" class="userid" value="<?php echo $u['uID'];?>">
                                </td>
                                <td class="uFullName"><?php echo $u['uFullName']; ?></td>
                                <td class="mReplyed">..</td>
                                <td class="mDone"></td>
                                <td class="mOutbox"></td>
                                <td class="mAht"></td>
                                <td class="mArt"></td>
                                <td class="mRepliedT"></td>
                            </tr>
                            <?php
                            }
                        ?>

                    </table>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <script>
        var tCommentReply=0;
        var tWallReply=0;
        var rpUsers=[];
        var from='<?php echo $from;?>';
        var to='<?php echo $to;?>';
        <?php if(isset($_GET['flash'])){ ?> ajUrl+='&flash=1'; <?php } ?>
        var reportHead={
            name:'Operators_Response_Report',
            title:[
                {title:"Sl",key:'s'},
                {title:"Name",key:'n'},
                {title:"Comments Replied ",key:'cr'},
                {title:"Comments Like ",key:'cl'},
                {title:"Comments Done ",key:'cd'},
                {title:"Comments Hide ",key:'ch'},
                {title:"Comments Del ",key:'cdl'},
                {title:"Comments Outbox ",key:'co'},
                {title:"Comments AHT ",key:'cht'},
                {title:"Comments ART ",key:'crt'},
                {title:"Comments Total ",key:'ct'},
                {title:"Wall Replied ",key:'wr'},
                {title:"Wall Like ",key:'wl'},
                {title:"Wall Done ",key:'wd'},
                {title:"Wall Hide ",key:'wh'},
                {title:"Wall Del ",key:'wdl'},
                {title:"Wall Outbox ",key:'wo'},
                {title:"Wall AHT ",key:'wht'},
                {title:"Wall ART ",key:'wrt'},
                {title:"Wall Total ",key:'wt'},
            ],
            data:[]
        };
        $(document).ready(function(){
            var i=0;
            $('.userReport').each(function(){
                var tID=$(this).closest('tr').attr('id');
                rpUsers[i]=$('#'+tID+' .userid').val();i++;
            });
            loadResponseReportByUserid(0);
            //t('length'+rpUsers.length);
        });
        function loadResponseReportByUserid(index){
            if(rpUsers.length>index){
                $('#us_'+rpUsers[index]+' .cReplyed').html(loadingImage);
                $.post(ajUrl,{loadResponseReportByUserid:rpUsers[index],from:from,to:to},function(data){
                    if(data.status==1){
                        r=data.data;
                        $('#us_'+rpUsers[index]+' .uFullName').html(r.uFullName);
                        $('#us_'+rpUsers[index]+' .cReplyed').html(r.cReplied);
                        $('#us_'+rpUsers[index]+' .cLike').html(r.cLike);
                        $('#us_'+rpUsers[index]+' .cDone').html(r.cDone);
                        $('#us_'+rpUsers[index]+' .cHide').html(r.cHide);
                        $('#us_'+rpUsers[index]+' .cDelete').html(r.cDelete);
                        $('#us_'+rpUsers[index]+' .cOutbox').html(r.cOutbox);
                        $('#us_'+rpUsers[index]+' .cAht').html(r.cAht);
                        $('#us_'+rpUsers[index]+' .cArt').html(r.cArt);
                        $('#us_'+rpUsers[index]+' .cRepliedT').html(r.cRepliedT);
                        $('#us_'+rpUsers[index]+' .wReplyed').html(r.wReplied);
                        $('#us_'+rpUsers[index]+' .wLike').html(r.wLike);
                        $('#us_'+rpUsers[index]+' .wDone').html(r.wDone);
                        $('#us_'+rpUsers[index]+' .wHide').html(r.wHide);
                        $('#us_'+rpUsers[index]+' .wDelete').html(r.wDelete);
                        $('#us_'+rpUsers[index]+' .wOutbox').html(r.wOutbox);
                        $('#us_'+rpUsers[index]+' .wAht').html(r.wAht);
                        $('#us_'+rpUsers[index]+' .wArt').html(r.wArt);
                        $('#us_'+rpUsers[index]+' .wRepliedT').html(r.wRepliedT);
                        <?php
                            if(OPERATION_MESSAGE_ALLWO==true){
                            ?>
                            $('#usm_'+rpUsers[index]+' .mReplyed').html(r.mReplied);
                            $('#usm_'+rpUsers[index]+' .mDone').html(r.mDone);
                            $('#usm_'+rpUsers[index]+' .mOutbox').html(r.mOutbox);
                            $('#usm_'+rpUsers[index]+' .mAht').html(r.mAht);
                            $('#usm_'+rpUsers[index]+' .mArt').html(r.mArt);
                            $('#usm_'+rpUsers[index]+' .mRepliedT').html(r.mRepliedT);
                            <?php
                            }
                        ?>
                        tCommentReply+=parse_int(r.cRepliedT);
                        tWallReply+=parse_int(r.wRepliedT);
                        $('#total .cRepliedT').html(tCommentReply);
                        $('#total .wRepliedT').html(tWallReply);


                        reportHead.data[index]={
                            s:index+1,
                            n:r.uFullName,
                            cr:r.cReplied,
                            cl:r.cLike,
                            cd:r.cDone,
                            ch:r.cHide,
                            cdl:r.cDelete,
                            co:r.cOutbox,
                            cht:r.cAht,
                            crt:r.cArt,
                            ct:r.cRepliedT,
                            wr:r.wReplied,
                            wl:r.wLike,
                            wd:r.wDone,
                            wh:r.wHide,
                            wdl:r.wDelete,
                            wo:r.wOutbox,
                            wht:r.wAht,
                            wrt:r.wArt,
                            wt:r.wRepliedT,
                        };

                    }
                    index++;
                    loadResponseReportByUserid(index);
                });
            }
            else{
                $('#exportBtn').show();
            }
        }
        <?php echo "var eIndex = ".json_encode($jArray).";";?>
        $('#exportBtn').click(function(){
            //t(reportHead.data.length)
            reportJsonToExcel(reportHead);
        });
    </script>
    <?php
    }
?>