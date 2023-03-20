<?php
    $gAr = array();
    $jArray=array('status'=>0,'m'=>array(),'login'=>1);
    if(isset($logdIn)){
        if(isset($_GET['stch'])){
            $tbl    = intval($_GET['stch']);
            $op_id  = intval($_GET['ch_id']);
            $action = intval($_GET['action']);
            $name   = $_GET['name'];
            if($name!='set_roles'){
                $data   = array('isActive'=>$action);
                $where  = array($name =>$op_id);
                $db->update($general->table($tbl),$data,$where);
            }
        }
        elseif(isset($_GET['change_premessions'])){
            $ugID = intval($_GET['change_premessions']);
            $perID = intval($_GET['per']);
            $st = intval($_GET['st']);
            $ugID=intval($ugID);
            $b=$db->groupInfoByID($ugID);
            if(!empty($b)){$db->permissionSetForPermission($ugID,$perID,$st);}
        }
        elseif(isset($_GET['change_m_premessions'])){
            //$uID    = intval($_GET['change_m_premessions']);
            $cmId   = intval($_GET['mid']);
            $st     = intval($_GET['st']);
            $ugID     = $_GET['change_m_premessions'];

            $ugID=intval($ugID);
            $b=$db->groupInfoByID($ugID);
            if(!empty($b)){$db->permissionSetForModule($ugID,$cmId,$st);}

        }
        elseif(isset($_GET['get_premessions']))                 {include("settings/get_premessions.php");}
        elseif(isset($_POST['addResponse']))                     {include("request/addResponse.php");}
        elseif(isset($_POST['viewResponse']))                    {include("request/viewResponse.php");}
        elseif(isset($_GET['viewTemplate']))                    {include("request/viewTemplate.php");}
        elseif(isset($_POST['editResponse']))                    {include("request/editResponse.php");}
        elseif(isset($_GET['assignmentGroupMember']))           {include("request/ass_group_member.php");}
        elseif(isset($_POST['assignmentSubmit']))                {include("request/assignment_submit.php");}
        elseif(isset($_GET['feed_set']))                        {include("request/assign_to_group.php");}
        elseif(isset($_GET['assignmentFilter']))                {include("request/assignmentFilter.php");}

        elseif(isset($_POST['commentFlowType']))                {include("settings/wallPostCommentFlowChange.php");}
        elseif(isset($_POST['commentAssignTypeChange']))        {include("settings/commentAssignTypeChange.php");}
        elseif(isset($_POST['newLikeByAgent']))                 {include("request/newLikeByAgent.php");}
        elseif(isset($_POST['newMessageByAgent']))              {include("request/newMessageByAgent.php");}
        elseif(isset($_POST['newCommentByAgent']))              {include("request/newCommentByAgent.php");}
        elseif(isset($_POST['nextPicup']))                      {include("request/nextPicup.php");}
        elseif(isset($_POST['loadWholeThreat']))                {include("request/loadWholeThreat.php");}
        elseif(isset($_POST['makeFav']))                        {include("request/makeFav.php");}
        elseif(isset($_POST['removeFav']))                      {include("request/removeFav.php");}
        elseif(isset($_POST['removeRes']))                      {include("request/removeRes.php");}
        elseif(isset($_POST['deleteAssignmentThreads']))        {include("request/deleteAssignmentThreads.php");}
        elseif(isset($_POST['removePostComment']))              {include("request/removePostComment.php");}
        elseif(isset($_POST['topQueueUpdate']))                 {include("status/topQueueUpdate.php");}
        elseif(isset($_POST['banUser']))                        {include("request/banUser.php");}
        elseif(isset($_POST['banAssignmentThreads']))           {include("request/banAssignmentThreads.php");}
        elseif(isset($_POST['doneAssignmentThreads']))          {include("request/doneAssignmentThreads.php");}
        elseif(isset($_POST['dashboardGraph']))                 {include("status/dashboardGraph.php");}
        elseif(isset($_POST['commentEdit']))                    {include("request/comment_edit.php");}
        elseif(isset($_POST['commentTranser']))                 {include("request/commentTranser.php");}
        elseif(isset($_POST['exportToExcel']))                  {include("status/exportToExcel.php");}
        elseif(isset($_POST['reportJsonToExcel']))              {include("status/reportJsonToExcel.php");}
        elseif(isset($_POST['tableExportToExcel']))             {include("status/tableExportToExcel.php");}
        elseif(isset($_POST['totalReport']))                    {include("report/totalReport.php");}
        elseif(isset($_POST['sentCommentHide']))                {include("request/sentCommentHide.php");}
        elseif(isset($_POST['sentCommentDelete']))              {include("request/sentCommentDelete.php");}
        elseif(isset($_POST['commentPostTransferWithMember']))  {include("request/commentPostTransferWithMember.php");}
        elseif(isset($_POST['banNdeleteAssignmentThreads']))    {include("request/banNdeleteAssignmentThreads.php");}
        elseif(isset($_POST['outboxRetry']))                    {include("request/outboxRetry.php");}
        elseif(isset($_POST['hideNdeleteAssignmentThreads']))   {include("request/hideNdeleteAssignmentThreads.php");}
        elseif(isset($_POST['hideAssignmentThreads']))          {include("request/hideAssignmentThreads.php");}
        elseif(isset($_POST['newBulkReply']))                   {include("request/newBulkReply.php");}
        elseif(isset($_POST['postCommentHide']))                {include("request/postCommentHide.php");}
        elseif(isset($_POST['assignmentLoad']))                 {include("request/assignmentLoad.php");}
        elseif(isset($_POST['logoutForBreak']))                 {include("request/logoutForBreak.php");}
        elseif(isset($_POST['loadResponseReportByUserid']))     {include("report/loadResponseReportByUserid.php");}
        elseif(isset($_POST['loadTrafficAnalysisReport']))      {include("report/loadTrafficAnalysisReport.php");}
        elseif(isset($_POST['loadAvailabilityReport']))         {include("report/loadAvailabilityReport.php");}
        elseif(isset($_POST['loadAgentHourlyPer']))             {include("report/loadAgentHourlyPer.php");}
        elseif(isset($_POST['releasePostComment']))             {include("request/releasePostComment.php");}
        elseif(isset($_POST['newAttatchment']))                 {include("request/newAttatchment.php");}
        elseif(isset($_POST['attatchmentRemove']))              {include("request/attatchmentRemove.php");}
        elseif(isset($_POST['messageLeftSideLoad']))            {include("request/messageLeftSideLoad.php");}
        elseif(isset($_POST['messageCheckForNew']))             {include("request/messageCheckForNew.php");}
        elseif(isset($_POST['messageSendersNewMessage']))       {include("request/messageSendersNewMessage.php");}
        elseif(isset($_POST['messageReply']))                   {include("request/messageReply.php");}
        elseif(isset($_POST['loadOperatorsReplySendersMessages'])){include("report/loadOperatorsReplySendersMessages.php");}
        elseif(isset($_POST['messageLeftSideRefresh']))         {include("request/messageLeftSideRefresh.php");}
        elseif(isset($_POST['messageAssignmentSubmit']))        {include("request/messageAssignmentSubmit.php");}
        else{
            $jArray['m']='invalid rq';
            $general->jsonHeader($jArray);
        }
    }
    else{
        $jArray['login']=0;
        $general->jsonHeader($jArray);
    }
?>
