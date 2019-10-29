<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

<?php $prioritys = array(0 => "<span class='label label-info'>".lang("ctn_429")."</span>", 1 => "<span class='label label-primary'>".lang("ctn_430")."</span>", 2=> "<span class='label label-warning'>".lang("ctn_431")."</span>", 3 => "<span class='label label-danger'>".lang("ctn_432")."</span>"); ?>

 <h3 class="home-label">#<?php echo $ticket->ID ?> - <?php echo $ticket->title ?></h3> 

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/tickets") ?>"><?php echo lang("ctn_461") ?></a></li>
  <li class="active"><?php echo lang("ctn_773") ?> #<?php echo $ticket->ID ?></li>
</ol>

 

<div class="row">
<div class="col-md-8">

<div class="panel panel-default">
<div class="panel-body">
<div class="col-md-12" style="word-wrap:break-word; overflow: visible !important;">
    <div class="pull-left">
      <?php echo $this->common->get_user_display(array("username" => $ticket->client_username, "avatar" => $ticket->client_avatar, "online_timestamp" => $ticket->client_online_timestamp)) ?>
  </div>
<h3 class="media-title"><?php echo $ticket->first_name ?> <?php echo $ticket->last_name ?></h3>
<p><?php echo $ticket->body ?></p>
<?php if($this->settings->info->enable_ticket_uploads && $files->num_rows() > 0) : ?>
    <hr>
    <h4><?php echo lang("ctn_437") ?></h4>
    <div class="form-group">
            <div class="col-md-12">
                <table class="table table-bordered">
                <?php foreach($files->result() as $r) : ?>
                    <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>" target="_blank"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                          <?php if( ($this->user->loggedin && $r->userid == $this->user->info->ID) || $this->common->has_permissions(array("admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("client/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php endif; ?></td></tr>
                <?php endforeach; ?>
                </table>
            </div>
    </div>
<?php endif; ?>
<div class="small-text">
<?php if($ticket_fields) : ?>
    <?php foreach($ticket_fields->result() as $r) : ?>
      <?php if(!$r->hide_clientside) : ?>
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
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>

  </div>

</div>
</div>

</div>
<div class="col-md-4">

<div class="panel panel-default">
<div class="panel-body">
  <h4 class="media-title"><?php echo lang("ctn_629") ?></h4>
<table class="table">
<tr><td class="ticket-label-info"><?php echo lang("ctn_611") ?></td><td> <?php echo $ticket->ID ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_468") ?></td><td><?php if(isset($ticket->client_username)) : ?><a href="<?php echo site_url("profile/" . $ticket->client_username) ?>"><?php echo $ticket->client_username ?></a> <?php else : ?><strong><?php echo $ticket->guest_email ?> [<?php echo lang("ctn_469") ?>]</strong><?php endif; ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_470") ?></td><td><?php echo date($this->settings->info->date_format, $ticket->timestamp); ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_428") ?></td><td><?php echo $prioritys[$ticket->priority] ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_471") ?></td><td><?php echo date($this->settings->info->date_format, $ticket->last_reply_timestamp) ?> <?php if(isset($ticket->lr_username)) : ?>by <a href="<?php echo site_url("profile/" . $ticket->lr_username) ?>"><?php echo $ticket->lr_username ?></a><?php endif; ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_462") ?></td><td><?php echo $ticket->cat_name ?></td></tr>
<tr><td class="ticket-label-info"><?php echo lang("ctn_391") ?></td><td><button class="btn btn-default btn-xs" type="button" id="status-button" ><?php echo $ticket->status_name ?></button></td></tr>
    
 
  <?php if($this->settings->info->ticket_rating) : ?>
    <?php if($ticket->public && !$owner) : ?>

    <?php else : ?>
      <tr><td class="ticket-label-info"><?php echo lang("ctn_554") ?></td><td>
    <div class="ticket-rating">
    <strong><?php echo lang("ctn_472") ?></strong><br />
    <?php for($i=1;$i<=5;$i++) : ?>
      <?php if($i > $ticket->rating) : ?>
        <span class="glyphicon glyphicon-star-empty click" id="ticket<?php echo $i ?>"></span>
      <?php else : ?>
        <span class="glyphicon glyphicon-star click" id="ticket<?php echo $i ?>"></span>
      <?php endif; ?>
    <?php endfor; ?>
    </div>
  </td></tr>
  <?php endif; ?>
  <?php endif; ?>
</table>

     <?php if($this->settings->info->enable_ticket_edit) : ?>
    <hr>
    <p><a href="<?php echo site_url("client/edit_ticket/" . $ticket->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a></p>
  <?php endif; ?>

</div>
</div>

</div>
</div>



<?php foreach($replies->result() as $r) : ?>
  <?php
    if($r->userid == 0 || $r->userid == $ticket->userid) {
      $class = "panel-primary";
    } else {
      $class = "panel-admin";
    }
  ?>
<div class="panel <?php echo $class ?>">
<div class="panel-body">
  <?php if( (isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass']) && $r->userid == 0) || ($this->user->loggedin && $r->userid == $this->user->info->ID) || $this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
    <div class="ticket-reply-options">
    <a href="<?php echo site_url("client/edit_ticket_reply/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a>
    <a href="<?php echo site_url("client/delete_ticket_reply/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>
    </div>
  <?php endif; ?>
  <div class="media">
  <div class="media-left">
    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
  </div>
  <div class="media-body">
    <h4 class="media-title"><?php if(isset($r->username)) : ?><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a><?php else : ?><strong><?php echo $ticket->guest_email ?></strong><?php endif; ?></h4>
<p><?php echo $r->body ?></p>
<p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp); ?></p>
<?php if($r->files && $this->settings->info->enable_ticket_uploads) : ?>
<?php $files = $this->tickets_model->get_reply_files($r->ID); ?>
<hr>
                <div class="form-group clearfix">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_437") ?></label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                            <?php foreach($files->result() as $r) : ?>
                                <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td>
                                  <?php if($this->user->loggedin && $r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
                                  <a href="<?php echo site_url("client/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
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
</div>
<?php endforeach; ?>

<?php if($ticket->status_close && $this->settings->info->close_ticket_reply) : ?>
<div class="panel panel-default">
<div class="panel-body">
<h4><?php echo lang("ctn_774") ?></h4>
</div>
</div>
<?php else : ?>
  <?php if($ticket->public && !$owner) : ?>
    <div class="panel panel-default">
<div class="panel-body">
<h4><?php echo lang("ctn_775") ?></h4>
</div>
</div>
  <?php else : ?>
<div class="panel panel-default">
<div class="panel-body">
<h4><?php echo lang("ctn_473") ?></h4>
<?php echo form_open_multipart(site_url("client/ticket_reply/" . $ticket->ID), array("class" => "form-horizontal")) ?>
<p><textarea name="body" id="ticket-body"></textarea></p>
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
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_474") ?>"></p>
<?php echo form_close() ?>
</div>
</div>
<?php endif; ?>

<?php endif; ?>

</div>
</div>
</div>

<script type="text/javascript">

CKEDITOR.replace('ticket-body', { height: '100'});

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
      if(id == 0) {
        $('#status-button').removeClass();
        $('#status-button').addClass("btn btn-info btn-xs dropdown-toggle");
        $('#status-button').html('<?php echo lang("ctn_465") ?>  <span class="caret"></span>');
      } else if(id == 1) {
        $('#status-button').removeClass();
        $('#status-button').addClass("btn btn-primary btn-xs dropdown-toggle");
        $('#status-button').html('<?php echo lang("ctn_466") ?>  <span class="caret"></span>');
      } else if(id == 2) {
        $('#status-button').removeClass();
        $('#status-button').addClass("btn btn-danger btn-xs dropdown-toggle");
        $('#status-button').html('<?php echo lang("ctn_467") ?>  <span class="caret"></span>');
      }
      //$('#status-button-update').html(msg);
      $('#status-button-update').fadeOut(500);
    }
  })
}

$(document).ready(function() {

  var rated = 0; 
  $('#ticket1').hover(function() {
    fill_stars(1);
  }, function() {
    empty_stars(5);
  });

  $('#ticket2').hover(function() {
    fill_stars(2);
  }, function() {
    empty_stars(5);
  });

  $('#ticket3').hover(function() {
    fill_stars(3);
  }, function() {
    empty_stars(5);
  });

  $('#ticket4').hover(function() {
    fill_stars(4);
  }, function() {
    empty_stars(5);
  });

  $('#ticket5').hover(function() {
    fill_stars(5);
  }, function() {
    empty_stars(5);
  });

  function fill_stars(stars) 
  {
    for(var i = 0; i<=stars;i++) {
      $('#ticket'+i).removeClass("glyphicon glyphicon-star-empty");
      $('#ticket'+i).addClass("glyphicon glyphicon-star");
    }
  }

  function empty_stars(stars) 
  {
    for(var i = 0; i<=stars;i++) {
      if(rated < i) {
        $('#ticket'+i).removeClass("glyphicon glyphicon-star");
        $('#ticket'+i).addClass("glyphicon glyphicon-star-empty");
      }
    }
  }

  

  $('#ticket1').click(function() {
    rate_ticket(1);
    fill_stars(1);
  });

  $('#ticket2').click(function() {
    rate_ticket(2);
    fill_stars(2);
  });

  $('#ticket3').click(function() {
    rate_ticket(3);
    fill_stars(3);
  });

  $('#ticket4').click(function() {
    rate_ticket(4);
    fill_stars(4);
  });

  $('#ticket5').click(function() {
    rate_ticket(5);
    fill_stars(5);
  });

  function rate_ticket(stars) 
  {
    rated = stars;
    $.ajax({
      url: global_base_url + "client/rate_ticket/" + <?php echo $ticket->ID ?> + "/" + global_hash,
      type: "get",
      data: {
        rating : stars
      },
      success: function(msg) {
        
      }
    });
  }
});

</script>