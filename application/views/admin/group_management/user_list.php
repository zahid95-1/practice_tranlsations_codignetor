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
	.page-content {
		/*padding: calc(20px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">.
			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
											<p class="text-truncate font-size-14 mb-2">Total Clients</p>
											<h4 class="mb-0">0</h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-stack-line font-size-24"></i>
										</div>
									</div>
								</div>

								<div class="card-body border-top py-3">
									<div class="text-truncate">
                                    <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i>
                                        2.4% </span>
										<span class="text-muted ms-2">From previous period</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
											<p class="text-truncate font-size-14 mb-2">Total Deposit</p>
											<h4 class="mb-0">$ 0</h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-store-2-line font-size-24"></i>
										</div>
									</div>
								</div>
								<div class="card-body border-top py-3">
									<div class="text-truncate">
                                    <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i>
                                        2.4% </span>
										<span class="text-muted ms-2">From previous period</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
											<p class="text-truncate font-size-14 mb-2">Total Lot</p>
											<h4 class="mb-0">$ 0</h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-briefcase-4-line font-size-24"></i>
										</div>
									</div>
								</div>
								<div class="card-body border-top py-3">
									<div class="text-truncate">
                                    <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i>
                                        2.4% </span>
										<span class="text-muted ms-2">From previous period</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- end row -->
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4">USER List</h4>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>User ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Group</th>
									<th>Country</th>
									<th>JoinDate</th>
									<th>Balance</th>
								</tr>
								</thead>
								<tbody>
								<?php if ($dataItem): foreach ($dataItem as $key=>$item): ?>
								<tr>
									<td><?=$item->unique_id?></td>
									<td><?=$item->first_name.' '.$item->last_name?></td>
									<td><?=$item->email?></td>
									<td><?=$item->mobile?></td>
									<td><?=$item->group_name?></td>
									<td><?=$item->country_name?></td>
									<td><?=$item->created_datetime?></td>
									<td>$0.00</td>
								</tr>
								<?php endforeach; endif;?>

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

	$("form#add_manager_event").submit(function(e) {
		e.preventDefault();

		var currentIndex=$(this).data('key-index');

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('#add_manager_'+currentIndex+'').html("Update");
					$('span#successMessage-'+currentIndex+'').html('Successfully Add Manager');
					setTimeout(function() {
						$('button#closeModal-'+currentIndex+'').trigger('click');
						$('td#managerNameTd-'+currentIndex+'').html('<span class="badge rounded-pill bg-success">'+response+'</span>');
					}, 2000);
				}
			},error: function (){
				alert("Something went wrong");
				$('#add_manager_'+currentIndex+'').html("Update");
			},
			beforeSend: function (xhr){
				$('#add_manager_'+currentIndex+'').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$("form#uploadedKycForm").submit(function(e) {
		e.preventDefault();
		var currentIndex=$(this).data('key-index');

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
					$('button#uploadKycBtn-'+currentIndex+'').html("Update");
					$('span#successMessage-kyc-'+currentIndex+'').html('Successfully Save identity and residency proof');
					$('td#identityProofAttachment-'+currentIndex+'').html('<a href="'+obj.identity_proof+'" target="_blank"><img src="'+obj.identity_proof+'" alt="logo-light" height="40"></a>');
					$('td#resiDencyAttachmentProof-'+currentIndex+'').html('<a href="'+obj.residency_proof+'" target="_blank"><img src="'+obj.residency_proof+'" alt="logo-light" height="40"></a>');

					$('td#markResidencyVerifiedTd-'+currentIndex+'').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');

					setTimeout(function() {
						$('form#uploadedKycForm').trigger("reset");
						$('span#successMessage-kyc-'+currentIndex+'').html('');
						$('button#closeKycModal-'+currentIndex+'').trigger('click');
					}, 2000);

				}
			},error: function (){
				alert("Something went wrong");
				$('button#uploadKycBtn-'+currentIndex+'').html("Update");
			},
			beforeSend: function (xhr){
				$('button#uploadKycBtn-'+currentIndex+'').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$(document).on('click', 'button#markResidencyVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified";
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
					$('td#markResidencyVerifiedTd-'+currentIndex+'').html(html);
				}
			}
		});

	});

	$(document).on('click', 'button#markIdentityVerified', function () {

		var currentIndex	=$(this).data('key-index');
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
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html(html);
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
					$('td#changeIbStatus-'+currentIndex+'').html(html);
				}else{
					var html		='<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="'+currentIndex+'" data-unique-id="'+userid+'" data-ib-status="0">Make IB</button>';
					$('td#changeIbStatus-'+currentIndex+'').html(html);
				}
			}
		});
	});

</script>
