<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-book"></span> <?php echo lang("ctn_505") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open_multipart(site_url("knowledge/edit_category_pro/" . $category->ID), array("class" => "form-horizontal")) ?>
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
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_625") ?></label>
                    <div class="col-md-8">
                        <select name="category_parent" class="form-control">
                        <option value="0"><?php echo lang("ctn_46") ?></option>
                        <?php foreach($categories->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>" <?php if($r->ID == $category->parent_category) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_626") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_786") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="ug" data-placeholder="<?php echo lang("ctn_594") ?>">
                            <?php foreach($user_groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_787") ?></span>
                    </div>
            </div>
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_514") ?>">
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