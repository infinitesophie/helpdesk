<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-info-sign"></span> <?php echo lang("ctn_776") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">

<?php echo form_open(site_url("FAQ/edit_faq_pro/" . $faq->ID), array("class" => "form-horizontal")) ?>
           <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_783") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="question" value="<?php echo $faq->question ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_784") ?></label>
                    <div class="col-md-8">
                        <textarea name="answer" id="cat-description"><?php echo $faq->answer ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_462") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="catid" class="form-control">
                            <?php foreach($categories->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if($r->ID == $faq->catid) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
    <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">
    <?php echo form_close() ?>

</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {
     
CKEDITOR.replace('cat-description', { height: '100'});
});
</script>