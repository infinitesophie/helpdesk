<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-wrench"></span> <?php echo lang("ctn_1") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_339") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_389") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_396") ?></button>
</div>
</div>

<table id="announcement-table" class="table table-hover table-striped table-bordered">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_389") ?></td><td><?php echo lang("ctn_391") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>


</div>

 <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_396") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_announcement_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_389") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="title">
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_390") ?></label>
                    <div class="col-md-9">
                        <textarea name="body" id="body-area"></textarea>
                    </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_391") ?></label>
                        <div class="col-md-9">
                            <select name="status" class="form-control">
                            <option value="0"><?php echo lang("ctn_392") ?></option>
                            <option value="1"><?php echo lang("ctn_393") ?></option>
                            </select>
                            <span class="help-block"><?php echo lang("ctn_394") ?></span>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_794") ?></label>
                        <div class="col-md-9">
                            <select name="color" class="form-control">
                            <option value="info">Information</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Danger</option>
                            </select>
                            <span class="help-block"><?php echo lang("ctn_861") ?></span>
                        </div>
            </div>
            

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="Add Announcement" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
CKEDITOR.replace('body-area', { height: '150'});

$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#announcement-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          
        ],
        "columns": [
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("admin/announcement_page") ?>",
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
        "user-exact",
        "title-exact"
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
