<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php if(isset($page_title)) : ?><?php echo $page_title ?> - <?php endif; ?><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

         <!-- Styles -->
        <link href="<?php echo base_url();?>styles/client2.css" rel="stylesheet" type="text/css">
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,500,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

        <!-- SCRIPTS -->
        <script type="text/javascript">
        var global_base_url = "<?php echo site_url('/') ?>";
        var global_hash = "<?php echo $this->security->get_csrf_hash() ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>scripts/custom/global.js"></script>
        
        <script type="text/javascript">
          $.widget.bridge('uitooltip', $.ui.tooltip);
        </script>
        <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>


        <!-- Favicon: http://realfavicongenerator.net -->
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url() ?>images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url() ?>images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url() ?>images/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?php echo base_url() ?>images/favicon/site.webmanifest">
        <link rel="mask-icon" href="<?php echo base_url() ?>images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="<?php echo base_url() ?>images/favicon/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="<?php echo base_url() ?>images/favicon/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <?php if(isset($datatable_lang) && !empty($datatable_lang)) : ?>
        <script type="text/javascript">
            $(document).ready(function() {
              $.extend( true, $.fn.dataTable.defaults, {
              "language": {
                "url": "<?php echo $datatable_lang ?>"
            }
              });
          });
        </script>
        <?php endif; ?>
        
        <script type="text/javascript">
          $.widget.bridge('uitooltip', $.ui.tooltip);
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
              $('[data-toggle="tooltip"]').tooltip();
            });
        </script>

        <!-- CODE INCLUDES -->
        <?php echo $cssincludes ?> 
    </head>
    <body>
    <div class="container-fluid background-area">
    </div>

    <div class="container-fluid header-top">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <div class="header-logo">
                                <?php if(!$this->settings->info->logo_option) : ?>
                                  <a href="<?php echo site_url() ?>" title="<?php echo $this->settings->info->site_name ?>"><?php echo $this->settings->info->site_name ?></a>
                                  <?php else : ?>
                                    <a href="<?php echo site_url() ?>" title="<?php echo $this->settings->info->site_name ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->settings->info->site_logo ?>" width="<?php echo $this->settings->info->logo_width ?>" height="<?php echo $this->settings->info->logo_height ?>"></a>
                                  <?php endif; ?>
                            </div>
                            <div class="header-links">
                                <ul>
                                    <li><a href="<?php echo site_url("client/tickets") ?>"><?php echo lang("ctn_518") ?></a></li>
                                    <?php if($this->settings->info->enable_knowledge) : ?>
                                    <li><a href="<?php echo site_url("client/knowledge") ?>"><?php echo lang("ctn_519") ?></a></li>
                                    <?php endif; ?>
                                    <?php if($this->settings->info->enable_faq) : ?>
                                        <li><a href="<?php echo site_url("client/faq") ?>"><?php echo lang("ctn_776") ?></a></li>
                                    <?php endif; ?>
                                    <?php if($this->settings->info->enable_documentation) : ?>
                                        <li><a href="<?php echo site_url("client/documentation") ?>"><?php echo lang("ctn_840") ?></a></li>
                                    <?php endif; ?>
                                    <?php if($this->user->loggedin && $this->settings->info->payment_enabled) : ?>
                                        <li><a href="<?php echo site_url("client/plans") ?>"><?php echo lang("ctn_520") ?></a></li>
                                    <?php endif; ?>
                                    <?php if($this->user->loggedin && (!isset($hideme) || $hideme != 1) ) : ?>
                                        <li>
                                            <div class="user-bar-segment">
                                                <a href="#" data-target="#" onclick="load_notifications()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="noti-menu-drop"><span class="glyphicon glyphicon-bell icon-size"></span><?php if($this->user->info->noti_count > 0) : ?><span class="badge notification-badge small-text"><?php echo $this->user->info->noti_count ?></span><?php endif; ?></a>
                                                <ul class="dropdown-menu" id="noti-menu" aria-labelledby="noti-menu-drop">
                                                        <li>
                                                        <div class="notification-box-title">
                                                        <?php echo lang("ctn_498") ?> <?php if($this->user->info->noti_count > 0) : ?><span class="badge click" id="noti-click-unread" onclick="load_notifications_unread()"><?php echo $this->user->info->noti_count ?></span><?php endif; ?>
                                                        </div>
                                                        <div id="notifications-scroll">
                                                          <div id="loading_spinner_notification">
                                                            <span class="glyphicon glyphicon-refresh" id="ajspinner_notification"></span>
                                                          </div>
                                                        </div>
                                                        <div class="notification-box-footer">
                                                        <a href="<?php echo site_url("client/notifications") ?>"><?php echo lang("ctn_516") ?></a>
                                                        </div>
                                                      </li>
                                                      </ul>
                                            </div>

                                        </li>
                                        <li>
                                            <div class="user-bar-segment">
                                                <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>" class="user_avatar"> <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                  <?php echo $this->user->info->first_name ?> <?php echo $this->user->info->last_name ?> <span class="caret"></span>
                                                 </a>
                                                <ul class="dropdown-menu" id="user-menu-links" aria-labelledby="dropdownMenu1">
                                                  <li><a href="<?php echo site_url("user_settings") ?>"><?php echo lang("ctn_156") ?></a></li>
                                                  <li><a href="<?php echo site_url("login/logout/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_521") ?></a></li>
                                                  <?php if($this->common->has_permissions(array("admin", "admin_members", "admin_payment", "admin_settings"), $this->user)) : ?>
                                                    <li role="separator" class="divider"></li>
                                                    <li><a href="<?php echo site_url("home") ?>"><?php echo lang("ctn_157") ?></a></li>
                                                  <?php endif; ?>
                                                </ul>
                                            </div>
                                        </li>
                                    <?php else : ?>
                                        <li><a href="<?php echo site_url("login") ?>"><?php echo lang("ctn_150") ?></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <div class="row">

                        <div class="col-md-offset-2 col-md-8 header-search">
                            <h2><?php echo lang("ctn_788") ?></h2>
                            <?php echo form_open(site_url("client/knowledge_search")); ?>
                            <div class="input-group">
                            <input type="text" name="search" class="form-control input-lg" placeholder="<?php echo lang("ctn_789") ?> ..." />
                            <div class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-lg">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                  </div><!-- /btn-group -->
                            </div>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

    <?php if($this->settings->info->install) : ?>
          <div class="row">
                        <div class="col-md-12">
                                <div class="alert alert-info"><b><span class="glyphicon glyphicon-warning-sign"></span></b> <a href="<?php echo site_url("install") ?>">Great job on uploading all the files and setting up the site correctly! Let's now create the Admin account and set the default settings. Click here! This message will disappear once you have run the install process.</a></div>
                        </div>
                    </div>
        <?php endif; ?>
    <?php 
    $announcements = $this->user_model->get_announcements(); 
    $config = $this->config->item("cookieprefix");
    ?>

    <?php if($announcements->num_rows() > 0) : ?>
        <?php foreach($announcements->result() as $r) : ?>
            <?php $cookie = $this->input->cookie($config . "announcement_" . $r->ID, TRUE);?>
            <?php if($r->color == "") $r->color="info"; ?>
            <?php if(!$cookie) : ?>
            <div class="row" id="announcement-<?php echo $r->ID ?>">
                    <div class="col-md-12">
                            <div class="alert alert-<?php echo $r->color ?>"><b><span class="glyphicon glyphicon-exclamation-sign"></span></b> <a href="<?php echo site_url("client/view_announcement/" . $r->ID) ?>"><?php echo $r->title ?></a> <div class="pull-right"><span class="glyphicon glyphicon-remove click" onclick="close_announcement(<?php echo $r->ID ?>)"></span></div></div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>



    <?php $gl = $this->session->flashdata('globalmsg'); ?>
    <?php if(!empty($gl)) :?>
                <div class="row">
                    <div class="col-md-12">
                            <div class="alert alert-success"><b><span class="glyphicon glyphicon-ok"></span></b> <?php echo $this->session->flashdata('globalmsg') ?></div>
                    </div>
                </div>
    <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php echo $content ?>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright <a href="#">Beta Version</a> 2019. - <a href="<?php echo site_url("client/change_language") ?>"><?php echo lang("ctn_171") ?></a></p>
      </div>
    </footer>


    </body>
</html>