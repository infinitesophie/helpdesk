<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">


<h3 class="home-label"><?php echo lang("ctn_146") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_146") ?></li>
</ol>

<p><?php echo lang("ctn_147") ?></p>

<hr>

	<div class="panel panel-default">
  	<div class="panel-body">
  	<?php echo form_open(site_url("client/change_language_pro"), array("class" => "form-horizontal")) ?>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_148") ?></label>
			    <div class="col-sm-10">
			      <select name="language" class="form-control">
			      <?php foreach($languages as $k=>$v) : ?>
			      	<option value="<?php echo $k ?>" <?php if($k == $user_lang) echo "selected" ?>><?php echo $v['display_name'] ?></option>
			      <?php endforeach; ?>
			      </select>
			    </div>
			</div>
			 <input type="submit" name="s" value="<?php echo lang("ctn_146") ?>" class="btn btn-primary form-control" />
    <?php echo form_close() ?>
    </div>
    </div>

</div>
</div>
    </div>