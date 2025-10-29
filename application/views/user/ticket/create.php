<?php
$errorObject='';
if (isset($_SESSION['error_create_ticket'])){
	$errorObject	=json_decode($_SESSION['error_create_ticket']);
}
?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
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
						<h4 class="mb-sm-0"><?= lang('create_ticket') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active"><?= lang('create_ticket') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">

				<?php if (isset($_SESSION['success_change_leverage'])):?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<?=$_SESSION['success_change_leverage']?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					<?php unset($_SESSION['success_change_leverage']); endif; ?>

				<?php if (isset($errorObject->mt5_error)): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<span><?=$errorObject->mt5_error?></span><br/>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>

				<form class="" action="<?php echo base_url()."user/add-ticket"?>" method="post" id="createTicket" enctype="multipart/form-data">

				<?php if (isset($_SESSION['success_ticket_create'])):?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<?=$_SESSION['success_ticket_create']?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					<?php unset($_SESSION['success_ticket_create']); endif; ?>

					<div class="col-lg-12">
						<div class="card">
							<div class="card-body d-flex">
								<div class="col-md-8 row mb-3">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('title') ?><span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="Enter ticket title" id="title" name="title" value="">
										<span class="error"><?=isset($errorObject->title)?$errorObject->title:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('department') ?>*</label>
										<div class="mb-3">
											<select class="form-control" name="department">
												<option value=""><?= lang('department') ?></option>
												<option value="Help and Support">Help and Support</option>
											</select>
										</div>
										<span class="error"><?=isset($errorObject->department)?$errorObject->department:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label"><?= lang('content') ?><span class="error">*</span></label>
										<textarea class="form-control" name="descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->descriptions)?$errorObject->descriptions:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-2 col-form-label"><?= lang('attachment') ?></label>
										<input type="file" class="form-control" id="identity_proof" name="identity_proof"  accept="image/png">
									</div>

									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit"><?= lang('create_ticket') ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->
<?php unset($_SESSION['error_create_ticket']); ?>
