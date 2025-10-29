<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];
$getUserData = $this->db->query("SELECT* FROM users u INNER JOIN country c ON u.country_id = c.id where u.role=1 and u.user_id=".$_SESSION['user_id']." ORDER BY u.user_id DESC")->result();
$checkKycAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$unique_id."'")->row();

?>
<style>
	.bg-danger {
		background-color: #ff3d60!important;
		width: 43%;
		padding: 8px;
	}
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">Kyc</h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Kyc</li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">

							<?php  if (empty($checkKycAttachment)){ ?>
							<div>
								<a class="btn btn-success mb-2" title="Edit" data-bs-toggle="modal" data-bs-target="#addKyC-0">
									<i class="mdi mdi-plus me-2"></i> Add Kyc
								</a>
							</div>
							<?php } ?>

							<div class="table-responsive mt-3">
								<table class="table table-centered datatable dt-responsive nowrap "
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead class="thead-light">
									<tr>
									<tr>
										<th>Reg. Date & Time</th>
										<th>Name</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>Country</th>
										<th>Wallet Balance</th>
										<th>Manager Name</th>
										<th style="text-align: center">KYC</th>
									</tr>
									</tr>
									</thead>
									<tbody>
									<?php
									if ($getUserData){
										foreach ($getUserData as $key=>$item){
											$createdDate = $item->created_datetime ;
											$datetime = new DateTime($createdDate);
											$date = $datetime->format('m/d/Y');
											$time = $datetime->format('H:i:s');

											$fullName=$item->first_name.$item->last_name;

											$getAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$item->unique_id."'")->row();

											$identity_proof='';
											$residency_proof='';
											$identity_proof_status='';
											$residency_proof_status='';

											if (!empty($getAttachment)){
												$identity_proof				=base_url()."assets/users/kyc/".$item->unique_id.'/'.$getAttachment->identity_proof;
												$residency_proof			=base_url()."assets/users/kyc/".$item->unique_id.'/'.$getAttachment->residency_proof;
												$identity_proof_status		=$getAttachment->identity_verified_status;
												$residency_proof_status		=$getAttachment->residency_verified_status;
											}

											if ($getAttachment){
											?>
											<tr>
												<td><?=$date.'@'.$time;?></td>
												<td><a href="javascript: void(0);" class="text-dark fw-bold"><?=$fullName?></a> </td>
												<td><?=$item->email?></td>
												<td>
													<?=$item->mobile?>
												</td>
												<td>
													<div class="badge badge-soft-success font-size-12"><?=$item->nicename?></div>
												</td>
												<td>
													$<?=$item->wallet_balance?>
												</td>
												<td id="managerNameTd-<?=$key?>">
													<?php
													if ($item->manager_name){
														echo '<span class="badge rounded-pill bg-success">'.$item->manager_name.'</span>';
													} ?>
												</td>
												<td style="text-align: center">
													<a class="btn btn-outline-secondary btn-sm edit" title="Edit" data-bs-toggle="modal" data-bs-target="#addKyC-<?=$key?>">
														<i class=" fas fa-user-edit"></i>
													</a>
													<div class="modal fade bs-example-modal-lg" id="addKyC-<?=$key?>" aria-hidden="true" aria-labelledby="..." tabindex="-1">
														<div class="modal-dialog modal-lg modal-dialog-centered">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title">Uploaded Kyc (Documents)</h5>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeKycModal-<?=$key?>"></button>
																</div>
																<div class="modal-body">
																	<form method="post" class="uploadedKycForm-<?=$key?>" autocomplete="off" action="<?php echo base_url(); ?>uploaded-kyc" id="uploadedKycForm" enctype="multipart/form-data" data-key-index="<?=$key?>">
																		<input type="hidden" value="<?=$item->user_id?>" name="register_id">
																		<input type="hidden" value="<?=$item->unique_id?>" name="specific_user_id">
																		<div class="wrapping">
																			<div class="row mb-3">
																				<label for="example-text-input" class="col-sm-2 col-form-label">Identity proof</label>
																				<div class="col-sm-10">
																					<input type="file" class="form-control" id="identity_proof" name="identity_proof" required>
																				</div>
																			</div>
																			<div class="row mb-3">
																				<label for="example-text-input" class="col-sm-2 col-form-label">Residency proof</label>
																				<div class="col-sm-10">
																					<input type="file" class="form-control" id="resedency_proof" name="resedency_proof" required>
																				</div>
																			</div>
																		</div>
																		<div class="row" style="margin-left: 127px;margin-top: 25px;">
																			<span id="successMessage-kyc-<?=$key?>" style="color:green;"></span>
																			<button class="btn btn-primary" type="submit" id="uploadKycBtn-<?=$key?>" style="width: 140px;">Update</button>
																		</div>
																	</form>

																	<div class="row" style="margin-left: 106px; margin-top: 14px; text-align: left;">
																		<p>** Uploaded Only JPG,JPEG,PNG,PDF OR GIF</p>
																		<p>** Maximum Size 5MB</p>
																	</div>

																	<table class="table mb-0 document-table">
																		<thead>
																		<tr>
																			<th>Document Name</th>
																			<th>Attachment</th>
																			<th>Status</th>
																		</tr>
																		</thead>
																		<tbody>
																		<tr>
																			<th scope="row">Identity proof</th>
																			<td id="identityProofAttachment-<?=$key?>">
																				<?php  if ($identity_proof){ ?>
																					<a href="<?php echo $identity_proof;?>" target="_blank"><img src="<?php echo $identity_proof;?>" alt="logo-light" height="40"></a>
																				<?php } ?>
																			</td>
																			<td id="markIdentityVerifiedTd-<?=$key?>" style="vertical-align: middle;">
																				<?php
																				if ($identity_proof_status && $identity_proof){
																					echo '<span class="badge rounded-pill bg-success">Verified</span>';
																				}else{ if ($identity_proof){
																					echo '<span class="badge rounded-pill bg-danger">Pending</span>';
																				} } ?>
																			</td>
																		</tr>
																		<tr>
																			<th scope="row">Residency proof</th>
																			<td id="resiDencyAttachmentProof-<?=$key?>">
																				<?php  if ($residency_proof){ ?>
																					<a href="<?php echo $residency_proof;?>" target="_blank"><img src="<?php echo $residency_proof;?>" alt="logo-light" height="40"></a>
																				<?php } ?>
																			</td>
																			<td id="markResidencyVerifiedTd-<?=$key?>" style="vertical-align: middle;">
																				<?php
																				if ($residency_proof_status && $residency_proof){
																					echo '<span class="badge rounded-pill bg-success">Verified</span>';
																				}else{
																					if ($residency_proof){
																						echo '<span class="badge rounded-pill bg-danger">Pending</span>';
																						?>
																				<?php } } ?>
																			</td>
																		</tr>
																		</tbody>
																	</table>

																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php } } } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->


<!-- Create Kyc-->
<div class="modal fade bs-example-modal-lg" id="addKyC-0" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Uploaded Kyc (Documents)</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeKycModal-0"></button>
			</div>
			<div class="modal-body">
				<form method="post" class="uploadedKycForm-0" autocomplete="off" action="<?php echo base_url(); ?>uploaded-kyc" id="createKycForm" enctype="multipart/form-data" data-key-index="0">

					<input type="hidden" value="<?=$_SESSION['user_id']?>" name="register_id">
					<input type="hidden" value="<?=$unique_id?>" name="specific_user_id">
					<div class="wrapping">
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Identity proof</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="identity_proof" name="identity_proof" required>
							</div>
						</div>
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label">Residency proof</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="resedency_proof" name="resedency_proof" required>
							</div>
						</div>
					</div>
					<div class="row" style="margin-left: 127px;margin-top: 25px;">
						<span id="successMessage-kyc-0" style="color:green;"></span>
						<button class="btn btn-primary" type="submit" id="uploadKycBtn-0" style="width: 120px;">Submit form</button>
					</div>
				</form>

				<div class="row" style="margin-left: 106px; margin-top: 14px; text-align: left;">
					<p>** Uploaded Only JPG,JPEG,PNG,PDF OR GIF</p>
					<p>** Maximum Size 5MB</p>
				</div>
			</div>
		</div>
	</div>
</div>


<script>

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

					$('td#markResidencyVerifiedTd-'+currentIndex+'').html('<span class="badge rounded-pill bg-danger">Pending</span>');
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html('<span class="badge rounded-pill bg-danger">Pending</span>');

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

	$("form#createKycForm").submit(function(e) {
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
					$('button#uploadKycBtn-'+currentIndex+'').html("Submit");
					$('span#successMessage-kyc-'+currentIndex+'').html('Successfully Save identity and residency proof');

					setTimeout(function() {
						$('form#uploadedKycForm').trigger("reset");
						$('span#successMessage-kyc-'+currentIndex+'').html('');
						$('button#closeKycModal-'+currentIndex+'').trigger('click');
						location.reload();
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
</script>
