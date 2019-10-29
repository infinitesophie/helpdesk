<?php
// Export mode
$mode = 0;
if(isset($_GET['mode'])) {
	$mode = intval($_GET['mode']);
}

?>

<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-send"></span> <?php if($page == "index") : ?><?php echo lang("ctn_518") ?><?php elseif($page == "your") : ?><?php echo lang("ctn_461") ?><?php elseif($page == "assigned") : ?><?php echo lang("ctn_480") ?><?php elseif($page == "archived") : ?><?php echo lang("ctn_803") ?><?php endif; ?></div>
    <div class="db-header-extra form-inline">
    <?php $default_order = null; ?>
    <?php if($views->num_rows() > 0) : ?>
      <?php
        $current_view = lang("ctn_642");
        $default_order = null;
        if($this->user->info->custom_view > 0) {
          foreach($views->result() as $r) {
            if($r->ID == $this->user->info->custom_view) {
              $current_view = $r->name;
              $default_order = $r->order_by;
              $default_order_type = $r->order_by_type;
            }
          }
        }
      ?>
         <div class="btn-group">
          <div class="dropdown">
        <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <?php echo $current_view ?>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <li><a href="<?php echo site_url("tickets/active_view/0/" . $page) ?>"><?php echo lang("ctn_643") ?></a></li>
          <?php foreach($views->result() as $r) : ?>
            <li><a href="<?php echo site_url("tickets/active_view/" . $r->ID . "/" . $page) ?>"><?php echo $r->name ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      </div>
    <?php endif; ?>

         <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_462") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("tickets/" . $page) ?>"><?php echo lang("ctn_600") ?></a></li>
    <?php foreach($categories->result() as $r) : ?>
      <li><a href="<?php echo site_url("tickets/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>

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
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="title2-exact"></span> <?php echo lang("ctn_481") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok no-display" id="title3-exact"></span> <?php echo lang("ctn_550") ?></a></li>
          <li><a href="#" onclick="change_search(5)"><span class="glyphicon glyphicon-ok no-display" id="title4-exact"></span> <?php echo lang("ctn_551") ?></a></li>
          <li><a href="#" onclick="change_search(6)"><span class="glyphicon glyphicon-ok no-display" id="title5-exact"></span> <?php echo lang("ctn_552") ?></a></li>
          <li><a href="#" onclick="change_search(7)"><span class="glyphicon glyphicon-ok no-display" id="title6-exact"></span> <?php echo lang("ctn_611") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

     <a href="<?php echo site_url("tickets/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_553") ?></a>
</div>
</div>

<?php if($mode == 0) : ?>
<a href="<?php echo site_url("tickets/" . $page . "/" . $catid . "?mode=1") ?>" class="btn btn-default"><?php echo lang("ctn_855") ?></a>
<?php endif; ?>

<div class="table-responsive">
<table id="ticket-table" class="table table-bordered table-hover table-striped">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_611") ?></td><td><?php echo lang("ctn_11") ?></td><td><?php echo lang("ctn_428") ?></td><td><?php echo lang("ctn_391") ?></td><td><?php echo lang("ctn_462") ?></td><td><?php echo lang("ctn_481") ?></td><td><?php echo lang("ctn_550") ?></td><td><?php echo lang("ctn_463") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody class="small-text">
</tbody>
</table>
</div>


</div>

<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#ticket-table').DataTable({
        "dom" : "<?php if($mode == 1) : ?>B<?php endif; ?><'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        <?php if($mode == 1) : ?>
	        buttons: [
	          { "extend": 'copy', "text":'<?php echo lang("ctn_847") ?>',"className": 'btn btn-default btn-sm' },
	          { "extend": 'csv', "text":'<?php echo lang("ctn_848") ?>',"className": 'btn btn-default btn-sm' },
	          { "extend": 'excel', "text":'<?php echo lang("ctn_849") ?>',"className": 'btn btn-default btn-sm' },
	          { "extend": 'pdf', "text":'<?php echo lang("ctn_850") ?>',"className": 'btn btn-default btn-sm' },
	          { "extend": 'print', "text":'<?php echo lang("ctn_851") ?>',"className": 'btn btn-default btn-sm' }
	        ],
	    <?php endif; ?>
        "order": [
        <?php if($default_order != null) : ?>
          [<?php echo $default_order ?>, "<?php echo $default_order_type ?>"]
        <?php else : ?>
        	[6, "desc"]
        <?php endif; ?>
        ],
        "columnDefs": [
    { className: "center-table-data", "targets": [ 0,2,3,4,5,6,7,8] }
  ],
        "columns": [
        null,
        null,
        null,
        null,
        null,
        { "orderable": false },
        { "orderable": false },
        null,
        { "orderable": false }
    ],
        "ajax": {
            url : "<?php echo site_url("tickets/ticket_page/" . $page . "/" . $catid . "/" . $mode) ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      },
      'rowCallback': function(row, data, index){
        <?php foreach($statuses->result() as $r) : ?>
         if(data[3].statusid == <?php echo $r->ID ?>){
            $(row).find('td:eq(3)').css('color', '#<?php echo $r->text_color ?>');
            $(row).find('td:eq(3)').css('background', '#<?php echo $r->color ?>');
            $(row).find('td:eq(3)').css('text-align', 'center');
            $(row).find('td:eq(3)').css('font-weight', '600');
            $(row).find('td:eq(3)').css('font-size', '14px');
            $(row).find('td:eq(3)').text(data[3].name);
        }
        <?php endforeach; ?>
       
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
        "title2-exact",
        "title3-exact",
        "title4-exact",
        "title5-exact",
        "title6-exact",
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
