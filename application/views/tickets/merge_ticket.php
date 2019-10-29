<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_718") ?></div>
    <div class="db-header-extra form-inline"> 

</div>
</div>

<div class="row">
<div class="col-md-6">
<?php echo form_open(site_url("tickets/merge_ticket_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
<h4><?php echo lang("ctn_719") ?></h4>
<p>Ticket: <a href=""><?php echo $ticket->title ?></a></p>
<p><strong><?php echo lang("ctn_52") ?></strong></p>
<table class="table">
<tr><td><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_725") ?>"></span> <?php echo lang("ctn_720") ?></td><td><input type="checkbox" name="merge_replies" value="1" checked></td></tr>
<tr><td><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_726") ?>"></span> <?php echo lang("ctn_721") ?></td><td><input type="checkbox" name="merge_user" value="1"></td></tr>
<tr><td><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_727") ?>"></span> <?php echo lang("ctn_722") ?></td><td><input type="checkbox" name="merge_history" value="1"></td></tr>
<tr><td><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_728") ?>"></span> <?php echo lang("ctn_723") ?></td><td><input type="checkbox" name="merge_ticket" value="1"></td></tr>
<tr><td><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_729") ?>"></span> <?php echo lang("ctn_724") ?></td><td><input type="checkbox" name="merge_files" value="1"></td></tr>
</table>



</div>
<div class="col-md-6">
<h4><?php echo lang("ctn_730") ?></h4>
<p><?php echo lang("ctn_731") ?></p>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_732") ?> ...</label>
        <div class="col-md-8 ui-front">
            <select name="primary_ticketid" class="form-control">
            <option value="0">None</option>
            <?php foreach($tickets->result() as $r) : ?>
              <option value="<?php echo $r->ID ?>">#<?php echo $r->ID ?> - <?php echo $r->title ?></option>
            <?php endforeach; ?>
            </select>
            <span class="help-block"><?php echo lang("ctn_733") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_734") ?> ...</label>
        <div class="col-md-8 ui-front">
            <input type="text" name="ticket_title" id="username-search" class="form-control" placeholder="<?php echo lang("ctn_735") ?> ... ">
            <input type="hidden" name='ticket_id' id="ticket-id">
        </div>
</div>
</div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_736") ?>">
<?php echo form_close() ?>


</div>
<script type="text/javascript">
$(document).ready(function() {

  /* Get list of usernames */
  $('#username-search').autocomplete({
    delay : 300,
    minLength: 1,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "tickets/get_tickets_id",
             data: {
                query : request.term
             },
             dataType: 'JSON',
             select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $("#ticket-id").val(ui.item.value);

                $(this).val("#" + ui.item.value+ " - " +ui.item.label);
            },
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });
});
  </script>
