<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

<div class="panel panel-default">
<div class="panel-body">

<div class="clearfix">
<span class="plan-title"><?php echo lang("ctn_224") ?></span>

<div class="pull-right">
<a href="<?php echo site_url("user_settings/change_password") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_225") ?></a>
</div>
</div>

<p><?php echo lang("ctn_237") ?></p>

<hr>

  	<?php echo form_open(site_url("user_settings/change_password_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_238") ?></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="current_password">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_239") ?></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="new_pass1">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_240") ?></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="new_pass2">
			    </div>
			</div>
			 <input type="submit" name="s" value="<?php echo lang("ctn_241") ?>" class="btn btn-primary form-control" />
    <?php echo form_close() ?>
    </div>
    </div>

</div>
</div>
</div>