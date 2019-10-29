<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_747") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/section_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_748") ?></label>
    <div class="col-sm-9">
    	<input type="checkbox" id="name-in" name="enable_knowledge" value="1" <?php if($this->settings->info->enable_knowledge) echo "checked" ?>>
    	<span class="help-block"><?php echo lang("ctn_749") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_750") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="enable_faq" value="1" <?php if($this->settings->info->enable_faq) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_751") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_808") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="enable_documentation" value="1" <?php if($this->settings->info->enable_documentation) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_809") ?></span>
    </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>