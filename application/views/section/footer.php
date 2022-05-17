<div class="modal fade" id="modal-delete">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="javascript:void(0)" style="color: #333333; font-weight: bold; font-size: 16px;">Apakah anda yakin?</a>
        <input type="hidden" name="id_delete" value="">
      </div>
      <div class="modal-footer justify-content-between">
      	<button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Tidak</button>
      	<button type="button" id="btn-delete" onclick="action_delete();" class="btn btn-danger btn-sm" style="font-weight: bold;"><i class="fas fa-angle-double-right"></i> Ya</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	$(function() {

		$('.select2').select2()

		$('.alert').delay(2750).slideUp('slow', function(){
			$(this).remove();
		});
		
	});

	function delete_data(url) {
		$('[name="id_delete"]').val(url);
		$('.modal-title').text('Konfirmasi Penghapusan');
		$('#modal-delete').modal('show');
	}

	function action_delete() {
		action = $('[name="id_delete"]').val();

		if (action) {
			$.ajax({
			    url: action,
			    type: "POST",
			    dataType: "JSON",
			    success: function(response) {
			    	$('#response').html('');
			    	$('#modal-delete').modal('hide');
			    	table.ajax.reload();
			    	if (response.message) {
			    		set_flashdata(response.message);
			    	} else {
				    	var message = 'Berhasil Menghapus ' + title;
				    	set_flashdata(message);
			    	}

			    	if (response.refresh) {
			    	  setTimeout(function() {
			    	  	window.location.reload();
			    	  }, 3525);
			    	}
			    	
			    }
			});
		}
	}

	function set_flashdata(message, type = null) {
		var color = type ? type : 'alert-success';
		$(window).scrollTop(0);
	  $('<div class="alert '+ color +' alert-dismissible" style="font-weight: bold;">' + message + '</div>').show().appendTo('#response');
	  $('.alert').delay(2750).slideUp('slow', function(){
	  	$(this).remove();
	  });
	}

	function set_datatable(segment) {
		table = $('#table').DataTable({
		  "processing": false,
		  "serverSide": true,
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "ordering": false,
		  "info": false,
		  "autoWidth": false,
		  "responsive": false,
		  "language": { 
		    "infoFiltered": "",
		    "sZeroRecords": "",
		    "sEmptyTable": "",
		    "sSearch": "Cari:"
		  },
		  "ajax": {
		    "url": index + segment,
		    "type": "POST",
		    "data": function(data) {
		      data.id = "<?= md5(time()) ?>";
		    },
		  },
		  "columnDefs": [{ 
		    "targets": [0],
		    "orderable": false,
		  }],
		});
	}
</script>

<style type="text/css">
	body {
		font-family: arial;
	}
	#navbar {
		background-color: #222284;
	}
	.page-item.active .page-link {
	    z-index: 1;
	    color: #fff;
	    background-color: #222284;
	    border-color: #222284;
	    height: 31px;
	    font-size: 12px;
	    font-weight: bold;
	}
	.page-link {
	    position: relative;
	    display: block;
	    padding: .5rem .75rem;
	    margin-left: -1px;
	    line-height: 1.25;
	    color: #222284;
	    background-color: #fff;
	    height: 31px;
	    font-size: 12px;
	    font-weight: bold;
	}
	.page-link:hover {
	    z-index: 2;
	    color: #222284;
	    text-decoration: none;
	    background-color: #fff;
	    height: 31px;
	    font-size: 12px;
	    font-weight: bold;
	}
	.page-link:focus {
	    z-index: 2;
	    outline: 0;
	    box-shadow: 0 0 0 0.2rem rgb(253, 126, 20, 0);
	    height: 31px;
	    font-size: 12px;
	    font-weight: bold;
	}
	.card {
		border-top: 3px solid #222284;
	}
	.btn-default {
		background-color: #222284;
		border-color: #222284;
		font-weight: bold;
	}
	.btn-default:hover {
		coloraction: #fff;
		background-color: #222284;
		border-color: #222284;
	}
	.btn-default:not(:disabled):not(.disabled).active, .show>.btn-default.dropdown-toggle {
	    color: #fff;
	    background-color: #222284;
	    border-color: #222284;
	}
	.btn-default:not(:disabled):not(.disabled) {
	    cursor: pointer;
	    color: #fff;
	    background-color: #222284;
	    border-color: #222284;
	    font-weight: bold;
	}
	.btn-default:disabled {
	    color: #fff;
	    background-color: #222284;
	    border-color: #222284;
	}
	.btn-secondary:not(:disabled):not(.disabled) {
		font-weight: bold;
	}
	.breadcrumb-item:not(:active)>a {
		color: #222284;
	}
	.sidebar-dark-light .nav-sidebar>.nav-item>.nav-link.active {
	    background-color: #FFFFFF;
	    color: #1f2d3d;
	}
	.sidebar-dark-light .nav-sidebar>.nav-item>.nav-link.active>p {
	    font-weight: bold;
	}
	.select2 {
		width: 100%;
	}
	.table thead th {
	    vertical-align: bottom;
	    /*border-bottom: 1px solid #dee2e6;*/
	    border-bottom: none;
	}

	select.form-control:focus {
	    border-color: #222284;
	    box-shadow: none;
	}
	#table_filter .form-control-sm:focus {
	    border-color: #222284;
	    box-shadow: none;
	}
	.form-control:focus {
	    border-color: #222284;
	    box-shadow: none;
	}

	#foto_profile{
	  background: #ffffff;
	  color: #222284;
	  border-radius: 50%;
	  display: inline-flex;
	  align-items: center;
	  justify-content: center;
	  font-weight: bold;
	}
</style>