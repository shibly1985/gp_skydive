<script>
    <?php echo 'var MESSAGE_AGENT_MAX_SERVICE='.intval($db->settingsValue('messageMaxService')).';';?>
</script>
<div class="x_panel wall_post">
    <div class="x_title">
    <script>
    $(document).ready(function(){
//        '<h2 onclick="messageLeftSideLoad()" style="cursor: pointer;" id="messageStartOrClose">Start</h2>';
//        var string='<span onclick="messageLeftSideLoad()" style="cursor: pointer;" id="messageStartOrClose">Start Service</span>';
    
    $('#msgRp').html('<button type="button" class="btn btn-success btn-primary" onclick="messageLeftSideLoad();">Start Service</button>');
    });
    </script>
        <h2 id="messageStartOrClose" style="cursor: pointer;" onclick="messageLeftSideLoad();"><span id="msgRp">..<img src="images/emo/smile.png"> ..</span></h2>
        <div class="clearfix pull-right">  <label> In Service <input type="checkbox" value="1" id="messageInService"></label></div>
    </div>

    <div class="x_content small_post full_life_time">
        <div class="post">
            <div class="comment_container" id="messageServeArea">
                <!--<div class="comment_inner_wrapper">
                <div class="comment_header">
                <img src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" class="post_profile_picture">
                </div>
                <div class="comment_body">
                <div class="comment_text">
                <span class="evac_user sender_id"><span>dfdfdf:</span></span>
                <span class="message">abcdef</span>
                <img class="status_image" src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" alt=""/>
                </div>
                <div class="comment_social clearfix">
                <div class="comment_actions">
                <div class="action with_bull comment_time_from_now">Time</div>
                </div>
                </div>
                </div>
                </div>
                <div class="comment_inner_wrapper">
                <div class="comment_header">
                <img src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" class="post_profile_picture">
                </div>
                <div class="comment_body">
                <div class="comment_text">
                <span class="evac_user sender_id"><span>dfdfdf:</span></span>
                <span class="message">abcdef</span>
                <img class="status_image" src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" alt=""/>
                </div>
                <div class="comment_social clearfix">
                <div class="comment_actions">
                <div class="action with_bull comment_time_from_now">Time</div>
                </div>
                </div>
                </div>
                </div>
                <div class="comment_inner_wrapper">
                <div class="comment_header">
                <img src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" class="post_profile_picture">
                </div>
                <div class="comment_body">
                <div class="comment_text">
                <span class="evac_user sender_id"><span>dfdfdf:</span></span>
                <span class="message">abcdef</span>
                <img class="status_image" src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" alt=""/>
                </div>
                <div class="comment_social clearfix">
                <div class="comment_actions">
                <div class="action with_bull comment_time_from_now">Time</div>
                </div>
                </div>
                </div>
                </div>
                <div class="comment_inner_wrapper">
                <div class="comment_header">
                <img src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" class="post_profile_picture">
                </div>
                <div class="comment_body">
                <div class="comment_text">
                <span class="evac_user sender_id"><span>dfdfdf:</span></span>
                <span class="message">abcdef</span>
                <img class="status_image" src="https://scontent.xx.fbcdn.net/v/t1.0-1/17352403_10155124715709371_4793317482964445432_n.jpg?oh=7708ee360b04ec126cef63349301d59b&oe=59A4A2F5" alt=""/>
                </div>
                <div class="comment_social clearfix">
                <div class="comment_actions">
                <div class="action with_bull comment_time_from_now">Time</div>
                </div>
                </div>
                </div>
                </div>-->
            </div>
            <div style="display: none;" id="messageLeftArea">
                <div class="comment_inner_wrapper">
                    <input type="hidden" class="sender_id" value="">
                    <div class="comment_header">
                        <img src="" class="post_profile_picture">
                    </div>
                    <div class="comment_body">
                        <div class="comment_text">
                            <span class="evac_user"></span>
                            <span class="message"></span>
                            <img class="status_image" src="" alt=""/>
                        </div>
                        <div class="comment_social clearfix">
                            <div class="comment_actions">
                                <div class="action with_bull comment_time_from_now">Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>