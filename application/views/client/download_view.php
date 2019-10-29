<div class="container">
  <div class="row">
  	<div class="col-md-3">
  		<?php echo $sidebar ?>
  	</div>
    <div class="col-md-9">
    	<div class="content-area-padding documentation-area">


<h3 class="home-label"><?php echo $project->name ?></h3>

    	<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
   <li><a href="<?php echo site_url("client/documentation") ?>"><?php echo lang("ctn_810") ?></a></li>
   <li class="active"><a href="<?php echo site_url("client/view_docs/" . $project->ID) ?>"><?php echo $project->name ?>'s <?php echo lang("ctn_810") ?></a></li>
</ol>

<hr>
<?php echo $project->description ?>

<hr>

<?php foreach($documents->result() as $document) : ?>

<?php if($document->link_documentid > 0) : ?>
<h3 id="document-<?php echo $document->ID ?>"><?php echo $document->link_title ?> - <a href="#top"><?php echo lang("ctn_811") ?></a></h3>
<hr>
<?php echo $document->link_document ?>
<?php else : ?>
<h3 id="document-<?php echo $document->ID ?>"><?php echo $document->title ?> - <a href="#top"><?php echo lang("ctn_811") ?></a></h3>
<hr>
<?php echo $document->document ?>
<?php endif; ?>

<?php $files = $this->documentation_model->get_files($document->ID); ?>

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
<hr>
<?php endforeach; ?>



</div>

</div>
</div>
</div>