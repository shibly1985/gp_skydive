<?php
    if(PROJECT=='gp'||PROJECT=='skydive'||PROJECT=='gpc'||PROJECT=='tmm'||PROJECT=='seemo'||LOCAL_SERVER_NAME=='192.168.0.3'){
        define('PER_QUEUE_CLEAN',98);
        define('PER_ADD_AGENT',97);
        define('PER_BAN_USER'                       ,99);
        define('PER_COMMENT_DELETE'                 ,100);
        define('PER_WALL_DELETE'                    ,101);
        define('PER_SERVICE_TIME'                   ,102);
        define('PER_USER_LICENCE'                   ,103);
        define('PER_USER_DELETE'                    ,104);
        define('PER_BULK_REPLY_COMMENT'             ,105);
        define('PER_BULK_REPLY_WALL'                ,106);

        define('PROFILE_CHANGE_DISPLAY_N'           ,97);
        define('PROFILE_CHANGE_FULL_N'              ,94);
        define('PROFILE_CHANGE_PASSWORD'            ,93);
        define('PROFILE_CHANGE_COMMENT_FLOW'        ,98);
        define('PROFILE_CHANGE_WALLPOST_FLOW'       ,99);
        define('MODULE_DAILY_TEAM_PERFORMANCE'      ,172);
        define('MODULE_TRAFFIC_ANALYSIS'            ,173);
        define('MODULE_X_SEC_REPORT'                ,174);
        define('MODULE_DELETED'                     ,168);
        define('MODULE_DONE_REPOT'                  ,175);
        define('MODULE_BREAK_REPORT'                ,176);
        define('MODULE_TOTAL_BREAK_REPORT'          ,178);
        define('MODULE_TRANSFER_REPORT'             ,176);
        define('MODULE_HIDE_REPORT'                 ,177);
        define('MODULE_AGENT_STATUS_REPORT'         ,180);
        define('MODULE_AVAILABILITY_REPORT'         ,181);
        define('MODULE_FCR_REPORT'                  ,182);
        define('MODULE_QUEUE_STATUS'                ,183);
        define('MODULE_TRANSFERD_QUEUE'             ,184);
        define('MODULE_QA'                          ,185);
        define('MODULE_POST_WISE_SENTIMENT'         ,200);
    }
    else if(PROJECT=='pizzainn'){
        define('PER_QUEUE_CLEAN'                    ,0);
        define('PER_ADD_AGENT'                      ,97);
        define('PER_BAN_USER'                       ,98);
        define('PER_COMMENT_DELETE'                 ,99);
        define('PER_WALL_DELETE'                    ,100);
        define('PER_SERVICE_TIME'                   ,101);
        define('PER_USER_LICENCE'                   ,102);
        define('PER_USER_DELETE'                    ,103);
        define('PER_BULK_REPLY_COMMENT'             ,104);
        define('PER_BULK_REPLY_WALL'                ,105);
        define('PROFILE_CHANGE_DISPLAY_N'           ,97);
        define('PROFILE_CHANGE_FULL_N'              ,94);
        define('PROFILE_CHANGE_PASSWORD'            ,93);
        define('PROFILE_CHANGE_COMMENT_FLOW'        ,98);
        define('PROFILE_CHANGE_WALLPOST_FLOW'       ,99);
        define('MODULE_DAILY_TEAM_PERFORMANCE'      ,172);
        define('MODULE_TRAFFIC_ANALYSIS'            ,173);
        define('MODULE_X_SEC_REPORT'                ,169);
        define('MODULE_DELETED'                     ,168);
        define('MODULE_DONE_REPOT'                  ,175);
        define('MODULE_BREAK_REPORT'                ,176);
        define('MODULE_TOTAL_BREAK_REPORT'          ,177);
        define('MODULE_TRANSFER_REPORT'             ,176);
        define('MODULE_HIDE_REPORT'                 ,168);
        define('MODULE_AGENT_STATUS_REPORT'         ,0);
        define('MODULE_AVAILABILITY_REPORT'         ,181);
        define('MODULE_FCR_REPORT'                  ,0);
        define('MODULE_QUEUE_STATUS'                ,0);
        define('MODULE_TRANSFERD_QUEUE'             ,0);
        define('MODULE_QA'                          ,0);
        define('MODULE_POST_WISE_SENTIMENT'         ,200);
    }
    else if(PROJECT=='sk'){
        define('PER_QUEUE_CLEAN',98);
        define('PER_ADD_AGENT',97);
        define('PER_BAN_USER'                       ,99);
        define('PER_COMMENT_DELETE'                 ,100);
        define('PER_WALL_DELETE'                    ,101);
        define('PER_USER_LICENCE'                   ,102);
        define('PER_SERVICE_TIME'                   ,103);
        define('PER_USER_DELETE'                    ,104);
        define('PER_BULK_REPLY_COMMENT'             ,105);
        define('PER_BULK_REPLY_WALL'                ,106);

        define('PROFILE_CHANGE_DISPLAY_N'           ,97);
        define('PROFILE_CHANGE_FULL_N'              ,94);
        define('PROFILE_CHANGE_PASSWORD'            ,93);
        define('PROFILE_CHANGE_COMMENT_FLOW'        ,98);
        define('PROFILE_CHANGE_WALLPOST_FLOW'       ,99);
        define('MODULE_DAILY_TEAM_PERFORMANCE'      ,172);
        define('MODULE_TRAFFIC_ANALYSIS'            ,173);
        define('MODULE_X_SEC_REPORT'                ,174);
        define('MODULE_DELETED'                     ,168);
        define('MODULE_DONE_REPOT'                  ,175);
        define('MODULE_BREAK_REPORT'                ,176);
        define('MODULE_TOTAL_BREAK_REPORT'          ,178);
        define('MODULE_TRANSFER_REPORT'             ,176);
        define('MODULE_HIDE_REPORT'                 ,177);
        define('MODULE_AGENT_STATUS_REPORT'         ,180);
        define('MODULE_AVAILABILITY_REPORT'         ,181);
        define('MODULE_FCR_REPORT'                  ,182);
        define('MODULE_QUEUE_STATUS'                ,0);
        define('MODULE_TRANSFERD_QUEUE'             ,184);
        define('MODULE_QA'                          ,0);
        define('MODULE_POST_WISE_SENTIMENT'         ,200);
    }
    else{      
        define('PER_QUEUE_CLEAN'                    ,98);
        define('PER_BAN_USER'                       ,99);
        define('PER_ADD_AGENT'                      ,97);
        define('PROFILE_CHANGE_PASSWORD'            ,93);
        define('PROFILE_CHANGE_FULL_N'              ,94);
        define('PROFILE_CHANGE_DISPLAY_N'           ,97);
        define('PROFILE_CHANGE_COMMENT_FLOW'        ,98);
        define('PROFILE_CHANGE_WALLPOST_FLOW'       ,99);
        define('PER_COMMENT_DELETE'                 ,0);
        define('PER_WALL_DELETE'                    ,0);
        define('PER_SERVICE_TIME'                   ,101);
        define('PER_USER_LICENCE'                   ,103);
        define('PER_USER_DELETE'                    ,104);
        define('PER_BULK_REPLY_COMMENT'             ,105);
        define('PER_BULK_REPLY_WALL'                ,106);

        define('MODULE_DAILY_TEAM_PERFORMANCE',169);
        define('MODULE_TRAFFIC_ANALYSIS',171);
        define('MODULE_X_SEC_REPORT',172);
        define('MODULE_DELETED',174);
        define('MODULE_DONE_REPOT',175);
        define('MODULE_BREAK_REPORT',176);
        define('MODULE_TOTAL_BREAK_REPORT',177);
        define('MODULE_TRANSFER_REPORT',178);
        define('MODULE_HIDE_REPORT',179);
        define('MODULE_AGENT_STATUS_REPORT'         ,180);
        define('MODULE_AVAILABILITY_REPORT'         ,172);
        define('MODULE_FCR_REPORT'                  ,174);
        define('MODULE_QUEUE_STATUS'                ,175);
        define('MODULE_TRANSFERD_QUEUE'             ,176);
        define('MODULE_QA'                          ,0);
        define('MODULE_POST_WISE_SENTIMENT'         ,200);
    }
?>