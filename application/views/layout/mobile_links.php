<div id="responsive-menu-links">
          <select name='link' OnChange="window.location.href=$(this).val();" class="form-control">
          <option value='<?php echo site_url("home") ?>'><?php echo lang("ctn_154") ?></option>
          <option value='<?php echo site_url("members") ?>'><?php echo lang("ctn_155") ?></option>
          <option value='<?php echo site_url("user_settings") ?>'><?php echo lang("ctn_156") ?></option>
          <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
            <option value='<?php echo site_url("admin") ?>'><?php echo lang("ctn_523") ?></option>
           <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
            <option value='<?php echo site_url("admin/settings") ?>'><?php echo lang("ctn_158") ?></option>
            <option value='<?php echo site_url("admin/social_settings") ?>'><?php echo lang("ctn_159") ?></option>
            <option value='<?php echo site_url("admin/ticket_settings") ?>'><?php echo lang("ctn_524") ?></option>
            <option value='<?php echo site_url("admin/section_settings") ?>'><?php echo lang("ctn_747") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/members") ?>'><?php echo lang("ctn_160") ?></option>
            <option value='<?php echo site_url("admin/custom_fields") ?>'><?php echo lang("ctn_346") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin) : ?>
            <option value='<?php echo site_url("admin/user_roles") ?>'><?php echo lang("ctn_316") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/user_groups") ?>'><?php echo lang("ctn_161") ?></option>
            <option value='<?php echo site_url("admin/ipblock") ?>'><?php echo lang("ctn_162") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_announcements) : ?>
            <option value='<?php echo site_url("admin/announcements") ?>'><?php echo lang("ctn_525") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin) : ?>
            <option value='<?php echo site_url("admin/email_templates") ?>'><?php echo lang("ctn_163") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/email_members") ?>'><?php echo lang("ctn_164") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_payment) : ?>
            <option value='<?php echo site_url("admin/payment_settings") ?>'><?php echo lang("ctn_246") ?></option>
            <option value='<?php echo site_url("admin/payment_plans") ?>'><?php echo lang("ctn_258") ?></option>
            <option value='<?php echo site_url("admin/payment_logs") ?>'><?php echo lang("ctn_288") ?></option>
            <option value='<?php echo site_url("admin/premium_users") ?>'><?php echo lang("ctn_325") ?></option>
            <?php endif; ?>
          <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
            <option value='<?php echo site_url("tickets") ?>'><?php echo lang("ctn_528") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
            <option value='<?php echo site_url("tickets/your") ?>'><?php echo lang("ctn_529") ?></option>
            <option value='<?php echo site_url("tickets/assigned") ?>'><?php echo lang("ctn_530") ?></option>
            <option value='<?php echo site_url("tickets/custom_views") ?>'><?php echo lang("ctn_627") ?></option>
            <option value='<?php echo site_url("tickets/archived") ?>'><?php echo lang("ctn_790") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager"), $this->user)) : ?>
            <option value='<?php echo site_url("tickets/categories") ?>'><?php echo lang("ctn_531") ?></option>
            <option value='<?php echo site_url("tickets/custom_fields") ?>'><?php echo lang("ctn_532") ?></option>
            <option value='<?php echo site_url("tickets/canned_responses") ?>'><?php echo lang("ctn_533") ?></option>
            <option value='<?php echo site_url("tickets/custom_statuses") ?>'><?php echo lang("ctn_791") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "knowledge_manager"), $this->user)) : ?>
            <option value='<?php echo site_url("knowledge") ?>'><?php echo lang("ctn_535") ?></option>
            <option value='<?php echo site_url("knowledge/categories") ?>'><?php echo lang("ctn_536") ?></option>
            <?php endif; ?>
            <?php if($this->settings->info->enable_faq && $this->common->has_permissions(array("admin", "faq_manager"), $this->user)) : ?>
            <option value='<?php echo site_url("FAQ") ?>'><?php echo lang("ctn_776") ?></option>
            <option value='<?php echo site_url("FAQ/categories") ?>'><?php echo lang("ctn_536") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager"), $this->user)) : ?>
            <option value='<?php echo site_url("reports") ?>'><?php echo lang("ctn_538") ?></option>
            <option value='<?php echo site_url("reports/ratings") ?>'><?php echo lang("ctn_539") ?></option>
            <option value='<?php echo site_url("reports/users") ?>'><?php echo lang("ctn_540") ?></option>
            <?php endif; ?>
          
          </select> 
        </div>