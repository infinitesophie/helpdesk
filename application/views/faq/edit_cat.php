<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-info-sign"></span> <?php echo lang("ctn_782") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("FAQ/edit_category_pro/" . $category->ID), array("class" => "form-horizontal")) ?>
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
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_514") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
     
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>