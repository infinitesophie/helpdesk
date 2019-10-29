<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	
<h3 class="home-label"><?php echo lang("ctn_456") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/knowledge") ?>"><?php echo lang("ctn_456") ?></a></li>
  <li class="active"><?php echo $article->title ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-heading"><?php echo $article->title ?></div>
<div class="panel-body">
<?php echo $article->body ?>
<p class="small-text"><?php echo lang("ctn_471") ?>: <?php echo date($this->settings->info->date_format, $article->last_updated_timestamp) ?></p>
<p><?php echo lang("ctn_852") ?> <strong><?php echo $article->useful_yes ?> / <?php echo $article->useful_total ?></strong> <?php echo lang("ctn_853") ?></p>
<?php if($user_vote->num_rows() == 0) : ?>
<p><?php echo lang("ctn_854") ?> <a href="<?php echo site_url("client/knowledge_vote/" . $article->ID . "/1/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_53") ?></a> | <a href="<?php echo site_url("client/knowledge_vote/" . $article->ID . "/0/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_54") ?></a></p>
<?php endif; ?>
</div>
</div>

</div>
</div>
</div>