<br />
<select name="sub_catid" id="sub_cat" class="form-control">
		<option value="0"><?php echo lang("ctn_434") ?></option>
    <?php foreach($categories->result() as $r) : ?>
        <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
    <?php endforeach; ?>
</select>