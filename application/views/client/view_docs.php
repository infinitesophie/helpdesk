<div class="container">
  <div class="row">
  	<div class="col-md-3">
  		<?php echo $sidebar ?>
  	</div>
    <div class="col-md-9">
    	<div class="content-area-padding documentation-area">
        
<h1 class="home-label"><?php echo $project->name ?></h1>

    	<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
   <li><a href="<?php echo site_url("client/documentation") ?>"><?php echo lang("ctn_810") ?></a></li>
  <li class="active"><?php echo $project->name ?>'s Documentation</li>
</ol>


<?php echo $project->description ?>

</div>

</div>
</div>
</div>