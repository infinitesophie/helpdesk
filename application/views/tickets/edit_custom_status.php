<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_791") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("tickets/edit_custom_status_pro/" . $status->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $status->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="color" value="<?php echo $status->color ?>">
                        <span class="help-block"><?php echo lang("ctn_796") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_797") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="text_color" value="<?php echo $status->text_color ?>">
                        <span class="help-block"><?php echo lang("ctn_796") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_795") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="close" value="1" <?php if($status->close) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_798") ?></span>
                    </div>
            </div>

    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>