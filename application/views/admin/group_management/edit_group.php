<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_group']) && isset($_SESSION['request_data'])){
	$errorObject	=json_decode($_SESSION['error_group']);
	$requestData 	=json_decode($_SESSION['request_data']);
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
						<h4 class="mb-sm-0">Update Group</h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Create Group</li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body d-flex">
							<form class="" action="<?php echo base_url()."update-group"?>" method="post" id="createGroupForm">
								<input type="hidden" value="<?=$params['groupId']?>" name="id">
								<div class="col-md-8 row mb-3">
									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Name<span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="Enter Group Name" id="example-text-input" name="group_name" value="<?=isset($requestData->group_name)?$requestData->group_name:$dataItem->group_name?>">
										<span class="error"><?=isset($errorObject->group_name)?$errorObject->group_name:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">MT5 Group Name<span class="error">*</span></label>
										<input class="form-control disabled" type="text" placeholder="Enter MT5 Group Name" id="subject-text-input" name="mt5_group_name" value="<?=isset($requestData->mt5_group_name)?$requestData->mt5_group_name:$dataItem->mt5_group_name?>" >
										<span class="error"><?=isset($errorObject->mt5_group_name)?$errorObject->mt5_group_name:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Minimum Deposit<span class="error">*</span></label>
										<input class="form-control" type="number" placeholder="Enter minimum deposit" id="from-name-text-input" name="minimum_deposit" value="<?=isset($requestData->minimum_deposit)?$requestData->minimum_deposit:$dataItem->minimum_deposit?>" >
										<span class="error"><?=isset($errorObject->minimum_deposit)?$errorObject->minimum_deposit:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Spread from<span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="Enter spread from" id="from-name-text-input" name="spread_from" value="<?=isset($requestData->spread_from)?$requestData->spread_from:$dataItem->spread_from?>" >
										<span class="error"><?=isset($errorObject->spread_from)?$errorObject->spread_from:''?></span>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Commission<span class="error">*</span></label>
										<input class="form-control" type="text" placeholder="Enter commission" id="from-name-text-input" name="commission" value="<?=isset($requestData->commission)?$requestData->commission:$dataItem->commission?>" >
										<span class="error"><?=isset($errorObject->commission)?$errorObject->commission:''?></span>
									</div>

									<div class="col-md-12 mt-3">
										<div class="mb-3">
											<label for="validationCustom04" class="form-label">Swap<span class="error">*</span></label>
											<select class="form-select" id="validationCustom04" name="swap" required>
												<option selected disabled value="">Choose Swap</option>
												<option value="1" <?php if (isset($dataItem->swap)){echo ($dataItem->swap==1)?"selected":'';}?>>Yes</option>
												<option value="2" <?php if (isset($dataItem->swap)){echo ($dataItem->swap==2)?"selected":'';}?>>No</option>
											</select>
											<span class="error"><?=isset($errorObject->swap)?$errorObject->swap:''?></span>
										</div>
									</div>

									<div class="col-sm-12">
										<label for="example-text-input" class="col-sm-12 col-form-label">Group Status<span class="error">*</span></label>
										<div class="d-flex">
											<div class="form-check mb-3">
												<input class="form-check-input" type="radio" name="status" id="status_active" value="1" <?php if (isset($dataItem->status)){echo ($dataItem->status==1)?"checked":'';}?>>
												<label class="form-check-label" for="status_active">
													Active
												</label>
											</div>
											<div class="form-check mb-3" style="margin-left: 10px;">
												<input class="form-check-input" type="radio" name="status" id="status_inactive" value="2" <?php if (isset($dataItem->status)){echo ($dataItem->status==2)?"checked":'';}?>>
												<label class="form-check-label" for="status_inactive">
													Inactive
												</label>
											</div>
										</div>
										<span class="error"><?=isset($errorObject->group_status)?$errorObject->group_status:''?></span>
									</div>

									<div class="d-grid mb-3 mt-5">
										<button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">Update Group</button>
									</div>
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
<?php unset($_SESSION['error_group']); ?>
