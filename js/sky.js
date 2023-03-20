var maxSendTimestamp=0;
var messageCurrentSender=0;
var messageMid='';
var messageCheckForNewStatus=false;
var messageSetDone=false;
var messageObj={
    senders:{},
    message:{}
};
var inService=false;
var messageLeftLoading=0;
$(document).ready(function(){
    setInterval(messageLeftSideRefresh,10000);
    setInterval(messageSendersNewMessage,20000);
});
var loadingImage = '<img src="images/loading.gif" alt="Wait Loading...." title="Wait Loading....">';
var ajUrl = "index.php?ajax=1";
var ajUrl2 = URL2+"/index.php?ajax=1"+'&prj='+PROJECT;
var ajUrl3 = URL3+"/index.php?ajax=1"+'&prj='+PROJECT;
var animateHtmlVar;
var isSending       = 0;
var isSendingCheck;
var callTime        = 1;
var commentpostids  = [];
var testComment     = '';
var reportDta       = {};
var isOldTitle      = true;
var titleInterval   = null;
var newTitle        = "Pending Comments";
var timerVar        =null;
setInterval(messageCheckForNew,1000);//প্রতি ১ সেকেন্ড পর পর কল হবে
$(document).on('click','#messageServeArea .comment_inner_wrapper',function(){
    var cID=this.id;
    var sender_id=$('#'+cID+' .sender_id').val();
    var message=messageObj.message[sender_id];
    messageCurrentSender=sender_id;
    $('#sender_id').val(sender_id);
    $('#main_comment').html('');
    maxSendTimestamp=0;
    $('#serve_'+sender_id).removeClass('newMsg');
    $('#messageServeArea .comment_inner_wrapper').removeClass('messageServ');
    $('#serve_'+sender_id).addClass('messageServ');
    $.each(message,function(i,d){
        if(d.sendType==1){
            messageMid=d.mid;
        }
        if(maxSendTimestamp<d.sendTimestamp){maxSendTimestamp=d.sendTimestamp;}
        //alert('d')
        //alert($('#allmsg_'+d.mid).val());
        if (!$('#allmsg_'+specialCharacterRemove(d.mid)).length){
            //if (1){
            //alert('ad')
            /*if(d.sendType==2){
            $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment');
            }
            else{
            $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');
            }*/
            if(d.at==''){

                messageDataSetNew(d,sender_id);

            }
            else{
                $.each(d.at,function(i,at){
                    if(at.type=='image'){
                        d.url=at.url;
                        messageDataSetNew(d,sender_id);
                    } 
                });
            }
        }

    });
    messageCheckForNew();//এর মধ্যে যদি আবার কোন মেসেজ করে থাকে তাই একবার ইমিডিয়েট নামানো হবে।
    setTimeout(commentMessageAtuoScroll,1000);
    setTimeout(commentMessageAtuoScroll,3000);
    setTimeout(commentMessageAtuoScroll,5500);
    //commentMessageAtuoScroll();
});
$(document).on('click','.photo',function(){
    var src=$(this).attr('src');
    $('#modalImg').attr('src',src);
    $('#modelCancle').html('Close');
    $('#modelSuccess').hide();
    $('#modelBody').html('<img class="status_image" src="'+src+'"/>')
    $('#model_show').click();
});
$(document).on('change','input:radio[name="wrapupType"]',function(){
    $('#modelBody .wrapUpNumber').val(parse_int($(this).val()));
    $('#modelBody .ScentimentTypeNumber').focus();

});
$(document).on('change','input:radio[name="ScentimentType"]',function(){
    $('#modelBody .ScentimentTypeNumber').val(parse_int($(this).val()));
    $('#modelBody .ScentimentTypeNumber').focus();

});
$(document).on('scroll','#wholw_comment_popup',function(){
    t('d')
    /*if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
    {
    wholeThreadView(after);
    }*/
});
function sameReturn(t){return t;}
function animateHtmlRun(id,text){
    for(i=0;i<callTime;i++){
        text+='.';
    }
    callTime++
    if(callTime==4){callTime=1;}
    $('#'+id).html(text);
}
function animateHtmlStop() {clearInterval(animateHtmlVar);}
function assignmentCommentDestribute(comments,names){
    $('#ass_post').html('');
    $(comments).each(function(i,d){
        var timeAgo =momentTime(d.created_time);
        if(d.photo!=''){
            var photo = '<div class="comment_footer"><img class="assing_comment_img" src="'+d.photo+'"></div>';
        }
        else{
            var photo = '';
        }
        var comment_id='';
        if(d.hasOwnProperty('comment_id')){
            comment_id=d.comment_id;
        }
        else{
            comment_id=d.post_id;
        }
        var assContent = '<div class="assignment_checkbox" id="'+comment_id+'">\
        <input id="check_'+i+'" type="checkbox" name="check_threads[]"  data-id="'+d.sender_id+'" data-activity="'+d.activity+'" value="'+comment_id+'">\
        <label for="check_'+i+++'">\
        <div class="comment_inner_wrapper">\
        <div class="comment_header">\
        <img src="https://graph.facebook.com/'+d.sender_id+'/picture?type=square" class="post_profile_picture">\
        </div>\
        <div class="comment_body">\
        <div class="comment_text">\
        <span class="evac_user name_'+d.sender_id+'">\
        </span><br><span>'+timeAgo+'</span><p><span>'+d.message+'</span></p>\
        </div></div>'+photo+'</div>\
        </label> \
        </div>';
        $('#ass_post').append(assContent);
    });
    setNames(names);
}
function assignAction(action){
    if(action==1){//Delete
        if(confirm('Are you sure to delete selected Post / Comment?')){
            if(confirm('Please check again befor submit')){
                var threads=[]; 
                $('input[name="check_threads[]"]:checked').each(function() {
                    threads.push(this.value);
                });
                $.ajax({
                    type:'post',
                    url:ajUrl,
                    data:'type='+wallPostType+'&deleteAssignmentThreads='+threads,
                    success:function(data){
                        if(rData.login==0){location.reload();LOGDIN=0;}
                        else{
                            assignmentCommentDestribute(data.comments,data.names);
                        }
                    }
                });
            }
        }
    }//Delete
    else if(action==2){//Ban
        if(confirm('Are you sure to ban them?')){
            var threads={};
            var id='';
            $('input[name="check_threads[]"]:checked').each(function() {
                id=$(this).attr('data-id');
                threads[this.value]=id;
            });
            var postData={banAssignmentThreads: threads,type:wallPostType}
            //            t(postData)
            $.ajax({
                type:'post',
                url:ajUrl,
                data:postData,
                success:function(data){if(rData.login==0){location.reload();LOGDIN=0;}
                    else{
                        assignmentCommentDestribute(data.comments,data.names);
                    }   
                }   
            });
        }
    }//Ban
    else if(action==3){//Done
        var threads=[];
        $('input[name="check_threads[]"]:checked').each(function() {
            threads.push(this.value);
        });
        //                t(threads)
        var postData={doneAssignmentThreads: threads,type:wallPostType}
        //        t(postData)
        $.ajax({
            type:'post',
            url:ajUrl,
            data:postData,
            success:function(data){
                if(data.login==0){location.reload();LOGDIN=0;}
                else{
                    if(data.status==1){
                        assignmentCommentDestribute(data.comments.comments,data.comments.names);
                    }
                }   
            }   
        });
    }//Done
    else if(action==4){//Ban+Delete
        if(confirm('Are you sure to ban them and delete this comment/post?')){
            if(confirm('Please check again before delete ! ! !')){
                var threads={};
                var id='';
                $('input[name="check_threads[]"]:checked').each(function() {
                    id=$(this).attr('data-id');
                    threads[this.value]=id;
                });
                var postData={banNdeleteAssignmentThreads: threads,type:wallPostType}
                //            t(postData)
                $.ajax({
                    type:'post',
                    url:ajUrl,
                    data:postData,
                    success:function(data){if(data.login==0){location.reload();LOGDIN=0;}
                        else{
                            assignmentCommentDestribute(data.comments,data.names);
                        }   
                    }   
                });
            }
        }
    }//Ban+Delete
    else if(action==5){//Ban+Hide
        if(confirm('Are you sure to ban them and hide this comment/post?')){
            var threads={};
            var id='';
            $('input[name="check_threads[]"]:checked').each(function() {
                id=$(this).attr('data-id');
                threads[this.value]=id;
            });
            var postData={hideNdeleteAssignmentThreads: threads,type:wallPostType}
            //t(postData)
            $.ajax({
                type:'post',
                url:ajUrl,
                data:postData,
                success:function(data){if(data.login==0){location.reload();LOGDIN=0;}
                    else{
                        assignmentCommentDestribute(data.comments,data.names);
                    }   
                }   
            });
        }
    }//Ban+Hide
    else if(action==6){//Ban
        if(confirm('Are you sure to hide this comment/post?')){
            var threads={};
            var id='';
            $('input[name="check_threads[]"]:checked').each(function() {

                id=$(this).attr('data-id');
                threads[this.value]=id;
            });
            var postData={hideAssignmentThreads: threads,type:wallPostType}
            t(postData);
            if(1){
                $.ajax({
                    type:'post',
                    url:ajUrl,
                    data:postData,
                    success:function(data){if(data.login==0){location.reload();LOGDIN=0;}
                        else{
                            assignmentCommentDestribute(data.comments,data.names);
                        }   
                    }   
                });
            }
        }
    }//Hide
    else{
        t(action);
    }
    $('#assignAction').val('');
}
function assignmentLoad(){
    var sendString='assignmentLoad='+wallPostType;
    var date_range = $("#date_range").val();
    var keyword = $("#keyword").val();
    sendString+='&date_range='+date_range+'&keyword='+keyword;
    $('#ass_post').html('Loading');
    $.ajax({
        type:'POST',
        url:ajUrl,
        data:sendString,
        success:function(data){
            if(data.status==1){
                commentpostids=data.post_ids;
                bulkCommentDestribute(data.comments,data.names);
            }else{alert('Some problem there');}
        }
    });
}
function addResponse(){
    var title       = encodeURIComponent($("#title").val());
    //    alert(title);return;
    var response = encodeURIComponent($('#response').val());
    if(title==''){
        $("#title").css("border","1px solid red");
        $("#response").css("border","1px solid #ccc");
    }
    else if(response=='' || response=='undefined'){
        $("#title").css("border","1px solid #ccc");
        $("#response").css("border","1px solid red");
    }
    else{
        var searchString='index.php?ajax=1&addResponse=1&title='+title+'&response='+response;
        $.ajax({
            type:'POST',
            url:ajUrl,
            data:searchString,
            success:function(data){             
                $("#title").val("");  
                $("#response").val("");  
                $("#modal").modal("hide");
                response = "'"+response+"'";
                var content = '<li id="tm_'+data.mtID+'"><i style="cursor:pointer" onclick="removeResponse('+data.mtID+')"  class="fa fa-remove"></i><i style="cursor:pointer" onclick="viewResponse('+data.mtID+')"  class="fa fa-eye"></i> <i style="cursor:pointer" onclick="makeFav('+data.mtID+')"  class="fa fa-heart"></i><a id="res_'+data.mtID+'" data-title="'+data.mtTitle+'" data-text="'+data.mtText+'" onclick="useTemplate('+data.mtID+',1)" class="template" href="javascript:void(0);">'+data.mtTitle+'</a></li>'; 
                $("#responseList").append(content);
            }
        })   
    }

}
function banUser(){
    var senderName=$('#include1').attr('data-value');
    var comment_id=getPostCommentId();
    var sender_id = $('#sender_id').val();
    if(sender_id!=''){
        if(confirm('Realy want\'s to ban this "'+senderName+'"?')){
            $.ajax({
                type:'POST',
                url:ajUrl,
                data:'banUser='+sender_id+'&comment_id='+comment_id+'&type='+wallPostType,
                success:function(data){
                    if(data.status==1){
                        if(wallPostType=='c'){
                            newCommentLoadDistribute(data.nextComment);
                        }
                        else if(wallPostType=='w'){
                            newWallPostLoadDistribute(data.nextComment);
                        }
                    }
                }
            })
        }
    }

}
function bulkCommentDestribute(comments,names){
    $('#ass_post').html('');
    var serial=1;
    $(comments).each(function(i,c){
        $('#commentBox .checkBoxInput').attr('id','check_'+serial);
        $('#commentBox .checkBoxInput').attr('data-id',c.sender_id);
        $('#commentBox .checkBoxLabel').attr('for','check_'+serial);
        $('#commentBox .evac_user').attr('class','evac_user name_'+c.sender_id);

        $('#commentBox .checkBoxInput').attr('value',c.comment_id);

        $('#commentBox .userImg').html(serial+'<img src="https://graph.facebook.com/'+c.sender_id+'/picture?type=square" class="post_profile_picture">');
        $('#commentBox .userMsg').html(c.message);
        $('#commentBox .postTime').attr('data-link',1);
        $('#commentBox .postTime').attr('data-post_id',c.post_id);
        $('#commentBox .postTime').attr('data-parent_id',c.parent_id);
        $('#commentBox .postTime').attr('data-comment_id',c.comment_id);
        $('#commentBox .postTime').attr('data-created_time',c.created_time);
        if(c.photo!=''&&c.photo!=null){
            $('#commentBox .threadImg').html('<img class="assing_comment_img" src="'+c.photo+'">');
        }
        else{
            $('#commentBox .threadImg').html('');
        }
        var assContent=$('#commentBox').html();
        $('#ass_post').append(assContent);
        serial++;
    });
    $('#commentBox .checkBoxInput').attr('id','abc');
    $('#commentBox .checkBoxLabel').attr('for','def');
    setNames(names);
    commentLinkCreate();
}
function bulkSend(){
    var error=0;
    var message=encodeURIComponent($('#msgData').val());
    var threads=[]; $('input[name="check_threads[]"]:checked').each(function() {threads.push(this.value);});
    var wrapupId=parse_int($("input[name=wrapupType]:checked").val());
    var scentiment=parse_int($("input[name=ScentimentType]:checked").val());
    var privRep=getPrivateReply();
    if(message==''){creatMessageForHtml('Message field is required.');error=1;}
    if(threads.length<=0){creatMessageForHtml('Any threat not set.');error=1;}
    if(wrapupId<=0){creatMessageForHtml('Wrapup field is required.');error=1;}
    if(scentiment<=0){creatMessageForHtml('Scentiment field is required.');error=1;}
    var needSend=0;
    var date_range = $("#date_range").val();
    if(error==0){
        if(privRep==2){
            if(confirm("If You send this then the comment go to private and remove this.\n Are You sure to do this?")){
                needSend=1;
            }
        }
        else{
            needSend=1;
        }
        if(needSend==1){
            var action_like = 0; if($("#action_like").is(':checked'))action_like=1;
            var action_hide = 0; if($("#action_hide").is(':checked'))action_hide=1;
            var action_ban  = 0; if($("#action_ban").is(':checked'))action_ban=1;

            post_data = {
                'newBulkReply':1,
                'date_range':date_range,
                'threads':threads,
                'message':message,
                'scentiment':scentiment,
                'wrapupId':wrapupId,
                'privRep':privRep,
                'action_like':action_like,
                'action_hide':action_hide,
                'action_ban':action_ban,
                'type':wallPostType
            };
            animateHtmlStop();
            animateHtmlVar = setInterval(function(){ animateHtmlRun('bulkSendBtn','Sending') }, 400);
            if(isSending==0){
                isSending=1;
                isSendingCheck=setInterval(isSendingCheckFunction,10000);
                $.post(ajUrl, post_data, function(data) {
                    animateHtmlStop();
                    clearInterval(isSendingCheck);
                    isSending=0;
                    commentpostids=data.bulk.post_ids;
                    bulkCommentDestribute(data.bulk.comments,data.bulk.names);
                });
            }
        }
    }

}
var currentServeComment='';
var lastServeCheckVar;
function commentServeTimeCheck(currentComment,lastComment){
    if(currentComment!=''){
        currentServeComment=currentComment;
        clearTimeout(lastServeCheckVar);
    }
    else{
        if(lastComment==lastServeCheckVar){
            newWallPostCommentLoad();
        }
        else{
            commentServeTimeCheckAction('')
        }
    }
}

function commentServeTimeCheckAction(currentComment,lastComment){
    /*if(currentComment!=''){
    currentServeComment=currentComment;
    clearTimeout(lastServeCheckVar);
    }
    else{
    if(lastComment==currentServeComment){
    newWallPostCommentLoad();
    }
    }
    lastServeCheckVar=setTimeout(commentServeTimeCheck('',currentComment),120000)// 2 minute*/
}
function clearScreen(){
    $('#inPostActivity').html('');
    $('#visitor_main_name').html('');
    $('#lifeTimeActivity').html('');
    $('#main_comment').html('');
    $("#post_time").html('');
    $("#visitor_main_propic").attr('scr','');
    $("#status_text").html('');
    $('#status_image').hide();
    $('#msgData').val('');
    $('#msgData').removeClass('redBorder');
    if ( typeof includCheckd !== 'undefined') {
        $('#include'+includCheckd).prop('checked',true);
    }
    $('#action_like').attr('checked', false); 
    $('#action_hide').attr('checked', false); 
    $('#action_ban').attr('checked', false); 
    $('#priv_rep').attr('checked', false); 
    $('#priv_rep_delete').attr('checked', false); 
    //    $('#wallPostInclude').val('');
    $('.wrapupSearch').val('');
    $('.responseSearch').val('');
    animateHtmlStop();
    $('.check_service_area').hide();
    $('#msgData').hide();
    $('#commentFromFb').hide();
    wrapupCollaps();
    //    t('called');
}
function commentReplyWrapupType(e){if (e.keyCode == 13) {
        var wrapVal = parse_int($('#modelBody .wrapUpNumber').val());
        if(wrapVal!=0){
            $('#modelBody .wrapUpNumber').removeClass('redBorder');
            $('#modelBody .ScentimentTypeNumber').focus();
        }
        else{
            //        t('wrap required');
            $('#modelBody .wrapUpNumber').addClass('redBorder');
        }
        return false;
    }else{return isNumberKey(e);}
}
function messageReplyWrapupType(e){if (e.keyCode == 13) {
        $('#modelBody .ScentimentTypeNumber').focus();
        return false;
    }else{return isNumberKey(e);}
}
function commentReplyScentiment(e){if (e.keyCode == 13){
        var wrapVal = parse_int($('#modelBody .ScentimentTypeNumber').val());
        if(wrapVal!=0){
            $('#modelBody .ScentimentTypeNumber').removeClass('redBorder');
            if(wallPostType=='m'){
                messageReplySend();
            }
            else{
                commentReplySend();    
            }
            return false;
        }
        else{
            $('#modelBody .ScentimentTypeNumber').addClass('redBorder');
        }
    }else{return isNumberKey(e);}
}
function messageReplyScentiment(e){
    if (e.keyCode == 13){
        messageReply();
        return false;
    }else{return isNumberKey(e);}
}
function createMsgFromJson(jsonMsg,showId){
    if(showId==undefined){showId='message_show_box';}
    //    t(showId)
    //    t(jsonMsg)
    var msgHtml='';
    var i=0;
    $.each(jsonMsg,function(c,ms){
        mt='';mm=''
        $.each(ms,function(tp,m){
            if(tp==0){mt=m;}
            else if(tp==1){mm=m;}
        });
        if(mt=='s'){msgHtml+= '<div class="notification success hideit" style="display:none;"><p><b>'+mm+'</b></p></div>';}
        else if(mt=='e'){msgHtml+= '<div class="notification failure hideit" style="display:none;"><p><b>'+mm+'</b></p></div>';}
            else if(mt=='i'){msgHtml+= '<div class="notification information hideit" style="display:none;"><p><b>'+mm+'</b></p></div>';}
    });
    creatMessageForHtml(msgHtml,showId,'html')
}
function creatMessageForHtml(msgData,showId,msgDataType){
    if(showId==undefined)showId='message_show_box';
    if(msgDataType==undefined)msgDataType='m';
    $('#'+showId).hide();
    var msgJvs = '<script type="text/javascript">$(document).ready(function(){$(".hideit").click(function(){$(this).hide();});});</'+'script>';
    if(msgDataType=='m'){
        var msgHtml = msgJvs+'<div class="notification failure hideit" style="display:none;"><p><b>'+msgData+'</b></p></div>';
    }else{msgHtml=msgJvs+msgData;}
    $('#'+showId).html(msgHtml);
    $('#'+showId).focus();
    $('#'+showId+' .notification').show();
    $('#'+showId).show();
}
function clearMessage(showId){
    if(showId==undefined)showId='message_show_box';
    //    t(showId)
    $('#'+showId).hide();
    //        alert(showId);
    return true;
}
function commentPostFlowType(flowType,commentType){
    $.ajax({
        type:'POST',
        url:ajUrl,
        data:'commentFlowType='+flowType+'&type='+commentType,
        success:function(data){}
    });
}
function commentAssignTypeChange(assType,commentType){
    $.ajax({
        type:'POST',
        url:ajUrl,
        data:'commentAssignTypeChange='+assType+'&type='+commentType,
        success:function(data){}
    });
}
function commentFullView(trId){
    $('#'+trId+' .msgMin').toggle(700);
    setTimeout(function(){$('#'+trId+' .msgMax').toggle(700);},700);
}
function commentReplySendInit(){$('#modelBody .wrapUpNumberLabel').click();}
function commentDataSet(d){
    // var message='';
    message = d.message;
    $('#visitor_comment .message').html(message);
    $('#visitor_comment .post_profile_picture').attr('src','https://graph.facebook.com/'+d.sender_id+'/picture');
    $('#visitor_comment .sender_id').html('<span class=name_'+d.sender_id+'></span>');

    if(d.photo!=''){
        $('#visitor_comment .photo').css('display','block');
        $('#visitor_comment .photo').attr('src',d.photo);
    }
    else{
        $('#visitor_comment .photo').css('display','none');
        $('#visitor_comment .photo').attr('src','');
    }
    //    t(commentpostids)
    //    t(d.post_id);
    /*if(commentpostids.hasOwnProperty(d.post_id)){
    var link=commentpostids[d.post_id];
    var abc=post_id.split('_');
    var postId=abc[1];

    if(d.hasOwnProperty('parent_id')){
    if(d.parent_id!=d.post_id){
    var parentPage=d.parent_id.split('_');
    if(parentPage[0]==PAGEID){
    if(d.parent_id==d.post_id){
    link+='?comment_id='+parentPage[1];
    }
    else{
    link+='?comment_id='+postId[0]+'&reply_comment_id='+postId[1];
    }
    }
    else{
    link+='?comment_id='+parentPage[1]+'&reply_comment_id='+postId[1];
    }
    }
    else{
    link+='?comment_id='+postId[1];
    }
    }

    }
    else{
    t(d.post_id)
    //                t(commentpostids)
    var link = 'javascript:void();';
    }*/
    var parent_id='';
    if(d.hasOwnProperty('parent_id')){parent_id=d.parent_id;}
    var link= commentLInk(d.post_id,d.comment_id,parent_id)
    $('#visitor_comment .time').html(momentTime(d.created_time));
    $('#visitor_comment .time').attr('href',link);
}
function commentLinkCreate(){
    $("[data-link]").each(function(){
        var post_id  = $(this).attr('data-post_id');
        var parent_id  = $(this).attr('data-parent_id');
        var comment_id  = $(this).attr('data-comment_id');
        var created_time= $(this).attr('data-created_time');
        var moment_show = $(this).attr('data-moment_show');
        //        t(created_time)
        //        t(momentTime(created_time))
        if(moment_show!=0){
            $(this).html(momentTime(created_time));
        }
        if(commentpostids.hasOwnProperty(post_id)){
            var link=commentpostids[post_id];
            if(link.indexOf('?')==-1){link+='?';}
            else{link+='&';}
            var abc=post_id.split('_');
            var postId=abc[1];
            abc=comment_id.split('_');
            var commentID=abc[1];
            abc=comment_id.split('_');
            var commentID=abc[1];
            if((parent_id!=''||post_id!=comment_id)&&(comment_id!=parent_id)){
                abc=parent_id.split('_');
                var parentId=abc[1];
                if(parentId==postId){
                    link+='comment_id='+commentID;
                }
                else{
                    link+='comment_id='+parentId+'&reply_comment_id='+commentID;
                }
                /*var parentPage=parent_id.split('_');
                if(parentPage[0]==PAGEID){
                if(parent_id==post_id){
                link+='comment_id='+parentPage[1];
                }
                else{
                link+='comment_id='+postId[0]+'&reply_comment_id='+postId[1];
                }
                }
                else{
                link+='comment_id='+parentPage[1]+'&reply_comment_id='+postId[1];
                }*/
            }
            $(this).attr('href',link);
            $(this).attr('target','_blank');
        }
        else{
            $(this).attr('href','javascript:void();');
            $(this).html('-')
            t(post_id)
        }
    });
}
function commentLInk(post_id,comment_id,parent_id){
    var link='javascript:void('+post_id+');';
    if(commentpostids.hasOwnProperty(post_id)){
        link=commentpostids[post_id];
        if(link==100)return 'javascript:void('+post_id+');';
        //t('line 664');t(link);
        if(post_id!=comment_id){
            if(parent_id==undefined){parent_id='';}
            if(link.indexOf('?')==-1){link+='?';}
            else{link+='&';}
            var abc=post_id.split('_');
            var postId=abc[1];
            abc=comment_id.split('_');
            var commentID=abc[1];
            abc=comment_id.split('_');
            var commentID=abc[1];
            if(parent_id!=''||post_id!=comment_id){
                abc=parent_id.split('_');
                var parentId=abc[1];
                if(parentId==postId){
                    link+='comment_id='+commentID;
                }
                else{
                    link+='comment_id='+parentId+'&reply_comment_id='+commentID;
                }
            }
        }
    }
    return link;
}
function commentPostTransfer(groupId){
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
    if(comment_id!=''&&comment_id!=undefined){
        if(confirm('Are You sure to transfer this?')){
            var isClose=getIsClose();
            $.ajax({
                type:"POST",
                url:ajUrl,
                data:{commentTranser:comment_id,isClose:isClose,group:groupId,type:wallPostType,targetType:targetType},
                success:function(data){
                    if(data.status==1){
                        if(data.hasOwnProperty('nextComment')){
                            commentpostids=data.nextComment.post_ids;
                            if(wallPostType=='c'){
                                newCommentLoadDistribute(data.nextComment);
                                //                                    t('line 280')
                            }
                            else{
                                newWallPostLoadDistribute(data.nextComment);
                            }
                        }
                        else{
                            wallPostPause();
                        }
                    }
                }
            })
        }

    }
}
function commentPostTransferWithMember(){
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
    if(comment_id!=''&&comment_id!=undefined){

        var users=[];
        $('input[name="check_user[]"]:checked').each(function(){users.push(this.value);})
        if(users.length>0){
            if(confirm('Are You sure to transfer this?')){
                var isClose=getIsClose();
                animateHtmlStop();
                animateHtmlVar = setInterval(function(){ animateHtmlRun('modelSuccess','Transfer') }, 400);
                $.ajax({
                    type:"POST",
                    url:ajUrl,
                    data:{commentPostTransferWithMember:comment_id,isClose:isClose,users:users,type:wallPostType,targetType:targetType},
                    success:function(data){
                        animateHtmlStop();
                        if(data.status==1){
                            $('.close').click();
                            if(data.hasOwnProperty('nextComment')){
                                commentpostids=data.nextComment.post_ids;
                                if(wallPostType=='c'){
                                    newCommentLoadDistribute(data.nextComment);
                                }
                                else{
                                    newWallPostLoadDistribute(data.nextComment);
                                }
                            }
                            else{
                                wallPostPause();
                            }
                        }
                    }
                })
            }
        }
        else{
            alert('No member selected');
        }
    }
}
function commentTransferPopUp(){
    $('#model_show').click();
    $('#modelTitle').html('Transfer to user or group');
    $('#modelSuccess').html('Transfer');
    $('#modelSuccess').show();
    $('#modelSuccess').attr('onclick','commentPostTransferWithMember()');
    $('#modelCancle').html('Cancel');
    var commentTransferInit=$('#commentTransferInit').html();
    $('#modelBody').html(commentTransferInit);
    $.each($('#modelBody input[data-input]'),function(a,b){
        var ugid=$(b).attr('data-input');
        $(b).attr('id','cg'+ugid);
    });
    $.each($('#modelBody label[data-label]'),function(a,b){
        var ugid=$(b).attr('data-label');
        $(b).attr('for','cg'+ugid);
    });


}
function cancel_edit_box(trId){
    $('#'+trId+' .replyEdit').hide();
    $('#'+trId+' .replyHtml').show();
    $('#'+trId+' .edit_button').show();
}
function comment_update(comment_id,targetType,trId){
    if(targetType==undefined){targetType='c';}
    if(trId==undefined)trId='rp_'+comment_id;
    var message = encodeURIComponent($('#'+trId+' .replyMessage').val());
    var searchString={
        commentEdit:comment_id,
        message:message,
        type:wallPostType,
        targetType:targetType
    }

    //'commentEdit='+comment_id+'&message='+message+'&type='+wallPostType+'&targetType='+targetType;
    $.ajax({
        type:'POSt',
        url:ajUrl,
        data:searchString,
        success:function(data){
            if(data.status==1){
                pNotify('Comment edit successfully','s');
                $('#'+trId+' .replyHtml').html(data.message);
                cancel_edit_box(trId);
            }
        }
    });
}
function messageCheckForNew(){
    if(messageCheckForNewStatus==false){
        if(messageCurrentSender!=''&&maxSendTimestamp>0){
            messageCheckForNewStatus=true;
            var sender_id=messageCurrentSender;
            $.post(ajUrl,{messageCheckForNew:sender_id,after:maxSendTimestamp},function(data){
                messageCheckForNewStatus=false;
                if(data.status==1){
                    var length=messageObj.message[sender_id].length;
                    $.each(data.message,function(i,d){
                        if(!$('#allmsg_'+specialCharacterRemove(d.mid)).length){
                            messageDataSetNew(d,sender_id);
                        }
                        messageObj.message[sender_id][length]=d;length++;
                        maxSendTimestamp=d.sendTimestamp;
                        //এই অংশ টুকু বাহিরে লিখলে d এর এ্যাক্সেস পায় না
                        if(d.at!=''){
                            if(d.at[0]['type']=='image'){
                                d.url=d.at[0]['url'];    
                            }
                        }
                        messageObj.senders[sender_id].text=d.text;
                        messageObj.senders[sender_id].url=d.url;
                        messageObj.senders[sender_id].sendTime=d.sendTime;
                        messageObj.senders[sender_id].sendTimestamp=d.sendTimestamp;
                        $('#serve_'+sender_id+' .message').html(d.text);
                        $('#serve_'+sender_id+' .status_image').attr('src',d.url);
                        $('#serve_'+sender_id+' .comment_time_from_now').html(d.sendTime);
                    });

                    document.getElementById('msgnotification').play();
                    commentMessageAtuoScroll();
                }
                //এজেন্ট লগিন আছে কি না সেটা চেক করতে হবে
            });
        }
    }
}
function messageDataSetNew(d,sender_id){
    $('#visitor_comment .message').html(d.text);
    $('#visitor_comment .comment_inner_wrapper').attr('id','allmsg_'+specialCharacterRemove(d.mid));
    if(d.sendType==2){
        $('#visitor_comment .post_profile_picture').attr('src','https://graph.facebook.com/'+PAGEID+'/picture');
        $('#visitor_comment .sender_id').html('<span>'+PAGE_NAME+'</span>');
        $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment');
    }
    else{
        $('#visitor_comment .post_profile_picture').attr('src',messageObj.senders[sender_id].senderPictureLink);
        $('#visitor_comment .sender_id').html('<span>'+messageObj.senders[sender_id].senderName+'</span>');
        $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');
    }
    if(d.at==''){
        $('#visitor_comment .multi_image').html('');
        if(d.url!=''&&d.url!='""'){
            $('#visitor_comment .photo').css('display','block');
            $('#visitor_comment .photo').attr('src',d.url);
        }
        else{
            $('#visitor_comment .photo').css('display','none');
            $('#visitor_comment .time').html(d.sendTime);
            $('#visitor_comment .time').attr('href','javascript:void()');
            $('#visitor_comment .time').attr('target','');
            $('#visitor_comment .photo').css('display','none');
            $('#visitor_comment .photo').attr('src','');
        }
    }
    else{
        var multiImg='';
        $.each(d.at,function(i,at){
            if(at['type']=='image'){
                multiImg+='<img class="status_image photo" src="'+at['url']+'"/>';
                //d.url=at.url;
                //messageDataSetNew(d,sender_id);
            }
            else{
                alert(at['type'])
                t(at);
            }
        });
        $('#visitor_comment .multi_image').html(multiImg);
    }

    var main_comment=$('#visitor_comment').html();
    $('#main_comment').append(main_comment);
}
function commentMessageAtuoScroll(){
    var height = 0;
    $('#main_comment .comment_inner_wrapper').each(function(i, value){
        height += parseInt($(this).height());
    });

    height += '';

    $('#main_comment').animate({scrollTop: (height+50)});
}
function messageSendersNewMessage(){
    var rqData={};
    $.each(messageObj.senders,function(sender_id,s){
        rqData[sender_id]=s.sendTimestamp;
    });
    if(!$.isEmptyObject(rqData)){
        $.post(ajUrl,{messageSendersNewMessage:rqData},function(data){
            if(data.status==1){
                document.getElementById('msgnotification').play();
                $.each(data.message,function(sender_id,mData){
                    messageObj.senders[sender_id].text=mData.text;
                    messageObj.senders[sender_id].url=mData.url;
                    messageObj.senders[sender_id].sendTime=mData.sendTime;
                    messageObj.senders[sender_id].sendTimestamp=mData.sendTimestamp;
                    $('#serve_'+sender_id+' .message').html(mData.text);
                    $('#serve_'+sender_id+' .status_image').attr('src',mData.url);
                    $('#serve_'+sender_id+' .comment_time_from_now').html(mData.sendTime);
                    $('#serve_'+sender_id).attr('class','comment_inner_wrapper newMsg');
                });
            }
        });
    }
}
function messageReplyInit(){
    var msgData = encodeURIComponent($('#msgData').val());
    var sendtype=$('input[name=sendtype]:checked').val();
    if(msgData!=''){
        $('#msgData').removeClass('redBorder');
        if(sendtype==3){
            $('#model_show').click();
            $('#wrapUpNumber').val('');
            $('#modelTitle').html('');
            $('#modelSuccess').html('Send');
            $('#modelSuccess').show();
            $('#modelSuccess').attr('onclick','messageReply(false)');
            $('#modelCancle').html('Cancel');
            var wrapUpData=$('#wrapup_lists').html();
            var Scentiment_list=$('#Scentiment_list').html();
            $('#modelBody').html(wrapUpData);
            $('#modelBody').append(Scentiment_list);
            setTimeout(commentReplySendInit,500);
        }
        else{
            //alert('call');
            messageReply(false);
        }
    }
    else{
        $('#msgData').addClass('redBorder');
    }
}
function messageReply(isDone){
    var sender_id       = $('#sender_id').val();
    var attatchment     = parse_int($('input[name=attatchment]:checked').val());
    //alert(attatchment     )
    var i=0;
    if(sender_id!=''){
        var sendtype=$('input[name=sendtype]:checked').val();
        if(sendtype!=1&&sendtype!=3)sendtype=2;
        var message= ($('#msgData').val());
        message=message.trim();
        //        alert('-'+message+'-');
        message=encodeURIComponent(message);
        //        alert('-'+message+'-')
        //alert(message)
        $('#messageModelCancle').click();
        //        alert(isDone)
        //        alert(attatchment)
        if( (message!=''&&message!='%20') || isDone===true || attatchment>0 ){
            //            alert('-'+message+'-')
            //            alert(isDone)
            //            alert(attatchment)
            var wrapupId=parse_int($('#modelBody .wrapUpNumber').val());
            if(wrapupId==0){
                wrapupId=parse_int($("#modelBody input[name=wrapupType]:checked").val());
            }
            var scentiment=parse_int($('#modelBody .ScentimentTypeNumber').val());
            if(scentiment==0){
                scentiment=parse_int($("#modelBody input[name=ScentimentType]:checked").val());
            }
            message=incText(message);
            messageData= {
                messageReply:sender_id,
                mid:messageMid,
                message:message,
                scentiment:scentiment,
                wrapupId:wrapupId,
                sendtype:sendtype,
                attatchment:attatchment
            };
            if(isDone==true){
                messageData.isDone=1;   
                sendtype=1;
            }

            if(sendtype==3){
                animateHtmlStop();
                animateHtmlVar = setInterval(function(){ animateHtmlRun('modelSuccess','Sending') }, 400);
            }
            if(isSending==0){
                isSending=1;
                $('#messageSendignShow').show();
                $.post(ajUrl, messageData, function(data) {
                    $('#messageSendignShow').hide();
                    animateHtmlStop();
                    //clearInterval(isSendingCheck);
                    isSending=0;
                    if(data.status==1){
                        messageReInit();
                        if(sendtype==3||sendtype==1){
                            $('#main_comment').html('');
                            messageCurrentSender='';
                            delete messageObj.senders[sender_id];
                            delete messageObj.message[sender_id];
                            $('#serve_'+sender_id).remove();
                            /*
                            if(data.hasOwnProperty('senders')){
                            $.each(data.senders,function(i,mData){
                            messageLeftBoxDataSet(mData);
                            });

                            $.each(data.message,function(sender_id,ma){
                            messageObj.message[sender_id]=[];
                            var length=0;
                            $.each(ma,function(i,m){
                            messageObj.message[sender_id][length]=m;
                            length++;
                            });
                            });
                            }*/
                        }
                        else{
                            //alert(sendtype)
                        }
                    }
                    else if(data.login==0){location.reload();LOGDIN=0;}

                    if(data.hasOwnProperty('m')){
                        $('.close').click();
                        createMsgFromJson(data.m);
                    }
                });
            }
            //}else{t('Invalid wrapup id');}
        }else{alert('Message field is empyt or any attachment not slect');}
    }
    else{alert('Any message or sender not select');}
    //$('#wallPostClose').attr('checked', false);
}
function messageReInit(){
    $('input[name=attatchment]').prop('checked', false);
    $('.close').click();
    $('#msgData').val('');
    $('#messageCharacterCount').html('0');
}

function messageLeftSideLoad(){
    inService=true;
    $('#messageInService').prop('checked', true);
    $('#messageStartOrClose').html('Currently Serving');
    //$('#messageServeArea').html('Loading...');
    maxSendTimestamp=0;
    messageObj={senders:{},message:{}};
    /*
    $('#main_comment').html('');
    messageLeftLoading=1;
    $.post(ajUrl,{messageLeftSideLoad:1},function(data){
    messageLeftLoading=0;
    if(data.status==1){
    var si=data.senders;
    $('#messageServeArea').html('');
    $.each(si,function(i,mData){
    messageLeftBoxDataSet(mData);
    });
    $.each(data.message,function(sender_id,ma){
    messageObj.message[sender_id]=[];
    var length=0;
    $.each(ma,function(i,m){
    messageObj.message[sender_id][length]=m;
    length++;
    });
    });
    }
    });*/
}
function messageLeftBoxDataSet(mData){
    document.getElementById('audionotification').play();
    messageObj.senders[mData.sender_id]=mData;
    if(mData.at!=''){
        if(mData.at[0]['type']=='image'){
            mData.url=mData.at[0]['url'];
        }
    }
    $('#messageLeftArea .comment_inner_wrapper').attr('id','serve_'+mData.sender_id);
    $('#messageLeftArea .sender_id').val(mData.sender_id);
    $('#messageLeftArea .post_profile_picture').attr('src',mData.senderPictureLink);
    $('#messageLeftArea .evac_user').html('<span>'+mData.senderName+':</span>');
    $('#messageLeftArea .message').html(mData.text);
    $('#messageLeftArea .status_image').attr('src',mData.url);
    $('#messageLeftArea .comment_time_from_now').html(mData.sendTime);
    var messageLeftArea=$('#messageLeftArea').html();
    $('#messageServeArea').append(messageLeftArea);
    $.post(ajUrl,{messageUserInfoFromFb:mData.sender_id},function(data){
        if(data.status==1){
            $('#serve_'+data.sender_id+' .evac_user').html('<span>'+data.senderName+':</span>');
            $('#serve_'+data.sender_id+' .post_profile_picture').attr('src',data.senderPictureLink);
        }
        
    });
}
function messageCharacterCount(){
    var msgData=$('#msgData').val();
    var messageCharacterCount=msgData.length;
    $('#messageCharacterCount').html(messageCharacterCount);
}
function messageReplyType(e){if (e.keyCode == 13 && !e.shiftKey) {messageReplyInit();return false;}else{messageCharacterCount();}}
function messageLeftSideRefresh(){
    var messageInService=$('#messageInService').is(':checked');
    if(messageInService==true&&messageLeftLoading==0){
        var i=0;
        var currentService  = [];
        $.each(messageObj.senders,function(si,x){
            currentService[i]=si;i++;
        });
        //t(sameReturn(messageObj))
        //t(sameReturn(MESSAGE_AGENT_MAX_SERVICE))
        //t(sameReturn(currentService.length))
        if(currentService.length<MESSAGE_AGENT_MAX_SERVICE){
            //t('call')
            if(currentService.length==0){
                currentService=0;
            }
            messageLeftLoading=1;
            $.post(ajUrl2,{messageLeftSideRefresh:currentService,STOKEN:STOKEN},function(data){
                messageLeftLoading=0;
                if(data.status==1){
                    var match=0;
                    var si=data.senders;
                    //$('#messageServeArea').html('');
                    $.each(si,function(i,mData){
                        match=0;
                        $.each(messageObj.senders,function(siId,x){
                            if(siId==mData.sender_id){
                                match=1;    
                            }
                        });
                        if(match==0){
                            messageLeftBoxDataSet(mData);
                        }
                    });
                    $.each(data.message,function(sender_id,ma){
                        match=0;
                        $.each(messageObj.message,function(siId,x){
                            if(siId==sender_id){
                                match=1;    
                            }
                        });
                        if(match==0){
                            messageObj.message[sender_id]=[];
                            var length=0;
                            $.each(ma,function(i,m){
                                messageObj.message[sender_id][length]=m;
                                length++;
                            });
                        }
                    });
                }
            });
        }
        else{
            t(sameReturn(messageObj))
        }
    }
    else{
        if(inService==true){
            $('#messageStartOrClose').html('Start');
        }
    }
}
function messageDone(){
    messageReply(true);
    return true;
    /*var mid=getPostCommentId();
    if(mid!=''){
    animateHtmlStop();
    animateHtmlVar = setInterval(function(){ animateHtmlRun('wallPostActionText','Action')},400);
    var isClose=0;if($("#wallPostClose").is(':checked'))isClose=1;
    post_data = {'newMessageByAgent':2, 'mid':mid, 'isClose':isClose,'is_done':'1'};
    $.post(ajUrl, post_data, function(data) {
    animateHtmlStop();
    if(data.status==1){
    clearScreen();
    $('.close').click();
    if(data.hasOwnProperty('nextPicup')){
    newMessageLoadDistribute(data.nextPicup);
    }
    else{
    wallPostPause();
    }

    }
    else if(data.login==0){location.reload();LOGDIN=0;}
    });
    }*/
}
function specialCharacterRemove(mString){
    return mString.replace(/[^a-zA-Z ]/g, "");
}
function commentReplyType(e){if (e.keyCode == 13 && !e.shiftKey) {wallPostReplyInit();return false;}}
function exportToExcel(eIndex){
    if(reportDta.hasOwnProperty(eIndex)){
        $.ajax({
            type:"POST",
            url:ajUrl,
            data:{exportToExcel:reportDta[eIndex]},
            success:function(data){
                if(data.status==1){
                    window.location=data.link;
                    //                  $('#exportFileDownload').attr('href',data.link);
                    //                  $('#exportFileDownload').click();
                }  
            }

        })


    }else{alert("It's cant export");}
}
function dashboardGraphSet(start,end){
    $('#status').html('Loading');
    $('#wall').html('Loading');
    $('#msg').html('Loading');
    $.ajax({
        type:"POST",
        url:ajUrl,
        data:'dashboardGraph=1&st='+start+'&en='+end,
        success:function(data){
            $('.dashboartGraphTitle').html(start+' - '+end);
            $.each(data.graph,function(index,graphVars){
                reportDta[index]=graphVars;
                graphMake(index,graphVars);
            });
            //            t(reportDta)
            var s =data.summary;
            reportDta.summary={
                title:"Report summery",
                legend:[
                    'Status Admin Post',
                    'Status User Activity',
                    'Status Admin Activity',
                    'Wall Post User Activity',
                    'Wall Post Admin Activity',
                    'Message In',
                    'Message OUT'
                ],
                serises:{
                    'Status Admin Post':[s.adminPost],
                    'Status User Activity':[s.userActivity],
                    'Status Admin Activity':[s.adminActivity],
                    'Wall Post User Activity':[s.wallPost],
                    'Wall Post Admin Activity':[s.wallAdminActivity],
                    'Message In':[s.messageIn],
                    'Message OUT':[s.messageOut]
                },
                xData:['Total']

            }
            $('#exportExcel').html('');
            $.each(reportDta,function(i,v){
                $('#exportExcel').append('<li><a href="javascript:void();" onclick="exportToExcel('+"'"+i+"'"+')">'+v.title+'</a></li>');
            });
            $('#adminPost').html(s.adminPost);
            $('#userActivity').html(s.userActivity);
            $('#adminActivity').html(s.adminActivity);
            $('#commentHandleTime').html(s.commentHandleTime);
            $('#commentHandleTimeWall').html(s.commentHandleTimeWall);
            $('#commentResponseTime').html(s.commentResponseTime);
            $('#commentResponseTimeWall').html(s.commentResponseTimeWall);
            $('#wallPost').html(s.wallPost);
            $('#wallAdminActivity').html(s.wallAdminActivity);
            $('#messageIn').html(s.messageIn);
            $('#messageOut').html(s.messageOut);
            $('#messageAHT').html(s.mAht);
            $('#messageART').html(s.mArt);
            $('#messageVisitor').html(s.muu);
            $('#messageReply').html(s.mur);
        }
    })
}  
function editResponse(mtID){
    var title    = encodeURIComponent($("#titleView").val());
    var response = encodeURIComponent($('#messageView').val());
    if(title==''){
        $("#titleView").css("border","1px solid red");
        $("#messageView").css("border","1px solid #ccc");
    }
    else if(response==''){
        $("#titleView").css("border","1px solid #ccc");
        $("#messageView").css("border","1px solid red");
    }
    else{
        var searchString='index.php?ajax=1&editResponse=1&title='+title+'&response='+response+'&mtID='+mtID;
        $.ajax({
            type:'POST',
            url:ajUrl,
            data:searchString,
            success:function(data){        
                $("#rvModal").modal("hide");
                var content = '<li id="tm_'+data.mtID+'"> <i class="fa fa-remove" onclick="removeResponse('+data.mtID+')" style="cursor:pointer"></i><i style="cursor:pointer" onclick="viewResponse('+mtID+')" class="fa fa-eye"> </i> <i class="fa fa-heart" onclick="makeFav('+data.mtID+')" style="cursor:pointer"></i><a data-text="'+data.mtText+'" onclick="useTemplate('+data.mtID+',1)" id="res_'+data.mtID+'" class="template" href="javascript:void(0);">'+data.mtTitle+'</a></li>'; 
                $("#tm_"+data.mtID).remove();
                $("#responseList").append(content);
            }
        }) 
    }

} 
function filter(element,id) {
    var value = $(element).val();
    $(id+" li").each(function() {
        if ($(this).text().search(new RegExp(value, "i")) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function getPostCommentId(){
    if(wallPostType=='c'){
        var comment_id=$('#comment_id').val();
    }
    else if(wallPostType=='m'){
        var comment_id=$('#mid').val();
    }
    else{
        var comment_id=$('#post_id').val();
    }
    return comment_id;
}
function getIsClose(){var isClose=0;if($("#wallPostClose").is(':checked'))isClose=1;return isClose;}
function getPrivateReply(){
    var priv_rep=0;
    if($("#priv_rep").is(':checked'))priv_rep=2;
    /*if(priv_rep==0){
    if($("#priv_rep_delete").is(':checked'))priv_rep=1;
    }*/
    return priv_rep;
}
function getPrivateReply(){
    var priv_rep=0;
    if(wallPostType=='c'){
        if($("#priv_rep_delete").is(':checked'))priv_rep=2;
        if(priv_rep==0){
            if($("#priv_rep").is(':checked'))priv_rep=1;
        }
    }
    return priv_rep;
}
function graphMake(setId,allData){
    var seriesData=[];
    var d={}
    $.each(allData.serises,function(name,data){
        d={
            name: name,
            type: 'line',
            smooth: true,
            data: data
        };
        seriesData.push(d);
    })
    //    t(seriesData)
    var echartLine = echarts.init(document.getElementById(setId));
    echartLine.setOption({
        title: {
            text: allData.title
        },
        tooltip: {
            trigger: 'line'
        },
        legend: {
            x: 60,
            y: 30,
            data: allData.legend
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {
                    show: true,
                    title: {line: 'Line',bar: 'Bar',stack: 'Stack',tiled: 'Tiled'},
                    type: ['line', 'bar', 'stack', 'tiled']
                },restore: {
                    show: true,title: "Restore"},
                saveAsImage: {show: true,title: "Save Image"}
            }
        },
        calculable: true,
        xAxis: [{type: 'category',
            boundaryGap: false,
            data: allData.xData
        }],
        yAxis: [{type: 'value'}],
        series:seriesData
    });
}
function isNumberKey(evt){
    var charCode=(evt.which)?evt.which:event.keyCode;
    //    t(charCode);
    if(
        (charCode>31&&
            (charCode<48|| charCode > 57)&&
            charCode!=46&&charCode!=13)&&
        (charCode>2543||charCode<2534)
    ){return false;}
    return true;
}
function isSendingCheckFunction(){
    isSending++;
    //    t(isSending+' ccc')
    if(isSending>6){
        isSending=0;
        clearInterval(isSendingCheck);
    }
}
function incText(text){
    //Now it's not need. data set before.
    return text;
    /*var type=$("input[name=wallPostInclude]:checked").val();
    var cusN=$('#include1').attr('data-value');
    var agN=$('#include2').attr('data-value');
    if(type==1){
    text = '@'+cusN+', '+text;
    }
    else if(type==2){
    text = text+' -'+agN;
    }
    else if(type==3){
    text = '@'+cusN+', '+text+' -'+agN;
    }
    return text;*/
}
function inPostActivity(rData){ 
    if(rData.life_time=='' || rData.life_time=='undefined'){
        $("#inPostActivity").html('No Activity');
    }
    else{
        $('#inPostActivity').html('');
        $(rData.post_activity).each(function(i,d){
            commentDataSet(d);
            var lifetime_comment=$('#visitor_comment').html();
            $('#inPostActivity').append(lifetime_comment);
        });  
    }
}               
function jsonConvert(str) {
    try {
        var jsStr =JSON.parse(str);
        return jsStr;
    } catch (err) {
        var errTxt='{"status": "0"}';
        return JSON.parse(errTxt);
    }
}
function lifeTimeActivity(rData){ 
    if(rData.life_time=='' || rData.life_time=='undefined'){
        $("#lifeTimeActivity").html('No Activity');
    }
    else{
        $('#lifeTimeActivity').html('');
        $(rData.life_time).each(function(i,d){
            commentDataSet(d);
            var lifetime_comment=$('#visitor_comment').html();
            $('#lifeTimeActivity').append(lifetime_comment);
        });  
    }
}
function linkByCommentId(post_id,comment_id,parent_id){
    if(parent_id==undefined){parent_id='';}
    //t(post_id+' '+comment_id)
    var link=commentpostids[post_id];
    if(link!=undefined&&link!=100){
        //t(link)
        if(link.indexOf('?')==-1){link+='?';}else{link+='&';}
        var postId=comment_id.split('_');
        if(parent_id!=''){
            var parentPage=parent_id.split('_');
            if(parentPage[0]==PAGEID){
                if(parent_id==post_id){
                    link+='comment_id='+postId[1];
                }
                else{
                    link+='comment_id='+postId[0]+'&reply_comment_id='+postId[1];
                }
            }
            else{
                link+='comment_id='+parentPage[1]+'&reply_comment_id='+postId[1];
            }
        }
    }
    else{
        link='javascript:void();';
    }
    return link;
}
function momentTime(targetTime){return moment(targetTime, "YYYYMMDDHHmmss").from(moment(lastTime, "YYYYMMDDHHmmss"));}
function makeFav(tmid,txt){
    $.post(ajUrl, 'mtID='+tmid+'&makeFav=1',  function(data) { 
        var content = '<li id="fmt_'+data.fmtID+'"><i class="fa fa-remove" onclick="removeFav('+data.fmtID+')" style="cursor:pointer"></i><a href="javascript:void();" id="fav_'+tmid+'" data-title="'+data.mtTitle+'" data-text="'+data.mtText+'" class="template" onclick="useTemplate('+tmid+',2)">'+data.mtTitle+'</a></li>'; 
        $("#fav_msg_tem").append(content);  
    });
}
function messageDataSet(d){
    $('#visitor_comment .message').html(d.text);
    if(d.sendType==2){
        $('#visitor_comment .post_profile_picture').attr('class','post_profile_picture');
        $('#visitor_comment .post_profile_picture').attr('src','https://graph.facebook.com/'+PAGEID+'/picture');
        $('#visitor_comment .sender_id').html('<span>'+PAGE_NAME+'</span>');
    }
    else{
        $('#visitor_comment .post_profile_picture').attr('class','post_profile_picture pic_'+d.sender_id);
        $('#visitor_comment .sender_id').html('<span class=name_'+d.sender_id+'></span>');
    }
    if(d.url!=''){
        $('#visitor_comment .photo').css('display','block');
        $('#visitor_comment .photo').attr('src',d.url);
    }
    else{
        $('#visitor_comment .time').html(momentTime(d.sendTime));
        $('#visitor_comment .time').attr('href','javascript:void()');
        $('#visitor_comment .time').attr('target','');
        $('#visitor_comment .photo').css('display','none');
        $('#visitor_comment .photo').attr('src','');
    }
}
function messageReplySend(){
    var mid             = $('#mid').val();
    var sender_id       = $('#sender_id').val();
    var attatchment     = $('input[name=attatchment]:checked').val();
    /*alert(attatchment);
    এটা নিয়ে কাজ আছে মোছা যাবেনা
    $('input[name=attatchment]').prop('checked', false);
    var attatchment     = $('input[name=attatchment]:checked').val();
    alert(attatchment     );*/
    //    t(comment_id+' a '+wallPostType);
    if(mid!=''&&sender_id!=''){
        var message= encodeURIComponent($('#msgData').val());
        message='';
        if(message!=''){
            var wrapupId=parse_int($('#modelBody .wrapUpNumber').val());
            if(wrapupId==0){
                wrapupId=parse_int($("#modelBody input[name=wrapupType]:checked").val());
            }
            var scentiment=parse_int($('#modelBody .ScentimentTypeNumber').val());
            if(scentiment==0){
                scentiment=parse_int($("#modelBody input[name=ScentimentType]:checked").val());
            }
            //if(wrapupId!=0){
            var isClose=getIsClose();
            message=incText(message);
            post_data = {
                'newMessageByAgent':1,
                'mid':mid,
                'message':message,
                'scentiment':scentiment,
                'wrapupId':wrapupId,
                'isClose':isClose,
                'type':wallPostType
            };
            //alert('d');
            animateHtmlStop();
            animateHtmlVar = setInterval(function(){ animateHtmlRun('modelSuccess','Sending') }, 400);
            //                t(isSending);
            if(isSending==0){
                isSending=1;
                isSendingCheck=setInterval(isSendingCheckFunction,1000);
                $.post(ajUrl, post_data, function(data) {
                    animateHtmlStop();
                    clearInterval(isSendingCheck);
                    isSending=0;
                    if(data.status==1){
                        $('.close').click();
                        if(data.hasOwnProperty('nextPicup')){
                            newMessageLoadDistribute(data.nextPicup);
                        }
                        else{
                            wallPostPause();
                        }

                    }
                    else if(data.login==0){location.reload();LOGDIN=0;}
                });
            }
            //}else{t('Invalid wrapup id');}
        }else{t('Message field is empyt');}
    }else{t('Any message or sender not select');}
    $('#wallPostClose').attr('checked', false);
}
function newCommentLoadDistribute(rData){
    clearScreen();
    if(rData.hasOwnProperty('serviceTime')){serviceTime=rData.serviceTime;}
    if(rData.status==1){
        $('#commentFromFb').show();
        $('.check_service_area').show();
        animateHtmlStop();
        $('#wallPostTitle').html('Comment');
        var pData=rData.post;
        $("#status_text").html(pData.message);
        $('#post_id').val(pData.post_id);
        //t(pData.type+' '+POST_TYPE_PHOTO);
        if(pData.type==POST_TYPE_PHOTO){
            $('.status_image').attr('src','');
            $('.status_image').attr('src',pData.link);
            $('#status_image').show();
        }
        else{
            $('#status_image').hide();
        }
        //$('#main_comment').html('');
        var commentSerial=1;
        var mainCommentID=rData.target;
        commentServeTimeCheck(mainCommentID);
        $('#comment_id').val(mainCommentID);
        $('#sender_id').val(rData.target_sender_id);
        var incSet=$("input[name=wallPostInclude]:checked").val();
        if(incSet==1||incSet==3){
            var nameSet=0;
            var nameData='@ Dear Customer';
            if(rData.hasOwnProperty('target_sender_name')){
                if(rData.target_sender_name!=''){nameData='@ '+rData.target_sender_name;}
            }
            else{
                $(rData.names).each(function(i,j){
                    if(nameSet==0){
                        if(j.id==rData.target_sender_id){
                            //t('set from old system');
                            nameData='@ '+j.name+', ';
                            nameSet=1;
                        }
                    }
                });
            }
            $('#msgData').val(nameData+', ');
            if(incSet==3){
                var agN=$('#include2').attr('data-value');
                var msgData=$('#msgData').val();
                $('#msgData').val(msgData+' -'+agN);
            }
        }
        else if(incSet==2){
            var agN=$('#include2').attr('data-value');
            $('#msgData').val(' -'+agN);
        }
        $(rData.comments).each(function(i,d){
            if(d.parent_id!=pData.post_id){
                $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment');
            }
            else{
                $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');
            }
            if(d.comment_id==mainCommentID){
                cLink=linkByCommentId(d.post_id,d.comment_id,d.parent_id);
                var mL='https://www.facebook.com/plugins/comment_embed.php?href='+encodeURIComponent(cLink+'&include_parent=false');
                $('#commentFromFb').html('<iframe src="'+mL+'" width="270" height="270" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>');
                $('#visitor_comment .comment_inner_wrapper').addClass('highlight_comment');
            }
            else{
                $('#visitor_comment .comment_inner_wrapper').removeClass('highlight_comment');
            }
            commentDataSet(d);
            $('#visitor_comment .comment_inner_wrapper').attr('id','comment_'+d.comment_id);
            var main_comment=$('#visitor_comment').html();
            //            t(main_comment)
            $('#main_comment').append(main_comment);
            $('#visitor_comment .comment_inner_wrapper').removeClass('highlight_comment');
            commentSerial++
        }); 
        var targetCommentDiv='comment_'+mainCommentID;
        var contactTopPosition = 150;
        $("#targetCommentDiv").animate({scrollTop: contactTopPosition});
        lifeTimeActivity(rData);
        inPostActivity(rData);  
        setNames(rData.names);
        //$("#c_id").val(pData.cID);
        // alert(msgString);
        replyBoxShow();
        newTitle = "Pending Comments";
        titleInterval = setInterval(changeTitle, 700);
    }
    else if(rData.login==0){location.reload();LOGDIN=0;}
        else{newPostOrCommentLoadRecall();}
    if(rData.hasOwnProperty('m')){
        createMsgFromJson(rData.m);
    }
    //$('#msgData').setCursorToTextEnd();
    //$("#includeCustomer").click();
}
function changeTitle() {
    document.title = isOldTitle ? thisPageTitle : newTitle;
    isOldTitle = !isOldTitle;
}
$(window).focus(function () {
    clearInterval(titleInterval);
    $("title").text(thisPageTitle);
});
function newPostOrCommentLoadRecall(){
    clearInterval(titleInterval);
    clearScreen();
    //var timerVar = setInterval(countTimer, 1000);
    setTimeout(function(){newWallPostCommentLoad()},3000);
}
function countTimer() {
    serviceTime++;
    var hour = Math.floor(serviceTime /3600);
    var minute = Math.floor((serviceTime - hour*3600)/60);
    var seconds = serviceTime - (hour*3600 + minute*60);
    if(minute<10)minute='0'+minute+''
    if(seconds<10)seconds='0'+seconds+''
    $('#activityHour').html(hour);
    $('#activityMinute').html(minute);
    $('#activitySecond').html(seconds);
}
function newWallPostCommentLoad(){
    clearInterval(timerVar);
    timerVar = setInterval(countTimer, 1000);
    clearScreen();
    //        t('s')
    animateHtmlVar = setInterval(function(){ animateHtmlRun('wallPostTitle','Loading') }, 500);
    $.ajax({
        type:'post',
        url:ajUrl,
        data:'nextPicup='+wallPostType,
        success:function(data,textStatus,xhr){
            lastTime=data.lastTime;
            if(data.hasOwnProperty('serviceTime')){serviceTime=data.serviceTime;}
            commentpostids=data.post_ids;
            if(wallPostType=='c'){
                newCommentLoadDistribute(data);
            }
            else if(wallPostType=='m'){
                newMessageLoadDistribute(data);
            }
            else{
                newWallPostLoadDistribute(data);
            }

        },
        statusCode: {
            500: function() {newPostOrCommentLoadRecall();},
            0: function() {setTimeout(newPostOrCommentLoadRecall,10000);}

        },
        complete: function(xhr, textStatus) {
            //            console.log(xhr.status);
        } 
    });
}
function newMessageLoadDistribute(rData){
    clearScreen();
    if(rData.hasOwnProperty('serviceTime')){serviceTime=rData.serviceTime;}
    if(rData.status==1){
        $('.check_service_area').show();
        animateHtmlStop();
        $('#wallPostTitle').html('Message');
        var commentSerial=1;
        $('#mid').val(rData.mid);
        $('#sender_id').val(rData.sender_id);
        var lastmid=rData.mid;
        $(rData.message).each(function(i,d){
            lastmid=d.mid;
            if(d.sendType==2){
                $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment');
            }
            else{
                $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');
            }
            $('#visitor_comment .comment_inner_wrapper').attr('id','allmsg_'+d.mid);
            messageDataSet(d);
            //t($('#visitor_comment .sender_id').html());
            var main_comment=$('#visitor_comment').html();
            $('#main_comment').append(main_comment);
            commentSerial++
        });
        var wtf = $('#main_comment');
        var height = wtf[0].scrollHeight;
        wtf.scrollTop(height);

        lifeTimeActivity(rData);
        setNamesForMmessages(rData.names);
        replyBoxShow();
    }
    else if(rData.login==0){location.reload();LOGDIN=0;}
        else{newPostOrCommentLoadRecall();}
    $("#includeCustomer").click();
}
function newWallPostLoadDistribute(rData){
    clearScreen();
    if(rData.hasOwnProperty('serviceTime')){serviceTime=rData.serviceTime;}
    if(rData.status==1){
        $('#commentFromFb').show();
        $('#check_service_area').show();
        var pData=rData.post;
        $('#visitor_main_name').html('<span class=name_'+pData.sender_id+'></span>');
        $('#visitor_main_propic').attr('src','https://graph.facebook.com/'+pData.sender_id+'/picture?type=square');
        $('#wallPostTitle').html('Wall Post');

        $("#post_time").html('<a target="_blank" href="'+commentpostids[pData.post_id]+'">'+momentTime(pData.time)+'</a>');
        $("#status_text").html(pData.message);
        $('#post_id').val(pData.post_id);
        $('#sender_id').val(pData.sender_id);
        if(pData.link!=''){
            $('.status_image').attr('src',pData.link);
            $('#status_image').show();
        }
        else{
            $('#status_image').hide();
        }
        var incSet=$("input[name=wallPostInclude]:checked").val();
        if(incSet==1||incSet==3){
            var nameSet=0;
            var nameData='@ Dear Customer';
            if(rData.hasOwnProperty('target_sender_name')){
                if(rData.target_sender_name!=''){nameData='@ '+rData.target_sender_name;}
            }
            else{
                $(rData.names).each(function(i,j){
                    if(j.id==rData.target_sender_id){
                        if(nameSet==0){
                            t('set from old system');
                            nameData='@ '+j.name+', ';
                            //$('#include1').attr('data-value',j.name);
                            nameSet=1;
                        }
                    }
                });
            }
            $('#msgData').val(nameData+', ');
            if(incSet==3){
                var agN=$('#include2').attr('data-value');
                var msgData=$('#msgData').val();
                $('#msgData').val(msgData+' -'+agN);
            }
        }
        else if(incSet==2){
            var agN=$('#include2').attr('data-value');
            $('#msgData').val(' -'+agN);
        }
        //$('#main_comment').html('');
        var commentSerial=1;
        var mainCommentID='';
        if(rData.targetType=='c'){
            mainCommentID=rData.target;
        }
        $('#targetType').val(rData.targetType);
        $('#comment_id').val(mainCommentID);
        var commentHilightSet=0;
        $('#visitor_comment .comment_inner_wrapper').removeClass('highlight_comment');
        if(rData.targetType=='p'){
            $('#posthilight').addClass('post_highlight');
            var cLink=commentpostids[rData.target];
            if (cLink !== null&&cLink !== 100){
                //t(cLink);
                if(cLink.indexOf('?')==-1){cLink+='?';}
                $('#commentFromFb').html('<iframe src="'+SITE_URL+'/comment_iframe.php?link='+cLink+'&postUrl"></iframe>');
            }
            else{
                $('#commentFromFb').html('');
            }


            var cLink=commentpostids[rData.target];
            if (cLink !== null&&cLink !== 100){
                if(cLink.indexOf('?')==-1){cLink+='?';}
                var mL='https://www.facebook.com/plugins/post.php?href='+encodeURIComponent(cLink)+'&width=270';
                $('#commentFromFb').html('<iframe src="'+mL+'" width="270" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>');
            }


        }
        else{$('#posthilight').removeClass('post_highlight');}

        $(rData.comments).each(function(i,d){
            if(d.parent_id!=pData.post_id){$('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment');}
            else{$('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');}
            if(rData.targetType=='c'){
                if(d.comment_id==mainCommentID){
                    cLink=linkByCommentId(d.post_id,d.comment_id,d.parent_id);
                    $('#commentFromFb').html(' <iframe src="'+SITE_URL+'/comment_iframe.php?link='+cLink+'"></iframe> ')

                    $('#visitor_comment .comment_inner_wrapper').addClass('highlight_comment');commentHilightSet=1;
                }
                else{$('#visitor_comment .comment_inner_wrapper').removeClass('highlight_comment');}
            }
            commentDataSet(d);
            //t($('#visitor_comment .sender_id').html());
            var main_comment=$('#visitor_comment').html();
            $('#main_comment').append(main_comment);
            $('#visitor_comment .comment_inner_wrapper').removeClass('highlight_comment');
            commentSerial++
        });

        lifeTimeActivity(rData);
        setNames(rData.names);
        replyBoxShow();
        titleInterval = setInterval(changeTitle, 700);
    }
    else if(rData.login==0){location.reload();LOGDIN=0;}
        else{newPostOrCommentLoadRecall();}
}
function parse_float(rString){rString=parseFloat(rString);if(isNaN(rString))rString=parseFloat(0.00);return rString;}
function parse_int(rString){rString=parseInt(rString);if(isNaN(rString))rString=parseInt(0);return rString;}
function postCommentLike(){
    var comment_id=getPostCommentId();
    if(comment_id!=''){
        animateHtmlStop();
        animateHtmlVar = setInterval(function(){ animateHtmlRun('wallPostActionText','Action')},400);
        post_data = {'newLikeByAgent':1, 'comment_id':comment_id,'type':wallPostType};
        $.post(ajUrl, post_data, function(data) {
            animateHtmlStop();
            if(data.login==0){location.reload();LOGDIN=0;}
        });
    }
}
function postCommentDone(){
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
    if(comment_id!=''){
        if(confirm('Sure to done this without any comment?')){
            wallPostHideDoneInit()
            animateHtmlStop();
            animateHtmlVar = setInterval(function(){animateHtmlRun('wallPostActionText','Action')},400);
            var wrapupId=parse_int($('#modelBody .wrapUpNumber').val());
            if(wrapupId==0){
                wrapupId=parse_int($("#modelBody input[name=wrapupType]:checked").val());
            }

            var scentiment=parse_int($('#modelBody .ScentimentTypeNumber').val());
            if(scentiment==0){
                scentiment=parse_int($("#modelBody input[name=ScentimentType]:checked").val());
            }

            var isClose=0;if($("#wallPostClose").is(':checked'))isClose=1;
            post_data = {'newCommentByAgent':2, 'comment_id':comment_id, 'isClose':isClose,'type':wallPostType,'targetType':targetType,'is_done':'1',scentiment:scentiment,wrapupId:wrapupId};
            $.post(ajUrl, post_data, function(data) {
                animateHtmlStop();
                $('#actionWorking').hide();
                if(data.status==1){
                    $('.close').click();
                    setTimeout(function(){pNotify('Comment Done Successfully.','s');},700);
                    if(data.hasOwnProperty('nextComment')){
                        if(wallPostType=='c'){
                            newCommentLoadDistribute(data.nextComment);
                        }
                        else if(wallPostType=='w'){
                            newWallPostLoadDistribute(data.nextComment);
                        }
                    }
                    else{
                        wallPostPause();
                    }

                }
                else if(data.login==0){location.reload();LOGDIN=0;}
            });
        }
    }
}
function postCommentHide(){
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
    if(confirm('Are You sure to HIDE and DONE this comment?')){
        var isClose=0;if($("#wallPostClose").is(':checked'))isClose=1;
        $('#actionWorking').show();
        animateHtmlStop();
        animateHtmlVar = setInterval(function(){animateHtmlRun('actionWorking','Wait')},400);
        var wrapupId=parse_int($('#modelBody .wrapUpNumber').val());
        if(wrapupId==0){
            wrapupId=parse_int($("#modelBody input[name=wrapupType]:checked").val());
        }

        var scentiment=parse_int($('#modelBody .ScentimentTypeNumber').val());
        if(scentiment==0){
            scentiment=parse_int($("#modelBody input[name=ScentimentType]:checked").val());
        }
        $.ajax({
            type:"POST",
            url:ajUrl,
            data:{postCommentHide:comment_id,type:wallPostType,targetType:targetType,isClose:isClose,scentiment:scentiment,wrapupId:wrapupId},
            success:function(data){
                animateHtmlStop();
                $('#actionWorking').hide();
                if(data.status==1){
                    $('.close').click();
                    setTimeout(function(){pNotify('Comment Hide Successfully.','s');},700);
                    if(data.hasOwnProperty('nextComment')){
                        if(wallPostType=='c'){
                            newCommentLoadDistribute(data.nextComment);
                        }
                        else if(wallPostType=='w'){
                            newWallPostLoadDistribute(data.nextComment);
                        }
                        //newCommentLoadDistribute(data.nextComment);
                    }
                    else{
                        wallPostPause();
                    }

                }
                else if(data.login==0){location.reload();LOGDIN=0;}
            }
        });
    }
}
function pNotifyFromJson(jsonMsg){
    $.each(jsonMsg,function(c,ms){
        mt='';mm=''
        $.each(ms,function(tp,m){if(tp==0){mt=m;}else if(tp==1){mm=m;}});
        pNotify(mm,mt);
    });
}
function pNotify(message,type){
    var title = '';
    if(type=='e'){
        title='Error';
        type='error';
    }
    else if(type=='w'){
        title='Warning';
        type='warning';
    }
    else if(type=='i'){
        title='Info';
        type='info';
    }
    else if(type=='s'){
        type='success';
        title = 'Success';
    }
    else{
        title='Error';
        type='error';
    }
    new PNotify({
        title: title,
        text: message,
        type: type,
        hide: true,
        styling: 'bootstrap3'
    });
}
function queueCleare(type,queueID){
    var targetID='rp_'+queueID;
    $.ajax({
        type:'POST',
        url:ajUrl,
        data:{outboxRetry:queueID,type:type},
        success:function(data){

            if(data.hasOwnProperty('e')){
                alert(data.e);
            }
            if(data.hasOwnProperty('errorCode')){$('#'+targetID+' .errorCode').html(data.errorCode)}
            if(data.hasOwnProperty('errorMessage')){$('#'+targetID+' .errorMessage').html(data.errorMessage)}
            if(data.hasOwnProperty('totalTry')){$('#'+targetID+' .totalTry').html(data.totalTry)}
            if(data.hasOwnProperty('status')){
                if(data.status==1){
                    $('#'+targetID).hide();
                }
            }
        }
    });
}
function replyBoxShow(){
    $('.check_service_area').show();
    $('#msgData').show();
    $('#msgData').focus();
    document.getElementById('audionotification').play();
    return true;
}
function removeFav(fmtID){
    $.post(ajUrl, 'fmtID='+fmtID+'&removeFav=1',  function(data) { 
        $("#fmt_"+fmtID).hide(300);  
    });
}
function removeResponse(mtID){
    $.post(ajUrl, 'mtID='+mtID+'&removeRes=1',  function(data) { 
        $("#tm_"+mtID).hide(300);  
    });
}
function removePostComment(){
    if(confirm('Are you sure to delete this Post / Comment?')){
        if(confirm('Please check again')){

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
            if(comment_id!=''){
                var isClose=getIsClose();
                animateHtmlStop();
                animateHtmlVar = setInterval(function(){ animateHtmlRun('wallPostActionText','Action')},400);
                post_data = {'removePostComment':1, 'comment_id':comment_id,'type':wallPostType,'isClose':isClose,'targetType':targetType};
                $.post(ajUrl, post_data, function(data) {
                    animateHtmlStop();
                    if(data.status==1){
                        $('.close').click();
                        if(data.hasOwnProperty('nextComment')){
                            newCommentLoadDistribute(data.nextComment);
                        }
                        else{
                            wallPostPause();
                        }

                    }else if(data.login==0){location.reload();LOGDIN=0;}
                });
            }
        }
    }
}
function comment_delete(comment_id,targetType,trId){
    if(trId==undefined)trId='rp_'+comment_id;
    if(confirm('Are you sure to delete this Comment?')){
        if(confirm('Please check again')){
            $('#'+trId).hide();
            var isClose=getIsClose();
            post_data = {'removePostComment':1, 'comment_id':comment_id,'type':wallPostType,'targetType':targetType,'isClose':isClose};
            $.post(ajUrl, post_data, function(data) {
                if(data.status==1){
                }else if(data.login==0){location.reload();LOGDIN=0;}
                    else{
                        alert(data.m);
                    }
            });
        }
    }
}
function sentCommentHide(comment_id,targetType){
    if(targetType==undefined){targetType='c';}
    if(confirm('Are You sure to hide this comment?')){
        $.ajax({
            type:"POST",
            url:ajUrl,
            data:{sentCommentHide:comment_id,type:wallPostType,targetType:targetType},
            success:function(data){
                if(data.status==1){
                    pNotify('Comment Hide Successfully.','s');
                }
                else{
                    pNotify('Some problem there Please try again.');
                }
            }
        });
    }
}
function sentCommentDelete(comment_id,target_comment,targetType){
    if(targetType==undefined){targetType='c';}
    if(confirm('Are You sure to delete this comment?')){
        if(confirm('Please think again before delete ! ! !')){
            $.ajax({
                type:"POST",
                url:ajUrl,
                data:{sentCommentDelete:comment_id,type:wallPostType,targetType:targetType},
                success:function(data){
                    if(data.status==1){
                        pNotify('Comment Delete Successfully.','s');
                        $('.cm_'+comment_id).hide();
                        //$('#rp_'+target_comment).hide();
                    }
                    else{
                        pNotify('Some problem there Please try again.');
                    }
                }
            });
        }
    }
}
function setNames(names){
    $(names).each(function(i,j){
        $('.name_'+j.id).html(j.name);
    });
}
function setNamesForMmessages(names){
    $(names).each(function(i,j){
        $('.name_'+j.id).html(j.name);
        $('.pic_'+j.id).attr('src',j.picture);
    });
}
function show_edit_box(comment_id,targetType,trId){
    if(targetType==undefined){targetType='c';}
    if(trId==undefined)trId='rp_'+comment_id;
    var message = $('#'+trId+' .replyHtml').html();
    $('#editDiv .replyCancle').attr("onclick","cancel_edit_box('"+trId+"')");
    $('#editDiv .replySend').attr("onclick","comment_update('"+comment_id+"','"+targetType+"','"+trId+"')");
    $('#editDiv .replyDelete').attr("onclick","comment_delete('"+comment_id+"','"+targetType+"','"+trId+"')");
    var editDiv=$('#editDiv').html();
    $('#'+trId+' .replyEdit').html(editDiv);
    $('#'+trId+' .replyMessage').val(message);
    $('#'+trId+' .replyHtml').hide();
    $('#'+trId+' .replyEdit').show();
    $('#'+trId+' .edit_button').hide();
}
function t(t){console.log(t);}
function useTemplate(mtID,type){
    if(type==0){
        var text = $('#tmp_'+mtID).attr("data-text");
    }
    else if(type==1){
        var text = $('#res_'+mtID).attr("data-text");
    }
    else if(type==2){
        var text = $('#fav_'+mtID).attr("data-text");
    }
    var isDisabled = $('#msgData').prop('disabled');
    if(isDisabled==0){
        var existing = $('#msgData').val();
        if(existing==''){ 
            $('#msgData').val(text); 
        }
        else{
            existing=existing.trim();
            var txt = existing+' '+text;
            $('#msgData').val(txt);
        }
        $('#msgData').focus();
    }
}
function useImo(text){
    var existing = $('#msgData').val();
    if(existing==''){ 
        $('#msgData').val(text);
    }else{
        var txt = existing+' '+text;
        $('#msgData').val(txt);  
    }
    $('#msgData').focus();
}
function visitorPostDone(){
    var post_id=$('#post_id').val();
    if(post_id!=''){
        animateHtmlStop();
        animateHtmlVar = setInterval(function(){ animateHtmlRun('wallPostActionText','Action')},400);
        var isClose=getIsClose();
        post_data = {'newCommentByAgent':2, 'comment_id':post_id, 'isClose':isClose,'type':'c','is_done':'1'};
        $.post(ajUrl, post_data, function(data) {
            animateHtmlStop();
            if(data.status==1){
                $('.close').click();
                if(data.hasOwnProperty('nextComment')){
                    newVisitiorPostLoadDistribute(data.nextComment);
                }
                else{
                    wallPostPause();
                }

            }else if(data.login==0){location.reload();LOGDIN=0;}
        });
    }
}
function viewResponse(mtID){
    var searchString='index.php?ajax=1&viewResponse=1&mtID='+mtID;
    $.ajax({
        type:'POST',
        url:ajUrl,
        data:searchString,
        success:function(rData){             
            $("#titleView").val(rData.data.mtTitle); 
            $("#messageView").val(rData.data.mtText); 
            $("#updateBtn").html('<button class="btn btn-success btn-sm" onclick="editResponse('+mtID+');">Update</button>'); 
            $("#rvModal").modal("show"); 
        }
    }) 
}
function viewTemplate(mtID){
    var title = $('#tmp_'+mtID).attr('data-title');
    var text = $('#tmp_'+mtID).attr("data-text");
    $("#ttitleView").html(title); 
    $("#tmessageView").html(text);
    $("#tvModal").modal("show");
}
function wholeThreadView(nextPage){
    if(nextPage==undefined)nextPage=0;
    var post_id=$("#post_id").val();
    if(nextPage==0){
        $('#modelBody').html('<h1>Loading...</h1>');
        $('#modelTitle').html('Whole Threat');
        $('#model_show').click();
        var message =$('#provider_status_text').html();
        $('#whole_thread .whole_status_text').html(message);
        $('#modelSuccess').hide()
    }
    else{
        $('.wholwThreadLoadMore'+nextPage).html('Loading..');
    }
    $.ajax({
        type:'post',
        url:ajUrl,
        data:'loadWholeThreat='+post_id+ '&nextPage=' +nextPage,
        success:function(data){
            if(data.status==1){
                commentpostids=data.post_ids;
                if(nextPage==0){
                    $('#whole_thread .whole_main_comment').html('');
                }
                else{
                    $('.wholwThreadLoadMore'+nextPage).hide();
                }
                //$('#inPostActivity').html('');
                $(data.whole_thread).each(function(i,d){
                    if(d.c==0){
                        $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper');
                    }
                    else{
                        $('#visitor_comment .comment_inner_wrapper').attr('class','comment_inner_wrapper sub_comment'); 
                    }
                    commentDataSet(d);
                    //t($('#visitor_comment .sender_id').html());
                    var main_comment=$('#visitor_comment').html();
                    $('#whole_thread .whole_main_comment').append(main_comment);
                });
                setNames(data.names);
                if(data.hasOwnProperty('nextPage')){
                    $('#whole_thread .whole_main_comment').append('<a class="wholwThreadLoadMore'+data.nextPage+'" href="javascript:void();" onclick="wholeThreadView('+data.nextPage+')">Load More</a>');
                }
                var whole_thread=$('#whole_thread').html();
                $('#modelBody').html('<div id="wholw_comment_popup">'+whole_thread+'</div>');
            }
        }
    });
}
function wallPostStart(){
    $('#wallPostCloseCall').show();
    $('#wallPostCloseStart').hide();
    newWallPostCommentLoad();
}
function wallPostPause(){
    clearInterval(timerVar);
    clearScreen();
    $('#wallPostTitle').html('Pause please click to start for new message load.');
    $('#wallPostCloseCall').hide();
    $('#wallPostCloseStart').show();
}
function wrapupSearch(){
    var wText=$('#wrapupSearch').val();
    wText=wText.trim();
    if(wText!=''){
        wrapupExpland();
        $(".wrapupBody").each(function() {
            var wId=$(this).attr('id');
            if ($('#'+wId+' label').text().search(new RegExp(wText, "i")) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    else{
        $('.wrapupBody').show();
        wrapupCollaps();
    }
}
function wrapupExpland(){
    $('#accordion h4 a').removeClass('collapsed');
    $('#accordion .panel-collapse').removeClass('collapse');
}
function wrapupCollaps(){
    $('#accordion h4 a').addClass('collapsed');
    $('#accordion .panel-collapse').addClass('collapse');
}
function select2(){$(".select2").select2();}