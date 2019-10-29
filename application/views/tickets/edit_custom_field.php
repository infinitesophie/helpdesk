<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_573") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("tickets/edit_custom_field_pro/" . $field->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_574") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $field->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_575") ?></label>
                    <div class="col-md-9 ui-front">
                        <select name="type" class="form-control">
                        <option value="0"><?php echo lang("ctn_576") ?></option>
                        <option value="1" <?php if($field->type == 1) echo "selected" ?>><?php echo lang("ctn_577") ?></option>
                        <option value="2" <?php if($field->type == 2) echo "selected" ?>><?php echo lang("ctn_578") ?></option>
                        <option value="3" <?php if($field->type == 3) echo "selected" ?>><?php echo lang("ctn_579") ?></option>
                        <option value="4" <?php if($field->type == 4) echo "selected" ?>><?php echo lang("ctn_580") ?></option>
                        <option value="5" <?php if($field->type == 5) echo "selected" ?>><?php echo lang("ctn_679") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_684") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="checkbox" name="hide_clientside" value="1" <?php if($field->hide_clientside) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_685") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_581") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="checkbox" name="required" value="1" <?php if($field->required) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_582") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_583") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="options" value="<?php echo $field->options ?>">
                        <span class="help-block"><?php echo lang("ctn_584") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_585") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="help_text" value="<?php echo $field->help_text ?>">
                        <span class="help-block"><?php echo lang("ctn_586") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_587") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="checkbox" name="all_cats" value="1" <?php if($field->all_cats) echo "checked" ?>>
                        <span class="help-block"><?php echo lang("ctn_588") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_462") ?></label>
                    <div class="col-md-9">
                        <select name="user_cats[]" multiple class="form-control chosen-select-no-single" id="categories" data-placeholder="Select categories you'd like this custom field to appear for.">
                            <?php foreach($user_cats->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_589") ?></span>
                    </div>
            </div>
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_598") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
$(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
});
</script>