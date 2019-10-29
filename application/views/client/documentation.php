<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	
<h3 class="home-label"><?php echo lang("ctn_810") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_810") ?></li>
</ol>


<?php foreach($projects->result() as $r) : ?>
<div class="project-blob">
<a href="<?php echo site_url("client/view_docs/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->icon ?>" width="80" height="80"></a>
<p><a href="<?php echo site_url("client/view_docs/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
	</div>
<?php endforeach; ?>



</div>
</div>
</div>