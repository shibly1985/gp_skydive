<?php
    $q=array();
    $type='c';
    $thisPageTitle='Comment';
    if(isset($_GET['type'])){
        if($_GET['type']=='w'){$type='w';$thisPageTitle='Wall Post';}
        if(OPERATION_MESSAGE_ALLWO==true&&$_GET['type']=='m'){$type='m';$thisPageTitle='Message';}
    }
?>
<div class="x_content">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $rModule['cmTitle'];?> <?php echo $thisPageTitle;?></h2>
                </div>
                <table class="table table-striped table-bordered fixtWidthReport">
                    <tr>
                        <td>SL</td>
                        <td>Agent</td>
                        <td style="width: 40%;">Comments</td>
                        <td>Create Time</td>
                        <td>Assign Time</td>
                    </tr>
                    <?php
                        $comments=$social->getCurrentAssigned($type,$general->showQuery());
                        //$general->printArray($comments);
                        $i=1;
                        $post_ids=array();
                        foreach($comments as $c){
                            $post_ids[$c['post_id']]=$c['post_id'];
                        ?>
                        <tr id="cm_<?php echo $c['comment_id'];?>">
                            <td><?php echo $i++;?></td>
                            <td><?php echo $c['uFullName'];?></td>
                            <td>
                            
                            <?php
                            if($c['photo']!=''){
                                ?>
                                <img src="<?php echo $c['photo'];?>" style="max-width: 300px;"><br>
                                <?php
                            }
                             echo $c['message'];
                             ?>
                            </td>
                            <td>
                            <a href="javascript:void();" id="menu<?php echo $c['comment_id'];?>" data-link="1" data-post_id="<?php echo $c['post_id'];?>" data-parent_id="<?php echo $c['parent_id'];?>" data-comment_id="<?php echo $c['comment_id'];?>" data-moment_show="0"><?php echo $general->make_date($c['created_time'],'time');?></a> 
                            </td>
                            <td><?php echo $general->make_date($c['assignTime'],'time');?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </table>
                <script>
                <?php echo 'commentpostids='.json_encode($social->getPostPermalinkUrlBiIds($post_ids,$type)).';';?>
                $(document).ready(function(){
                    commentLinkCreate();
                });
                </script>
            </div>
        </div>
    </div>
</div>