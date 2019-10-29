<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-list-alt"></span> <?php echo lang("ctn_542") ?></div>
    <div class="db-header-extra form-inline"> 
    <?php echo form_open(site_url("reports")) ?>
    <div class="form-group">
      <input type="text" name="start_date" class="input-sm form-control datepicker" value="<?php echo date("m/d/Y", time() - (3600*24*7)) ?>">
    </div>
    <div class="form-group">
      <input type="text" name="end_date" class="input-sm form-control datepicker" value="<?php echo date("m/d/Y") ?>">
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_543") ?>" >
    </div>
    <?php echo form_close() ?>

</div>
</div>

<div class="btn-group" role="group" aria-label="...">
<a href="<?php echo site_url("reports/index/0") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_544") ?></a><a href="<?php echo site_url("reports/index/1") ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_545") ?></a><a href="<?php echo site_url("reports/index/2") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_546") ?></a>
</div>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php if($type == 0) : ?><?php echo lang("ctn_547") ?><?php elseif($type == 1) : ?><?php echo lang("ctn_548") ?><?php else : ?><?php echo lang("ctn_549") ?><?php endif; ?></h4>
<canvas id="myChart" class="graph-height"></canvas>
</div>
</div>



</div>
<script type="text/javascript">
var ctx = $("#myChart");
    var data = {
        labels: [<?php foreach($dates as $i) : ?>
        "<?php echo $i['date'] ?>",
        <?php endforeach; ?>],
        datasets: [
            <?php if($results2) : ?>
            {
                label: "<?php echo lang("ctn_545") ?>",
                fill: true,
                lineTension: 0.2,
                backgroundColor: "rgba(32,113,210,0.4)",
                borderColor: "rgba(32,113,210,0.9)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(75,192,192,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [<?php foreach($results2 as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
            <?php endif; ?>
            {
                label: "<?php echo lang("ctn_544") ?>",
                fill: true,
                lineTension: 0.2,
                backgroundColor: "rgba(29,210,142,0.5)",
                borderColor: "rgba(29,210,142,0.9)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(75,192,192,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [<?php foreach($results as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
        ]
    };
    Chart.defaults.global.defaultFontFamily = "'Open Sans'";
    Chart.defaults.global.defaultFontSize = 14;
    var options = { title : { text: "" }};
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            defaultFontSize: 22,
            responsive: true,
            hover : {
                mode : "single"
            },
            legend : {
                display : false,
                labels : {
                    boxWidth: 15,
                    padding: 10,
                    fontSize: 14,
                    usePointStyle : false
                }
            },
            animation : {
                duration: 2000,
                easing: "easeOutElastic"
            },
            scales : {
                yAxes : [{
                    display: true,
                    title : {
                        fontSize: 14
                    },
                    gridLines : {
                        display : true
                    }
                }],
                xAxes : [{
                    display : true,
                    scaleLabel : {
                        display : false
                    },
                    ticks : {
                        display : true
                    },
                    gridLines : {
                        display : true,
                        drawTicks : false,
                        tickMarkLength: 5,
                        zeroLineWidth: 0,
                    }
                }]
            }
        }
    });
</script>