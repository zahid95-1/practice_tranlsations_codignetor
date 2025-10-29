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
						<h4 class="mb-sm-0">Create Role</h4>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_role'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_role']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php unset($_SESSION['success_role']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">

								<form class="" action="<?php echo base_url()."update-role"?>" method="post" id="createRole" enctype="multipart/form-data" >
									<input type="hidden" value="<?=$dataItem->role_id?>" name="role_id">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Role Name<span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="" id="roleName" name="role_name" value="<?=isset($dataItem)?$dataItem->role_name:''?>" >
										<span class="error" id="amountErr"><?=isset($errorObject->role_name)?$errorObject->role_name:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Status<span class="error">*</span></label>
										<div class="">
											<select class="form-control select2" name="status" id="status">
												<option value="1" <?=($dataItem->status==1)?'selected':''?>>Active</option>
												<option value="0" <?=($dataItem->status==0)?'selected':''?>>Inactive</option>
											</select>
										</div>
									</div>

									<div class="d-grid mb-3 mt-5" id="submitBtnActions">
										<button class="btn btn-primary" type="submit" id="depositBtn">Update Role</button>
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
