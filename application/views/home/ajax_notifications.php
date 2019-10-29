<?php if($notifications->num_rows() == 0) : ?>
<div class="notification-box-bit animation-fade clearfix">
<?php echo lang("ctn_475") ?>
</div>
<?php else : ?>
<?php foreach($notifications->result() as $r) : ?>
<div class="notification-box-bit animation-fade clearfix <?php if(!$r->status) : ?>active-noti<?php endif; ?>">
  <div class="notification-icon-bit">
  	<?php if(isset($r->avatar)) : ?>
	    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="notification-user-icon">
	<?php else : ?>
		<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/default.png" class="notification-user-icon">
	<?php endif; ?>
  </div>
  <div class="notification-text-bit click" onclick="load_notification_url(<?php echo $r->ID ?>)">
    <?php if(isset($r->username)) : ?><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a><?php else : ?><?php echo lang("ctn_844") ?><?php endif; ?> <?php echo $r->message ?>
    <p class="notification-datestamp"><?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->timestamp)) ?></p>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>