<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	<h3 class="home-label"><?php echo lang("ctn_520") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_520") ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-body">

<div class="clearfix">
<span class="plan-title"><?php echo lang("ctn_273") ?></span>

<div class="pull-right">
<a href="<?php echo site_url("client/payment_log") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_623") ?></a> <a href="<?php echo site_url("client/funds") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_245") ?></a>
</div>
</div>

<hr>

<div class="row">

<?php foreach($plans->result() as $r) : ?>
<div class="col-md-4">

<div class="planarea" style="background: #<?php echo $r->hexcolor ?>; color: #<?php echo $r->fontcolor ?>;">
<h4 class="plan-title"><?php echo $r->name ?></h4>
<center>
<div class="plan-icon">
<span class="<?php echo $r->icon ?>" style="font-size: 28pt; color: #<?php echo $r->fontcolor ?>; "></span>
</div>
</center>
<p class="align-center"><?php echo $r->description ?></p>
<hr>
<?php if($r->days >0) : ?>
<p class="plan-days"><?php echo $r->days ?> <?php echo lang("ctn_277") ?></p>
<?php else : ?>
<p class="plan-days"><?php echo lang("ctn_283") ?></p>
<?php endif; ?>
<p class="plan-cost"><?php echo $this->settings->info->payment_symbol ?><?php echo number_format($r->cost,2) ?></p>
<hr>
<a href="<?php echo site_url("client/buy_plan/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-default form-control"><?php echo lang("ctn_284") ?></a>
</div>

</div>
<?php endforeach; ?>
</div>

<hr>

<p><?php echo lang("ctn_248") ?>: <?php echo number_format($this->user->info->points,2) ?></p>

<?php if($this->user->info->premium_time > 0) : ?>
	<?php $time = $this->common->convert_time($this->user->info->premium_time) ?>
<p><?php echo lang("ctn_276") ?> <?php echo $this->common->get_time_string($time) ?> <?php echo lang("ctn_281") ?></p>
<?php elseif($this->user->info->premium_time == -1) : ?>
<p><?php echo lang("ctn_282") ?></p>
<?php endif; ?>

</div>
</div>

</div>
</div>
</div>