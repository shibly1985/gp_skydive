<?php
    if(isset($_GET['type'])){
        $type = $_GET['type'];
    }
    else{
        $type="c";    
    }
    $link=$pUrl.'&type='.$type;
?>
<div class="x_content">
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
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Queue Report (<?php if($type=='w'){echo 'Wall Post';}else{echo 'Comments';}?>)<span id="totalDisplay"></span></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix">
            </div>
        </div>
        <div class="x_content">
            <?php
                if($type=='c'){
                    $query="select message,created_time,post_id,parent_id,comment_id from ".$general->table(13)." c where c.replyed=0";
                }
                else{
             $query="
            SELECT p.message AS message, 
            p.created_time AS created_time,
            p.post_id AS post_id
            FROM ".$general->table(12)." p
            WHERE p.replyed=0 UNION
            SELECT c.message AS message,
            c.created_time AS created_time,
            c.post_id AS post_id
            FROM ".$general->table(14)." c
            WHERE c.replyed=0";
                }
                $queue_message=$db->fetchQuery($query);
                /* $jArray['commentQueue']=$total;
                $query="select count(c.post_id) as total from ".$general->table(12)." c where c.replyed=0;";
                //      $jArray[__LINE__]=$query;
                $total=$db->fetchQuery($query);
                $total=$total[0]['total'];
                $jArray['wallPostQue']=$total;
                $query="select count(c.comment_id) as total from ".$general->table(14)." c where c.replyed=0;";
                //      $jArray[__LINE__]=$query;
                $total=$db->fetchQuery($query);
                $total=$total[0]['total'];
                $jArray['wallPostQue']+=$total;       */
            ?>
            <table class="table">
                <tr>
                    <th>SL</th>
                    <th>Comments</th>
                </tr>
                <?php 
                 $cp=1;if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME]))$cp=intval($_GET[PAGE_INATION_CURRENT_PAGE_NAME]);
                    $pageination    = $general->pagination_init_customQuery($query,50,$cp);
                    $queue_message       = $db->fetchQuery($query.$pageination['limit']);
                    $cp=$cp-1;
                    $i=$cp*50;
                    foreach($queue_message as $qm){
                         $post_ids[$qm['post_id']]=$qm['post_id'];
                    ?>
                    <tr>
                        <td><?php echo ++$i;?></td>
                        <td>
                        <?php echo $qm['message'];?>
                        <a href="javascript:void();" data-link="1" data-post_id="<?php echo $qm['post_id'];?>" data-parent_id="<?php echo $qm['parent_id'];?>" data-comment_id="<?php echo $qm['comment_id'];?>" data-created_time="<?php echo date('YmdHis',$qm['created_time']);?>">-</a>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
            </table>
            <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link.'&'.PAGE_INATION_CURRENT_PAGE_NAME.'=');?></ul>
        </div>
    </div>
</div>

<script type="text/javascript">
            <?php echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';'; ?>
            $(document).ready(function(){commentLinkCreate();});
        </script>
<script type="text/javascript">
    $(document).ready(function(){
        <?php
            if($pageination['total']>0){
            ?>
            var total = '<?php echo $pageination['total']?>';
            <?php
            }
            else{
            ?>
            var total = 0;
            <?php
            }
        ?>
        $("#totalDisplay").html(" ("+total+")");
    });
</script>