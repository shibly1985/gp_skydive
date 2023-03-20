<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<?php
    $type='c';
    $tbl=13;
    $pTbl=4;
    if(isset($_GET['type'])){
        if($_GET['type']=='m'){$type='m'; $tbl=9;$pTbl=12;}
        elseif($_GET['type']=='w'){$type='w';$tbl=14;};
    }
?>
<div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=c">
      <h3><i class="fa fa-comments-o"></i> Comments</h3>
      </a>
    </div>
  </div>
  <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=w">
      <h3><i class="fa fa-comments-o"></i> Wall Post</h3>
      </a>
    </div>
  </div>
  <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=m">
          <h3><i class="fa fa-weixin" aria-hidden="true"></i> Message</h3>
      </a>
    </div>
  </div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <?php
            if(isset($_GET['edit'])){
                if(isset($_POST['update'])){
                    $data = array(
                        'message'  => $_POST['message']
                    );
                    $where = array(
                        'comment_id' => $_GET['edit']
                    );
                    $update = $db->update($general->table($tbl),$data,$where);
                    if($update){
                        SetMessage(30);
                    }
                }
                $comment_id=$_GET['edit'];
                if($type=='m'){$general->redirect($pUrl,37,'Comment');}
                $c=$social->getCommentInfoById($comment_id,$type);
                if(empty($c)){$general->redirect($pUrl,37,'Comment');}
                $post=$social->getPostInfoById($c['post_id'],$type);
                $reply_id = $_GET['edit'];
                $reply_data= $db->get_rowData($general->table($tbl),'comment_id',$comment_id);
                $comment_data= $db->get_rowData($general->table($tbl),'comment_id',$reply_data['parent_id']);
                $post_data= $db->get_rowData($general->table($pTbl),'post_id',$comment_data['post_id']);
                show_msg('Data');
            ?>
            <div class="post">
                <div class="post-content">
                    <div class="post_story clearfix">
                        <div class="provider_status_text" id="provider_status_text">
                            <?php
                                if(!empty($post_data['link'])){
                                ?>
                                <img class="status_image photo" src="<?php echo $post_data['link']; ?>" alt="" style="" id="status_image">  
                                <?php
                                }
                            ?>
                            <div id="status_text"><?php echo $post_data['message']; ?></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="comment_container">                      
                    <div class="comment" id="main_comment">
                        <div class="comment_inner_wrapper">
                            <div class="comment_header">
                                <img src="http://graph.facebook.com/<?php echo $comment_data['sender_id'];?>/picture" class="post_profile_picture">
                            </div>
                            <div class="comment_body">
                                <div class="comment_text">
                                    <?php
                                        $names[]=$comment_data['sender_id'];
                                        $name = $social->getNamesByUserId($names); 
                                    ?>
                                    <span class="evac_user sender_id"><span class="name_865949533540578"><?php echo $name[0]['name'];?></span></span>
                                    <span class="message"><?php echo $comment_data['message']; ?></span>
                                    <?php
                                        if(!empty($comment_data['photo'])){
                                        ?>
                                        <img class="status_image photo" src="<?php echo $comment_data['photo']?>" alt="">
                                        <?php
                                        }
                                    ?>
                                </div>
                                <div class="comment_social clearfix">
                                    <div class="comment_actions">
                                        <div class="action with_bull comment_time_from_now"><a href="javascript:void(0)" class="time"><?php echo $general->make_date($comment_data['created_time'],'time');?></a></div>
                                    </div>
                                </div>
                                <br>
                                <form method="POST">
                                <textarea id="msgData" class="form-control" placeholder="Write your message"  name="message" type="text" style="display: block;"><?php  echo htmlspecialchars($reply_data['message']);?></textarea>
                                <button type="submit" name="update" class="btn btn-success pull-right" style="margin-top: 10px;" value="update">Update</button>
                                </form>
                            </div> 

                        </div>
                    </div>
                </div>                                          
            </div>
        </div>
        <br>
        <?php
        }
        else{
        ?>
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
            $link=$pUrl;
            if(isset($_GET['show'])){
                $from = $_GET['from'];
                $to = $_GET['to'];

                //                echo $st_date;
            }else{
                $from = date('m/d/Y',strtotime('-7 day'));
                $to = date('m/d/Y',strtotime('today'));

                //                echo $st_date;
            }


        ?>
        <form method="GET" action="" class="form-inline form_inline">
            <?php echo URL_INFO;?>
			<div class="form-group">
				<input type="text" name="from" class="form-control" value="<?php echo @$from;?>" id="form_date">
			</div>
			<div class="form-group">
				<label>Time</label> 
				<select name="from_hours" class="form-control">
					<option value="0">Hours</option>
					<?php $general->hourDropdown(@$_GET['from_hours']);?>
				</select>
			</div>
            <select name="from_minute" class="form-control">
                <option value="0">Minute</option>
                <?php $general->minuteDropdown(@$_GET['from_minute']);?>
            </select>
            <input type="text" class="form-control" name="to" value="<?php echo @$to;?>" id="to_date">
			<div class="form-group">
				<label>Time</label> 
				<select name="to_hours" class="form-control">
					<option value="0">Hours</option>
					<?php $general->hourDropdown(@$_GET['to_hours']);?>
				</select>
			</div>
			<div class="form-group">
				<select name="to_minute" class="form-control">
					<option value="0">Minute</option>
					<?php $general->minuteDropdown(@$_GET['to_minute']);?>
				</select>
			</div>
            <input type="submit" value="Show" name="show" class="btn btn-success btn-sm">
        </form>
        <?php
            //$form           = @$_GET['from'];
            $from_hours     = @$_GET['from_hours'];
            $from_minute    = @$_GET['from_minute'];
            // $to             = @$_GET['to'];
            $to_hours       = @$_GET['to_hours'];
            $to_minute      = @$_GET['to_minute'];
            $link.='&from='.$from.'&from_hours='.$from_hours.'&from_minute='.$from_minute.'&to='.$to.'&to_hours='.$to_hours.'&to_minute='.$to_minute.'&show=Show&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';

            $form_date= strtotime($from.' '.$from_hours.':'.$from_minute);
            $to_date  = strtotime($to.' '.$to_hours.':'.$to_minute);
            if(empty($form_date)){
                $form_date =strtotime("-30 day", TODAY_TIME);
            }
            if(empty($to_st)){
                $to_date =$general->make_future_timestamp(1,TIME);
            }
            //                echo $general->make_date($to_date,'time');


            if($type=='c'){
                $query  = "select c.replyBy,c.comment_id,c.replyTime,c.message,c.parent_id from ".$general->table($tbl)." c where c.replyed=1 and c.replyBy=".UID." and c.sender_id=".PAGE_ID." and c.replyTime between ".$form_date." and ".$to_date; 
            }
            elseif($type=='w'){
                $query  = "select c.replyBy,c.comment_id,c.replyTime,c.message,c.parent_id from ".$general->table($tbl)." c where c.replyed=1 and c.replyBy=".UID." and c.sender_id!=".PAGE_ID." and c.replyTime between ".$form_date." and ".$to_date;
            }
            elseif($type=='m'){
                $query  = "select c.replyBy,c.replyTime,c.text,c.sendType,c.sendTime from ".$general->table($tbl)." c where c.replyed=1 and c.sendType=2 and c.replyBy=".UID." and  c.replyTime between ".$form_date." and ".$to_date." order by sendTime desc";
            }

            $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
            $pageination=$general->pagination_init_customQuery($query,20,$cp);
            $all_data=$db->fetchQuery($query.$pageination['limit']);
        ?>
        <h2 style="text-transform: capitalize!important">Reply by "<?php echo strtolower($userData['uFullName']);?>"</h2>
        <table class="table">
            <tr>
                <td>SL</td>
                <td style="width: 50%;"><?php echo  ($type == 'm' ?  'Message' : 'Comments')?></td>
                <td>Reply</td>
                <td>Edit</td>
            </tr>
            <?php
                $i=$pageination['start'];
                if($type=='m'){
                    //var_dump($all_data);
                    foreach($all_data as $key => $ad){
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td>
                            <?php 
                                $t=$ad['sendTime']-1;
                                if($key+1==count($all_data)){
                                    $f=0;
                                }else{
                                    $f=$all_data[$key+1]['sendTime'];     
                                }

                                $message = $db->selectAll($general->table($tbl),'where sendTime between '.$f.' and '.$t);
                                foreach($message as $msg){
                                    echo $msg['text'].'<br>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php echo $ad['text']; ?>
                        </td>
                        <!--<td><button class="btn btn-sm btn-default">Edit</button></td>-->
                    </tr>
                    <?php
                    }
                }
                else{
                    foreach($all_data as $ad){
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td>
                            <?php 
                                $reply=$db->getRowData($general->table($tbl),"WHERE comment_id='".$ad['parent_id']."'");
                                echo $reply['message'];
                            ?>
                        </td>
                        <td>
                            <?php if($type!='m'){ echo $ad['message'];}else{ echo $ad['text'];}?>
                        </td>
                        <td><a href="<?php echo $pUrl;?>&edit=<?php echo $ad['comment_id'];?>&type=<?php echo $type;?>">Edit</a></td>
                    </tr>
                    <?php
                    }
                }
            ?>
        </table>
        <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link);?></ul>

        <?php
        }
    ?>
    </div>

