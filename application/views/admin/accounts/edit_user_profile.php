<?php
$getUserInfo='';
$getCountry = $this->db->query("SELECT id,nicename FROM country");
if (isset($_REQUEST['userId'])) {
	$getUserInfo = $this->db->query("SELECT * FROM users where unique_id='".$_REQUEST['userId']."'")->row();
}
?>

<div class="main-content" id="result">
	<div class="page-content">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">Updated Profile -(<?=$getUserInfo->first_name.' '.$getUserInfo->last_name?>)</h4>
							<form class="needs-validation" method="post" action="<?php echo base_url() ?>update-user-info" novalidate>
								<input type="hidden" value="<?=$_REQUEST['userId']?>" name="unique_id">
								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom01" class="form-label">First Name</label>
											<input type="text" class="form-control" id="validationCustom01" placeholder="First name" value="<?=$getUserInfo->first_name?>" name="first_name">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom01" class="form-label">Last Name</label>
											<input type="text" class="form-control" id="validationCustom02" placeholder="Last name" value="<?=$getUserInfo->last_name?>" name="last_name">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom03" class="form-label">Email</label>
											<input type="email" class="form-control" id="validationCustom03" placeholder="example@gmail.com" value="<?=$getUserInfo->email?>" name="email">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom02" class="form-label">Phone</label>
											<input type="text" class="form-control" id="validationCustom03" placeholder="880545454254" value="<?=$getUserInfo->mobile?>" name="mobile">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom04" class="form-label">Gender</label>
											<select class="form-select" id="validationCustom04" name="gender">
												<option selected disabled value="">Choose Gender</option>
												<option value="male" <?php if ($getUserInfo->gender=='male'){echo "selected";} ?>>Male</option>
												<option value="female" <?php if ($getUserInfo->gender=='female'){echo "selected";} ?>>Female</option>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom05" class="form-label">Country</label>
											<select class="form-select" id="validationCustom05" name="country_id">
												<option selected disabled value="">Choose Country</option>
												<?php
												foreach($getCountry->result() as $Country){
													$countryId = $Country->id ;
													$countryName = $Country->nicename ;
													?>
													<option value="<?php echo $countryId ?>" <?php if ($getUserInfo->country_id==$countryId){echo "selected";} ?>><?php echo $countryName ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom06" class="form-label">Address</label>
											<input type="text" class="form-control" value="<?=$getUserInfo->address?>" name="address" id="validationCustom06" placeholder="Address.......">
										</div>
									</div>

									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom07" class="form-label">City</label>
											<input type="text" class="form-control" id="validationCustom07" placeholder="City" name="city" value="<?=$getUserInfo->city?>">
										</div>
									</div>

									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom08" class="form-label">State</label>
											<input type="text" class="form-control" id="validationCustom08" placeholder="State" name="state" value="<?=$getUserInfo->state?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom09" class="form-label">Zip Code</label>
											<input type="text" class="form-control" id="validationCustom09" placeholder="Zip Code" name="zip" value="<?=$getUserInfo->zip?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom05" class="form-label">Manager</label>
											<select class="form-select" id="validationCustom05" name="manager_name">
												<option selected disabled value="">Choose Manager</option>
												<option value="vishal" <?php if ($getUserInfo->manager_name=='vishal'){echo "selected";} ?>>Vishal</option>
												<option value="tanvir" <?php if ($getUserInfo->manager_name=='tanvir'){echo "selected";} ?>>Tanvir</option>
												<option value="kaveya" <?php if ($getUserInfo->manager_name=='kaveya'){echo "selected";} ?>>Kaveya</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label for="validationCustom04" class="form-label">User Status</label>
											<select class="form-select" id="validationCustom04" name="status">
												<option selected disabled value="">Choose Status</option>
												<option value="1" <?php if ($getUserInfo->status==1){echo "selected";} ?>>Active</option>
												<option value="0" <?php if ($getUserInfo->status==0){echo "selected";} ?>>Block</option>
											</select>
										</div>
									</div>
								</div>
								<div>
									<button class="btn btn-primary" type="submit">Updated User</button>
								</div>
							</form>
						</div>
					</div>
					<!-- end card -->
				</div> <!-- end col -->
			</div>
		</div>
</div>
