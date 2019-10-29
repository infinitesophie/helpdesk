<ul class="newnav nav nav-sidebar">
           <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment || $this->user->info->admin_announcements)

           ) : ?>
              <li id="admin_sb">
                <a data-toggle="collapse" data-parent="#admin_sb" href="#admin_sb_c" class="collapsed <?php if(isset($activeLink['admin'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-wrench sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_157") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['admin'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="admin_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['admin'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['admin']['general'])) echo "active" ?>"><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_523") ?></a></li>
                    <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
                      <li class="<?php if(isset($activeLink['admin']['settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/settings") ?>"> <?php echo lang("ctn_158") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['social_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/social_settings") ?>"> <?php echo lang("ctn_159") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['ticket_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/ticket_settings") ?>"> <?php echo lang("ctn_524") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['section'])) echo "active" ?>"><a href="<?php echo site_url("admin/section_settings") ?>"> <?php echo lang("ctn_747") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['members'])) echo "active" ?>"><a href="<?php echo site_url("admin/members") ?>"> <?php echo lang("ctn_160") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['custom_fields'])) echo "active" ?>"><a href="<?php echo site_url("admin/custom_fields") ?>"> <?php echo lang("ctn_346") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_roles'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_roles") ?>"> <?php echo lang("ctn_316") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_announcements) : ?>
                    <li class="<?php if(isset($activeLink['admin']['announcements'])) echo "active" ?>"><a href="<?php echo site_url("admin/announcements") ?>"> <?php echo lang("ctn_525") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_groups'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_groups") ?>"> <?php echo lang("ctn_161") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['ipblock'])) echo "active" ?>"><a href="<?php echo site_url("admin/ipblock") ?>"> <?php echo lang("ctn_162") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                      <li class="<?php if(isset($activeLink['admin']['email_templates'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_templates") ?>"> <?php echo lang("ctn_163") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                      <li class="<?php if(isset($activeLink['admin']['email_members'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_members") ?>"> <?php echo lang("ctn_164") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_payment) : ?>
                    <li class="<?php if(isset($activeLink['admin']['payment_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/payment_settings") ?>"> <?php echo lang("ctn_246") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['payment_plans'])) echo "active" ?>"><a href="<?php echo site_url("admin/payment_plans") ?>"> <?php echo lang("ctn_258") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['payment_logs'])) echo "active" ?>"><a href="<?php echo site_url("admin/payment_logs") ?>"> <?php echo lang("ctn_288") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['premium_users'])) echo "active" ?>"><a href="<?php echo site_url("admin/premium_users") ?>"> <?php echo lang("ctn_325") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                      <li class="<?php if(isset($activeLink['admin']['tools'])) echo "active" ?>"><a href="<?php echo site_url("admin/tools") ?>"> <?php echo lang("ctn_686") ?></a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker", "knowledge_manager"), $this->user)) : ?>
              <li class="<?php if(isset($activeLink['home']['general'])) echo "active" ?>"><a href="<?php echo site_url("home") ?>"><span class="glyphicon glyphicon-home sidebar-icon sidebar-icon-blue"></span> <?php echo lang("ctn_526") ?> <span class="sr-only">(current)</span></a></li>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker"), $this->user)) : ?>
            <li id="ticket_sb">
                <a data-toggle="collapse" data-parent="#ticket_sb" href="#ticket_sb_c" class="collapsed <?php if(isset($activeLink['ticket'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-send sidebar-icon sidebar-icon-green"></span> <?php echo lang("ctn_527") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['ticket'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="ticket_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['ticket'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <?php if($this->common->has_permissions(array("admin", "ticket_manager"), $this->user)) : ?>
                    <li class="<?php if(isset($activeLink['ticket']['general'])) echo "active" ?>"><a href="<?php echo site_url("tickets") ?>"> <?php echo lang("ctn_528") ?></a></li>
                  <?php endif; ?>
                    <li class="<?php if(isset($activeLink['ticket']['your'])) echo "active" ?>"><a href="<?php echo site_url("tickets/your") ?>"> <?php echo lang("ctn_529") ?></a></li>
                    <li class="<?php if(isset($activeLink['ticket']['ass'])) echo "active" ?>"><a href="<?php echo site_url("tickets/assigned") ?>"> <?php echo lang("ctn_530") ?></a></li>
                    <li class="<?php if(isset($activeLink['ticket']['archived'])) echo "active" ?>"><a href="<?php echo site_url("tickets/archived") ?>"> <?php echo lang("ctn_790") ?></a></li>
                    <?php if($this->common->has_permissions(array("admin", "ticket_manager"), $this->user)) : ?>
                    <li class="<?php if(isset($activeLink['ticket']['cats'])) echo "active" ?>"><a href="<?php echo site_url("tickets/categories") ?>"> <?php echo lang("ctn_531") ?></a></li>
                    <li class="<?php if(isset($activeLink['ticket']['custom'])) echo "active" ?>"><a href="<?php echo site_url("tickets/custom_fields") ?>"> <?php echo lang("ctn_532") ?></a></li>
                    <li class="<?php if(isset($activeLink['ticket']['canned'])) echo "active" ?>"><a href="<?php echo site_url("tickets/canned_responses") ?>"> <?php echo lang("ctn_533") ?></a></li>
                    <li class="<?php if(isset($activeLink['ticket']['custom_statuses'])) echo "active" ?>"><a href="<?php echo site_url("tickets/custom_statuses") ?>"> <?php echo lang("ctn_791") ?></a></li>
                    <?php endif; ?>
                    <li class="<?php if(isset($activeLink['ticket']['custom_view'])) echo "active" ?>"><a href="<?php echo site_url("tickets/custom_views") ?>"> <?php echo lang("ctn_627") ?></a></li>
                  
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_knowledge && $this->common->has_permissions(array("admin", "knowledge_manager"), $this->user)) : ?>
            <li id="knowledge_sb">
                <a data-toggle="collapse" data-parent="#knowledge_sb" href="#knowledge_sb_c" class="collapsed <?php if(isset($activeLink['knowledge'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-book sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_534") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['knowledge'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="knowledge_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['knowledge'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['knowledge']['general'])) echo "active" ?>"><a href="<?php echo site_url("knowledge") ?>"> <?php echo lang("ctn_535") ?></a></li>
                    <li class="<?php if(isset($activeLink['knowledge']['cats'])) echo "active" ?>"><a href="<?php echo site_url("knowledge/categories") ?>"> <?php echo lang("ctn_536") ?></a></li>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_faq && $this->common->has_permissions(array("admin", "faq_manager"), $this->user)) : ?>
            <li id="faq_sb">
                <a data-toggle="collapse" data-parent="#faq_sb" href="#faq_sb_c" class="collapsed <?php if(isset($activeLink['faq'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-info-sign sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_776") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['faq'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="faq_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['faq'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['faq']['general'])) echo "active" ?>"><a href="<?php echo site_url("FAQ") ?>"> <?php echo lang("ctn_776") ?></a></li>
                    <li class="<?php if(isset($activeLink['faq']['cats'])) echo "active" ?>"><a href="<?php echo site_url("FAQ/categories") ?>"> <?php echo lang("ctn_536") ?></a></li>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_documentation && $this->common->has_permissions(array("admin", "documentation_manager"), $this->user)) : ?>
            <li id="report_sb">
                <a data-toggle="collapse" data-parent="#documentation_sb" href="#documentation_sb_c" class="collapsed <?php if(isset($activeLink['documentation'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-file sidebar-icon sidebar-icon-blue"></span> <?php echo lang("ctn_810") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['documentation'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="documentation_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['documentation'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['documentation']['general'])) echo "active" ?>"><a href="<?php echo site_url("documentation") ?>"><?php echo lang("ctn_810") ?></a></li>
                    <li class="<?php if(isset($activeLink['documentation']['order'])) echo "active" ?>"><a href="<?php echo site_url("documentation/order") ?>"><?php echo lang("ctn_838") ?></a></li>
                    <li class="<?php if(isset($activeLink['documentation']['projects'])) echo "active" ?>"><a href="<?php echo site_url("documentation/projects") ?>"><?php echo lang("ctn_839") ?></a></li>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager"), $this->user)) : ?>
            <li id="report_sb">
                <a data-toggle="collapse" data-parent="#report_sb" href="#report_sb_c" class="collapsed <?php if(isset($activeLink['report'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-list-alt sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_537") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['report'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="report_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['report'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['report']['general'])) echo "active" ?>"><a href="<?php echo site_url("reports") ?>"> <?php echo lang("ctn_538") ?></a></li>
                    <li class="<?php if(isset($activeLink['report']['ratings'])) echo "active" ?>"><a href="<?php echo site_url("reports/ratings") ?>"> <?php echo lang("ctn_539") ?></a></li>
                    <li class="<?php if(isset($activeLink['report']['users'])) echo "active" ?>"><a href="<?php echo site_url("reports/users") ?>"> <?php echo lang("ctn_540") ?></a></li>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "ticket_manager", "ticket_worker", "knowledge_manager"), $this->user)) : ?>
                <li class="<?php if(isset($activeLink['members']['general'])) echo "active" ?>"><a href="<?php echo site_url("members") ?>"><span class="glyphicon glyphicon-user sidebar-icon sidebar-icon-brown"></span> <?php echo lang("ctn_155") ?></a></li>
            <?php endif; ?>
  
          <li class="<?php if(isset($activeLink['test']['general'])) echo "active" ?>"><a href="<?php echo site_url("client") ?>"><span class="glyphicon glyphicon-heart sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_541") ?></a></li>
          
          </ul>