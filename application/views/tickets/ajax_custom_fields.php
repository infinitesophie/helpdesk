<?php foreach($fields->result() as $r) : ?>
    <?php if(isset($clientside)) : ?>
        <?php if(!$r->hide_clientside) : ?>
                    <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-9">
                            <?php if($r->type == 0 || $r->type == 5) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>"></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-block"><?php echo $r->help_text ?></span>
                        </div>
                </div>
                <?php endif; ?>
                <?php else : ?>
                     <div class="form-group">

                        <label for="name-in" class="col-md-3 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
                        <div class="col-md-9">
                            <?php if($r->type == 0 || $r->type == 5) : ?>
                                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>">
                            <?php elseif($r->type == 1) : ?>
                                <textarea name="cf_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>"></textarea>
                            <?php elseif($r->type == 2) : ?>
                                 <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 3) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <?php foreach($options as $k=>$v) : ?>
                                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php elseif($r->type == 4) : ?>
                                <?php $options = explode(",", $r->options); ?>
                                <?php if(count($options) > 0) : ?>
                                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                                    <?php foreach($options as $k=>$v) : ?>
                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="help-block"><?php echo $r->help_text ?></span>
                        </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>

        <script type="text/javascript">

$(document).ready(function() {
    <?php foreach($fields->result() as $r) : ?>
    <?php if($r->type == 1) : ?>
    CKEDITOR.replace('field_id_<?php echo $r->ID ?>', { height: '100'});
    <?php endif; ?>
    <?php endforeach; ?>

  });
</script>