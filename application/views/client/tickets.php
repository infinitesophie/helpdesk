<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

      
<h3 class="home-label"><?php echo lang("ctn_518") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_461") ?></li>
</ol>



<div class="panel panel-default">
<div class="panel-body">

  <div class="db-header clearfix">
    <div class="page-header-title"> <?php echo lang("ctn_461") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_425") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

<a href="<?php echo site_url("client/public_tickets") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_767") ?></a>

</div>
</div>



<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_611") ?></td><td><?php echo lang("ctn_389") ?></td><td><?php echo lang("ctn_428") ?></td><td><?php echo lang("ctn_391") ?></td><td><?php echo lang("ctn_462") ?></td><td><?php echo lang("ctn_463") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>
</div>

</div>
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
        	[5, "desc"]
        ],
        "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        { "orderable": false },
    ],
        "ajax": {
            url : "<?php echo site_url("client/ticket_page/" . $page) ?>",
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