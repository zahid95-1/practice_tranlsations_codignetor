<?php
/*===================GetUserInfo=========================*/

$getHeader	=$this->input->request_headers();	
if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
	$request= 'api';
	$uid = $getHeader['Authorization'];
	}else{
		$uid = $_SESSION['unique_id'];
		$request = 'web';
	}


$checkKycAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$uid."'")->row();



$identity_proof	=(!empty($checkKycAttachment) && $checkKycAttachment->identity_proof)?base_url()."assets/users/kyc/".$uid.'/'.$checkKycAttachment->identity_proof:'';
$residency_proof=(!empty($checkKycAttachment) &&  $checkKycAttachment->residency_proof)?base_url()."assets/users/kyc/".$uid.'/'.$checkKycAttachment->residency_proof:'';
$residency_proof_back=(!empty($checkKycAttachment) &&  $checkKycAttachment->resedency_proof_back)?base_url()."assets/users/kyc/".$uid.'/'.$checkKycAttachment->resedency_proof_back:'';
$profile_image=(!empty($checkKycAttachment) &&  $checkKycAttachment->profile_image)?base_url()."assets/users/kyc/".$uid.'/'.$checkKycAttachment->profile_image:'';

$identity_proof_status='Pending';
$colorCodeIdentity='gray';
if (!empty($checkKycAttachment) && $checkKycAttachment->identity_verified_status==1){
	$identity_proof_status='Verified';
	$colorCodeIdentity='green';
}elseif(!empty($checkKycAttachment) && $checkKycAttachment->identity_verified_status==2){
	$identity_proof_status='Rejected';
	$colorCodeIdentity='red';
}

$residency_proof_status='Pending';
$residency_proof_back_status='Pending';
$colorCodeResidency='gray';
$colorCodeResidencyBack='gray';
if (!empty($checkKycAttachment) &&  $checkKycAttachment->residency_verified_status==1){
	$residency_proof_status='Verified';
	$colorCodeResidency='green';
}elseif(!empty($checkKycAttachment) &&  $checkKycAttachment->residency_verified_status==2){
	$residency_proof_status='Rejected';
	$colorCodeResidency='red';
}

if(!empty($checkKycAttachment) &&  $checkKycAttachment->residency_proof_back_status==1){
	$residency_proof_back_status='Verified';
	$colorCodeResidencyBack='green';
}elseif(!empty($checkKycAttachment) &&  $checkKycAttachment->residency_proof_back_status==2){
	$residency_proof_back_status='Rejected';
	$colorCodeResidencyBack='red';
}

$data = array('identity_proof' => $identity_proof,
			  'residency_proof' => $residency_proof,
			  'residency_proof_back' => $residency_proof_back,
			  'profile_image' => $profile_image,
			  'identity_proof_status' => $identity_proof_status,
			  'residency_proof_status' => $residency_proof_status,
			  'residency_proof_back_status' => $residency_proof_back_status,
		);

if($request == 'api'){
	$dataItem=array(
							'status'=>200,
							'data'=>$data,
						);
						print_r(json_encode($dataItem,true));
						exit();
}

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
						<h4 class="mb-sm-0"><?= lang('uploaded_kyc_documents') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('kyc') ?></li>
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
							<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>

							<form method="post" class="uploadedKycForm-0" autocomplete="off" action="<?php echo base_url(); ?>uploaded-kyc" id="createKycForm" enctype="multipart/form-data" data-key-index="0">

								<input type="hidden" value="<?=$_SESSION['user_id']?>" name="register_id">
								<input type="hidden" value="<?=$uid?>" name="specific_user_id">
								<div class="wrapping">
									<div class="row mb-3">
										<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('identity_proof') ?></label>
										<div class="col-sm-10">
											<?php
											if ($identity_proof_status=='Verified'){ ?>
												<input type="file" class="form-control" id="" name="" readonly style="pointer-events: none">
											<?php }else{ ?>
												<input type="file" class="form-control" id="identity_proof" name="identity_proof"  accept="image/png">
											<?php } ?>

                                            <?php if (isset($_SESSION['identity_proof_error'])){ ?>
                                                <span style="color: red"><?php echo $_SESSION['identity_proof_error'];?></span>
                                            <?php unset($_SESSION['identity_proof_error']); } ?>
                                        </div>

									</div>
									<div class="row mb-3">
										<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('residency_proof_front') ?></label>
										<div class="col-sm-10">

											<?php
											if ($residency_proof_status=='Verified'){ ?>
												<input type="file" class="form-control" id="" name="" readonly style="pointer-events: none">
											<?php }else{ ?>
												<input type="file" class="form-control" id="resedency_proof" name="resedency_proof"  accept="image/png">
											<?php } ?>

                                            <?php if (isset($_SESSION['resedency_proof_error'])){ ?>
                                                <span style="color: red"><?php echo $_SESSION['resedency_proof_error'];?></span>
                                             <?php unset($_SESSION['resedency_proof_error']); } ?>
                                        </div>
									</div>

									<div class="row mb-3">
										<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('residency_proof_back') ?></label>
										<div class="col-sm-10">

											<?php
											if ($residency_proof_back_status =='Verified'){ ?>
												<input type="file" class="form-control" id="" name="" readonly style="pointer-events: none">
											<?php }else{ ?>
												<input type="file" class="form-control" id="resedency_proof_back" name="resedency_proof_back"  accept="image/png">
											<?php } ?>

											<?php if (isset($_SESSION['resedency_proof_back_error'])){ ?>
												<span style="color: red"><?php echo $_SESSION['resedency_proof_back_error'];?></span>
												<?php unset($_SESSION['resedency_proof_back_error']); } ?>
										</div>
									</div>

									<div class="row mb-3">
										<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('profile_image') ?></label>
										<div class="col-sm-10">
											<input type="file" class="form-control" id="profile_mage" name="profile_image"  accept="image/png">
                                            <?php if (isset($_SESSION['profile_error'])){ ?>
                                                <span style="color: red"><?php echo $_SESSION['profile_error'];?></span>
                                                <?php unset($_SESSION['profile_error']); } ?>
                                        </div>
									</div>
								</div>
								<div class="row" style="margin-left: 260px;margin-top: 25px;">
									<span id="successMessage-kyc-0" style="color:green;"></span>
									<button class="btn btn-primary" type="submit" id="uploadKycBtn-0" style="width: 120px;"><?= lang('submit') ?></button>
								</div>
							</form>

							<div class="row" style="margin-left: 256px; margin-top: 14px; text-align: left;">
								<p>** Uploaded Only JPG,JPEG,PNG,PDF OR GIF</p>
								<p>** Maximum Size 5MB</p>
								<p style="color: red"> * Rejected Attachment need to re-upload for verifications.</p>
							</div>

							<table class="table mb-0 document-table">
								<thead>
								<tr>
									<th><?= lang('identity_proof') ?></th>
									<th><?= lang('status') ?></th>
									<th><?= lang('residency_proof_front') ?></th>
									<th><?= lang('status') ?></th>
									<th><?= lang('residency_proof_back') ?></th>
									<th><?= lang('status') ?></th>
									<th><?= lang('profile_image') ?></th>
								</tr>
								</thead>
								<tbody>
								
								<tr>
									<td>
										<?php if($identity_proof): ?>
											<a href="<?php echo $identity_proof;?>" target="_blank">
												<img src="<?php echo $identity_proof;?>" alt="logo-light" height="40">
											</a>
										<?php endif; ?>

									</td>
									<td style="color: <?=$colorCodeIdentity?>"><?php if ($identity_proof){echo $identity_proof_status; }?></td>
									<td>
										<?php if($residency_proof): ?>
											<a href="<?php echo $residency_proof;?>" target="_blank">
												<img src="<?php echo $residency_proof;?>" alt="logo-light" height="40">
											</a>
										<?php endif; ?>

									</td>
									<td style="color:<?=$colorCodeResidency?>;"><?php if ($residency_proof){echo $residency_proof_status;} ?></td>

									<td>
										<?php if($residency_proof_back): ?>
											<a href="<?php echo $residency_proof_back;?>" target="_blank">
												<img src="<?php echo $residency_proof_back;?>" alt="logo-light" height="40">
											</a>
										<?php endif; ?>

									</td>
									<td style="color:<?=$colorCodeResidencyBack?>;"><?php if ($residency_proof_back){echo $residency_proof_back_status;} ?></td>

									<td>
										<?php if($profile_image): ?>
											<a href="<?php echo $profile_image;?>" target="_blank">
												<img src="<?php echo $profile_image;?>" alt="logo-light" height="40">
											</a>
										<?php endif; ?>

									</td>
								</tr>

								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->




