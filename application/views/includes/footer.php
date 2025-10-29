<?php
$copyrightContent ='© Cryptocians 2022 - 23 | All Right Reserved.';
$getSettingsModel =$this->db->query("SELECT copy_right_display_status,copy_right_text FROM setting")->row();

if ( $getSettingsModel ){
	if ( 1==$getSettingsModel->copy_right_display_status ){
		$copyrightContent=$getSettingsModel->copy_right_text;
	}
}
?>

<footer class="footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<?php if ($getSettingsModel->copy_right_display_status){ ?>
				<script>document.write(new Date().getFullYear())</script> <?=$copyrightContent?>
				<?php } ?>
			</div>
			<div class="col-sm-6">
				<div class="text-sm-end d-none d-sm-block">
				</div>
			</div>
		</div>
	</div>
</footer>
</div>


<!-- JAVASCRIPT -->
<script src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>assets/libs/node-waves/waves.min.js"></script>
<script src="<?=base_url()?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>

<!-- apexcharts -->
<script src="<?=base_url()?>assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Required datatable js -->
<script src="<?=base_url()?>assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="<?=base_url()?>assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>


<script src="<?=base_url()?>assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>

<!-- Responsive examples -->
<script src="<?=base_url()?>assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!--pdf scrip-->
<script src="<?=base_url()?>assets/libs/jszip/jszip.min.js"></script>
<script src="<?=base_url()?>assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- Datatable init js -->
<script src="<?=base_url()?>assets/js/pages/datatables.init.js"></script>

<script src="<?=base_url()?>assets/js/pages/form-validation.init.js"></script>

<script src="<?=base_url()?>assets/libs/parsleyjs/parsley.min.js"></script>

<script src="<?=base_url()?>assets/libs/select2/js/select2.min.js"></script>
<!-- App js -->
<script src="<?=base_url()?>assets/js/app.js?t=<?=time()?>"></script>


<script>
	// parsley validation
	$(document).ready(function() {
		$('.custom-validation').parsley();
		$(".select2").select2();
		$("#from_mt5_login_id").select2();

		$('#status').fadeOut();
		$('#preloader').delay(500).fadeOut('slow');

		$(".select44").select2();
	});
	$(document).ready(function(){
		$('ul li a').click(function(){

			// $('ul li a').removeClass("mm-active");
			// $('ul li a').parent().removeClass("mm-active");
			// $('ul li a').next().removeClass("mm-show");

			$(this).toggleClass("mm-active");
			$(this).parent().toggleClass("mm-active");
			$(this).next().toggleClass("mm-show");
		});
	});


	$(document).on('click', 'button#vertical-menu-btn', function(event) {
		// $('body').toggleClass('sidebar-enable vertical-collpsed');
		event.preventDefault();
		$('body').toggleClass('sidebar-enable');
		if ($(window).width() >= 992) {
			$('body').toggleClass('vertical-collpsed');
		} else {
			$('body').removeClass('vertical-collpsed');
		}
	});

	$(function() {
		$('ul li a').filter(function() {
			return this.href === location.href
		}).addClass('mm-active');

		$('ul li a').filter(function() {
			return this.href === location.href
		}).parent().addClass('mm-active');

		$('ul li a').filter(function() {
			return this.href === location.href
		}).parent().parent().addClass('mm-show');
	})
</script>
<script>
	$(document).ready(function() {

		var form = $('form');

		// Disable the submit button after it has been clicked
		form.on('submit', function() {
			$(this).find(":submit").prop('disabled', true);
			$(this).find(":submit").removeAttr("type");
		});

		// Re-enable the submit button if the form is reset
		form.on('reset', function() {
			$(this).find(":submit").prop('disabled', false);
			$(this).find(":submit").attr("type", "submit");
		});

		// Re-enable the submit button if there is a validation error
		form.on('invalid-form.validate', function() {
			$(this).find(":submit").prop('disabled', false);
			$(this).find(":submit").attr("type", "submit");
		});
	});

	//Buttons examples
	var table2 = $('#datatable-buttons-registrations').DataTable({
		lengthChange: false,
		"language": {
			"paginate": {
				"previous": "<i class='mdi mdi-chevron-left'>",
				"next": "<i class='mdi mdi-chevron-right'>"
			}
		},
		"drawCallback": function () {
			$('.dataTables_paginate > .pagination').addClass('pagination-rounded');
		},
		buttons: ['copy', 'excel', 'pdf', 'colvis'],
		order: [[0, 'desc']] // Order by the first column (column index 0) in ascending order
	});

	table2.buttons().container()
		.appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

	$(".dataTables_length select").addClass('form-select form-select-sm');


</script>

</body>

</html>
