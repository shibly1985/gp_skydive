<?php
    include_once("../class/class.db.php");
    include_once("../class/class.general.php");
    include_once("../class/class.social.php");
    include_once("../class/class.social2.php");
    include_once("../class/messages.php"); 
    $db     = new DB();
    $general= new General();
    $social = new social();
    include("../init.php");
    $a=$db->fetchQuery("select max(serviceStart) as m from ".$general->table(38));
    //echo $general->make_date($a[0]['m'],'time');echo'<br>'; 
    $lastSync=$a[0]['m'];
    if(empty($lastSync)){$lastSync=0;}
    if($lastSync==0){
        $a=$db->fetchQuery("select min(active) as m from ".$general->table(37));
        //$general->printArray($a);
        $lastSync=$a[0]['m'];
        $st=date('d-m-Y h:00:00 A',$lastSync);
        $st= '01-02-2017';//  date('d-m-Y h:00:00 A',$lastSync);
        $st=strtotime($st); 

    }
    else{
        $st=date('d-m-Y h:00:00 A',$lastSync);
        $st=strtotime($st); 
        $st=strtotime('+1 hour',$st); 
    }
    $next1Hour=strtotime('+1 hour',$st);
    $totalLoop=0;
    $activity=array();
    while($next1Hour<strtotime('-1 hour')){
        //get all click from this time range
        $a=$db->fetchQuery("select * from ".$general->table(37)." where active>=".$st." and active<=".$next1Hour);
        if(!empty($a)){
            echo $general->make_date($st,'time');echo' to '; 
            echo $general->make_date($next1Hour,'time');echo"\n
            <br>
            "; 
            foreach($a as $ac){
                if(isset($activity[$ac['uID']][$st])){
                    $o=$activity[$ac['uID']][$st];
                    $n2Min=strtotime('+5 minute',$o['lsatAct']);
                    if($ac['active'] < $n2Min){
                        $o['totalAct']+=$general->timestampDiffInArray($ac['active'],$o['lsatAct'],true);
                        $o['lsatAct']=$ac['active'];
                    }
                    else{
                        $o['totalAct']+=300;
                        $o['lsatAct']=$ac['active'];
                    }

                    if($o['lsatServ']==0){
                        $o['lsatServ']=$ac['service'];
                    }else{
                        $n2Min=strtotime('+5 minute',$o['lsatServ']);
                        if($ac['service']<$n2Min){
                            if($ac['service']!=0){
                                $o['totalServ']+=$general->timestampDiffInArray($ac['service'],$o['lsatServ'],true);
                                $o['lsatServ']=$ac['service'];
                            }
                        }
                        else{
                            $o['totalServ']+=300;
                            $o['lsatServ']=$ac['service'];
                        }
                    }

                    $o['hit']+=1;
                    $activity[$ac['uID']][$st]=$o;
                }
                else{
                    $activity[$ac['uID']][$st]=array(
                        'uID'           => $ac['uID'],
                        'serviceStart'  => $st,
                        'lsatServ'      => $ac['service'],
                        'totalServ'     => 0,
                        'lsatAct'       => $ac['active'],
                        'totalAct'      => 0,
                        'hit'           => 1
                    );
                }
            }
            //$general->printArray($a);   
        }
        $st=$next1Hour;
        $next1Hour=strtotime('+1 hour',$next1Hour);
        $totalLoop++;
        if($totalLoop>25)break;
    }
    if(!empty($activity)){
        foreach($activity as $d){
            foreach($d as $dd){
                $data=array(
                    'serviceStart'  => $dd['serviceStart'],
                    'uID'           => $dd['uID'],
                    'hit'           => $dd['hit'],
                    'service'       => $dd['totalServ'],
                    'active'        => $dd['totalAct']
                );
                if($dd['hit']==1){
                    $data['service']=120;
                    $data['active']=120;
                }
                $insert=$db->insert($general->table(38),$data);
            }
        }
    }
    $s2=new social2();
    $s2->cronLog('agentServiceClear');
    mysqli_close($GLOBALS['connection']);
?>
