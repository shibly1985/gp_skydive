<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body status_modal_body"></div>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-md attatchmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="messageModelCancle" aria-label="Close"><span aria-hidden="true">×</span></button>

                <h4 class="modal-title">All File</h4>
            </div>
            <div class="modal-body">
                <div class="post">
                    <?php
                    if($wallPostType=='c'||$wallPostType=='w'){
                    ?><button type="button" class="btn btn-success" onclick="wallPostReplyInit()">Send</button><?php    
                    }
                    else{
                    ?><button type="button" class="btn btn-success" onclick="messageReply(false)">Send</button><?php    
                    }
                ?>
                    <div class="container">
                        <div class="row">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="col-sm-6">
                                    <input  type="file" name="s_file" id="fileAt">
                                </div>
                                <div class="col-sm-6">
                                    <a href="javascript:void();" onclick="newFileUpload()">
                                        <button style="float:left;margin:2px; padding:0px 5px; height:25px;" class="btn btn-default" type="button">Upload</button>
                                    </a>
                                </div>	
                            </form>
                        </div>
                        <!--<div id="attatchmentFilesArea">
                        <div class="row">
                        <?php
                            /*$images=$db->selectAll($general->table(61),'where uID='.UID.' order by afFile desc');
                            if(!empty($images)){
                            foreach($images as $i){
                            ?> 								
                            <div class="col-sm-3 image_cross" id="atDiv<?php echo $i['afID'];?>">
                            <button style="float: left;" class="btn btn-default" type="radio" name="attatchment" value="<?php echo $i['afID'];?>">
                            <label for="atc<?php echo $i['afID'];?>">
                            <img src="<?php echo URL.'/attachments/'.UID.'/'.$i['afFile'];?>" style="height: 100px; max-width: 100px;">
                            </label>
                            </button>
                            <a href="javascript:void();" onclick="alert('df')">X</a>
                            </div>
                            <?php
                            }
                            }*/
                        ?>
                        </div>
                        </div>-->
                    </div>

                    <div id="attatchmentFilesArea">
                        <?php
                            $images=$db->selectAll($general->table(61),'where uID='.UID.' order by afOrder desc');
                            if(!empty($images)){
                                foreach($images as $i){
                                ?>      
                                <div id="atDiv<?php echo $i['afID'];?>">
                                    <input type="radio" name="attatchment" value="<?php echo $i['afID'];?>" id="atc<?php echo $i['afID'];?>">
                                    <label for="atc<?php echo $i['afID'];?>">
                                        <img src="<?php echo URL.'/attachments/'.UID.'/'.$i['afFile'];?>" style="max-height: 100px;max-width: 100px;">
                                    </label>
                                    <a href="javascript:void();" onclick="attatchmentRemove('<?php echo $i['afID'];?>')">X</a>
                                </div>
                                <?php
                                }
                            }
                        ?>
                    </div>
                </div>
                <div style="display: none;" id="attatchmentNewFile">
                    <div>
                        <input type="radio" name="attatchment" value="" class="atinp">
                        <label for="">
                            <img src="" style="max-height: 100px;max-width: 100px;" class="imgat">
                        </label>
                        <a href="javascript:void();" onclick="attatchmentRemove('<?php echo $i['afID'];?>')">X</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                    if($wallPostType=='c'||$wallPostType=='w'){
                    ?><button type="button" class="btn btn-success" onclick="wallPostReplyInit()">Send</button><?php    
                    }
                    else{
                    ?><button type="button" class="btn btn-success" onclick="messageReply(false)">Send</button><?php    
                    }
                ?>
            </div>

        </div>
    </div>
</div>
<script>
    function attatchmentRemove(fileId){
        $.post(ajUrl,{attatchmentRemove:fileId},function(data){
            if(data.status==1){
                $('#atDiv'+fileId).hide();
                //$('#atc'+fileId).hide();
            }
        })
    }
    function newFileUpload(){
        var newFile = $('#fileAt').prop('files')[0];
        var form_data = new FormData();                  
        form_data.append('uploadFile', newFile);
        form_data.append('newAttatchment', '1');
        if(newFile['size']<1024*1024*2){
            $.ajax({
                url: ajUrl,
                //dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){
                    if(data.status==1){
                        $('#attatchmentNewFile input').attr('id','atc'+data.fileId);
                        $('#attatchmentNewFile input').attr('value',data.fileId);
                        $('#attatchmentNewFile label').attr('for','atc'+data.fileId);
                        $('#attatchmentNewFile img').attr('src',SITE_URL+'/attachments/'+UID+'/'+data.fileName);
                        //$('#attatchmentNewFile a').attr('onclick','attatchmentRemove('+data.fileId+')');
                        var attatchmentNewFile=$('#attatchmentNewFile').html();
                        $('#attatchmentFilesArea').prepend(attatchmentNewFile);
                        $('#attatchmentNewFile input').attr('id','');
                    }
                    if(data.hasOwnProperty('msg')){
                        alert(data.msg);
                    }
                }
            });
        }else{alert('Please upload only following size: Max 2MB');}
    }
</script>