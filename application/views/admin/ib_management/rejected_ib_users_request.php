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
							<h4 class="card-title mb-4"><?=$this->lang->line('rejected_ib_request_list')?></h4>
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
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($dataItem) && $dataItem){
								foreach ($dataItem as $key=>$item){
									$getParentIbUser=$this->db->query("SELECT * FROM users u
									INNER JOIN ib_commission ibc ON ibc.user_id = u.user_id
									INNER JOIN `groups` g ON g.id = ibc.group_id
									WHERE u.unique_id = '".$item->parent_id."'")->row();

									$firstName='';

									$firstName=$IbName=$IbGroup='';

									if ($getParentIbUser){
										$IbName=$getParentIbUser->first_name." ".$getParentIbUser->last_name;
										$IbGroup=$getParentIbUser->group_name;
									}else{
										$IbName="(Super Admin)";
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

<!-- Sweet Alerts js -->
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>

