<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra form-inline">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_announcement_pro/" . $announcement->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_389") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="title" value="<?php echo $announcement->title ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_390") ?></label>
                    <div class="col-md-9">
                        <textarea name="body" id="body-area"><?php echo $announcement->body ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_391") ?></label>
                        <div class="col-md-9">
                            <select name="status" class="form-control">
                            <option value="0"><?php echo lang("ctn_392") ?></option>
                            <option value="1" <?php if($announcement->status == 1) echo "selected" ?>><?php echo lang("ctn_393") ?></option>
                            </select>
                            <span class="help-block"><?php echo lang("ctn_394") ?></span>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_794") ?></label>
                        <div class="col-md-9">
                            <select name="color" class="form-control">
                            <option value="info" <?php if($announcement->color == "info") echo "selected" ?>>Information</option>
                            <option value="success" <?php if($announcement->color == "success") echo "selected" ?>>Success</option>
                            <option value="warning" <?php if($announcement->color == "warning") echo "selected" ?>>Warning</option>
                            <option value="danger" <?php if($announcement->color == "danger") echo "selected" ?>>Danger</option>
                            </select>
                            <span class="help-block"><?php echo lang("ctn_861") ?></span>
                        </div>
            </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_395") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
CKEDITOR.replace('body-area', { height: '150'});
</script>