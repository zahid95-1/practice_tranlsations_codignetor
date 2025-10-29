<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_ib_request'])){
	$errorObject	=json_decode($_SESSION['error_ib_request']);
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
						<h4 class="mb-sm-0">IB Request</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">IB Request</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_ib_request'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_ib_request']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_ib_request']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-4  mb-3">

								<form class="" action="<?php echo base_url()."user/change-ib-status"?>" method="post" id="depositWireTransfer" enctype="multipart/form-data" >
									<div class="form-check mb-3">
										<input class="form-check-input" type="checkbox" id="formCheck1" name="ib_request">
										<label class="form-check-label" for="formCheck1">
											I agree with <a href="<?=ConfigData['broker_agreement']?>" target="_blank">terms & conditions</a>.
										</label>
									</div>
									<span class="error"><?=isset($errorObject->ib_request)?$errorObject->ib_request:''?></span>

									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary" type="submit">Request For IB</button>
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

<?php unset($_SESSION['error_ib_request']); ?>
