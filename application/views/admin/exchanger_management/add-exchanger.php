
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">Add exchanger</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Add Exchanger</li>
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
							
							<form class="custom-validation" action="<?php echo base_url()."save-new-exchanger"?>" method="post">
								
								<div class="auth-form-group-custom mb-4">
									<i class="ri-user-2-line auti-custom-input-icon"></i>
									<label for="first_name">Exchanger Name</label>
									<input type="text" class="form-control" id="e_name" placeholder="Enter Name" name="e_name" required>
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-mail-line auti-custom-input-icon"></i>
									<label for="useremail">Email</label>
									<input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
									
								</div>

								<div class="auth-form-group-custom mb-4">
									<i class="ri-phone-fill auti-custom-input-icon"></i>
									<label for="first_name">Phone</label>
									<input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" required>
								</div>

								

								<div class="mb-0 col-md-6">
									<div>
										<button type="submit" class="btn btn-primary waves-effect waves-light me-1">
											Create Exchanger
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
