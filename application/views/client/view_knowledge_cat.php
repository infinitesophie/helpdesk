<div class="container">
  <div class="row">
    <div class="col-md-12 content-area">

      <h3 class="home-label"><?php echo lang("ctn_456") ?></h3>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("client/knowledge") ?>"><?php echo lang("ctn_456") ?></a></li>
  <li class="active"><?php echo $category->name ?></li>
</ol>


<div class="panel panel-default">
<div class="panel-heading"><?php echo $category->name ?></div>
<div class="panel-body">
<?php echo $category->description ?>
<?php if($subcats->num_rows() > 0) : ?>
<hr>
<?php foreach($subcats->result() as $r) : ?>
<div class="category-page">
<a href="<?php echo site_url("client/view_knowledge_cat/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" class="category-image"></a>
<p class="category-title"><a href="<?php echo site_url("client/view_knowledge_cat/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
<?php echo $r->description ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_389") ?></td><td><?php echo lang("ctn_458") ?></td><td><?php echo lang("ctn_459") ?></td></tr>
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
        	[0, "desc"]
        ],
        "columns": [
        null,
        { "orderable": false },
        { "orderable": false },
    ],
        "ajax": {
            url : "<?php echo site_url("client/knowledge_cat_page/" . $category->ID) ?>",
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