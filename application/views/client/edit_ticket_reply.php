<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/tickets") ?>"><?php echo lang("ctn_461") ?></a></li>
  <li><a href="<?php echo site_url("client/view_ticket/" . $ticket->ID) ?>"><?php echo lang("ctn_773") ?> #<?php echo $ticket->ID ?></a></li>
  <li class="active"><?php echo lang("ctn_780") ?> ...</li>
</ol>

<div class="panel panel-default">
<div class="panel-body">

<h4><?php echo lang("ctn_441") ?></h4>
<?php echo form_open_multipart(site_url("client/edit_ticket_reply_pro/" . $reply->ID), array("class" => "form-horizontal")) ?>
<p><textarea name="body" id="ticket-body"><?php echo $reply->body ?></textarea></p>
<?php if($this->settings->info->enable_ticket_uploads) : ?>
                <hr>
                <h4><?php echo lang("ctn_437") ?></h4>
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
<p><input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_442") ?>"></p>
<?php echo form_close() ?>
</div>
</div>

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
</script>