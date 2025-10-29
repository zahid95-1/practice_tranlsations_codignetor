<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];

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
						<h4 class="mb-sm-0"><?= lang('change_password_title') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('change_password_title') ?></li>
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

							<form method="post" class="form-control" autocomplete="off" action="<?php echo base_url(); ?>user/update-crm-password" id="changePassword"  data-key-index="0">

					<input type="hidden" value="<?php echo $unique_id;?>" name="uid" id="uid">
					<div class="wrapping">
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('current_password') ?></label>
							<div class="col-sm-4">
								<input type="password" class="form-control" id="c_password" name="c_password" required placeholder="********" >
							</div>
						</div>
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('new_password') ?></label>
							<div class="col-sm-4">
								<input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"   min-length="8" max-length="8" required class="form-control" id="n_password" name="n_password" required placeholder="********">
							</div>
						</div>
						<div class="row mb-3">
							<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('reenter_password') ?></label>
							<div class="col-sm-4">
								<input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"   min-length="8" max-length="8" required class="form-control" id="r_password" name="r_password" required accept="image/*" placeholder="********">
							</div>
						</div>
					</div>
					<div class="row" style="margin-left: 127px;margin-top: 25px;">
						<span id="successMessage-kyc-0" style="color:green;"></span>
						<button class="btn btn-primary" type="submit" id="uploadKycBtn-0" style="width: 120px;"><?= lang('submit') ?></button>
					</div>
				</form>

			

																

						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->




