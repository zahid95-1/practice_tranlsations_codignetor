<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_new_user'])){
	$errorObject	=json_decode($_SESSION['error_new_user']);
}

?>

<?php

$getNotificationCnt = $this->db->query("SELECT count(1) as cnt FROM users where notification = 0;
")->row();

if($getNotificationCnt->cnt > 0){
	$this->db->query("update users u set notification = 1 where notification = 0");	
}
/*===================GetUserInfo=========================*/
$where = " WHERE  1 = 1 ";
if(isset($_REQUEST['username']) || isset($_REQUEST['date'])){
	$date = $this->input->post('date');
	$username = $this->input->post('username');

	if($this->input->post('date'))
	$where .= " and DATE(created_datetime) like '%$date%'";

	if($this->input->post('username'))
	$where .= " and CONCAT (first_name,' ',last_name) LIKE '%$username%' OR first_name LIKE '%$username%' OR last_name LIKE '%$username%'" ;

	if (ConfigData['prefix']=="IGM" || ConfigData['prefix']=='UFX' || ConfigData['prefix']=='TG'){
		$getUserData = $this->db->query("SELECT user_id,unique_id,parent_id,wallet_balance,manager_name,username,first_name,last_name,email, role,mobile,gender,name,nicename,created_datetime,ib_status,is_deleted FROM `MDV_Registered_Account` $where")->result();
	}else{
		$getUserData = $this->db->query("SELECT user_id,unique_id,parent_id,wallet_balance,manager_name,username,first_name,last_name,email, role,mobile,gender,name,nicename,created_datetime,ib_status,is_deleted FROM `MDV_Registered_Account` $where LIMIT 10")->result();
	}

}else{
	if (ConfigData['prefix']=="IGM" || ConfigData['prefix']=='UFX' || ConfigData['prefix']=='TG'){
		$getUserData = $this->db->query("SELECT user_id,unique_id,parent_id,wallet_balance,manager_name,username,first_name,last_name,email, role,mobile,gender,name,nicename,created_datetime,ib_status,is_deleted FROM `MDV_Registered_Account` $where")->result();
	}else{
		$getUserData = $this->db->query("SELECT user_id,unique_id,parent_id,wallet_balance,manager_name,username,first_name,last_name,email, role,mobile,gender,name,nicename,created_datetime,ib_status,is_deleted FROM `MDV_Registered_Account` $where LIMIT 10")->result();
	}
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
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">Registered Account</h4>
							<br>
							<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p> 
							<form class="form-control" action="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>" method="post">
							    <div class="row">
							        <div class="col-sm-2">
							            <input class="form-control" type="text" onfocus="(this.type='date')" name="date" id="date" value="<?php if(isset($_POST['date'])){ echo $_POST['date']; }?>" placeholder="Registered date">
							        </div>
							        <div class="col-sm-2">
							            <input class="form-control" type="text" name="username" id="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; }?>" placeholder="Name">
							        </div>
							        <div class="col-sm-1">
							            <input class="form-control btn-primary" type="submit" name="search" id="search" value="Search">
							        </div>
							    </div>
							</form>
							<br>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr >
									<th >Sr.no</th>
								    <th >Reg. Date & Time</th>
									<th  style="text-align: center">KYC</th>
									<th >Name</th>
									<th >Email</th>
									<th >Mobile</th>
									<th >Country</th>
									<th >Deposit Balance</th>
									<th >Wallet Balance</th>
									<th >Manager Name</th>
									<th >IB Name</th>
									
									<th data-orderable="false">IB Status</th>
									<th data-orderable="false">Action</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if ($getUserData){
									$c = 0;
								foreach ($getUserData as $key=>$item){
									$c++;
								$userId=$item->user_id;
								$balanceMt5	   =$this->db->query("SELECT SUM(balance) as totalBalanceMt5  FROM `trading_accounts` WHERE `user_id` = $userId")->row();

								$createdDate = $item->created_datetime ;
								$datetime = new DateTime($createdDate);
								$date = $datetime->format('m/d/Y');
								$time = $datetime->format('H:i:s');
								$fullName=$item->first_name.' '.$item->last_name;
								$getIbUser=$this->db->query("SELECT* FROM users where unique_id='".$item->parent_id."'")->row();
								$firstName='';
								if ($getIbUser){
									$firstName=$getIbUser->first_name;
								}
								$getAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$item->unique_id."'")->row();

								$identity_proof='';
								$residency_proof='';
								$identity_proof_status='';
								$residency_proof_status='';
								$profile_proof=base_url() .'assets/images/users/avatar-1.jpg';

								if (!empty($getAttachment)){
									$identity_proof				=base_url()."assets/users/kyc/".$item->unique_id.'/'.$getAttachment->identity_proof;
									$residency_proof			=base_url()."assets/users/kyc/".$item->unique_id.'/'.$getAttachment->residency_proof;
									$identity_proof_status		=$getAttachment->identity_verified_status;
									$residency_proof_status		=$getAttachment->residency_verified_status;

									if ($getAttachment->profile_image){
										$profile_proof = base_url() . "assets/users/kyc/" .$item->unique_id. '/' . $getAttachment->profile_image;
									}
								}

								$getTotalBalance	   =$this->db->query("SELECT SUM(entered_amount) as totalPayment  FROM `payments` WHERE `user_id` = $userId")->row();
								?>
								<tr>
									<td ><?php echo $c; ?></td>
								    <td><?=$date.'@'.$time;?></td>
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
															<input type="hidden" value="1" name="edit_from_admin">
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

																<div class="row mb-3">
																	<label for="example-text-input" class="col-sm-2 col-form-label">Profile Image</label>
																	<div class="col-sm-10">
																		<input type="file" class="form-control" id="profile_mage" name="profile_image" accept="image/*">
																	</div>
																</div>

															</div>
															<div class="row" style="width: 120px; margin-left: 127px;margin-top: 25px;">
																<span id="successMessage-kyc-<?=$key?>" style="color:green;"></span>
																<button class="btn btn-primary" type="submit" id="uploadKycBtn-<?=$key?>">Submit form</button>
															</div>
														</form>

														<div class="row" style="margin-left: 106px; margin-top: 14px; text-align: left;position: relative">
															<p>** Uploaded Only JPG,JPEG,PNG,PDF OR GIF</p>
															<p>** Maximum Size 5MB</p>
															<img src="<?=$profile_proof?>" class="profile-image" id="profileImageSrc_<?=$key?>">
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
																	}else{ if ($identity_proof){ ?>
																	<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityVerified" data-key-index="<?=$key?>" data-unique-id="<?=$item->unique_id?>">Mark Verified</button>
																     <?php } } ?>
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
																		?>
																	<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerified" data-key-index="<?=$key?>" data-unique-id="<?=$item->unique_id?>">Mark Verified</button>
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
									<td><a href="javascript: void(0);" class="text-dark fw-bold"><?=$fullName?></a> </td>
									
									<td><?=$item->email?></td>
									<td>
										<?=$item->mobile?>
									</td>
									<td>
										<div class="badge badge-soft-success font-size-12"><?=$item->nicename?></div>
									</td>
									<td>
										$<?=($getTotalBalance->totalPayment)?$getTotalBalance->totalPayment:'0.00'?>
									</td>
									<td>
										$<?=($balanceMt5->totalBalanceMt5)?$balanceMt5->totalBalanceMt5:'0.00'?>
									</td>
									<td id="managerNameTd-<?=$key?>">
										<?php
										if ($item->manager_name){
											echo '<span class="badge rounded-pill bg-success">'.$item->manager_name.'</span>';
										}else{
										?>
										<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addManagerModal-<?=$key?>">Add Manager</button>
										<!-- First modal dialog -->
										<div class="modal fade" id="addManagerModal-<?=$key?>" aria-hidden="true" aria-labelledby="..." tabindex="-1">
											<div class="modal-dialog modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Assign Manager</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
													</div>
													<form action="<?php echo base_url(); ?>add-manager" method="post" id="add_manager_event" class="custom-validation" data-key-index="<?=$key?>">
														<input type="hidden" value="<?=$item->user_id?>" name="register_id">
														<div class="modal-body">
															<div class="mb-3">
																<label>Select Manager Name</label>
																<select name="manager_name" class="form-control" id="manager_name" required>
																	<option value="">Select Manager Name</option>
																	<option value="vishal">Vishal</option>
																	<option value="tanvir">Tanvir</option>
																	<option value="kaveya">Kaveya</option>
																</select>
																<span style="color: red" id="managerNameError"></span>
															</div>
															<span id="successMessage-<?=$key?>" style="color:green;"></span>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary" id="add_manager_<?=$key?>">Update</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<?php } ?>
									</td>
									<td>
										<?=$firstName?>
									</td>
									

									<td id="changeIbStatus-<?=$key?>">
										<?php
										if ($item->ib_status==1){
										?>
										<button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="<?=$key?>" data-unique-id="<?=$item->unique_id?>" data-ib-status="<?=$item->ib_status?>">Make IB</button>
										<?php }else{ ?>
											<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="<?=$key?>" data-unique-id="<?=$item->unique_id?>" data-ib-status="<?=$item->ib_status?>">Make IB</button>
										<?php } ?>
											</td>

									<td>
										<a class="btn btn-outline-secondary btn-sm edit" title="Edit" href="<?php echo base_url(); ?>edit-user-profile?userId=<?=$item->unique_id?>">
											<i class=" fas fa-edit"></i>
										</a>

										<a class="btn btn-outline-secondary btn-sm edit" title="User Details">
											<i class="fas fa-align-left"></i>
										</a>

										<a class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?php echo base_url(); ?>login-user-profile?userId=<?=$item->unique_id?>">
											<i class="fas fa-eye"></i>
										</a>

									<?php if($item->is_deleted== 0)
									{ ?>
										<a class="btn btn-outline-secondary btn-sm edit" href="<?php echo base_url(); ?>activate-account?userId=<?=$item->unique_id?>"title="Delete">
											<i class="fas fa-trash-alt"></i>
										</a>
									<?php }else{ ?>
											<a class="btn btn-outline-secondary btn-sm edit" href="<?php echo base_url(); ?>activate-account?userId=<?=$item->unique_id?>"title="Activate">
											<i class="fas fa-check"></i>
										</a>
									<?php } ?>

									</td>
								</tr>
								<?php } } ?>
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

					if (obj.profile_proof) {
						$('#profileImageSrc_' + currentIndex + '').attr('src', obj.profile_proof);
					}

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
