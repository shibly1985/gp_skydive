<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <?php
            $uID=0;
            if(isset($_GET['show'])){
                $date = @$_GET['date'];
                $hour = @$_GET['hour'];
                $uID  = @$_GET['uID'];
                $date_timestamp = strtotime($date);
                $hour_timestamp = 3600*$hour;
                $from =  $date_timestamp+$hour_timestamp;
                $to =strtotime('+59 minute',$from);

            }else{
                $date = date('d-m-Y',strtotime('today'));
                $from = strtotime($date);
                $to = strtotime('+59 minute',$from); 
            }

            $ugID=0;
            if(isset($_GET['ug'])){
                $ugID    = intval($_GET['ug']);
            }
        ?>
        <form method="GET" class="form-inline" action="">
            <!--<input type="hidden" name="<?php echo MODULE_URL;?>" value="<?php echo $pSlug;?>">-->
            <?php echo URL_INFO;?>
            <div class="form-group" >
                <label>Date:</label>
                <input type="text" style="height:27px; width: 170px; position:initial;" class="daterangepicker form-control" value="<?php echo $date;?>" name="date">
            </div>
            <?php
                if($uID==0){
                ?>
                <div class="form-group" id="groupHour">
                    <label>Hour:</label>
                    <select name="hour" id="" class="form-control" style="height:27px; width: 170px; padding:0px;">
                        <option value="0" <?php if(@$hour==0) echo 'selected'; ?>>12:00 AM - 12:59 AM</option>
                        <option value="1" <?php if(@$hour==1) echo 'selected'; ?>>01:00 AM - 01:59 AM</option>
                        <option value="2" <?php if(@$hour==2) echo 'selected'; ?>>02:00 AM - 02:59 AM</option>
                        <option value="3" <?php if(@$hour==3) echo 'selected'; ?>>03:00 AM - 03:59 AM</option>
                        <option value="4" <?php if(@$hour==4) echo 'selected'; ?>>04:00 AM - 04:59 AM</option>
                        <option value="5" <?php if(@$hour==5) echo 'selected'; ?>>05:00 AM - 05:59 AM</option>
                        <option value="6" <?php if(@$hour==6) echo 'selected'; ?>>06:00 AM - 06:59 AM</option>
                        <option value="7" <?php if(@$hour==7) echo 'selected'; ?>>07:00 AM - 07:59 AM</option>
                        <option value="8" <?php if(@$hour==8) echo 'selected'; ?>>08:00 AM - 08:59 AM</option>
                        <option value="9" <?php if(@$hour==9) echo 'selected'; ?>>09:00 AM - 09:59 AM</option>
                        <option value="10" <?php if(@$hour==10) echo 'selected'; ?>>10:00 AM - 10:59 AM</option>
                        <option value="11" <?php if(@$hour==11) echo 'selected'; ?>>11:00 AM - 11:59 AM</option>
                        <option value="12" <?php if(@$hour==12) echo 'selected'; ?>>12:00 PM - 12:59 PM</option>
                        <option value="13" <?php if(@$hour==13) echo 'selected'; ?>>01:00 PM - 01:59 PM</option>
                        <option value="14" <?php if(@$hour==14) echo 'selected'; ?>>02:00 PM - 02:59 PM</option>
                        <option value="15" <?php if(@$hour==15) echo 'selected'; ?>>03:00 PM - 03:59 PM</option>
                        <option value="16" <?php if(@$hour==16) echo 'selected'; ?>>04:00 PM - 04:59 PM</option>
                        <option value="17" <?php if(@$hour==17) echo 'selected'; ?>>05:00 PM - 05:59 PM</option>
                        <option value="18" <?php if(@$hour==18) echo 'selected'; ?>>06:00 PM - 06:59 PM</option>
                        <option value="19" <?php if(@$hour==19) echo 'selected'; ?>>07:00 PM - 07:59 PM</option>
                        <option value="20" <?php if(@$hour==20) echo 'selected'; ?>>08:00 PM - 08:59 PM</option>
                        <option value="21" <?php if(@$hour==21) echo 'selected'; ?>>09:00 PM - 09:59 PM</option>
                        <option value="22" <?php if(@$hour==22) echo 'selected'; ?>>10:00 PM - 10:59 PM</option>
                        <option value="23" <?php if(@$hour==23) echo 'selected'; ?>>11:00 PM - 11:59 PM</option>
                    </select>
                </div> 
                <?php
                }
            ?>
            <div class="form-group">
                <select name="ug" class="form-control select2" style="height:27px; width: 170px;">
                    <option value="">All Groups</option>
                    <?php
                        $groups=$db->allGroups('order by ugTitle asc');
                        foreach($groups as $g){?><option value="<?php echo $g['ugID'];?>" <?php echo $general->selected($g['ugID'],@$ugID);?>><?php echo $g['ugTitle'];?></option><?php } ?>
                </select>
            </div>
            <div class="form-group" id="groupuID">
                <label>User:</label>
                <select name="uID" id="selectuID" class="form-control select2"> 
                    <option value="0">All Users</option>
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
        <script>var reportDate='<?php echo date('d-m-y',$from);?>';</script>
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
        <div class="x_content">
            <a class="btn btn-default" id="exportBtn">Export</a>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th><span title="Total Handling Query - Comment">THQ</span> - C</th>
                        <th><span title="Total Handling Query - Wall">THQ</span> - W</th>
                        <th><span title="Total Handling Query - Total">THQ</span> - T</th>
                        <th><span title="Average Handling Time - Comment">AHT - C</span></th>
                        <th><span title="Average Handling Time - Wall">AHT - W</span></th>
                        <?php if($uID!=0||$ugID!=0){ ?>
                            <th>Available Time</th>
                            <th>Engaged Time</th>
                            <th>Away Time</th>
                            <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $reporData=array(
                            'name'=>'AHP_'.$ugID.'_'.$uID.'_'.date('d_m_Y',$from).'_'.date('d_m_Y',$to),
                            'title'=>array(
                                array('title'=>"Date"                           ,'key'=>'d' ,'w'=>10    ,'hw'=> 3),
                                array('title'=>"From"                           ,'key'=>'f','w'=>10    ,'hw'=> 12),
                                array('title'=>"To"                             ,'key'=>'t','w'=>10    ,'hw'=> 15),
                                array('title'=>"Total Handling Query - Comment" ,'key'=>'c' ,'w'=>12    ,'hw'=> 12),
                                array('title'=>"Total Handling Query - Wall"    ,'key'=>'w' ,'w'=>12    ,'hw'=> 15),
                                array('title'=>"Total Handling Query - Total"   ,'key'=>'t' ,'w'=>12    ,'hw'=> 15),
                                array('title'=>"Average Handling Time - Comment",'key'=>'tc' ,'w'=>10    ,'hw'=> 15),
                                array('title'=>"Average Handling Time - Wall"   ,'key'=>'tw' ,'w'=>10    ,'hw'=> 15),
                                /*array('title'=>"Available Time"                 ,'key'=>'a' ,'w'=>10    ,'hw'=> 15),
                                array('title'=>"Engaged Time"                   ,'key'=>'e' ,'w'=>12    ,'hw'=> 15),
                                array('title'=>"Away Time"                      ,'key'=>'aw' ,'w'=>12    ,'hw'=> 15),*/
                            ),
                            'data'=>array()
                        );
                        if($uID!=0||$ugID!=0){
                            $reporData['title'][]=array('title'=>"Available Time"                 ,'key'=>'a' ,'w'=>10    ,'hw'=> 15);
                            $reporData['title'][]=array('title'=>"Engaged Time"                   ,'key'=>'e' ,'w'=>12    ,'hw'=> 15);
                            $reporData['title'][]=array('title'=>"Away Time"                      ,'key'=>'aw' ,'w'=>12    ,'hw'=> 15);
                        }
                        $jArray = array();
                        $twp = 0;
                        $tcm = 0;
                        $tactive = 0;
                        $tservice = 0;
                        $taway = 0;
                        $oneHour=3600;
                        $maxTime=strtotime('+1 day ',$from);
                        while($from<$maxTime){
                            $to=strtotime('+1 hour',$from);$to=strtotime('-1 second',$to);
                        ?>
                        <tr id="rp_<?php echo $uID;?>_<?php echo $from;?>" class="ajaxReport">
                            <td class="date">
                                <?php echo $general->make_date($from)?>
                                <input type="hidden" class="from" value="<?php echo $from;?>">
                                <input type="hidden" class="to" value="<?php echo $to;?>">
                            </td>
                            <td class="from"><?php echo $general->make_date($from,'tam')?></td>
                            <td class="to"><?php echo $general->make_date($to,'tam')?></td>
                            <td class="thqc">-</td>
                            <td class="thqw">-</td>
                            <td class="thqt">-</td>
                            <td class="ahtc">-</td>
                            <td class="ahtw">-</td>
                            <?php if($uID!=0||$ugID!=0){ ?>
                            <td class="active">-</td>
                            <td class="service">-</td>
                            <td class="away">-</td>
                            <?php } ?>
                        </tr>
                        <?php
                            $from=$to;
                            $from=strtotime('+1 second',$from);
                        }
                    ?>
                    <tr id="total">
                        <td colspan="3">Total</td>
                        <td class="thqct"></td>
                        <td class="thqwt"></td>
                        <td class="thqtt"></td>
                        <td></td>
                        <?php if($uID!=0||$ugID!=0){ ?>
                        <td class="active">-</td>
                        <td class="service">-</td>
                        <td class="away">-</td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="">
    <?php echo "var reportExport = ".json_encode($reporData).";";?>
    var userid='<?php echo $uID;?>';
    var groutid='<?php echo $ugID;?>';
    $(document).ready(function(){
        var i=0;
        rpUsers=[];
        $('.ajaxReport').each(function(){
            var tID=$(this).closest('tr').attr('id');
            var from=$('#'+tID+' .from').val();
            var to=$('#'+tID+' .to').val();
            rpUsers[i]={from:from,to:to,tID:tID}
            i++;
        });
        loadAgentHourlyPer(0);
        $("#exportBtn").click(function(){
            reportJsonToExcel(reportExport);
        });
    });
    var tcm=0;
    var twc=0;
    function loadAgentHourlyPer(index){
        if(rpUsers.length>index){
            var cID=rpUsers[index].tID;
            $('#'+cID+' .thqc').html(loadingImage);
            $.post(ajUrl,{loadAgentHourlyPer:1,from:rpUsers[index].from,to:rpUsers[index].to,userid:userid,groutid:groutid},function(data){
                if(data.status==1){
                    var c=data.data;
                    $('#'+cID+' .from').html(c.from);
                    $('#'+cID+' .to').html(c.to);
                    var cm=parse_int(c.cm);
                    var wc=parse_int(c.wc);
                    tcm+=cm;
                    twc+=wc;
                    //var activity=c.cm+' + '+c.wc+' = '+(cm+wc);
                    $('#'+cID+' .thqc').html(c.cm);
                    $('#'+cID+' .thqw').html(c.wc);
                    $('#'+cID+' .thqt').html(c.wc+c.cm);
                    $('#'+cID+' .ahtc').html(c.caht);
                    $('#'+cID+' .ahtw').html(c.waht);
                    <?php if($uID!=0||$ugID!=0){ ?>
                    $('#'+cID+' .active').html(c.active);
                    $('#'+cID+' .service').html(c.service);
                    $('#'+cID+' .away').html(c.away);
                    <?php } ?>
                    //var activity=tcm+' + '+twc+' = '+(tcm+twc);
                    $('#total .thqct').html(tcm);
                    $('#total .thqwt').html(twc);
                    $('#total .thqtt').html(tcm+twc);
                    var exportI={
                        d:reportDate,f:c.from,t:c.to,c:c.cm,w:c.wc,t:c.wc+c.cm,tc:c.caht,tw:c.waht
                        <?php if($uID!=0||$ugID!=0){ ?>,a:c.active,e:c.service,aw:c.away <?php } ?>
                    }
                    reportExport.data.push(exportI);

                }
                else{
                    $('#'+cID+' .activity').html('-');
                }
                index++;
                loadAgentHourlyPer(index);
            });
        }
        else{
            //t('done'+index);
        }
    }
</script>