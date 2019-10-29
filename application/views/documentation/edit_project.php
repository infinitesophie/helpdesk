<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_830") ?></div>
    <div class="db-header-extra form-inline"> 

   
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open_multipart(site_url("documentation/edit_project_pro/" . $project->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_831") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $project->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="cat-description"><?php echo $project->description ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_832") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="footer" value="<?php echo $project->footer ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_347") ?></label>
                    <div class="col-md-8">
                      <p><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->icon ?>"><br /></p>
                        <input type="file" name="userfile" />
                        <span class="help-block"><?php echo lang("ctn_512") ?></span>
                    </div>
            </div>
      
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
     
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>