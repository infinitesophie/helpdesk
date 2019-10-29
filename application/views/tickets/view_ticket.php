<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<?php $prioritys = array(0 => "<span class='label label-info'>".lang("ctn_429")."</span>", 1 => "<span class='label label-primary'>".lang("ctn_430")."</span>", 2=> "<span class='label label-warning'>".lang("ctn_431")."</span>", 3 => "<span class='label label-danger'>".lang("ctn_432")."</span>"); ?>

<div class="row">
<div class="col-md-8">

<div class="panel panel-default">
<div class="panel-body" style="position: relative;">
<div class="ticket-priority">
<?php echo $prioritys[$ticket->priority] ?>
</div>
<div class="row" style="">
  <div class="col-md-12" style="word-wrap:break-word; overflow: visible !important;">
    <div class="pull-left">
 <?php echo $this->common->get_user_display(array("username" => $ticket->client_username, "avatar" => $ticket->client_avatar, "online_timestamp" => $ticket->client_online_timestamp)) ?>
    </div>
<h3 class="media-title"><?php echo $ticket->title ?></h3>
<p><?php echo nl2br($ticket->body) ?></p>
<?php if(!empty($ticket->notes) && $this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
<hr>
<p><?php echo lang("ctn_483") ?></p>
<p><i><?php echo $ticket->notes ?></i></p>
<?php endif; ?>
<?php if($this->settings->info->enable_ticket_uploads && $files->num_rows() > 0) : ?>
    <hr>
    <h4><?php echo lang("ctn_437") ?></h4>
    <div class="form-group">
            <div class="col-md-12">
                <table class="table table-bordered">
                <?php foreach($files->result() as $r) : ?>
                    <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>" target="_blank"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                          <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("tickets/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php endif; ?></td></tr>
                <?php endforeach; ?>
                </table>
            </div>
    </div>
<?php endif; ?>
<div class="small-text">
<?php if($ticket_fields) : ?>
    <?php foreach($ticket_fields->result() as $r) : ?>
      <?php if($r->type == 5) : ?>
        <p><strong><?php echo $r->name ?></strong><br /><?php echo $r->value ?></p>
           <?php if(isset($r->itemname) && !empty($r->itemname)) : ?>
                <p><?php echo lang("ctn_680") ?>: <strong><?php echo $r->itemname ?></strong></p>
            <?php endif; ?>
            <?php if(isset($r->support) && !empty($r->support)) : ?>
                <p><?php echo lang("ctn_681") ?>: <strong><?php echo date($this->settings->info->date_format, $r->support) ?></strong></p>
            <?php endif; ?>
            <?php if(isset($r->error) && !empty($r->error)) : ?>
                <p><?php echo lang("ctn_682") ?>: <?php echo $r->error ?></p>
            <?php endif; ?>
      <?php else :?>
        <p><strong><?php echo $r->name ?></strong><br /><?php echo $r->value ?></p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>

  </div>
  </div> <!-- End media -->

</div>
</div>

<?php foreach($replies->result() as $r) : ?>
  <div class="white-area-content content-separator">
  <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
    <div class="ticket-reply-options">
    <a href="<?php echo site_url("tickets/edit_ticket_reply/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a>
    <a href="<?php echo site_url("tickets/delete_ticket_reply/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>
    </div>
  <?php endif; ?>

<div class="media">
  <div class="media-left">
    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
  </div>
  <div class="media-body">
    <h4 class="media-title"><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></h4>
    <p><?php echo $r->body ?></p>
    <p class="small-text"><?php echo lang("ctn_628") ?>: <?php echo date($this->settings->info->date_format, $r->timestamp); ?></p>
    <?php if($r->files && $this->settings->info->enable_ticket_uploads) : ?>
<?php $files = $this->tickets_model->get_reply_files($r->ID); ?>
<hr>
                <div class="form-group clearfix">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_437") ?></label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                            <?php foreach($files->result() as $r) : ?>
                                <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                                  <?php if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("tickets/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php endif; ?>
                                </td></tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                </div>
<?php endif; ?>
  </div>
</div>

</div>
<?php endforeach; ?>

<div class="white-area-content content-separator">
<h4 class="media-title"><?php echo lang("ctn_473") ?></h4>
<?php echo form_open_multipart(site_url("tickets/ticket_reply/" . $ticket->ID), array("class" => "form-horizontal")) ?>
<p><textarea name="body" id="ticket-body"></textarea></p>
<p><?php echo lang("ctn_683") ?>: <input type="checkbox" name="assign" value="1" checked></p>
<?php if($this->settings->info->enable_ticket_uploads) : ?>
                <hr>
                <h4><?php echo lang("ctn_436") ?></h4>
                <input type="hidden" name="file_count" value="1" id="file_count">
                <div id="file_block">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_438") ?></label>
                        <div class="col-md-8">
                            <input type="file" name="user_file_1" class="form-control">
                        </div>
                </div>
                </div>
                <input type="button" name="s" value="<?php echo lang("ctn_439") ?>" class="btn btn-info btn-xs" onclick="add_file()">
                <hr>
            <?php endif; ?>
<?php if($canned->num_rows() > 0) : ?>
<p><?php echo lang("ctn_533") ?></p>
<p><select id="cannedr" class="form-control"><option value="0"><?php echo lang("ctn_434") ?></option>
<?php foreach($canned->result() as $r) : ?>
<option value="<?php echo html_escape($r->body) ?>"><?php echo $r->title ?></option>
<?php endforeach; ?>
</select></p>
<?php endif; ?>
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_474") ?>"></p>
<?php echo form_close() ?>
</div>

</div>
<div class="col-md-4">

<div class="panel panel-default">
<div class="panel-body">
<h4 class="media-title"><?php echo lang("ctn_629") ?></h4>
<table class="table">
<tr><td class="ticket-label-info"><?php echo lang("ctn_611") ?></td><td> <?php echo $ticket->ID ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_468") ?></td><td> <?php if(isset($ticket->client_username)) : ?><a href="<?php echo site_url("profile/" . $ticket->client_username) ?>"><?php echo $ticket->client_username ?></a> <?php else : ?> <?php echo lang("ctn_469") ?>: <?php echo $ticket->guest_email ?><?php endif; ?></td></tr>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_25") ?></td><td><?php echo $ticket->client_username ?>   <?php if($this->common->has_permissions(array("admin", "admin_members"), $this->user)) : ?>
    <a href="<?php echo site_url("admin/edit_member/" . $ticket->userid) ?>" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a>
  <?php endif; ?></td></tr>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_24") ?></td><td><?php echo $ticket->client_email ?></td></tr>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_81") ?></td><td><?php echo $ticket->first_name ?> <?php echo $ticket->last_name ?></td></tr>
  <?php if($user_fields) : ?>
  <?php foreach($user_fields->result() as $r) : ?>
    <tr><td class="ticket-label-info"><?php echo $r->name ?></td><td><?php echo $r->value ?></td></tr>
  <?php endforeach; ?>
  <?php endif; ?>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_470") ?></td><td> <?php echo date($this->settings->info->date_format, $ticket->timestamp); ?></td></tr>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_471") ?></td><td> <?php echo date($this->settings->info->date_format, $ticket->last_reply_timestamp) ?> <?php if(isset($ticket->lr_username)) : ?><?php echo lang("ctn_602") ?> <a href="<?php echo site_url("profile/" . $ticket->lr_username) ?>"><?php echo $ticket->lr_username ?></a><?php endif; ?> </td></tr>
  <tr><td class="ticket-label-info"><?php echo lang("ctn_603") ?></td><td> <?php if(isset($ticket->username)) : ?><a href="<?php echo site_url("profile/" . $ticket->username) ?>"><?php echo $ticket->username ?></a><?php else : ?><?php echo lang("ctn_46") ?><?php endif; ?></td></tr>
    <tr><td class="ticket-label-info"><?php echo lang("ctn_462") ?></td><td> <a href=""><?php echo $ticket->cat_name ?></a></td></tr>
    <tr><td class="ticket-label-info"><?php echo lang("ctn_792") ?></td><td><?php 
    if($ticket->close_timestamp ==0) {
      $ticket->close_timestamp = time();
    }

    $time = $ticket->close_timestamp - $ticket->timestamp;

    // Get time
    $t = $this->common->get_time_string($this->common->convert_simple_time_fixed($time));
    echo $t;
    ?></td></tr>
    <?php if($this->settings->info->ticket_rating) : ?>
    <tr><td class="ticket-label-info"><?php echo lang("ctn_554") ?></td><td>
      <?php for($i=1;$i<=5;$i++) : ?>
      <?php if($i > $ticket->rating) : ?>
        <span class="glyphicon glyphicon-star-empty click" id="ticket<?php echo $i ?>"></span>
      <?php else : ?>
        <span class="glyphicon glyphicon-star click" id="ticket<?php echo $i ?>"></span>
      <?php endif; ?>
    <?php endfor; ?>
    </td></tr>
  <?php endif; ?>
    </table>
    <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
    <p>
<div class="dropdown ui-front form-inline">
    <button id="status-button-update" type="button" class="btn btn-default btn-xs"> <span class="glyphicon glyphicon-refresh spin"></span></button>
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="status-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php if(isset($ticket->status_name)) : ?><?php echo $ticket->status_name ?><?php else : ?><?php echo lang("ctn_46") ?><?php endif; ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <?php foreach($statuses->result() as $r) : ?>
      <li><a href="javascript: void(0)" onclick="changeStatus(<?php echo $ticket->ID ?>,<?php echo $r->ID ?>);"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>

    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assignModal"><?php echo lang("ctn_630") ?></button> <a href="<?php echo site_url("tickets/assign_user/" . $ticket->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_604") ?>" onclick="return confirm('<?php echo lang("ctn_631") ?>')"><span class="glyphicon glyphicon-pushpin"></span></a> <a href="<?php echo site_url("tickets/merge_ticket/" . $ticket->ID) ?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_718") ?>"><span class="glyphicon glyphicon-transfer"></span></a> <a href="<?php echo site_url("tickets/print_view/" . $ticket->ID) ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_632") ?>"><span class="glyphicon glyphicon-print"></span></a> <a href="<?php echo site_url("tickets/notify_ticket/" . $ticket->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_673") ?>"><span class="glyphicon glyphicon-bullhorn"></span></a> <a href="<?php echo site_url("tickets/edit_ticket/" . $ticket->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("tickets/delete_ticket/" . $ticket->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>
</div>

    </p>
  <?php endif; ?>
  
  </div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<h4 class="media-title"><?php echo lang("ctn_633") ?></h4>

<?php foreach($history->result() as $r) : ?>
<div class="media">
  <div class="media-left">
    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
  </div>
  <div class="media-body">
    <p><?php echo $r->message ?></p>
    <p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
  </div>
</div>
<hr>
<?php endforeach; ?>

<p class="align-center"><a href="<?php echo site_url("tickets/ticket_history/" . $ticket->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_634") ?></a></p>

</div>
</div>


</div>
</div>

<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-pushpin"></span> <?php echo lang("ctn_561") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("tickets/assign_user_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="username" id="username-search" value="">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_561") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-pushpin"></span> <?php echo lang("ctn_564") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("tickets/edit_ticket_note_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_564") ?></label>
                    <div class="col-md-8 ui-front">
                        <textarea id="note-area" name="note"><?php echo $ticket->notes ?></textarea>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_599") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function() {

  /* Get list of usernames */
  $('#username-search').autocomplete({
    delay : 300,
    minLength: 2,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "tickets/get_usernames",
             data: {
                query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });
$('#cannedr').change(function() {
  var body = $('#cannedr').val();

  body = body.replace('[USER]', client_user);
  body = body.replace('[ADMIN_NAME]', admin_user);
  body = body.replace('[SITE_NAME]', site_name);
  body = body.replace('[FIRST_NAME]', client_first_name);
  body = body.replace('[LAST_NAME]', client_last_name);
  body = body.replace('[STAFF_FIRST_NAME]', staff_first_name);
  body = body.replace('[STAFF_LAST_NAME]', staff_last_name);
  
  
  CKEDITOR.instances['ticket-body'].setData(body);
});
});
CKEDITOR.replace('ticket-body', { height: '200'});
CKEDITOR.replace('note-area', { height: '200'});

function add_file() 
{
    var count = $('#file_count').val();
    count++;
    var html = '<div class="form-group">'+
                    '<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_438") ?></label>'+
                    '<div class="col-md-8">'+
                        '<input type="file" name="user_file_'+count+'" class="form-control">'+
                    '</div>'+
            '</div>';
    $('#file_block').append(html);
    $('#file_count').val(count);
}

function changeStatus(ticketid, id) {
  $('#status-button-update').fadeIn(100);
  $.ajax({
    url: global_base_url + "tickets/change_status",
    type: "GET",
    data: {
      status : id,
      ticketid : ticketid
    },
    dataType : 'json',
    success: function(msg) {
      if(msg.error) {
        alert(msg.error_msg);
        return;
      }
  
      $('#status-button').removeClass();
      $('#status-button').addClass("btn btn-default btn-xs dropdown-toggle");
      $('#status-button').html(msg.name + ' <span class="caret"></span>');
      

      <?php if($this->settings->info->ticket_note_close) : ?>
      if(msg.close == 1) {
        // Load Close Ticket Note
        $('#closeModal').modal();
      }
      <?php endif; ?>
      //$('#status-button-update').html(msg);
      $('#status-button-update').fadeOut(500);
    }
  })
}

var admin_user = "<?php echo $this->user->info->username ?>";
var staff_first_name = "<?php echo $this->user->info->first_name ?>";
var staff_last_name = "<?php echo $this->user->info->last_name ?>";
<?php if(isset($ticket->client_username)) : ?>
var client_user = "<?php echo $ticket->client_username ?>";
var client_first_name = "<?php echo $ticket->first_name ?>";
var client_last_name = "<?php echo $ticket->last_name ?>";
<?php else : ?>
var client_user = "<?php echo $ticket->guest_email ?>";
var client_first_name = "<?php echo $ticket->guest_email ?>"
var client_last_name = "";
<?php endif; ?>
var site_name = "<?php echo $this->settings->info->site_name ?>";


</script>