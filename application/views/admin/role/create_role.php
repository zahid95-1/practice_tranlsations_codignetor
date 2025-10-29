<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_role'])){
	$errorObject	=json_decode($_SESSION['error_role']);
}
?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	button.disable-btn {
		background: #5664d2bd;
		pointer-events: none;
	}
	span.select2.select2-container.select2-container--default {
		width: 785px!important;
	}
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0"><?= $this->lang->line('create_role') ?></h4>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_role'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?= $_SESSION['success_role'] ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_role']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">

								<form action="<?= base_url('admin/role/create-role') ?>" method="post" id="createRole" enctype="multipart/form-data">

									<!-- Role Name -->
									<div class="col-sm-12">
										<label for="role_name" class="col-sm-12 col-form-label"><?= $this->lang->line('role_name') ?> <span class="error">*</span></label>
										<input class="form-control" type="text" id="role_name" name="role_name" placeholder="<?= $this->lang->line('role_name_placeholder') ?>" value="">
										<span class="error"><?= isset($errorObject->role_name) ? $errorObject->role_name : '' ?></span>
									</div>

									<!-- Status -->
									<div class="col-sm-12 mt-3">
										<label for="status" class="col-sm-12 col-form-label"><?= $this->lang->line('status') ?> <span class="error">*</span></label>
										<select class="form-control select2" name="status" id="status">
											<option value="1"><?= $this->lang->line('active') ?></option>
											<option value="0"><?= $this->lang->line('inactive') ?></option>
										</select>
									</div>

									<!-- Submit Button -->
									<div class="d-grid mb-3 mt-5" id="submitBtnActions">
										<button class="btn btn-primary" type="submit" id="depositBtn"><?= $this->lang->line('create_role_button') ?></button>
									</div>

								</form>

							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php unset($_SESSION['error_role']); ?>
