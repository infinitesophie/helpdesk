<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    <ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/tickets") ?>"><?php echo lang("ctn_461") ?></a></li>
  <li><a href="<?php echo site_url("client/view_ticket/" . $ticket->ID) ?>"><?php echo lang("ctn_773") ?> #<?php echo $ticket->ID ?></a></li>
  <li class="active"><?php echo lang("ctn_781") ?> ...</li>
</ol>


<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open_multipart(site_url("client/edit_ticket_pro/" . $ticket->ID), array("class" => "form-horizontal")) ?>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_427") ?></label>
                <div class="col-md-9 ui-front">
                    <input type="text" class="form-control" name="title" value="<?php echo $ticket->title ?>">
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_428") ?></label>
                <div class="col-md-9 ui-front">
                    <select name="priority" class="form-control">
                    <option value="0"><?php echo lang("ctn_429") ?></option>
                    <option value="1" <?php if($ticket->priority == 1) echo "selected" ?>><?php echo lang("ctn_430") ?></option>
                    <option value="2" <?php if($ticket->priority == 2) echo "selected" ?>><?php echo lang("ctn_431") ?></option>
                    <option value="3" <?php if($ticket->priority == 3) echo "selected" ?>><?php echo lang("ctn_432") ?></option>
                    </select>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_433") ?></label>
                <div class="col-md-9 ui-front">
                    <select name="catid" id="parent_cat" class="form-control">
                        <option value="0"><?php echo lang("ctn_434") ?></option>
                        <?php foreach($categories->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $ticket->categoryid) echo "selected" ?> <?php if($r->ID == $ticket->cat_parent) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="sub_cats"><?php if($sub_cats) : ?>
                         <br />
                        <select name="sub_catid" id="sub_cat" class="form-control">
                                <option value="0"><?php echo lang("ctn_434") ?></option>
                            <?php foreach($sub_cats->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if($r->ID == $ticket->categoryid) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>   
                        <?php endif; ?>
                    </div>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_435") ?></label>
                <div class="col-md-9 ui-front">
                    <textarea name="body" id="ticket-body"><?php echo $ticket->body ?></textarea>
                </div>
        </div>
        <hr>
        <?php foreach($fields->result() as $r) : ?>
                <?php if(!$r->hide_clientside) : ?>
                        <div class="form-group">

                            <label for="name-in" class="col-md-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                            <div class="col-md-9">
                                <?php if($r->type == 0) : ?>
                                    <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php if(isset($r->value)) echo $r->value ?>">
                                <?php elseif($r->type == 1) : ?>
                                    <textarea name="cf_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>"><?php if(isset($r->value)) echo $r->value ?></textarea>
                                <?php elseif($r->type == 2) : ?>
                                     <?php $options = explode(",", $r->options); ?>
                                     <?php if(isset($r->value)) : ?>
                                     <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                                     <?php else : ?>
                                        <?php $values= array(); ?>
                                    <?php endif; ?>
                                    <?php if(count($options) > 0) : ?>
                                        <?php foreach($options as $k=>$v) : ?>
                                        <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php elseif($r->type == 3) : ?>
                                    <?php $options = explode(",", $r->options); ?>
                                    <?php if(count($options) > 0) : ?>
                                        <?php foreach($options as $k=>$v) : ?>
                                        <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php elseif($r->type == 4) : ?>
                                    <?php $options = explode(",", $r->options); ?>
                                    <?php if(count($options) > 0) : ?>
                                        <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                        <?php foreach($options as $k=>$v) : ?>
                                        <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <span class="help-block"><?php echo $r->help_text ?></span>
                            </div>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
        <div id="custom_fields_extra">
            <?php if($sub_cat_fields) : ?>
            <?php foreach($sub_cat_fields->result() as $r) : ?>
                    <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-9">
                            <?php if($r->type == 0) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php if(isset($r->value)) echo $r->value ?>">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>"><?php if(isset($r->value)) echo $r->value ?></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                 <?php if(isset($r->value)) : ?>
                                 <?php $values = array_map('trim', (explode(",", $r->value))); ?>
                                 <?php else : ?>
                                    <?php $values= array(); ?>
                                <?php endif; ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-block"><?php echo $r->help_text ?></span>
                        </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <hr>
        <?php if($this->settings->info->enable_ticket_uploads) : ?>
        <h4><?php echo lang("ctn_436") ?></h4>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_437") ?></label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                            <?php foreach($ticket_files->result() as $r) : ?>
                                <tr><td><a href="<?php echo base_url() . $this->settings->info->upload_path_relative . "/" . $r->upload_file_name ?>"><?php echo $r->upload_file_name ?></a></td><td><?php echo $r->file_size ?>kb</td><td><a href="<?php echo site_url("client/delete_file_attachment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                            <?php endforeach; ?>
                            </table>
                        </div>
                </div>
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
        <input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_440") ?>" />
        <?php echo form_close() ?>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">

$(document).ready(function() {
    <?php foreach($fields->result() as $r) : ?>
    <?php if($r->type == 1) : ?>
    CKEDITOR.replace('field_id_<?php echo $r->ID ?>', { height: '100'});
    <?php endif; ?>
    <?php endforeach; ?>
    CKEDITOR.replace('ticket-body', { height: '200'});
    CKEDITOR.replace('ticket-notes', { height: '100'});

    $('#parent_cat').change(function() {
        // Get any sub cats
        var parent_cat = $('#parent_cat').val();
        $.ajax({
            url: global_base_url + "client/get_sub_cats/" + parent_cat,
            type: "get",
            data: {
            },
            success: function(msg) {
                $('#sub_cats').html(msg);
                load_custom_fields(parent_cat);
            }
        });
    });

    $('#sub_cats').on("change", "#sub_cat", function() {
        var catid = $('#sub_cat').val();
        load_custom_fields(catid);
    });

    function load_custom_fields(parent_cat) {
        $.ajax({
            url: global_base_url + "client/get_custom_fields/" + parent_cat,
            type: "get",
            data: {
            },
            success: function(msg) {
                $('#custom_fields_extra').html(msg);
            }
        });
    }

});
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