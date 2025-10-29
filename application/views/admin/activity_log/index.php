<?php
$filterId=$fromdate=$toDate='';
if (isset($dataItem['filter'])) {
    $filterId=$dataItem['filter']['filterId'];
    $fromdate=$dataItem['filter']['from_date'];
    $toDate=$dataItem['filter']['to_date'];
}
?>

<style>
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
	div#datatable-buttons_info,div#datatable-buttons_paginate{
		display: none;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"><?= lang('activity_log_listing') ?></h4>
							<form class="form-control">
							    <div class="row">
                                    <div class="col-md-3">
										<select class="form-select" id="validationCustom04" name="filtering_options">
											<option value=""><?= lang('select_filter_date') ?></option>
											<option value="today"><?= lang('today') ?></option>
											<option value="last_3_days"><?= lang('last_3_days') ?></option>
											<option value="last_week"><?= lang('last_week') ?></option>
											<option value="last_month"><?= lang('last_month') ?></option>
											<option value="last_3_month"><?= lang('last_3_month') ?></option>
											<option value="last_6_month"><?= lang('last_6_month') ?></option>
											<option value="last_1_year"><?= lang('last_1_year') ?></option>
										</select>
                                    </div>

							        <div class="col-sm-3 <?php if ($filterId!=7){echo 'd-none';} ?>" id="from_date">
							            <input class="form-control" type="text" onfocus="(this.type='date')" name="from_date" id="" value="<?php if(isset($_REQUEST['from_date'])){ echo $_REQUEST['from_date']; }?>" placeholder="From Date">
							        </div>
							        <div class="col-sm-3 <?php if ($filterId!=7){echo 'd-none';} ?>" id="to_date">
							            <input class="form-control " type="text" onfocus="(this.type='date')"  name="to_date" id="" value="<?php if(isset($_REQUEST['to_date'])){ echo $_REQUEST['to_date']; }?>" placeholder="To Date">
							        </div>
<!--							        <div class="col-sm-3">-->
<!--							            <input class="form-control btn-primary" type="submit" name="search" id="search" value="Search">-->
<!--							        </div>-->
							    </div>
							</form>
							<br>

							<table id="datatableActivity" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?= lang('sr_no') ?></th>
									<th><?= lang('name') ?></th>
									<th><?= lang('email') ?></th>
									<th><?= lang('phone') ?></th>
									<th><?= lang('action') ?></th>
									<th><?= lang('ip') ?></th>
									<th><?= lang('browser_name') ?></th>
									<th><?= lang('country_code') ?></th>
									<th><?= lang('create_date') ?></th>
								</tr>
								</thead>
								<tbody id="activityLog">

								</tbody>
							</table>


						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<script>
    $(document).on('change', 'select#validationCustom04', function() {
        if (Number($(this).val())===7){
            $('#from_date').removeClass('d-none');
            $('#to_date').removeClass('d-none');
        }else {
            $('#from_date').addClass('d-none');
            $('#to_date').addClass('d-none');
        }
    });



	$(document).ready(function() {
		var dataTable=$('#datatableActivity').DataTable({
			"language": {
				"paginate": {
					"previous": "<i class='mdi mdi-chevron-left'>",
					"next": "<i class='mdi mdi-chevron-right'>"
				}
			},
			"drawCallback": function () {
				$('.dataTables_paginate > .pagination').addClass('pagination-rounded');
			},
			"serverSide": true,
			"ajax": {
				"url": '<?php echo base_url();?>admin/activity/activity-logs-data',
				"type": "GET"
			},
			"columns": [
				{ "data": "id", "name": "id", "searchable": false, "orderable": false },
				{ "data": "full_name" },
				{ "data": "email" },
				{ "data": "mobile" },
				{ "data": "action_message" },
				{ "data": "ip"},
				{ "data": "browser_name" },
				{ "data": "country_code" },
				{ "data": "created_at" }
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html(index + 1);
			},
			"searching": true // Enable searching
		});

		// Event listener on the dropdown
		$('#validationCustom04').on('change', function() {
			var selectedDate = $(this).val();
			// Trigger DataTable search with the selected date
			dataTable.search(selectedDate).draw();
		});


	});

</script>
