<?php show_msg()?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-striped users_view_table">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td width="150">Customer Info</td>
                        <td>Post</td>
                        <td>Assign</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $new_request=$db->selectAll($general->table(4),' Where sender_id='.$page_id);
                        $sl=1;

                        $ids=array();
                        foreach($new_request as $nr){
                            $ids[]=$nr['sender_id'];
                        }
                        $names=$social->getNamesByUserId($ids);
                        $name_id=array();
                        foreach($names as $id=>$n ){
                            $name_id[$n['id']]=$n['name'];
                        }

                        foreach($new_request as $nr){
                        ?>
                        <tr>
                            <td><?=$sl++?></td>
                            <td>
                                <img src="http://graph.facebook.com/<?php echo $nr['sender_id']; ?>/picture?type=square" alt=""/>
                                <p><strong><?php echo $name_id[$nr['sender_id']] ?></strong></p>
                            </td>
                            <td><?=$nr['message']?></td>
                            <td><button type="button" class="btn btn-info btn-sm" onclick="fIDSet('<?php echo $nr['post_id'];?>')" data-toggle="modal" data-target="#myModal">Assign To</button></td>
                        </tr>
                        <?php
                        }    
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign to group</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="fid" value="">
                <input type="hidden" id="ugid" value="">
                <table class="table table-striped">    
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Operator Groups</th>
                        </tr>
                    </thead>
                    <tbody id="user_groups"> </tbody>
                </table>
                <input type="submit" name="submit" value="Assign" onclick="assignTo()" class="btn btn-success" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script >
    function fIDSet(fID){
        $('#fid').val(fID)
        var searchString='?ajax=1&feed_set='+fID;
        jx.load(searchString,function(data){
            $('#user_groups').html(data);
        });
    }

    function assignTo(){
        var ugid = []
        $(".group:checked").each(function (){
            ugid.push(parseInt($(this).val()));
        });

        var fid = $("#fid").val();
        var searchString='?ajax=1&assign_to_group=1&ugID='+ugid+'&fID='+fid;
        jx.load(searchString,function(data){
            $("#myModal").modal('toggle');
            $("#list_"+fid).hide();
            $('.group').attr('checked', false);
        });
    }
</script>