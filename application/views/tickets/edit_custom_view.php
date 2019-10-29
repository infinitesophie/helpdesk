<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_627") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("tickets/edit_custom_view_pro/" . $view->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $view->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_391") ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                        <option value="-1"><?php echo lang("ctn_600") ?></option>
                        <?php foreach($statuses->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $view->status) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_462") ?></label>
                    <div class="col-md-8">
                        <select name="categoryid" class="form-control">
                        <option value="0"><?php echo lang("ctn_600") ?></option>
                        <?php foreach($categories->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>" <?php if($view->categoryid == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_635") ?></label>
                    <div class="col-md-8">
                        <select name="order_by" class="form-control">
                        <option value="0"><?php echo lang("ctn_636") ?></option>
                        <option value="1" <?php if($view->order_by == 1) echo "selected" ?>><?php echo lang("ctn_425") ?></option>
                        <option value="2" <?php if($view->order_by == 2) echo "selected" ?>><?php echo lang("ctn_428") ?></option>
                        <option value="3" <?php if($view->order_by == 3) echo "selected" ?>><?php echo lang("ctn_391") ?></option>
                        <option value="4" <?php if($view->order_by == 4) echo "selected" ?>><?php echo lang("ctn_462") ?></option>
                        <option value="6" <?php if($view->order_by == 6) echo "selected" ?>><?php echo lang("ctn_603") ?></option>
                        <option value="7" <?php if($view->order_by == 7) echo "selected" ?>><?php echo lang("ctn_463") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_637") ?></label>
                    <div class="col-md-8">
                        <select name="order_by_type" class="form-control">
                        <option value="asc" <?php if($view->order_by_type == "asc") echo "selected" ?>><?php echo lang("ctn_638") ?></option>
                        <option value="desc" <?php if($view->order_by_type == "desc") echo "selected" ?>><?php echo lang("ctn_639") ?></option>
                        </select>
                    </div>
            </div>

    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_640") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>