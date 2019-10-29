<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <link href="<?php echo base_url();?>styles/client2.css?v=1.0" rel="stylesheet" type="text/css">
               

        <!-- CODE INCLUDES -->
    </head>
    <body>
    	<div class="center-pdf-logo"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->icon ?>"></div>
    	<h1 class="pdf-project-heading"><?php echo $project->name ?></h1>
    	<?php echo $project->description ?>

    	<hr>
    	<a name="top" class="pdf-heading"><strong>Table Of Contents</strong></a>
    	<ul class="table-of-contents">
		<?php foreach($documents->result() as $r) : ?>
			<?php if($r->link_documentid > 0) {
				$r->title = $r->link_title;
			}
			?>
		<li><a href="#document-<?php echo $r->ID ?>"><?php echo $r->title ?></a></li>
		<?php endforeach; ?>
		</ul>

		<hr>

		<?php foreach($documents->result() as $document) : ?>

		<?php if($document->link_documentid > 0) : ?>
		<a name="document-<?php echo $document->ID ?>" class="pdf-heading"><strong><?php echo $document->link_title ?></strong> - <a href="#top">Top</a></a>
		<hr>
		<?php echo $document->link_document ?>
		<?php else : ?>
		<div class="pdf-heading"><a name="document-<?php echo $document->ID ?>"><strong><?php echo $document->title ?></strong> - <a href="#top">Top</a></a></div>
		<hr>
		<?php echo $document->document ?>
		<?php endif; ?>

		<hr>
		<?php endforeach; ?>

    </body>
    </html>