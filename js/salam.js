
function wallPostReplyInit(){
    var msgData= ($('#msgData').val());
    msgData=msgData.trim();
    msgData=encodeURIComponent(msgData);
    //var msgData         = encodeURIComponent($('#msgData').val());
    var attatchment     = parse_int($('input[name=attatchment]:checked').val());
    if(msgData!=''|| attatchment>0){
        $('.close').click();
        $('#msgData').removeClass('redBorder');
        $('#model_show').click();
        $('#wrapUpNumber').val('');
        $('#modelTitle').html('');
        $('#modelSuccess').html('Send');
        $('#modelSuccess').show();
        $('#modelSuccess').attr('onclick','commentReplySend()');
        $('#modelCancle').html('Cancel');
        var wrapUpData=$('#wrapup_lists').html();
        var Scentiment_list=$('#Scentiment_list').html();
        $('#modelBody').html(wrapUpData);
        $('#modelBody').prepend('<div id="return_msg"></div>');
        $('#modelBody').append(Scentiment_list);
        setTimeout(commentReplySendInit,500);
    }
    else{
        $('#msgData').addClass('redBorder');
        alert('Message field is empyt or any attachment not slect');
    }
}
function wallPostHideDoneInit(type){
    $('.close').click();
    $('#msgData').removeClass('redBorder');
    $('#model_show').click();
    $('#wrapUpNumber').val('');
    $('#modelTitle').html('');
    $('#modelSuccess').html('Send');
    $('#modelSuccess').show();
    if(type=='d'){
        $('#modelSuccess').attr('onclick','postCommentDone()');
    }
    else if(type=='h'){
        $('#modelSuccess').attr('onclick','postCommentHide()');
    }
    $('#modelCancle').html('Cancel');
    var wrapUpData=$('#wrapup_lists').html();
    var Scentiment_list=$('#Scentiment_list').html();
    $('#modelBody').html(wrapUpData);
    $('#modelBody').prepend('<div id="return_msg"></div>');
    $('#modelBody').append(Scentiment_list);
    $('#modelBody .wrapUpNumber').attr('onkeypress','');
    $('#modelBody .ScentimentTypeNumber').attr('onkeypress','');
    setTimeout(commentReplySendInit,500);

}
function commentReplySend(){
    if(wallPostType=='c'){
        var targetType='c';
        var comment_id=$('#comment_id').val();
    }
    else{
        var targetType=$('#targetType').val();
        if(targetType=='p'){
            var comment_id=$('#post_id').val();   
        }
        else{
            var comment_id=$('#comment_id').val();
        }
    }
    //t(comment_id+' a '+wallPostType+' b '+targetType);
    if(comment_id!=''){

        var message= ($('#msgData').val());
        message=message.trim();
        message=encodeURIComponent(message);

        //var message= encodeURIComponent($('#msgData').val());
        var attatchment     = parse_int($('input[name=attatchment]:checked').val());
        if(message!=''|| attatchment>0){
            var wrapupId=parse_int($('#modelBody .wrapUpNumber').val());
            if(wrapupId==0){
                wrapupId=parse_int($("#modelBody input[name=wrapupType]:checked").val());
            }
            if(wrapupId!=0){
                $('#modelBody .wrapUpNumber').removeClass('redBorder');
                var found=0;
                $.each($('#modelBody input[name=wrapupType]'),function(a,b){
                    if(found==0){
                        if($(b).val()==wrapupId){found=1;}
                    }
                });
                if(found==0){wrapupId=0;}
            }


            var scentiment=parse_int($('#modelBody .ScentimentTypeNumber').val());
            if(scentiment==0){
                scentiment=parse_int($("#modelBody input[name=ScentimentType]:checked").val());
            }

            if(scentiment!=0){
                $('#modelBody .ScentimentType').removeClass('redBorder');
                var found=0;
                $.each($('#modelBody input[name=ScentimentType]'),function(a,b){
                    if(found==0){
                        if($(b).val()==scentiment){found=1;}
                    }
                });
                if(found==0){scentiment=0;}
            }

            if(wrapupId!=0){
                if(scentiment!=0){
                    var isClose=getIsClose();
                    var privRep=getPrivateReply();
                    var needSend=0;
                    if(privRep==2){
                        if(confirm("If You send this then the comment go to private and remove this.\n Are You sure to do this?")){
                            needSend=1;
                        }
                    }
                    else{
                        needSend=1;
                    }
                    if(needSend==1){
                        var action_like=0; if($("#action_like").is(':checked'))action_like=1;
                        var action_hide=0; if($("#action_hide").is(':checked'))action_hide=1;
                        var action_ban=0; if($("#action_ban").is(':checked'))action_ban=1;
                        message=incText(message);
                        post_data = {
                            'newCommentByAgent':1,
                            'targetType':targetType,
                            'comment_id':comment_id,
                            'message':message,
                            'scentiment':scentiment,
                            'wrapupId':wrapupId,
                            'isClose':isClose,
                            'privRep':privRep,
                            'action_like':action_like,
                            'action_hide':action_hide,
                            'action_ban':action_ban,
                            'attatchment':attatchment,
                            'type':wallPostType
                        };
                        animateHtmlStop();
                        animateHtmlVar = setInterval(function(){ animateHtmlRun('modelSuccess','Sending') }, 400);
                        if(isSending==0){
                            isSending=1;
                            isSendingCheck=setInterval(isSendingCheckFunction,1000);
                            $.post(ajUrl, post_data, function(data) {
                                animateHtmlStop();
                                clearInterval(isSendingCheck);
                                isSending=0;
                                if(data.status==1){
                                    createMsgFromJson(data.m);
                                    lastTime=data.lastTime;
                                    $('.close').click();
                                    $('input[name=attatchment]').prop('checked', false);
                                    if(data.hasOwnProperty('nextComment')){
                                        commentpostids=data.nextComment.post_ids;
                                        if(wallPostType=='c'){
                                            newCommentLoadDistribute(data.nextComment);
                                            //t(363)
                                        }
                                        else{
                                            newWallPostLoadDistribute(data.nextComment);
                                        }
                                        setTimeout(commentMessageAtuoScroll,1000);
                                        setTimeout(commentMessageAtuoScroll,3000);
                                        setTimeout(commentMessageAtuoScroll,5500);
                                    }
                                    else{
                                        wallPostPause();
                                    }
                                    //clearScreen();// If this function call therer then new loaded comment not show
                                }
                                else if(data.login==0){location.reload();LOGDIN=0;}
                                    else{createMsgFromJson(data.m,'return_msg');}
                            });
                        }
                    }
                }
                else{
                    $('#modelBody .ScentimentTypeNumber').addClass('redBorder');
                    $('#modelBody .ScentimentTypeNumber').focus();
                    t('Invalid Scentiment id');
                }
            }
            else{
                $('#modelBody .wrapUpNumber').addClass('redBorder');
                $('#modelBody .wrapUpNumber').focus();
                t('Invalid wrapup id');
            }
        }
        else{alert('Message field is empyt or any attachment not slect');}
    }else{t('Comment not select');}
    $('#wallPostClose').attr('checked', false);
    $('#priv_rep').attr('checked', false);
}