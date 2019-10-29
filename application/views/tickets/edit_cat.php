<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_590") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open_multipart(site_url("tickets/edit_category_pro/" . $category->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_506") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $category->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_510") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="cat-description"><?php echo $category->description ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_511") ?></label>
                    <div class="col-md-8">
                    	<p><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $category->image ?>"><br /></p>
                        <input type="file" name="userfile" />
                        <span class="help-block"><?php echo lang("ctn_512") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_591") ?></label>
                    <div class="col-md-8">
                        <select name="cat_parent" class="form-control">
                        <option value="0"><?php echo lang("ctn_46") ?></option>
                        <?php foreach($categories->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($r->ID == $category->cat_parent) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_592") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="no_tickets" value="1" <?php if($category->no_tickets) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_593") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_15") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="ug" data-placeholder="<?php echo lang("ctn_594") ?>">
                            <?php foreach($user_groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_595") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_856") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="auto_assign" id="username-search" placeholder="<?php echo lang("ctn_559") ?>" <?php if(!empty($assigned_user)) : ?>value="<?php echo $assigned_user ?>"<?php endif; ?>>
                        <span class="help-block"><?php echo lang("ctn_857") ?></span>
                    </div>
            </div>
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_597") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
      $(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>