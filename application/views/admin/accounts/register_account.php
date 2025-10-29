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
	.profile-image{
		border-radius: 76%;
		border: 2px solid #5664d2;
		width: 100px;
		height: 100px;
		position: absolute;
		right: 70px;
		top: -56px;
		padding: 0;
	}
	.error {
		border: 1px solid red;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title"><?=$this->lang->line('registered_account')?></h4>
							<br>
							<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>
							<form class="form-control" id="dateFilterForm">
								<div class="row">
									<div class="col-sm-2">
										<input class="form-control" type="text" onfocus="(this.type='date')" name="startDate" id="startDate" value="" placeholder="<?=$this->lang->line('placeholder_start_date')?>">
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" onfocus="(this.type='date')" name="endDate" id="endDate" value="" placeholder="<?=$this->lang->line('placeholder_end_date')?>">
									</div>
									<div class="col-sm-1">
										<input class="form-control btn-primary" type="button" name="search" id="dateFilterFormBtn" value="<?=$this->lang->line('button_search')?>">
									</div>
									<div class="col-sm-1">
										<input class="form-control btn-danger" type="button" name="search" id="resetBtn" value="<?=$this->lang->line('button_reset')?>">
									</div>
									<div class="col-sm-6" style="text-align: right">
										<a href="javascript:void(0)" style="text-decoration: none;box-shadow: none;" id="exportCsv">
											<img src="<?=base_url()?>/assets/images/csv-2.png" alt="<?=$this->lang->line('button_export_csv')?>" style="width: 72px;">
										</a>
									</div>
								</div>
							</form>

							<br>
							<table id="datatableActivity" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('reg_date_time')?></th>
									<th style="text-align: center"><?=$this->lang->line('kyc')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('mobile')?></th>
									<th><?=$this->lang->line('country')?></th>
									<!-- <th><?=$this->lang->line('deposit_balance')?></th> -->
									<th data-orderable="false"><?=$this->lang->line('ib_status')?></th>
									<th data-orderable="false"><?=$this->lang->line('action')?></th>
								</tr>

								</thead>

								<tbody id="registerAccountBody">

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

<div class="modal fade" id="accountListDetails" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">More Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal"></button>
			</div>

			<table class="table mb-0" id="accountDetailsTable">

			</table>
		</div>
	</div>
</div>


<div class="modal fade bs-example-modal-lg" id="kycNodal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Uploaded Kyc (Documents)</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeKycModal"></button>
			</div>
			<div class="modal-body">
				<form method="post" class="uploadedKycForm" autocomplete="off" action="<?php echo base_url(); ?>uploaded-kyc" id="uploadedKycForm" enctype="multipart/form-data" data-key-index="">
					<input type="hidden" value="" name="register_id" id="userSelectedId">
					<input type="hidden" value="" name="specific_user_id" id="userUniqueKey">
					<input type="hidden" value="1" name="edit_from_admin">
					<div class="wrapping">
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Identity proof</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="identity_proof" name="identity_proof" required>
							</div>
						</div>
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Residency proof (Front)</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="resedency_proof" name="resedency_proof" required>
							</div>
						</div>

						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Residency proof (Back)</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="resedency_proof_back" name="resedency_proof_back" required>
							</div>
						</div>

						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Profile Image</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="profile_mage" name="profile_image" accept="image/*">
							</div>
						</div>

					</div>
					<div class="row" style="width: 280px; margin-left: 127px;margin-top: 25px;">
						<span id="successMessage-kyc" style="color:green;"></span>
						<button class="btn btn-primary" type="submit" id="uploadKycBtn">Submit form</button>
					</div>
				</form>

				<div class="row" style="margin-left: 106px; margin-top: 14px; text-align: left;position: relative" id="profileImageBlock">

				</div>

				<div class="row" style="margin-left: 106px; margin-top: 14px; text-align: left;position: relative" id="userProfileImageContent">

				</div>

				<table class="table mb-0 document-table">
					<thead>
					<tr>
						<th>Document Name</th>
						<th>Attachment</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody id="kycModalCOntent">

					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>

<script>
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
				"url": '<?php echo base_url();?>admin/account/get-registered-data',
				"type": "GET"
			},
			"columns": [
				{ "data": "id", "name": "id", "searchable": false, "orderable": false },
				{ "data": "formatted_created_datetime" },
				{
					"data": "id",
					"render": function (data, type, row, meta) {
						return '<a class="btn btn-outline-secondary btn-sm edit" title="Edit" data-userid="'+row.id+'" data-parentid="'+row.unique_id+'" data-account-id="'+data+'" id="addKyc"><i class=" fas fa-user-edit"></i></a>';
					}
				},
				{
					"data": "full_name",
					"render": function (data, type, row, meta) {
						//let fName= '<a href="set-ib-for-parent-user?userId='+row.unique_id+'>'+data+'</a>';
						let fName='<a href="<?php echo base_url(); ?>set-ib-for-parent-user?userId='+row.unique_id+'">'+data+'</a>';
					  return fName;
					}
				},
				{ "data": "email" },
				{ "data": "mobile"},
				{ "data": "nicename"},
				// {
				// 	"data": "totalPayment",
				// 	"render": function (data, type, row, meta) {
				// 		let totalBalace=data??0;
				// 		return '$'+totalBalace;
				// 	}
				// },
				{
					"data": "id",
					"render": function (data, type, row, meta) {
						let htmlBasic='<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="" data-key-index="" data-unique-id="" data-ib-status="">IN-ACTIVE</button>';
						if (row.ib_status==1){
							htmlBasic='<button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="" data-key-index="" data-unique-id="" data-ib-status="">ACTIVE</button>';
						}
						return htmlBasic;
					}
				},
				{
					"data": "id",
					"render": function (data, type, row, meta) {
						let htmlBasic='<a style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" title="User Details" href="javascript:void(0)" id="VieUserDetails" data-parentid="'+row.parent_id+'" data-account-id="'+row.user_id+'"><i class="fas fa-align-left"></i></a>' +
							'<a style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?php echo base_url(); ?>login-user-profile?userId='+row.unique_id+'"><i class="fas fa-eye"></i></a>' +
							'<a  style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" title="Edit" href="<?php echo base_url(); ?>edit-user-profile?userId='+row.unique_id+'"><i class=" fas fa-edit"></i></a>';

							if (row.is_deleted==0){
								htmlBasic+='<a style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" href="<?php echo base_url(); ?>activate-account?userId='+row.unique_id+'"title="Delete"><i class="fas fa-trash-alt"></i></a>';
							}else {
								htmlBasic+='<a style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" href="<?php echo base_url(); ?>activate-account?userId='+row.unique_id+'"title="Activate"><i class="fas fa-check"></i>';
							}

						// Assuming you want to add a link to the last column
						htmlBasic+='<a  style="margin-right:5px" class="btn btn-outline-secondary btn-sm edit" title="Resend Register Email" href="<?php echo base_url(); ?>resend-email?userId='+row.unique_id+'"><i class="fas fa-undo-alt"></i></a>';
						return htmlBasic;
					}
				}
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html(index + 1);
			},
			"searching": true // Enable searching
		});

		// Your form submission code
		$(document).on('click', 'input#dateFilterFormBtn', function () {
			// Get start and end dates from form inputs
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();

			if (startDate && endDate){
				$('#startDate').removeClass('error');
				$('#endDate').removeClass('error');
				// Update DataTable parameters
				dataTable.ajax.url('<?php echo base_url();?>admin/account/get-registered-data?startDate=' + startDate + '&endDate=' + endDate).load();
			}else {
				$('#startDate').addClass('error');
				$('#endDate').addClass('error');
			}
		});

		$(document).on('click', 'input#resetBtn', function () {
			// Get start and end dates from form inputs
			 $('#startDate').val('');
			 $('#endDate').val('');

			$('#startDate').removeClass('error');
			$('#endDate').removeClass('error');
			dataTable.ajax.url('<?php echo base_url();?>admin/account/get-registered-data').load();
		});


		// Event listener on the dropdown
		$('#validationCustom04').on('change', function() {
			var selectedDate = $(this).val();
			// Trigger DataTable search with the selected date
			dataTable.search(selectedDate).draw();
		});

		// Account Live Details
		$(document).on('click', 'a#VieUserDetails', function () {

			var accountId		=$(this).data('account-id');
			var parentid		=$(this).data('parentid');

			let loader=`<div class="loading-data">
					<span class="loader"></span>
				</div>`;

			$('#kycModalCOntent').html(loader);
			$('#accountListDetails').modal('show');

			var url = "<?php echo base_url(); ?>admin/account/get-registered-user-restall-data";
			var post_data = {
				'accountId': accountId,
				'parent_id': parentid,
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};

			$.ajax({
				url : url,
				type : 'POST',
				data: post_data,
				success : function(response)
				{
					var obj = JSON.parse(response);
					if (response!=0){
						let html=`<tbody>
									<tr>
										<th scope="row">Wallet Balance :</th>
										<td>$${obj?.totalBalanceMt5??0}</td>
										<th scope="row">Parent IB Name :</th>
										<td>${obj.parentIbName}</td>
									</tr>
									</tbody>`;

						$('#accountDetailsTable').html(html)
					}
				}
			});
		});


		$(document).on('click', 'a#addKyc', function () {

			var accountId		=$(this).data('account-id');
			var parentid		=$(this).data('parentid');
			var userid		=$(this).data('userid');

			$('input#userSelectedId').val(userid);
			$('input#userUniqueKey').val(parentid);

			$('#kycNodal').modal('show');

			var url = "<?php echo base_url(); ?>admin/account/get-kyc-data";
			var post_data = {
				'accountId': accountId,
				'parent_id': parentid,
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};

			$.ajax({
				url : url,
				type : 'POST',
				data: post_data,
				success : function(response)
				{
					var obj = JSON.parse(response);
				//	console.log(response);
					if (response!=0){
						let profileImageBlock=`<p>** Uploaded Only JPG,JPEG,PNG,PDF OR GIF</p>
											<p>** Maximum Size 5MB</p>
											<img src="${obj.profile_proof}" class="profile-image" id="profileImageSrc">`;

						$('div#profileImageBlock').html(profileImageBlock);

						let html=`	<tr>
									<th scope="row">Identity proof</th>
									<td id="identityProofAttachment">`;
									if (obj.identity_proof){
										html+=`<a href="${obj.identity_proof}" target="_blank"><img src="${obj.identity_proof}" alt="logo-light" height="40"></a>`;
									}else {
										html+=`N/A`;
									}
									html+=`</td>
									<td id="identityMarkStatus" style="vertical-align: middle;">`;
										if (obj.identity_proof_status==1 && obj.identity_proof){
											html+=`<span class="badge rounded-pill bg-success">Verified</span>`;
										}else if (obj.identity_proof_status==2){
											html+=`<span class="badge rounded-pill bg-danger">Rejected</span>`;
										}else if (obj.identity_proof) {
											html+=`<span class="badge rounded-pill bg-dark">Pending</span>`;
										}else {
											html+=`N/A`;
										}
								html+=`</td>
										<td id="markIdentityVerifiedTd" style="vertical-align: middle;">`;
										if (obj.identity_proof){
											html+=`<button type="button" class="btn btn-danger btn-sm waves-effect waves-danger" style="margin-right: 6px;" id="markIdentityRejected" data-key-index="1" data-unique-id="${parentid}">Rejected</button>`;
											html+=`<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityVerified" data-key-index="1" data-unique-id="${parentid}">Mark Verified</button></td>`;
										}else {
											html+=`N/A`;
										}
								html+=`</tr>
								<tr>
									<th scope="row">Residency proof (Front)</th>
									<td id="resiDencyAttachmentProof">`;
									if (obj.residency_proof){
										html+=`<a href="${obj.residency_proof}" target="_blank"><img src="${obj.residency_proof}" alt="logo-light" height="40"></a>`;
									}else {
										html+=`N/A`;
									}

									html+=`</td>
									<td id="resedencyMarkStatus" style="vertical-align: middle;">`;
									if (obj.residency_proof_status==1 && obj.residency_proof){
										html+=`<span class="badge rounded-pill bg-success">Verified</span>`;
									}else if (obj.residency_proof_status==2){
										html+=`<span class="badge rounded-pill bg-danger">Rejected</span>`;
									}else if (obj.residency_proof) {
										html+=`<span class="badge rounded-pill bg-dark">Pending</span>`;
									}else {
										html+=`N/A`;
									}
									html+=`</td><td id="markResidencyVerifiedTd" style="vertical-align: middle;">`;
										if (obj.residency_proof){
											html+=`<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" style="margin-right: 6px;" id="markResidencyRejected" data-key-index="" data-unique-id="${obj.unique_id}">Rejected</button>`;
											html+=`<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerified" data-key-index="" data-unique-id="${obj.unique_id}">Mark Verified</button>`;
										}else {
											html+=`N/A`;
										}
									html+=`</td></tr>`;

										html+=`<tr>
											<th scope="row">Residency proof (Back)</th>
											<td id="resiDencyAttachmentProofBack">`;
											if (obj.residency_proof_back){
												html+=`<a href="${obj.residency_proof_back}" target="_blank"><img src="${obj.residency_proof_back}" alt="logo-light" height="40"></a>`;
											}else {
												html+=`N/A`;
											}

											html+=`</td>
														<td id="resedencyMarkStatusBack" style="vertical-align: middle;">`;
											if (obj.residency_proof_status_back==1 && obj.residency_proof_back){
												html+=`<span class="badge rounded-pill bg-success">Verified</span>`;
											}else if (obj.residency_proof_status_back==2){
												html+=`<span class="badge rounded-pill bg-danger">Rejected</span>`;
											}else if (obj.residency_proof_back) {
												html+=`<span class="badge rounded-pill bg-dark">Pending</span>`;
											}else {
												html+=`N/A`;
											}
											html+=`</td><td id="markResidencyVerifiedTdBack" style="vertical-align: middle;">`;
											if (obj.residency_proof_back){
												html+=`<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" style="margin-right: 6px;" id="markResidencyRejectedBack" data-key-index="" data-unique-id="${obj.unique_id}">Rejected</button>`;
												html+=`<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerifiedBack" data-key-index="" data-unique-id="${obj.unique_id}">Mark Verified</button>`;
											}else {
												html+=`N/A`;
											}
											html+=`</td></tr>`;

						$('#kycModalCOntent').html(html)
					}
				}
			});
		});

	});

	//mensTakenoptions
	$(document).on('click', 'a#exportCsv', function () {
		// var startUp         	  	=$('select#coderNumber').find(':selected').attr('datastartUp');
		// var dataEndOFCode          	=$('select#coderNumber').find(':selected').attr('dataEndOFCode');
		// var prefix					=$('select#coderNumber').val();
		// var customer					=$('select#customer').val();
		var startDate					=$('#startDate').val()||'';
		var endDate					=$('#endDate').val()||'';

		var url ="<?php echo base_url('admin/account/export-csv-account'); ?>"+'?startDate='+startDate+'&endDate='+endDate+'';
		window.location.href =url;
	});

	$("form#uploadedKycForm").submit(function(e) {
		e.preventDefault();
		var currentIndex=0;

		var xhr = new XMLHttpRequest()

		var formData = new FormData($(this)[0]);
		formData.append('file',xhr.file);

		console.log(formData);

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			processData: false,
			contentType: false,
			cache: false,
			data: formData, // serializes the form's elements.
			success: function(response)
			{
				if (response){
					var obj = JSON.parse(response);
					$('button#uploadKycBtn').html("Update");
					$('span#successMessage-kyc').html('Successfully Save identity and residency proof');
					$('td#identityProofAttachment').html('<a href="'+obj.identity_proof+'" target="_blank"><img src="'+obj.identity_proof+'" alt="logo-light" height="40"></a>');
					$('td#resiDencyAttachmentProof').html('<a href="'+obj.residency_proof+'" target="_blank"><img src="'+obj.residency_proof+'" alt="logo-light" height="40"></a>');
					$('td#resiDencyAttachmentProofBack').html('<a href="'+obj.residency_proof_back+'" target="_blank"><img src="'+obj.residency_proof_back+'" alt="logo-light" height="40"></a>');

					$('td#markResidencyVerifiedTd').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');
					$('td#markResidencyVerifiedTdBack').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerifiedBack" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');
					$('td#markIdentityVerifiedTd').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');

					if (obj.profile_proof) {
						$('#profileImageSrc').attr('src', obj.profile_proof);
					}

					setTimeout(function() {
						$('form#uploadedKycForm').trigger("reset");
						$('span#successMessage-kyc').html('');
						$('button#closeKycModal').trigger('click');
					}, 2000);

				}
			},error: function (){
				alert("Something went wrong");
				$('button#uploadKycBtn').html("Update");
			},
			beforeSend: function (xhr){
				$('button#uploadKycBtn').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$(document).on('click', 'button#markResidencyRejectedBack', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-back-part-attachment-verified";
		var post_data = {
			'userid': userid,
			'type':2,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-danger">Rejected</span>';
					$('td#resedencyMarkStatusBack').html(html);
				}
			}
		});

	});

	$(document).on('click', 'button#markResidencyRejected', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified";
		var post_data = {
			'userid': userid,
			'type':2,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-danger">Rejected</span>';
					$('td#resedencyMarkStatus').html(html);
				}
			}
		});

	});


	$(document).on('click', 'button#markResidencyVerifiedBack', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-back-part-attachment-verified";
		var post_data = {
			'userid': userid,
			'type':1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#resedencyMarkStatusBack').html(html);
				}
			}
		});

	});


	$(document).on('click', 'button#markResidencyVerifiedBack', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified-back";
		var post_data = {
			'userid': userid,
			'type':1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#resedencyMarkStatus').html(html);
				}
			}
		});

	});

	$(document).on('click', 'button#markResidencyVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified";
		var post_data = {
			'userid': userid,
			'type':1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#resedencyMarkStatus').html(html);
				}
			}
		});

	});

	$(document).on('click', 'button#markIdentityRejected', function () {

		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 2,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){

					var html='<span class="badge rounded-pill bg-danger">Rejected</span>';
					$('td#identityMarkStatus').html(html);
				}
			}
		});
	});

	$(document).on('click', 'button#markIdentityVerified', function () {

		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#identityMarkStatus').html(html);

					//$('td#markIdentityVerifiedTd').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityRejected" data-key-index="1" data-unique-id="'+userid+'">Rejected</button>');
				}
			}
		});
	});

	$(document).on('click', 'button#changeIbStatus', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');
		var ibStatus		=$(this).data('ib-status')||0;

		var url = "<?php echo base_url(); ?>change-ib-status";
		var post_data = {
			'userid': userid,
			'ib_status': ibStatus,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (Number(response)===1){
					var html		='<button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="'+currentIndex+'" data-unique-id="'+userid+'" data-ib-status="1">Make IB</button>';
					$('td#changeIbStatus').html(html);
				}else{
					var html		='<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="'+currentIndex+'" data-unique-id="'+userid+'" data-ib-status="0">Make IB</button>';
					$('td#changeIbStatus').html(html);
				}
			}
		});
	});

</script>
