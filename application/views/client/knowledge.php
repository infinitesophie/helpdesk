<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	
<h3 class="home-label"><?php echo lang("ctn_456") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_456") ?></li>
</ol>


<?php foreach($categories->result() as $r) : ?>
<div class="category-page">
<a href="<?php echo site_url("client/view_knowledge_cat/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" class="category-image"></a>
<p class="category-title"><a href="<?php echo site_url("client/view_knowledge_cat/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
<?php echo $r->description ?>
</div>
<?php endforeach; ?>


<hr>

<h3 class="home-label"><?php echo lang("ctn_457") ?></h3>

<div class="list-group">
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

</div>
</div>
</div>