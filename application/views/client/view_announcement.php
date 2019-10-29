<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">


<h3 class="home-label">Announcements</h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo $announcement->title ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-heading"><?php echo $announcement->title ?></div>
<div class="panel-body">
<?php echo $announcement->body ?>
</div>
</div>


</div>
</div>
</div>