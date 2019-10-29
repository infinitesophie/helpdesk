<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

    	
<h3 class="home-label"><?php echo lang("ctn_777") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_776") ?></li>
</ol>


<div class="panel panel-default">
<div class="panel-body">


<div class="clearfix" style="margin-bottom: 20px;">
<?php foreach($categories->result() as $r) : ?>
	<?php $count = $this->FAQ_model->get_faq_count($r->ID); ?>
<a href="<?php echo site_url("client/view_faq/" . $r->ID) ?>" class="btn btn-default faq-button col-md-2"><?php echo $r->name ?> (<?php echo $count ?>)</a>
<?php endforeach; ?>
</div>

<hr>
<p><?php echo lang("ctn_778") ?></p>

</div>
</div>

</div>
</div>
</div>