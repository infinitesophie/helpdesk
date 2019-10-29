<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7" id="ticket-area">

                            <?php if($this->settings->info->price_per_ticket > 0 && $this->user->loggedin) : ?>
                                <p><?php echo lang("ctn_864") ?> <strong><?php echo number_format($this->settings->info->price_per_ticket, 2) ?></strong> <?php echo lang("ctn_865") ?></p>
                                <p><?php echo lang("ctn_248") ?>: <?php echo number_format($this->user->info->points,2) ?>. <?php echo lang("ctn_866") ?> <a href="<?php echo site_url("client/funds") ?>"><?php echo lang("ctn_250") ?></a> <?php echo lang("ctn_867") ?></p>
                            <?php endif; ?>
                    

                            <?php if($this->settings->info->enable_ticket_guests || $this->user->loggedin) : ?>
                            <h3 class="home-label"><?php echo lang("ctn_453") ?></h3> 

                            <?php if(!empty($this->settings->info->site_desc)) : ?>
                                <?php echo $this->settings->info->site_desc ?>
                            <?php endif; ?>

                            <div class="panel panel-default">
<div class="panel-body">                
<?php if($this->settings->info->price_per_ticket > 0  && (!$this->user->loggedin || $this->user->info->points < $this->settings->info->price_per_ticket)) : ?>

    <?php else : ?>
<?php echo form_open_multipart(site_url("client/add_pro"), array("class" => "form-horizontal", "id" => "ticket_form")) ?>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_427") ?></label>
                <div class="col-md-9 ui-front">
                    <input type="text" class="form-control" id='article-title' name="title" value="">
                </div>
        </div>
        <?php if(!$this->user->loggedin && $this->settings->info->enable_ticket_guests) : ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_454") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="guest_email">
                    </div>
            </div>
        <?php endif; ?>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_428") ?></label>
                <div class="col-md-9 ui-front">
                    <select name="priority" class="form-control">
                    <option value="0"><?php echo lang("ctn_429") ?></option>
                    <option value="1"><?php echo lang("ctn_430") ?></option>
                    <option value="2"><?php echo lang("ctn_431") ?></option>
                    <option value="3"><?php echo lang("ctn_432") ?></option>
                    </select>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_433") ?></label>
                <div class="col-md-9 ui-front">
                    <select name="catid" id="parent_cat" class="form-control">
                        <option value="0"><?php echo lang("ctn_434") ?> </option>
                        <?php foreach($categories->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="sub_cats"></div>
                </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-3 label-heading"></label>
                <div class="col-md-9 ui-front help-block" id='cat-desc'>
                    
                </div>
        </div>
        <div class="form-group">
                <div class="col-md-12 ui-front">
                    <textarea name="body" id="ticket-body"></textarea>
                </div>
        </div>
        <hr>
        <?php foreach($fields->result() as $r) : ?>
             <?php if(!$r->hide_clientside) : ?>
                    <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-9">
                            <?php if($r->type == 0 || $r->type == 5) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>"></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-block"><?php echo $r->help_text ?></span>
                        </div>
                </div>
            <?php endif; ?>
                <?php endforeach; ?>
        <div id="custom_fields_extra"></div>
        <hr>
        <?php if($this->settings->info->enable_ticket_uploads) : ?>
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
        <?php if($this->settings->info->captcha_ticket) : ?>
            <?php if(!$this->settings->info->google_recaptcha) : ?>
                <div class="form-group">

                    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
                    <div class="col-md-9">
                        <p><?php echo $cap['image'] ?></p>
                        <input type="text" class="form-control" id="captcha-in" name="captcha" placeholder="<?php echo lang("ctn_306") ?>" value="">
                    </div>
                </div>
                <?php else: ?>
                    <div class="form-group">

                    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
                    <div class="col-md-9">
                        <div class="g-recaptcha" data-sitekey="<?php echo $this->settings->info->google_recaptcha_key ?>"></div>
                    </div>
                </div>
                <?php endif ?>
        <?php endif; ?>
        <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_455") ?>" />
        <?php echo form_close() ?>

<?php endif; ?>
        </div>
</div>
    <?php else : ?>
        <?php if(!empty($this->settings->info->site_desc)) : ?>
                                <?php echo $this->settings->info->site_desc ?>
                            <?php endif; ?>
        <div class="align-center">
            <h3><?php echo lang("ctn_768") ?> <a href="<?php echo site_url("login") ?>"><?php echo lang("ctn_150") ?></a> <?php echo lang("ctn_769") ?></h3>
            <h3><?php echo lang("ctn_770") ?> <a href="<?php echo site_url("register") ?>"><?php echo lang("ctn_151") ?></a> <?php echo lang("ctn_771") ?></h3>
        </div>
    <?php endif; ?>

                        </div>
                        <div class="col-md-4 col-md-offset-1" id="knowledge-area">
                             <?php if($this->settings->info->enable_knowledge) : ?>

<div class="list-group">
    <a href="<?php echo site_url("client/knowledge") ?>" class="list-group-item active" style="word-wrap:break-word; overflow: visible !important;">
    <h4 class="list-group-item-heading"><?php echo lang("ctn_502") ?></h4>
  </a>
<?php foreach($articles->result() as $r) : ?>
    <?php
    $groups = $this->knowledge_model->get_category_groups($r->catid);
        if($groups->num_rows() > 0) {
            $groupids = array();
            foreach($groups->result() as $rr) {
                $groupids[] = $rr->groupid;
            }

            if($this->user->loggedin) {
                $userid = $this->user->info->ID;
            } else {
                $userid = 0;
            }

            $member = $this->knowledge_model->get_user_groups($groupids, $userid);
            if($member->num_rows() ==0) $r->body = "";
        }

    ?>
<?php $str = explode("***", wordwrap(strip_tags($r->body), 100, "***")); ?>
  <a href="<?php echo site_url("client/view_knowledge/" . $r->ID) ?>" class="list-group-item">
    <h4 class="list-group-item-heading"><?php echo $r->title ?></h4>
    <p class="list-group-item-text"><?php echo $str[0] ?> ... </p>
  </a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if($this->settings->info->public_tickets) : ?>
<div class="list-group">
    <a href="<?php echo site_url("client/public_tickets") ?>" class="list-group-item active" style="word-wrap:break-word; overflow: visible !important;">
    <h4 class="list-group-item-heading"><?php echo lang("ctn_767") ?></h4>
  </a>
<?php foreach($public_tickets->result() as $r) : ?>
    
<?php $str = explode("***", wordwrap(strip_tags($r->body), 100, "***")); ?>
  <a href="<?php echo site_url("client/view_ticket/" . $r->ID) ?>" class="list-group-item" style="word-wrap:break-word; overflow: visible !important;">
    <h4 class="list-group-item-heading"><?php echo $r->title ?></h4>
    <p class="list-group-item-text"><?php echo $str[0] ?> ... </p>
  </a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if($this->settings->info->enable_faq) : ?>

<div class="list-group">
    <a href="<?php echo site_url("client/faq") ?>" class="list-group-item active" style="word-wrap:break-word; overflow: visible !important;">
    <h4 class="list-group-item-heading"><?php echo lang("ctn_772") ?></h4>
  </a>
<?php foreach($faq->result() as $r) : ?>
    
  <div class="list-group-item" style="word-wrap:break-word; overflow: visible !important;">
    <h4 class="panel-title faq-title-fp">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $r->ID ?>" aria-expanded="true" aria-controls="collapse<?php echo $r->ID ?>">
        <?php echo $r->question ?>
        </a>
      </h4>
    <div id="collapse<?php echo $r->ID ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $r->ID ?>">
      <div class="panel-body">
        <?php echo $r->answer ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">

$(document).ready(function() {
    $('#article-title').autocomplete({
    delay : 300,
    minLength: 2,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "client/get_articles",
             data: {
                    query : request.term
             },
             success: function (msg) {
                if(msg != 0) {
                 $('#knowledgebase').html(msg);
                }
             }
         });
      }
  });
    <?php foreach($fields->result() as $r) : ?>
    <?php if($r->type == 1) : ?>
    CKEDITOR.replace('field_id_<?php echo $r->ID ?>', { height: '100'});
    <?php endif; ?>
    <?php endforeach; ?>
    CKEDITOR.replace('ticket-body', { height: '200'});

    $('#parent_cat').change(function() {
        // Get any sub cats

        var parent_cat = $('#parent_cat').val();
        get_cat_desc(parent_cat);
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
        get_cat_desc(catid);
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

function get_cat_desc(id) 
{
    $.ajax({
            url: global_base_url + "client/get_category_description/" + id,
            type: "get",
            data: {
            },
            success: function(msg) {
                if(!msg) return;
                $('#cat-desc').html(msg);
            }
        });
}

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
<script type="text/javascript">
  $(document).ready(function() {
    var form = "ticket_form";
    $('#'+form + ' input').on("focus", function(e) {
      clearerrors();
    });
    $('#'+form + ' select').on("focus", function(e) {
      clearerrors();
    });
    $('#'+form + ' #cke_ticket-body').on("focus", function(e) {
      clearerrors();
    });
    $('#'+form).on("submit", function(e) {

      e.preventDefault();
      // Ajax check
      var data = $(this).serialize();
      $.ajax({
        url : global_base_url + "client/ajax_check_ticket",
        type : 'POST',
        data : {
            <?php foreach($fields->result() as $r) : ?>
           <?php if($r->type == 1) : ?>
           cf_<?php echo $r->ID ?> : CKEDITOR.instances['field_id_<?php echo $r->ID ?>'].getData(),
           <?php endif; ?>
           <?php endforeach; ?>
          formData : data,
          form_body : CKEDITOR.instances['ticket-body'].getData(),
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash() ?>'
        },
        dataType: 'JSON',
        success: function(data) {
          if(data.error) {
            $('#'+form).prepend('<div class="form-error">'+data.error_msg+'</div>');
          }
          if(data.success) {
            // allow form submit
            $('#'+form+ ' input[type="submit"]').val("Working ...");
            $('#'+form).unbind('submit').submit();
          }
          if(data.field_errors) {
            var errors = data.fieldErrors;
            console.log(errors);
            for (var property in errors) {
                if (errors.hasOwnProperty(property)) {
                    // Find form name
                    var field_name = '#' + form + ' input[name="'+property+'"]';
                    if( !($(field_name).size() > 0)) {
                        field_name = '#' + form + ' select[name="'+property+'"]';
                    }
                    if(property == "body") {
                        field_name = '#' + form + ' #cke_ticket-body';
                    }
                    console.log("FIELD: " + field_name);
                    $(field_name).addClass("errorField");
                    // Get input group of field
                    $(field_name).parent().closest('.form-group').after('<div class="form-error-no-margin">'+errors[property]+'</div>');
                    

                }
            }
          }
        }
      });

      return false;


    });
  });

  function clearerrors() 
  {
    console.log("Called");
    $('.form-error').remove();
    $('.form-error-no-margin').remove();
    $('.errorField').removeClass('errorField');
  }
</script>