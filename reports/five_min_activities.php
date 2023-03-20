<script src="vendors/Chart.js/dist/Chart.min.js"></script>
<script src="vendors/echarts/dist/echarts.min.js"></script>
<?php
    $type='c';
    if(isset($_GET['type'])){
        if($_GET['type']=='m')$type='m';
        elseif($_GET['type']=='w')$type='w';
    }
?>
<div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=c">
      <h3><i class="fa fa-comments-o"></i> Comments</h3>
      </a>
    </div>
  </div>
  <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=w">
      <h3><i class="fa fa-comments-o"></i> Wall Post</h3>
      </a>
    </div>
  </div>
  <div class="animated flipInY col-lg-2 col-md-2 col-sm-3 col-xs-12">
    <div class="tile-stats senti_box">
      <a href="<?php echo $pUrl;?>&type=m">
          <h3><i class="fa fa-weixin" aria-hidden="true"></i> Message</h3>
      </a>
    </div>
  </div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $rModule['cmTitle'];?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
            $st_date =  strtotime("-5 minutes");
            $ed_date =  date('Y-m-d H:i:s');
        ?>
        <?php
            function toDate($x){return date('Y-m-d', $x);}
            $i=0;
            $dates=array();
            $comments=array();
            $replys=array();
            $avTimes=array();
            $wrapusData=array();
            $wrapup = $db->selectAll($general->table(11),'where isActive=1');
            $scentimentData=array();
            $scentiment=array(
                'Nutral'    =>SCENTIMENT_TYPE_NUTRAL,
                'Positive'  =>SCENTIMENT_TYPE_POSITIVE,
                'Negetive'  =>SCENTIMENT_TYPE_NEGETIVE
            );
            $range_of_dates=array(
                5 => 1,
                4 => 2,
                3 => 3,
                2 => 4,
                1 => 5
            );

            $general->arrayIndexChange($wrapup,'wuID');
            if($type=='c')include("reports/five_min_activities_c.php");
            elseif($type=='w')include("reports/five_min_activities_w.php");
            elseif($type=='m')include("reports/five_min_activities_m.php");
            
            $jArray= array();
            $jArray2= array();
            $jArray['xData'] = array(1, 2, 3,4,5);
            $jArray['serises']['Comments'] = $comments;
            $jArray['serises']['Replys'] = $replys;
            $jArray['serises']['avTimes'] = $avTimes;
            $jArray2['xData'] = array(1, 2, 3,4,5);
            $t=array();
            foreach($wrapusData as $w){
                foreach($w as $k=>$b){
                    $t[$k][]=$b;
                }
            }
            $wt = array();
            foreach($t as $k=>$b){
                foreach($b as $bk){
                  $jArray2['serises'][$wrapup[$k]['wuTitle']][]=$bk ;  
                }
            }
            $jArray3 = array();
            $jArray3['xData'] = array(1, 2, 3,4,5);
            $t=array();
            foreach($scentimentData as $w){
                foreach($w as $k=>$b){
                    $t[$k][]=$b;
                }
            }
            foreach($t as $k=>$b){
             if($k==1){
                  $jArray3['serises']['Nutral'] = $b;}
                 elseif($k==2){$jArray3['serises']['Positive'] = $b;}
                 elseif($k==3){$jArray3['serises']['Negetive'] = $b;}
                 
            }
        ?>
        <!--<script type="text/javascript">
        <?php
            if(!empty($dates)){
            ?>
            var ctx = document.getElementById("mybarChart");
            var mybarChart = new Chart(ctx, {
            type: 'bar',
            data: {
            labels: [<?php echo '"'.implode('","',$dates).'"';?>],
            datasets: [{
            label: 'Comment',
            backgroundColor: "#26B99A",
            data: [<?php echo implode(',',$comments);?>]
            },
            {
            label: 'Reply',
            backgroundColor: "#03586A",
            data: [<?php echo implode(',',$replys);?>]
            }]
            },

            options: {
            scales: {
            yAxes: [{
            ticks: {
            beginAtZero: true
            }
            }]
            }
            }
            });
            <?php
            }
        ?>
        </script>-->
        <script>
            var theme = {
                color: [
                    '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
                    '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
                ],

                title: {
                    itemGap: 8,
                    textStyle: {
                        fontWeight: 'normal',
                        color: '#408829'
                    }
                },

                dataRange: {
                    color: ['#1f610a', '#97b58d']
                },

                toolbox: {
                    color: ['#408829', '#408829', '#408829', '#408829']
                },

                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.5)',
                    axisPointer: {
                        type: 'line',
                        lineStyle: {
                            color: '#408829',
                            type: 'dashed'
                        },
                        crossStyle: {
                            color: '#408829'
                        },
                        shadowStyle: {
                            color: 'rgba(200,200,200,0.3)'
                        }
                    }
                },

                dataZoom: {
                    dataBackgroundColor: '#eee',
                    fillerColor: 'rgba(64,136,41,0.2)',
                    handleColor: '#408829'
                },
                grid: {
                    borderWidth: 0
                },

                categoryAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },

                valueAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },
                timeline: {
                    lineStyle: {
                        color: '#408829'
                    },
                    controlStyle: {
                        normal: {color: '#408829'},
                        emphasis: {color: '#408829'}
                    }
                },

                k: {
                    itemStyle: {
                        normal: {
                            color: '#68a54a',
                            color0: '#a9cba2',
                            lineStyle: {
                                width: 1,
                                color: '#408829',
                                color0: '#86b379'
                            }
                        }
                    }
                },
                map: {
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                color: '#ddd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        },
                        emphasis: {
                            areaStyle: {
                                color: '#99d2dd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        }
                    }
                },
                force: {
                    itemStyle: {
                        normal: {
                            linkStyle: {
                                strokeColor: '#408829'
                            }
                        }
                    }
                },
                chord: {
                    padding: 4,
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        },
                        emphasis: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        }
                    }
                },
                gauge: {
                    startAngle: 225,
                    endAngle: -45,
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                            width: 8
                        }
                    },
                    axisTick: {
                        splitNumber: 10,
                        length: 12,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: 'auto'
                        }
                    },
                    splitLine: {
                        length: 18,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    pointer: {
                        length: '90%',
                        color: 'auto'
                    },
                    title: {
                        textStyle: {
                            color: '#333'
                        }
                    },
                    detail: {
                        textStyle: {
                            color: 'auto'
                        }
                    }
                },
                textStyle: {
                    fontFamily: 'Arial, Verdana, sans-serif'
                }
            };

            var responseState = echarts.init(document.getElementById('responseState'), theme);
            responseState.setOption({
                title: {
                    x: 'center',
                    y: 'top',
                    padding: [0, 0, 20, 0],
                    text: 'Daily Comment , Reply and Avg. Reply',
                    textStyle: {
                        fontSize: 15,
                        fontWeight: 'normal'
                    }
                },
                tooltip: {
                    trigger: 'axis'
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataView: {
                            show: true,
                            readOnly: false,
                            title: "Text View",
                            lang: [
                                "Text View",
                                "Back",
                                "Refresh",
                            ],
                        },
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Save'
                        }
                    }
                },
                calculable: true,
                legend: {
                    data: ['Comments', 'Reply', 'Average Reply'],
                    y: 'bottom'
                },
                xAxis: [{
                    type: 'category',
                    data: [1,2,3,4,5]
                }],
                yAxis: [{
                    type: 'value',
                    name: 'Comments',
                    axisLabel: {
                        formatter: '{value} Per Hour'
                    }
                    }, {
                        type: 'value',
                        name: 'Reply',
                        axisLabel: {
                            formatter: '{value} Min'
                        }
                }],
                series: [
                    {
                        name: 'Comments',
                        type: 'bar',
                        data: [<?php echo implode(',',$comments);?>]
                    }, 
                    {
                        name: 'Reply',
                        type: 'bar',
                        data: [<?php echo implode(',',$replys);?>]
                    },
                    {
                        name: 'Average Reply',
                        type: 'line',
                        yAxisIndex: 1,
                        data: [<?php echo implode(',',$avTimes);?>]
                }]
            });

            var sce = document.getElementById("responseDailyStateScen");
            var mybarChart = new Chart(sce, {
                type: 'bar',
                data: {
                    labels: [<?php echo '"'.implode('","',array_keys($scentimentData)).'"';?>],
                    datasets: [
                        <?php
                            $t=array();
                            foreach($scentimentData as $w){
                                foreach($w as $k=>$b){
                                    $t[$k][]=$b;
                                }
                            }
                            foreach($t as $k=>$b){
                            ?>
                            {
                                label: '<?php if($k==1){echo 'Nutral';}elseif($k==2){echo 'Positive';}elseif($k==3){echo 'Negetive';}?>',
                                backgroundColor: "<?php echo $colorCodes[rand(0,(count($colorCodes)-1))];?>",
                                data: [<?php echo implode(',',$b);?>]
                            },
                            <?php
                            }
                        ?>
                    ]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            var ctx = document.getElementById("responseDailyState");
            var mybarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php echo '"'.implode('","',array_keys($wrapusData)).'"';?>],
                    datasets: [
                        <?php
                            $t=array();
                            foreach($wrapusData as $w){
                                foreach($w as $k=>$b){
                                    $t[$k][]=$b;
                                }
                            }
                            foreach($t as $k=>$b){
                            ?>
                            {
                                label: '<?php echo $wrapup[$k]['wuTitle'];?>',
                                backgroundColor: "<?php echo $colorCodes[rand(0,(count($colorCodes)-1))];?>",
                                data: [<?php echo implode(',',$b);?>]
                            },
                            <?php
                            }
                        ?>
                    ]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        </script> 
    </div>
</div>
<script type="">
        <?php echo "var eIndex = ".json_encode($jArray).";";?>
        <?php echo "var eIndex2 = ".json_encode($jArray2).";";?>
        <?php echo "var eIndex3 = ".json_encode($jArray3).";";?>

    $("#exportResponseFive").click(function(){
        reportExportToExcel(eIndex); 
    });
    $("#exportWrapupFive").click(function(){
        reportExportToExcel(eIndex2); 
    });
    $("#exportSentimentFive").click(function(){
        reportExportToExcel(eIndex3); 
    });

    
</script>

