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
<a href="<?php echo site_url("client/view_faq/" . $r->ID) ?>" class="btn <?php if($r->ID == $category->ID) : ?>btn-primary<?php else : ?>btn-default<?php endif; ?> faq-button col-md-2"><?php echo $r->name ?> (<?php echo $count ?>)</a>
<?php endforeach; ?>
</div>

<hr>

<?php foreach($faq->result() as $r) : ?>
	<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading<?php echo $r->ID ?>">
      <h4 class="panel-title faq-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $r->ID ?>" aria-expanded="true" aria-controls="collapse<?php echo $r->ID ?>">
          <span class="glyphicon glyphicon-plus"></span> <?php echo $r->question ?>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $r->ID ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $r->ID ?>">
      <div class="panel-body">
        <?php echo $r->answer ?>
      </div>
    </div>
</div>
<?php endforeach; ?>

</div>
</div>

</div>
</div>
</div>