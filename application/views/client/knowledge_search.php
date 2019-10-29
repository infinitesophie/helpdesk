<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	
<h3 class="home-label"><?php echo lang("ctn_456") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/knowledge") ?>"><?php echo lang("ctn_519") ?></a></li>
  <li class="active"><?php echo lang("ctn_779") ?> ... "<?php echo $search ?>"</li>
</ol>


<?php if($articles->num_rows() > 0) : ?>
<table id="ticket-table" class="table table-bordered table-hover table-striped">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_389") ?></td><td><?php echo lang("ctn_458") ?></td><td><?php echo lang("ctn_459") ?></td></tr>
</thead>
<tbody>
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
  <?php
    $summary = substr(strip_tags($r->body), 0, 100);
  ?>
<tr><td><?php echo $r->title ?></td><td><?php echo $summary ?></td><td><a href="<?php echo site_url("client/view_knowledge/" . $r->ID) ?>" class="btn btn-info btn-xs"><?php echo lang("ctn_459") ?></a></td></tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else : ?>

  <p><?php echo lang("ctn_460") ?></p>
<?php endif; ?>

</div>
</div>
</div>