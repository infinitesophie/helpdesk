<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-list-alt"></span> <?php echo lang("ctn_555") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_481") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>
</div>
</div>

<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped small-text">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_481") ?></td><td><?php echo lang("ctn_556") ?></td><td><?php echo lang("ctn_557") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#ticket-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [0, "desc"]
        ],
        "columns": [
        null,
        null,
        null,
    ],
        "ajax": {
            url : "<?php echo site_url("reports/user_page") ?>",
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
