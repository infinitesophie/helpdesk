<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

      <h3 class="home-label"><?php echo lang("ctn_623") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/plans") ?>"><?php echo lang("ctn_520") ?></a></li>
  <li class="active"><?php echo lang("ctn_623") ?></li>
</ol>

<div class="panel panel-default">
<div class="panel-body">

<div class="clearfix">
<span class="plan-title"><?php echo lang("ctn_273") ?></span>

<div class="pull-right">
<a href="<?php echo site_url("client/payment_log") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_623") ?></a> <a href="<?php echo site_url("client/funds") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_245") ?></a>
</div>
</div>

<hr>

<table id="payment_table" class="table table-bordered table-hover table-striped">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_25") ?></td><td><?php echo lang("ctn_291") ?></td><td><?php echo lang("ctn_292") ?></td><td><?php echo lang("ctn_293") ?></td><td><?php echo lang("ctn_378") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>

</div>
</div>

</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#payment_table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [3, "asc" ]
        ],
        "columns": [
        { "orderable" : false },
        { "orderable" : false },
        null,
        null,
        null
    ],
        "ajax": {
            url : "<?php echo site_url("funds/payment_logs_page") ?>",
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