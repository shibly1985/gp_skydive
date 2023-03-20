<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0; max-height: 100%; max-width: 100%;">
            <?php
                $logoFile='skydive_logo_header.png';
                if(file_exists('images/'.PROJECT.'_logo_header.png')){
                    $logoFile=PROJECT.'_logo_header.png';
                }
            ?>
            <a href="<?php echo URL ?>?<?php echo MODULE_URLN;?>" class="site_title">
                <img src="images/<?php echo $logoFile;?>" alt="Logo" style="max-height: 100%; max-width: 100%;"/>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">

                <?php
                    $md5Email=md5($userData['uEmail']);
                    if($userData['uImage']!=''){
                        $gravatar_link = URL.'/images/operators/'.$userData['uImage'];
                    }
                    else{
                        $gravatar_link = URL.'/images/man_icon2.png';   
                    }

                ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('.<?php echo $md5Email;?>').attr('src','<?php echo $gravatar_link;?>');
                    });
                </script>
                <img src="" alt="#" class="img-circle profile_img <?php echo $md5Email;?>">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $userData['uFullName'] ?></h2>
                <h2>
                    <?php
                        echo $db->getData($general->table(22),' where ugID='.$userData['ugID'],'ugTitle');
                    ?>
                </h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">

                <ul class="nav side-menu">
                    <li><a href="<?php echo URL;?>"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
                    <?php
                        if(UGID==SUPERADMIN_USER){
                        ?>
                        <li>
                            <a><i class="fa fa-clone"></i> Super Admin <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="?<?php echo MODULE_URLN;?>=module">Module</a></li>
                                <li><a href="?<?php echo MODULE_URLN;?>=permission">Permission</a></li>
                            </ul>
                        </li>
                        <?php
                        }
                    ?>
                    <li><a><i class="fa fa-pencil-square-o"></i> Feeds<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php
                                if($db->modulePermission(142)){?><li><a href="?<?php echo MODULE_URLN;?>=comments"><!--<i class="fa fa-comments-o" aria-hidden="true"></i>--> Comments</a></li><?php } 
                                if($db->modulePermission(155)){?><li><a href="?<?php echo MODULE_URLN;?>=wall_post"><!--<i class="fa fa-comments-o" aria-hidden="true"></i>--> Wall Post</a></li><?php }
                                if(OPERATION_MESSAGE_ALLWO==true){
                                    if($db->modulePermission(152)){?><li><a href="?<?php echo MODULE_URLN;?>=messages"><!--<i class="fa fa-weixin" aria-hidden="true"></i>--> Messages</a></li><?php }
                                }
                                if($db->modulePermission(MODULE_QUEUE_STATUS)){?>
                                <li><a>Queue Status<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="?<?php echo MODULE_URLN;?>=queue_status&type=c">Comments</a></li>
                                        <li><a href="?<?php echo MODULE_URLN;?>=queue_status&type=w">Wall</a></li>
                                        <?php
                                            if(OPERATION_MESSAGE_ALLWO==true){
                                            ?>
                                            <li><a href="?<?php echo MODULE_URLN;?>=queue_status&type=m">Message</a></li>
                                            <?php
                                            }
                                        ?>
                                    </ul>
                                </li>

                                <?php }
                                if($db->modulePermission(MODULE_TRANSFERD_QUEUE)){?><li><a href="?<?php echo MODULE_URLN;?>=transferd_queue">Transferd Queue</a></li><?php }
                            ?>
                        </ul>
                    </li>
                    <?php
                        if($db->modulePermission(159)){?>
                        <!--<li><a href="?<?php echo MODULE_URLN;?>=sent"><i class="fa fa-comments-o" aria-hidden="true"></i>Sent</a></li>-->
                        <li><a><i class="fa fa-comments-o"></i> Sent<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="?<?php echo MODULE_URLN;?>=sent&type=c">Comments</a></li>
                                <li><a href="?<?php echo MODULE_URLN;?>=sent&type=w">Wall</a></li>
                                <?php
                                    if(OPERATION_MESSAGE_ALLWO==true){
                                    ?>
                                    <li><a href="?<?php echo MODULE_URLN;?>=sent&type=m">Message</a></li>
                                    <?php
                                    }
                                ?>
                            </ul>
                        </li>

                        <?php } 
                        if($db->modulePermission(145)){?>
                        <li><a><i class="fa fa-pencil-square-o"></i> Assignment<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php
                                    if(OPERATION_MESSAGE_ALLWO==true){
                                    ?><li><a href="?<?php echo MODULE_URLN;?>=assignment&type=m">Message</a></li><?php
                                    }
                                ?>
                                <li><a href="?<?php echo MODULE_URLN;?>=assignment&type=c">Comment</a></li>
                                <li><a href="?<?php echo MODULE_URLN;?>=assignment&type=w">Wall Post</a></li>

                                <?php if($db->modulePermission(146)){
                                    ?>
                                    <?php if($db->permission(PER_BULK_REPLY_COMMENT)){
                                        ?>
                                        <li><a href="?<?php echo MODULE_URLN;?>=bulk-reply&type=c">Bulk Reply Comment</a></li>
                                        <?php
                                        }
                                        if($db->permission(PER_BULK_REPLY_WALL)){
                                        ?>
                                        <li><a href="?<?php echo MODULE_URLN;?>=bulk-reply&type=w">Bulk Reply Wall</a></li>
                                        <?php
                                        }
                                    }
                                ?>
                            </ul>
                        </li>
                        <?php }
                        if($db->modulePermission(150)){?><li><a href="?<?php echo MODULE_URLN;?>=new_requests"><i class="fa fa-comments-o" aria-hidden="true"></i> New Requests</a></li><?php } 
                        if($db->modulePermission(139)){
                        ?>
                        <li><a><i class="fa fa-wrench"></i> Settings<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="?<?php echo MODULE_URLN;?>=settings">Settings</a></li>
                                <?php if($db->modulePermission(166)){ ?> <li><a href="?<?php echo MODULE_URLN;?>=wrapup_category">Wrapup Category</a></li><?php
                                    }
                                    if($db->modulePermission(140)){?><li><a href="?<?php echo MODULE_URLN;?>=wrapup">Wrapup</a></li>
                                    <?php 
                                        if($db->modulePermission(143)){?><li><a href="?<?php echo MODULE_URLN;?>=message_templates">Message Templates</a></li><?php }
                                        if($db->modulePermission(149)){?><li><a href="?<?php echo MODULE_URLN;?>=privilege">Privilege</a></li><?php } ?>
                                </ul>
                            </li>
                            <?php
                            }
                        }
                        if($db->modulePermission(141) || $db->modulePermission(144)){
                        ?>
                        <li><a><i class="fa fa-users"></i> Agents<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php if($db->modulePermission(141)){ ?><li><a href="?<?php echo MODULE_URLN;?>=agents">All Agents</a></li><?php }
                                    if($db->modulePermission(144)) {?><li><a href="?<?php echo MODULE_URLN;?>=agents_group">Agents Group</a></li><?php }?>
                            </ul>
                        </li>

                        <?php
                        }
                        if($db->modulePermission(167)){
                        ?>
                        <li><a><i class="fa fa-envelope"></i> Outbox<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">

                                <li><a href="?<?php echo MODULE_URLN;?>=outbox&type=c">Comments</a></li>
                                <li><a href="?<?php echo MODULE_URLN;?>=outbox&type=w">Wall Posts</a></li>
                            </ul>
                        </li>

                        <?php
                        }
                        if( $db->modulePermission(147) || $db->modulePermission(148) || $db->modulePermission(51) || $db->modulePermission(153) || $db->modulePermission(156) || $db->modulePermission(157) || $db->modulePermission(158)){
                        ?>
                        <li><a><i class="fa fa-bar-chart"></i> Reports<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="javascript:void();">Attendance<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <?php
                                            if($db->modulePermission(151)) {?><li><a href="?<?php echo MODULE_URLN;?>=rep_attendance">Agent Login Report</a></li><?php }
                                            if($db->modulePermission(MODULE_AGENT_STATUS_REPORT)) {?><li><a href="?<?php echo MODULE_URLN;?>=agent_status_report">Agent Status Report</a></li><?php }
                                            if($db->modulePermission(MODULE_TOTAL_BREAK_REPORT)) {?><li><a href="?<?php echo MODULE_URLN;?>=total_break_report">Total Break Report</a></li><?php }
                                            if($db->modulePermission(MODULE_AVAILABILITY_REPORT)) {?><li><a href="?<?php echo MODULE_URLN;?>=availability_report">Agent Performance Report</a></li><?php }
                                            if($db->modulePermission(MODULE_BREAK_REPORT)) {?><li><a href="?<?php echo MODULE_URLN;?>=break_report">Break Report</a></li><?php }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                                    if($db->modulePermission(MODULE_FCR_REPORT)){
                                    ?>
                                    <li><a href="javascript:void();">FCR<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="?<?php echo MODULE_URLN;?>=fcr_report&type=c">Comment</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=fcr_report&type=w">Wall</a></li>
                                        </ul>
                                    </li>
                                    <?php 

                                    }
                                    if($db->modulePermission(170)){ ?><li><a href="?<?php echo MODULE_URLN;?>=agent_hourly_per">Agent Hourly Performance</a></li><?php }
                                    if($db->modulePermission(147)){ ?>
                                    <li><a href="javascript:void();">Response Stats<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="?<?php echo MODULE_URLN;?>=report1&type=w">Wrap-up Stats</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=report1&type=s">Sentiment Stats</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=report1&type=r">Response Stats
                                                </a></li>
                                        </ul>
                                    </li>
                                    <?php 
                                    }
                                    if($db->modulePermission(148)){ ?>
                                    <li><a href="javascript:void();">Wrap-up Stats<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php
                                                if(OPERATION_MESSAGE_ALLWO==true){
                                                ?><li><a href="?<?php echo MODULE_URLN;?>=wrap_up_stats&type=m">Message</a></li><?php
                                                }
                                            ?>
                                            <li><a href="?<?php echo MODULE_URLN;?>=wrap_up_stats&type=c">Comment</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=wrap_up_stats&type=w">Wall</a></li>
                                        </ul>
                                    </li>
                                    <?php 
                                    }
                                    if($db->modulePermission(153)) {?><li><a href="?<?php echo MODULE_URLN;?>=operators_response">Operators Response</a></li><?php }
                                    if($db->modulePermission(156)) {?>
                                    <li><a href="javascript:void();">Operators Reply<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="?<?php echo MODULE_URLN;?>=operators_reply&type=c">Comment</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=operators_reply&type=w">Wall</a></li>
                                            <?php
                                                if(OPERATION_MESSAGE_ALLWO==true){
                                                ?>
                                                <li><a href="?<?php echo MODULE_URLN;?>=operators_reply&type=m">Message
                                                    </a></li>
                                                <?php
                                                }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php }
                                    if($db->modulePermission(158)) {?><li><a href="?<?php echo MODULE_URLN;?>=five_min_activities">Five minutes activities </a></li><?php }
                                    if($db->modulePermission(161)) {?><li><a href="?<?php echo MODULE_URLN;?>=sentiment_report">Sentiment report</a></li><?php }
                                    if($db->modulePermission(162)) {?><li><a href="?<?php echo MODULE_URLN;?>=customer_contact_frequency">Customer contact frequency</a></li><?php }
                                    if($db->modulePermission(163)) {?>
                                    <li><a href="javascript:void();">Total Activity<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="?<?php echo MODULE_URLN;?>=total_activity&type=c">Comment</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=total_activity&type=wp">Wall Post</a></li>
                                            <li><a href="?<?php echo MODULE_URLN;?>=total_activity&type=wc">Wall Comment</a></li>
                                        </ul>
                                    </li>

                                    <?php }
                                    if($db->modulePermission(MODULE_DAILY_TEAM_PERFORMANCE)){?><li><a href="?<?php echo MODULE_URLN;?>=daily_team_performance">Daily Team Performance</a></li><?php }
                                    if($db->modulePermission(MODULE_TRAFFIC_ANALYSIS)){?><li><a href="?<?php echo MODULE_URLN;?>=traffic_analysis">Traffic Analysis</a></li><?php }
                                    if($db->modulePermission(MODULE_X_SEC_REPORT)){?><li><a href="?<?php echo MODULE_URLN;?>=x_sec_report">X Second Report</a></li><?php }
                                    if($db->modulePermission(MODULE_DELETED)){?><li><a href="?<?php echo MODULE_URLN;?>=deleted">Delete Report</a></li><?php }
                                    if($db->modulePermission(MODULE_DONE_REPOT)){?><li><a href="?<?php echo MODULE_URLN;?>=done_report">Done Report</a></li><?php }
                                    if($db->modulePermission(MODULE_HIDE_REPORT)){?><li><a href="?<?php echo MODULE_URLN;?>=hide_report">Hide Report</a></li><?php }
                                    if($db->modulePermission(MODULE_TRANSFER_REPORT)){?><li><a href="?<?php echo MODULE_URLN;?>=transfer_report">Transfer Report</a></li><?php }
                                    if($db->modulePermission(MODULE_QA)){?><li><a href="?<?php echo MODULE_URLN;?>=qa">QA Report</a></li><?php }
                                    if($db->modulePermission(MODULE_POST_WISE_SENTIMENT)){?><li><a href="?<?php echo MODULE_URLN;?>=post_wise_sentiment">Post Wise Sentiment</a></li><?php }


                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                    ?>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a href="javascript:void(0)" data-toggle="modal" data-placement="top" title="Logout" data-target="#logoutModal">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>