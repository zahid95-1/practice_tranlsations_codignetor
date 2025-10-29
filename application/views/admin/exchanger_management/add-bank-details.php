
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">Add Bank Details</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Add Bank Details</li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->
			<!-- end row -->
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							
							<form class="custom-validation" action="<?php echo base_url()."save-bank-details"?>" method="post">
								
								<div class="auth-form-group-custom mb-4">
									
									<label for="bank_name">Bank Name</label>
									<input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" name="bank_name" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									
									<label for="account_no">Account No</label>
									<input type="text" class="form-control" id="account_no" placeholder="Enter Account No" name="account_no" required>
									
								</div>

								<div class="auth-form-group-custom mb-4">
									
									<label for="ifsc_code">IFSC code</label>
									<input type="text" class="form-control" id="ifsc_code" placeholder="IFSC" name="ifsc_code" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									
									<label for="branch_name">Branch Name</label>
									<input type="text" class="form-control" id="branch_name" placeholder="Branch Name" name="branch_name" required>
								</div>

								

								<div class="mb-0 col-md-6">
									<div>
										<button type="submit" class="btn btn-primary waves-effect waves-light me-1">
											Submit
										</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

		</div> <!-- container-fluid -->
	</div>
</div>
