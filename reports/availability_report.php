<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <?php
            $uID=0;
            $ugID=0;
            if(isset($_GET['date_range'])){
                $date_range=$_GET['date_range'];
            }
            else{
                $dr = date('d-m-Y',strtotime('-7 day')).'';
                $dr2= date('d-m-Y').'';
                $date_range=$dr.'__'.$dr2;
            }

            $dates=explode('__',$date_range);
            //$general->printArray($dates);
            $from_date=strtotime($dates[0]);
            $to_date=strtotime($dates[1]);
            if(date('h:i',$to_date)=='12:00'){
                $to_date=strtotime('+1 day',$to_date);
                $to_date=strtotime('-1 second',$to_date);
            }
            if(isset($_GET['aggrup'])){
                $ugID=intval($_GET['aggrup']);
                $ug=$db->groupInfoByID($ugID);
                if(empty($ug)){$ugID=0;}
            }
            if($ugID==0){
                if(isset($_GET['ag'])){
                    $uID    = intval($_GET['ag']);
                    $a=$db->userInfoByID($uID);
                    if(empty($a)){$uID=0;$error=0;}
                }
            }
            $dateRangeVal=date('d-m-Y',$from_date).'__'.date('d-m-Y',$to_date);
            $dateRangeShow=date('d-m-Y',$from_date).' - '.date('d-m-Y',$to_date);
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#reportrange').daterangepicker({
                    opens: "right",
                    autoApply: true,
                    locale:{format:'DD-MM-YYYY'},
                    startDate: "<?php echo date('d-m-Y',$from_date);?>",
                    endDate: "<?php echo date('d-m-Y',$to_date);?>"
                    }, 
                    function(start, end) {
                        $('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
                        $('#date_range').val(start.format('DD-MM-YYYY')+'__'+end.format('DD-MM-YYYY'));
                });
            });
        </script>
        <form method="GET" class="form-inline" action="">
            <?php echo URL_INFO;?>
            <div class="form-group">
                <input type="hidden" id="date_range" name="date_range" value="<?php echo $dateRangeVal;?>">
                <div id="reportrange" class="pull-right sky_datepicker" >
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?php echo $dateRangeShow;?></span> <b class="caret"></b>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2 ">
                    <select name="aggrup" class="form-control select2 sky_textbox ">
                        <option class="" value="">All Groups</option>
                        <?php
                            $groups=$db->allGroups();
                            foreach($groups as $g){
                            ?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$_GET['aggrup']);?>><?php echo $g['ugTitle'];?></option><?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <select name="ag" class="form-control select2"> 
                    <option class="sky_textbox" value="0">All Users</option>
                    <?php
                        $users= $db->allUsers();
                        foreach($users as $u){                       
                        ?>
                        <option value="<?php echo $u['uID'];?>" <?php if($u['uID']==@$uID){echo 'selected';}?>><?php echo $u['uFullName']; ?></option>
                        <?php
                        }
                    ?>
                </select>
            </div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>

    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
            $jArray = array();
            if($uID==0&&$ugID!=0){
            ?>
            <div class="x_content">
                <a class="btn btn-default" id="exportBtn" style="display: none;">Export</a>
                <table class="table table-striped table-bordered" style="text-align: right;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Team</th>
                            <th>Agent</th>
                            <th>1st login</th>
                            <th>last logout</th>
                            <th>Service Time</th>
                            <th>Break</th>
                            <th>Login Hours (S+B)</th>
                            <th>Total Login hours</th>
                            <th>Reply</th>
                            <th>Hide</th>
                            <th>Done</th>
                            <th>Transfer</th>
                            <th>AHT-C</th>
                            <th>AHT-W</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //$dates = range($from_date, $to_date,86400);
                            $from=$from_date; 
                            $users=$db->allUsers('and ugID='.$ugID);
                            $i=1;
                            while($from<$to_date){
                                $to=strtotime('+1 day',$from);
                                $to =strtotime('-1 second', $to);
                                if(!empty($users)){
                                    foreach($users as $u){
                                    ?>
                                    <tr id="rp_<?php echo $u['uID'];?><?php echo $from;?>_<?php echo $to;?>" class="ajaxReport">
                                        <td><?php echo $i++;?></td>
                                        <td>
                                            <span class="date"><?php echo date('d-m-Y',$from);?></span>
                                            <input type="hidden" class="from" value="<?php echo $from;?>">
                                            <input type="hidden" class="to" value="<?php echo $to;?>">
                                            <input type="hidden" class="userid" value="<?php echo $u['uID'];?>">
                                        </td>
                                        <td class="team"><?php echo $ug['ugTitle'];?></td>
                                        <td class="agent"><?php echo $u['uFullName'];?></td>
                                        <td class="fLogIn"></td>
                                        <td class="lLogOut"></td>
                                        <td class="available"></td>
                                        <td class="tBbreak"></td>
                                        <td class="tHours"></td>
                                        <td class="tLogin"></td>
                                        <td class="reply"></td>
                                        <td class="tHide"></td>
                                        <td class="done"></td>
                                        <td class="transfer"></td>
                                        <td class="ahtc"></td>
                                        <td class="ahtw"></td>
                                    </tr>
                                    <?php
                                    }
                                }
                                $from=$to;
                                $from =strtotime('+1 second', $from);
                            }
                        ?>
                        <tr id="total">
                            <td>&nbsp;</td>
                            <td colspan="9">Total</td>
                            <td class="reply"></td>
                            <td class="tHide"></td>
                            <td class="done"></td>
                            <td class="transfer"></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else if($uID==0){
            ?>
            <div class="x_content">
            </div>
            <?php
            }
            else{
            ?>
            <div class="x_content">
                <a class="btn btn-default" id="exportBtn" style="display: none;">Export</a>
                <table class="table table-striped table-bordered" style="text-align: right;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>1st login</th>
                            <th>last logout</th>
                            <th>Service Time</th>
                            <th>Break</th>
                            <th>Login Hours (S+B)</th>
                            <th>Total Login hours</th>
                            <th>Reply</th>
                            <th>Hide</th>
                            <th>Done</th>
                            <th>Transfer</th>
                            <th>AHT-C</th>
                            <th>AHT W</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            //$dates = range($from_date, $to_date,86400);
                            $from=$from_date; 
                            $i=1;
                            while($from<$to_date){
                                $to=strtotime('+1 day',$from);
                                $to =strtotime('-1 second', $to);
                            ?>
                            <tr id="rp_<?php echo $from;?>" class="ajaxReport">
                                <td><?php echo $i++;?></td>
                                <td>
                                    <span class="date"><?php echo date('d-m-Y',$from);?></span>
                                    <input type="hidden" class="from" value="<?php echo $from;?>">
                                    <input type="hidden" class="to" value="<?php echo $to;?>">
                                    <input type="hidden" class="userid" value="<?php echo $uID;?>">
                                </td>
                                <td class="agent"><?php echo $a['uFullName'];?></td>
                                <td class="fLogIn"></td>
                                <td class="lLogOut"></td>
                                <td class="available"></td>
                                <td class="tBbreak"></td>
                                <td class="tHours"></td>
                                <td class="tLogin"></td>
                                <td class="reply"></td>
                                <td class="tHide"></td>
                                <td class="done"></td>
                                <td class="transfer"></td>
                                <td class="ahtc"></td>
                                <td class="ahtw"></td>
                            </tr>
                            <?php
                                $from=$to;
                                $from =strtotime('+1 second', $from);
                            }
                        ?>
                        <tr id="total">
                            <td>&nbsp;</td>
                            <td colspan="9">Total</td>
                            <td class="reply"></td>
                            <td class="tHide"></td>
                            <td class="done"></td>
                            <td class="transfer"></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            }
        ?>
    </div>
</div>

<script type="">
    var reportHead={
        name:'Agent_Performance_Report',
        title:[
            {title:"Date",key:'date'},
            {title:"Agent",key:'agent'},
            {title:"1st login",key:'fLogIn'},
            {title:"Last logout",key:'lLogOut'},
            {title:"Service Time",key:'available'},
            {title:"Total break",key:'tBbreak'},
            {title:"Total hours",key:'tHours'},
            {title:"Total Login hours",key:'tLogin'},
            {title:"Total reply",key:'reply'},
            {title:"Total hide",key:'tHide'},
            {title:"Total done",key:'done'},
            {title:"Total transfer",key:'transfer'},
            {title:"AHT Comment",key:'ahtc'},
            {title:"AHT",key:'ahtw'}
        ],
        data:[]
    };
    $(document).ready(function(){
        var i=0;
        rpUsers=[];
        $('.ajaxReport').each(function(){
            var tID=$(this).closest('tr').attr('id');
            var from=$('#'+tID+' .from').val();
            var to=$('#'+tID+' .to').val();
            var userid=$('#'+tID+' .userid').val();
            rpUsers[i]={from:from,to:to,tID:tID,userid:userid}
            i++;
        });
        loadAvailabilityReport(0);
        $('#exportBtn').click(function(){
            //t(reportHead.data.length)
            reportJsonToExcel(reportHead);
        });
    });
    var tReply=0;
    var tHide=0;
    var tDone=0;
    var tTransfer=0;
    function loadAvailabilityReport(index){
        //if(rpUsers.length>index&&index<3){
        if(rpUsers.length>index){
            var cID=rpUsers[index].tID;
            $('#'+cID+' .fLogIn').html(loadingImage);
            $.post(ajUrl,{loadAvailabilityReport:1,from:rpUsers[index].from,to:rpUsers[index].to,userid:rpUsers[index].userid},function(data){
                if(data.status==1){
                    c=data.data;
                    $('#'+cID+' .fLogIn').html(c.fLogIn);
                    $('#'+cID+' .lLogOut').html(c.lLogOut);
                    $('#'+cID+' .available').html(c.available);
                    $('#'+cID+' .tBbreak').html(c.tBbreak);
                    $('#'+cID+' .tHours').html(c.tHours);
                    $('#'+cID+' .tLogin').html(c.tLogin);
                    $('#'+cID+' .reply').html(c.reply);
                    $('#'+cID+' .tHide').html(c.tHide);
                    $('#'+cID+' .done').html(c.done);
                    $('#'+cID+' .transfer').html(c.transfer);
                    $('#'+cID+' .ahtc').html(c.caht);
                    $('#'+cID+' .ahtw').html(c.waht);
                    tReply+=c.reply;$('#total .reply').html(tReply);
                    tHide+=c.tHide;$('#total .tHide').html(tHide);
                    tDone+=c.done;$('#total .done').html(tDone);
                    tTransfer+=c.transfer;$('#total .transfer').html(tTransfer);
                    
                    var agentN=$('#'+cID+' .agent').html();
                    reportHead.data[index]={
                        date:c.date,
                        agent:agentN,
                        fLogIn:c.fLogIn,
                        lLogOut:c.lLogOut,
                        available:c.available,
                        tBbreak:c.tBbreak,
                        tHours:c.tHours,
                        tlogin:c.tlogin,
                        reply:c.reply,
                        tHide:c.tHide,
                        done:c.done,
                        transfer:c.transfer,
                        ahtc:c.caht,
                        ahtw:c.waht
                    };
                }
                else{
                    $('#'+cID+' .fLogIn').html('-');
                }
                index++;
                loadAvailabilityReport(index);
            });
        }
        else{
            $('#exportBtn').show();
            //t(reportHead);
        }
    }
    //reportJsonToExcel(reportHead);
</script>