<?php
    $data = array($pUrl=>$rModule['cmTitle']);$general->breadcrumb($data);
?>
     <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
<div class="row">
    <div class="col-sm-2 small_image" id="pr_branch">
        <script type="text/javascript">
            $("input[name=pr_f_group]:radio").change(function(){
                $('#permissions_set').html('Click permission');
            });
            function set_permissions(roleId){
                $('#permissions_set').html(loadingImage);
                jx.load('?ajax=1&get_premessions='+roleId,function(data){$('#permissions_set').html(data);});
            }
        </script>
    </div>
    <div class="col-sm-3 report_table">
        <table class="table table-striped table-bordered table-hover only_show table_fixed_header_user">
            <thead>
                <tr>
                    <th>Operators Group</th>
                    <th style="width: 30px;">Permission</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $usergroup=$db->allGroups();
                    foreach($usergroup as $ug){
                    ?>
                    <tr>
                        <td><?=$ug['ugTitle']?></td>
                        <td>
                            <a href="javascript:void()">
                                <button class="btn btn-xs btn-info" onclick="set_permissions('<?=$ug['ugID']?>')"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                            </a>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-7 small_image" id="permissions_set" style="overflow: auto;">Click permission</div>

    </div>
    </div>
    </div>