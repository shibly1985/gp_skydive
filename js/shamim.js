function reportExportToExcel(eIndex){

    $.ajax({
        type:"POST",
        url:ajUrl,
        data:{exportToExcel:eIndex},
        success:function(data){
            if(data.status==1){
                window.location=data.link;
                //                  $('#exportFileDownload').attr('href',data.link);
                //                  $('#exportFileDownload').click();
            }  
        }

    })

}function reportJsonToExcel(reportData){
    var rqData=JSON.stringify(reportData);
    $('#exportBtn').html('Wait');
    $.ajax({
        type:"POST",
        url:ajUrl,
        data:{reportJsonToExcel:rqData},
        success:function(data){
            if(data.status==1){
                $('#exportBtn').html('Export');
                window.location=data.link;
            }  
        }

    })

}
function reportTableExportToExcel(table){
    $.ajax({
        type:"POST",
        url:ajUrl,
        data:{tableExportToExcel:table,name:"asdfs"},
        success:function(data){

        }

    })
}
function filterAssign(type){
    var quantity = $("#selectQuantity").val();
    var keyword = $("#keyword").val();
    var flowType = $('input:radio[name="flow"]:checked').val();
    if(flowType==undefined){flowType='auto';}
    var radioDate =  $('input:radio[name="radioDate"]:checked').val();
    var sendString='?ajax=1&assignmentFilter=1&quantity='+quantity+'&flowType='+flowType+'&type='+type+'&keyword='+keyword; 
    if(radioDate==0){
        var date_range = $("#date_range").val();
        date_range = date_range.split('__');
        var dateFrom = date_range[0];
        var dateTo   = date_range[1];
        sendString+='&dateFrom='+dateFrom+'&dateTo='+dateTo;
    }

    /*  var dateFrom = $("#form_date").val();
    var dateTo   = $("#to_date").val();
    var sendString='?ajax=1&assignmentFilter=1&quantity='+quantity+'&dataType='+dataType+'&dateFrom='+dateFrom+'&dateTo='+dateTo;   */
    jx.load(sendString,function(data){
        rData = jQuery.parseJSON(data);
        if(rData.status==1){
            $('#ass_post').html('');
            var i=0;
            $(rData.comments).each(function(i,d){
                if(d.photo!='' || d.photo!='null'){
                    var photo = '<div class="comment_footer"><img class="assing_comment_img" src="'+d.photo+'"></div>';
                }
                else{
                    var photo = '';
                }
                var assContent = '<div class="assignment_checkbox" id="'+d.comment_id+'"><input id="check_'+i+'" type="checkbox" name="check_threads[]" value="'+d.comment_id+'"><label for="check_'+i+++'"><div class="comment_inner_wrapper"><div class="comment_header"><img src="https://graph.facebook.com/'+d.sender_id+'/picture?type=square" class="post_profile_picture"></div><div class="comment_body"><div class="comment_text"><span class="evac_user name_'+d.sender_id+'"></span><br><span>'+momentTime(d.created_time)+'</span><p><span>'+d.message+'</span></p> </div></div>'+photo+'</div> </label> </div>';
                $('#ass_post').append(assContent);
            });
            setNames(rData.names);
        }
        $('.close').click();

    });
}

function logoutForBreak(){
    var reason =  parse_int($("#selectReason").val()); 
    var textAppTime =  encodeURIComponent($("#textAppTime").val()); 
    if(textAppTime>4 && textAppTime<61){var c=0;}else{var c=1;}
    if(reason==0){
        $("#textReason").focus();
        $("#textReason").css("border-color","red");

    }
    else if(c==1){
        $("#textAppTime").focus();
        $("#textAppTime").css("border-color","red");  
    }
    else{
        var postData={logoutForBreak:1,reason:reason,textAppTime:textAppTime}
        $.ajax({
            type:"POST",
            url:ajUrl,
            data:postData,
            success:function(data){
                if(data.status==1){
                    LOGDIN=0;
                    window.location='?logout=1';
                }  
            }

        })  
    }
}
