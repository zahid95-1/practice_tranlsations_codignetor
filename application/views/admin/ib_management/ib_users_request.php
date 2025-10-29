<?php
$getGroupList 		= $this->db->query("SELECT id,group_name FROM `groups` g  ")->result();
$getPlanList 		= $this->db->query("SELECT plan_id,plan_name FROM `ib_plan`")->result();
?>
<style>
	span.select2.select2-container.select2-container--default {
		width: 100% !important;
	}
</style>
<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<div class="main-content" id="result">

	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"><?=$this->lang->line('ib_request')?></h4>
							<?php if (isset($_SESSION['success_trading_account'])):?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<?=$_SESSION['success_trading_account']?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php unset($_SESSION['success_trading_account']); endif; ?>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('country')?></th>
									<th><?=$this->lang->line('mobile_no')?></th>
									<th><?=$this->lang->line('parent_ib')?></th>
									<th><?=$this->lang->line('parent_group')?></th>
									<th><?=$this->lang->line('parent_plan')?></th>
									<th><?=$this->lang->line('action')?></th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item){
										$getParentIbUser=$this->db->query("SELECT *
                                                                FROM users u
                                                                LEFT JOIN ib_accounts ibc ON ibc.unique_id = u.unique_id 
                                                                LEFT JOIN ib_plan p ON p.plan_id = ibc.plan_id
                                                                LEFT JOIN `groups` g ON g.id = ibc.group_id  
                                                                WHERE u.unique_id='".$item->parent_id."'")->row();

										$firstName='';

										$firstName=$IbName=$IbGroup='';

										if ($getParentIbUser){
											$IbName=$getParentIbUser->first_name." ".$getParentIbUser->last_name;
											$IbGroup=$getParentIbUser->group_name;
											$IbPlan = $getParentIbUser->plan_name;
										}else{
											$IbName="(Super Admin)";
											$IbPlan = "None";
										}
										?>
										<tr id="rowChangeIbStatus-<?=$key?>">
											<td><?=++$key?></td>
											<td><?=$item->first_name.' '.$item->last_name;?></td>
											<td><?=$item->email?></td>
											<td><?=$item->name?></td>
											<td><?=$item->mobile?></td>
											<td><?=$IbName?></td>
											<td><?=$IbGroup?></td>
											<td><?=$IbPlan?></td>
											<td id="changeIbStatus-<?=$key?>">
												<?php if ($item->first_name == 'Admin') { ?>
													<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="approvedIbAdminRequest" data-uniqueid="<?=$item->unique_id?>">
														<?=$this->lang->line('approve')?>
													</button>
												<?php } else { ?>
													<button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="approvedIbRequest" data-uniqueid="<?=$item->unique_id?>">
														<?=$this->lang->line('approve')?>
													</button>
												<?php } ?>

												<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="rejectedRequest" data-uniqueid="<?=$item->unique_id?>">
													<?=$this->lang->line('reject')?>
												</button>
											</td>

										</tr>
									<?php } } ?>
								</tbody>
							</table>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->
		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<div class="modal fade" id="addManagerModal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Assign Plan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
			</div>
			<form action="<?php echo base_url(); ?>approved-ib-request" method="post" id="add_manager_event" class="custom-validation" data-key-index="<?=$key?>">
				<input type="hidden" value="" name="unique_id" id="unique_id">

				<div class="modal-body" style="text-align: center">
					<div class="">
						<h4>Are you sure ? You want to Approve this IB Request ?</h4>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">No</button>
					<button type="submit" class="btn btn-primary waves-effect waves-light">Yes</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="addAdminManagerModal" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Assign Plan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
			</div>
			<form action="<?php echo base_url(); ?>approved-ib-admin-request" method="post" id="add_manager_event" class="custom-validation" data-key-index="<?=$key?>">
				<input type="hidden" value="" name="unique_id" id="unique_id_ad">
				<div class="modal-body">

					<!--<div class="col-md-12 mt-3">
						<div class="mb-3">
							<label for="validationCustom04" class="form-label">Select Group*</label>
							<select class="form-select select2" id="validationCustom04" name="group_id" required>
								<option selected disabled value="">Choose Group</option>
								<?php if (isset($getGroupList)):foreach ($getGroupList as $key=>$item):?>
									<option value="<?=$item->id?>"><?=$item->group_name?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
						<span class="error"><?=isset($errorObject->group_id)?$errorObject->group_id:''?></span>
					</div>-->
					
					<div class="col-md-12 mt-3" style ="display:none">
						<div class="mb-3" >
							<input name="group_id" id="group_id" value = "9">
						</div>
						<span class="error"><?=isset($errorObject->group_id)?$errorObject->group_id:''?></span>
					</div>

					<div class="col-md-12 mt-3">
						<div class="mb-3">
							<label for="validationCustom03" class="form-label">Select Plan*</label>
							<select class="form-select select2" id="validationCustom03" name="ib_plan_id" required>
								<option selected disabled value="">Choose Plan</option>
								<?php if (isset($getPlanList)):foreach ($getPlanList as $key=>$item):?>
									<option value="<?=$item->plan_id?>"><?=$item->plan_name?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
						<span class="error"><?=isset($errorObject->plan_id)?$errorObject->plan_id:''?></span>
					</div>
					
					<div class="col-md-12 mt-3" style ="display:none">
						<div class="mb-3">
						<input name = "ib_comm_calc_type" id="ib_comm_calc_type" value ="1">
						</div>
					</div>

					<!--<div class="col-md-12 mt-3">
						<div class="mb-3">
							<label for="ib_comm_calc_tpe" class="form-label">Select IB Comm. Calculation Type*</label>
							<select class="form-select select2" id="ib_comm_calc_type" name="ib_comm_calc_type" required>
								<option selected disabled value="">Choose Type</option>
								<option value="1">Normal IB calc</option>
								<option value="2">Currency Based</option>
							</select>
						</div>
					</div>-->

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="rejectedModal" aria-hidden="true" aria-labelledby="..." tabindex="-1" data-bs-keyboard="false" aria-modal="true" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reject IB</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
			</div>
			<form action="<?php echo base_url(); ?>rejected-ib-request" method="post" id="rejected_ib_request" class="custom-validation" data-key-index="<?=$key?>">
				<input type="hidden" value="" name="unique_id" id="unique_id_rejected">
				<div class="modal-body" style="text-align: center">
					<div class="">
						<h4>Are you sure ? You want to reject this IB Request ?</h4>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">No</button>
					<button type="submit" class="btn btn-primary waves-effect waves-light">Yes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Sweet Alerts js -->
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>


<script>
	$(document).on('click', 'button#approvedIbRequest', function () {
		var userid			=$(this).data('uniqueid');
		$('#unique_id').val(userid);
		$('#addManagerModal').modal('show');
	});

	$(document).on('click', 'button#rejectedRequest', function () {
		var userid			=$(this).data('uniqueid');
		$('#unique_id_rejected').val(userid);
		$('#rejectedModal').modal('show');
	});

	$(document).on('click', 'button#approvedIbAdminRequest', function () {
		var userid			=$(this).data('uniqueid');
		$('#unique_id_ad').val(userid);
		$('#addAdminManagerModal').modal('show');
	});


</script>
