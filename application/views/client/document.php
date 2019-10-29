<?php

if($document->link_documentid > 0) {
  $title = $document->link_title;
  $document = $document->link_document;
} else {
  $title = $document->title;
  $document = $document->document;
}

?>
<div class="container">
  <div class="row">
  	<div class="col-md-3">
  		<?php echo $sidebar ?>
  	</div>
    <div class="col-md-9">
    	<div class="content-area-padding documentation-area">

<h3 class="home-label"><?php echo $title ?></h3>

    	<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
   <li><a href="<?php echo site_url("client/documentation") ?>"><?php echo lang("ctn_810") ?></a></li>
   <li><a href="<?php echo site_url("client/view_docs/" . $project->ID) ?>"><?php echo $project->name ?>'s <?php echo lang("ctn_810") ?></a></li>
  <li class="active"><?php echo $title ?></li>
</ol>


<hr>
<?php echo $document ?>

<?php if($files->num_rows() > 0) : ?>
<hr>
<h3><?php echo lang("ctn_437") ?></h3>
<?php foreach($files->result() as $r) : ?>
<div class="attached-file">
<p><span class="glyphicon glyphicon-file"></span></p>
<p><a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->file_name ?>" download="<?php echo $r->name ?>"><?php echo $r->name ?></a></p>
<p class="small-text"><?php echo $r->file_type ?> - <?php echo $r->file_size ?>KB</p>
</div>
<?php endforeach; ?>
<?php endif; ?>

</div>

</div>
</div>
</div>