<?php $prioritys = array(0 => "<span class='label label-info'>".lang("ctn_429")."</span>", 1 => "<span class='label label-primary'>".lang("ctn_430")."</span>", 2=> "<span class='label label-warning'>".lang("ctn_431")."</span>", 3 => "<span class='label label-danger'>".lang("ctn_432")."</span>"); ?>
<div class="white-area-content">

<div class="row">

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #62acec; border-left: 5px solid #5798d1;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-send giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->total_tickets) ?></span><br /><?php echo lang("ctn_476") ?>
	</div>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #5cb85c; border-left: 5px solid #4f9f4f;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-wrench giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->total_assigned_tickets) ?></span><br /><?php echo lang("ctn_477") ?>
	</div>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #f0ad4e; border-left: 5px solid #d89b45;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->tickets_today) ?></span><br /><?php echo lang("ctn_478") ?>
	</div>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #d9534f; border-left: 5px solid #b94643;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-user giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($online_count) ?></span><br /><?php echo lang("ctn_139") ?>
	</div>
</div>
</div>

</div>

<hr>


<div class="row">
<div class="col-md-9">
<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_479") ?></h4>
<canvas id="myChart" class="graph-height"></canvas>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_480") ?></h4>
<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped small-text">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_389") ?></td><td><?php echo lang("ctn_428") ?></td><td><?php echo lang("ctn_391") ?></td><td><?php echo lang("ctn_481") ?></td><td><?php echo lang("ctn_463") ?></td></tr>
</thead>
<tbody>
<?php foreach($assigned_tickets->result() as $r) : ?>
    <?php
    if(!isset($r->status_name)) {
                $status = lang("ctn_46");
            } else {
                $status = $r->status_name; 
            }

    if(isset($r->client_username)) {
        $user = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp));
    } else {
        $user = '<div class="user-box-avatar"><img src="'.base_url().$this->settings->info->upload_path_relative.'/guest.png'.'" data-toggle="tooltip" data-placement="bottom" title="'.$r->guest_email.'"></div>';
    }

            ?>
<tr><td><a href="<?php echo site_url("tickets/view/" . $r->ID) ?>"><?php echo $r->title ?></a></td><td><?php echo $prioritys[$r->priority] ?></td><td><?php echo $status ?></td><td><?php echo $user ?></td><td><?php echo date($this->settings->info->date_format,$r->last_reply_timestamp) ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<a href="<?php echo site_url("tickets/assigned") ?>" class="btn btn-primary btn-sm form-control"><?php echo lang("ctn_482") ?></a>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo lang("ctn_326") ?> <b><?php echo date($this->settings->info->date_format, $this->user->info->online_timestamp); ?></b>
</div>
</div>

</div>
<div class="col-md-3">

<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_461") ?></h4>
<table class="table">
<?php foreach($your_tickets->result() as $r) : ?>
    <?php
    if(isset($r->client_username)) {
        $user = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp));
    } else {
        $user = '<div class="user-box-avatar"><img src="'.base_url().$this->settings->info->upload_path_relative.'/guest.png'.'" data-toggle="tooltip" data-placement="bottom" title="'.$r->guest_email.'"></div>';
    }
    ?>
<tr><td width="30"><?php echo $user ?>
</td><td><p class="task-blob-title"><a href="<?php echo site_url("tickets/view/" . $r->ID) ?>"><?php echo $r->title ?></a></p>
<p><?php echo $prioritys[$r->priority] ?> <label class="label label-primary label-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo date($this->settings->info->date_format, $r->last_reply_timestamp) ?>"><span class="glyphicon glyphicon-time"></span></label></p>
</td></tr>
<?php endforeach; ?>
</table>

<div class="align-center">
<a href="<?php echo site_url("tickets/your") ?>" class="align-center btn btn-primary btn-sm"><?php echo lang("ctn_461") ?></a>
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body small-text">
<h4 class="home-label"><?php echo lang("ctn_483") ?></h4>
<?php echo $this->settings->info->notes ?>
</div>
</div>



</div>
</div>

</div>
<script type="text/javascript">
var ctx = $("#myChart");
    var data = {
        labels: ["<?php echo lang("ctn_484") ?>", "<?php echo lang("ctn_485") ?>", "<?php echo lang("ctn_486") ?>", "<?php echo lang("ctn_487") ?>", "<?php echo lang("ctn_488") ?>", "<?php echo lang("ctn_489") ?>", "<?php echo lang("ctn_490") ?>", "<?php echo lang("ctn_491") ?>", "<?php echo lang("ctn_492") ?>", "<?php echo lang("ctn_493") ?>", "<?php echo lang("ctn_494") ?>", "<?php echo lang("ctn_495") ?>"],
        datasets: [
            {
                label: "<?php echo lang("ctn_496") ?>",
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
                data: [<?php foreach($close_tickets as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
            {
                label: "<?php echo lang("ctn_497") ?>",
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
                data: [<?php foreach($open_tickets as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
        ]
    };
    Chart.defaults.global.defaultFontFamily = "'Open Sans'";
    Chart.defaults.global.defaultFontSize = 8;
    var options = { title : { text: "" }};
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            defaultFontSize: 8,
            responsive: true,
            hover : {
                mode : "single"
            },
            legend : {
                display : false,
                labels : {
                    boxWidth: 15,
                    padding: 10,
                    fontSize: 11,
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
                        fontSize: 11
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