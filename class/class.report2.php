<?php
    class socialReport2{
        public $db;
        public $general;
        public $social;
        function __construct(){
            $this->db       = new DB();
            $this->general  = new General();
            $this->social   = new social();
        }
        function dashboardSummery($from,$to){
            $return=array();
            $com = $db->selectAll($general->table(4),' WHERE created_time between '.$from. ' AND '.$to,'count(created_time) as a');
            $return['adminPost']=$com[0]['a'];
        }
    }
?>
