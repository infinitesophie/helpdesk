<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-book"></span> <?php echo lang("ctn_502") ?></div>
    <div class="db-header-extra"> 
</div>
</div>


<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("knowledge/edit_article_pro/" . $article->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_464") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="title" value="<?php echo $article->title ?>" >
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_503") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="article-description"><?php echo $article->body ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_462") ?></label>
                    <div class="col-md-8">
                        <select name="catid" class="form-control">
                        <?php foreach($categories->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $article->catid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_513") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('article-description', { height: '250'});
});
</script>