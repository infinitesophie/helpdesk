<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-book"></span> <?php echo lang("ctn_505") ?></div>
    <div class="db-header-extra form-inline"> 

 <div class="form-group has-feedback no-margin">
<div class="input-group">
<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_336") ?>" id="form-search-input" />
<div class="input-group-btn">
    <input type="hidden" id="search_type" value="0">
        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
          <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_337") ?></a></li>
          <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_338") ?></a></li>
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_506") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

    <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_507") ?>" data-toggle="modal" data-target="#myModal" />
</div>
</div>

<div class="table-responsive">
<table id="cat-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_508") ?></td><td><?php echo lang("ctn_509") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-send"></span> <?php echo lang("ctn_507") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("knowledge/add_category_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_506") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_510") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="cat-description"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_511") ?></label>
                    <div class="col-md-8">
                        <p><input type="file" name="userfile" /></p>
                        <span class="help-block"><?php echo lang("ctn_512") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_625") ?></label>
                    <div class="col-md-8">
                        <select name="category_parent" class="form-control">
                        <option value="0"><?php echo lang("ctn_46") ?></option>
                        <?php foreach($categories->result() as $r) : ?>
                          <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_626") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_786") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="ug" data-placeholder="<?php echo lang("ctn_594") ?>">
                            <?php foreach($user_groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_787") ?></span>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_507") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('cat-description', { height: '100'});

  $('#myModal').on('shown.bs.modal', function () {
      $(".chosen-select-no-single").chosen({
        disable_search_threshold:10
      });
  });

});
</script>
<script type="text/javascript">
$(document).ready(function() {
   var st = $('#search_type').val();
    var table = $('#cat-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
        	[1, "ASC"]
        ],
        "columns": [
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("knowledge/cat_page") ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

} );
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "title-exact",
      ];
      set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
      for(var i = 0; i<options.length;i++) {
        if(options[i] == icon) {
          $('#' + icon).fadeIn(10);
        } else {
          $('#' + options[i]).fadeOut(10);
        }
      }
    }
</script>