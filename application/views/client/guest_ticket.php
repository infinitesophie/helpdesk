<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/tickets") ?>"><?php echo lang("ctn_461") ?></a></li>
  <li class="active"><?php echo lang("ctn_452") ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-body">

<h4><?php echo lang("ctn_446") ?></h4>

<p><?php echo lang("ctn_447") ?> <strong><?php echo lang("ctn_448") ?></strong> <?php echo lang("ctn_449") ?> <strong><?php echo lang("ctn_450") ?></strong>.</p>

<hr> 

<?php echo form_open(site_url("client/guest_login_pro")) ?>
<div class="input-group">
		<span class="input-group-addon white-form-bg"><span class="glyphicon glyphicon-user"></span></span>
		<input type="text" name="email" class="form-control" placeholder="<?php echo lang("ctn_451") ?>">
</div><br />

<div class="input-group">
		<span class="input-group-addon white-form-bg"><span class="glyphicon glyphicon-lock"></span></span>
		<input type="password" name="pass" class="form-control" placeholder="<?php echo lang("ctn_180") ?>">
</div>
<br />
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_452") ?>"></p>
<?php echo form_close() ?>

</div>
</div>

</div>
</div>
</div>