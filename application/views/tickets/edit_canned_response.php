<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_567") ?></div>
    <div class="db-header-extra form-inline"> 

    <a href="<?php echo site_url("tickets/add_canned_response") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_568") ?></a>
</div>
</div>

<blockquote>
[USER] - <?php echo lang("ctn_569") ?><br />
[SITE_NAME] - <?php echo lang("ctn_570") ?><br />
[ADMIN_NAME] - <?php echo lang("ctn_571") ?><br />
[FIRST_NAME] - <?php echo lang("ctn_644") ?><br />
[LAST_NAME] - <?php echo lang("ctn_645") ?><br />
[STAFF_FIRST_NAME] - <?php echo lang("ctn_646") ?><br />
[STAFF_LAST_NAME] - <?php echo lang("ctn_647") ?>
</blockquote>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("tickets/edit_canned_response_pro/" . $canned->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_389") ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="title" value="<?php echo $canned->title ?>">
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_533") ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="body" id="canned-area"><?php echo $canned->body ?></textarea>
        </div>
</div>
<input type="submit" name="s" class="btn btn-primary btn-xs form-control" value="<?php echo lang("ctn_596") ?>">
<?php echo form_close() ?>
</div>
</div>


</div>
<script type="text/javascript">
$(document).ready(function() {
    
CKEDITOR.replace('canned-area', { height: '100'});
});
</script>