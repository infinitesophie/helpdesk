<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_791") ?></div>
    <div class="db-header-extra form-inline">




<input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_793") ?>" data-toggle="modal" data-target="#addModal" />

</div>
</div>

<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped small-text">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_794") ?></td><td><?php echo lang("ctn_795") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
  <?php foreach($statuses->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td style="background: #<?php echo $r->color ?>; color: #<?php echo $r->text_color ?>">#<?php echo $r->color ?></td><td><?php if($r->close) : ?><?php echo lang("ctn_53") ?><?php else : ?><?php echo lang("ctn_54") ?><?php endif; ?></td><td><?php echo '<a href="'.site_url("tickets/edit_custom_status/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_custom_status/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>' ?></td></tr>
  <?php endforeach; ?>
</tbody>
</table>
</div>


</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_793") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("tickets/add_custom_status"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_794") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="color" value="">
                        <span class="help-block"><?php echo lang("ctn_796") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_797") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="text_color" value="">
                        <span class="help-block"><?php echo lang("ctn_796") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_795") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="close" value="1">
                        <span class="help-block"><?php echo lang("ctn_798") ?></span>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_793") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>