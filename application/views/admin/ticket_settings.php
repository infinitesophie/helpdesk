<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_407") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/ticket_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_408") ?></label>
    <div class="col-sm-9">
    	<input type="checkbox" id="name-in" name="enable_ticket_uploads" value="1" <?php if($this->settings->info->enable_ticket_uploads) echo "checked" ?>>
    	<span class="help-block"><?php echo lang("ctn_409") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_410") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="enable_ticket_guests" value="1" <?php if($this->settings->info->enable_ticket_guests) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_411") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_412") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="enable_ticket_edit" value="1" <?php if($this->settings->info->enable_ticket_edit) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_413") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_414") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="require_login" value="1" <?php if($this->settings->info->require_login) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_415") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_416") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="ticket_rating" value="1" <?php if($this->settings->info->ticket_rating) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_417") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_671") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="captcha_ticket" value="1" <?php if($this->settings->info->captcha_ticket) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_672") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_858") ?></label>
    <div class="col-sm-9">
      <input type="text" id="name-in" name="alert_users" class="form-control" value="<?php echo $this->settings->info->alert_users ?>">
      <span class="help-block"><?php echo lang("ctn_859") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_752") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="public_tickets" value="1" <?php if($this->settings->info->public_tickets) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_753") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_754") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="ticket_note_close" value="1" <?php if($this->settings->info->ticket_note_close) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_755") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_756") ?></label>
    <div class="col-sm-9">
      <input type="checkbox" id="name-in" name="close_ticket_reply" value="1" <?php if($this->settings->info->close_ticket_reply) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_757") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_758") ?></label>
    <div class="col-sm-4">
      <h4><?php echo lang("ctn_759") ?></h4>
      <select name="staff_status" class="form-control">
          <option value="0"><?php echo lang("ctn_760") ?></option>
        <?php foreach($statuses->result() as $r) : ?>
          <option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->settings->info->staff_status) echo "selected" ?>><?php echo $r->name ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-sm-4">
      <h4><?php echo lang("ctn_761") ?></h4>
      <select name="client_status" class="form-control">
        <option value="0"><?php echo lang("ctn_760") ?></option>
        <?php foreach($statuses->result() as $r) : ?>
          <option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->settings->info->client_status) echo "selected" ?>><?php echo $r->name ?></option>
        <?php endforeach; ?>
      </select>
    </div>
</div>
<h4><?php echo lang("ctn_418") ?></h4>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_419") ?></label>
    <div class="col-sm-9">
      <select name="protocol">
        <option value="1" <?php if($this->settings->info->protocol) echo "selected" ?>>IMap</option>
      </select>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_420") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="protocol_path" value="<?php echo $this->settings->info->protocol_path ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_421") ?></label>
    <div class="col-sm-9">
      <select name="protocol_ssl" class="form-control">
      <option value="0"><?php echo lang("ctn_54") ?></option>
      <option value="1" <?php if($this->settings->info->protocol_ssl) echo "selected" ?>><?php echo lang("ctn_53") ?></option>
      </select>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_762") ?></label>
    <div class="col-sm-9">
      <select name="disable_cert" class="form-control">
      <option value="0"><?php echo lang("ctn_54") ?></option>
      <option value="1" <?php if($this->settings->info->disable_cert) echo "selected" ?>>Yes</option>
      </select>
      <span class="help-block"><?php echo lang("ctn_763") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_422") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="protocol_email" value="<?php echo $this->settings->info->protocol_email ?>">
      <span class="help-block"><?php echo lang("ctn_423") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_424") ?></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" id="name-in" name="protocol_password" value="<?php echo $this->settings->info->protocol_password ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_425") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="ticket_title" value="<?php echo $this->settings->info->ticket_title ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_615") ?></label>
    <div class="col-sm-9">
      <select name="catid" class="form-control">
      <?php foreach($categories->result() as $r) : ?>
        <option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->settings->info->default_category) echo "selected" ?>><?php echo $r->name ?></option>
      <?php endforeach; ?>
      </select>
      <span class="help-block"><?php echo lang("ctn_616") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_804") ?></label>
    <div class="col-sm-9">
      <select name="default_status" class="form-control">
      <?php foreach($statuses->result() as $r) : ?>
        <option value="<?php echo $r->ID ?>" <?php if($r->ID == $this->settings->info->default_status) echo "selected" ?>><?php echo $r->name ?></option>
      <?php endforeach; ?>
      </select>
      <span class="help-block"><?php echo lang("ctn_805") ?></span>
    </div>
</div>
<h3><?php echo lang("ctn_697") ?></h3>
<p><?php echo lang("ctn_698") ?></p>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_699") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="imap_ticket_string" value="<?php echo $this->settings->info->imap_ticket_string ?>">
      <span class="help-block"><?php echo lang("ctn_700") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_701") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="imap_reply_string" value="<?php echo $this->settings->info->imap_reply_string ?>">
      <span class="help-block"><?php echo lang("ctn_702") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_426") ?></label>
    <div class="col-sm-9">
      <strong><?php echo lang("ctn_617") ?></strong><br />
      wget <?php echo site_url("cron/ticket_replies") ?><br><br />
      <strong><?php echo lang("ctn_618") ?></strong><br />
      wget <?php echo site_url("cron/ticket_create") ?><br><br />
      <strong><?php echo lang("ctn_860") ?></strong>
      wget <?php echo site_url("cron/auto_close") ?><br /><br />
      <p>Try using <strong>curl -s [CRON_URL] > /dev/null</strong> if wget is creating issues for you.</p>
    </div>
</div>
<h3><?php echo lang("ctn_703") ?></h3>
<div class="form-group">
    <label for="name-in" class="col-sm-3"><?php echo lang("ctn_704") ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name-in" name="envato_personal_token" value="<?php echo $this->settings->info->envato_personal_token ?>">
      <span class="help-block"><?php echo lang("ctn_705") ?></span>
    </div>
</div>


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>