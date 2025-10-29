<?php
/*===================GetUserInfo=========================*/
?>
<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<style>
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
	span.select2.select2-container.select2-container--default {
		width: 100%!important;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"><?=$this->lang->line('ib_listing')?></h4>
							<p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>
							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr_no')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('mt5_login_id')?></th>
									<th><?=$this->lang->line('country')?></th>
									<th><?=$this->lang->line('mobile_no')?></th>
									<th><?=$this->lang->line('comm_group')?></th>
									<th><?=$this->lang->line('downline_share')?></th>
									<th><?=$this->lang->line('parent_ib')?></th>
									<!-- <th><?=$this->lang->line('parent_group')?></th> -->
									<th><?=$this->lang->line('joining_date')?></th>
									<th><?=$this->lang->line('total_ib_earnings')?></th>
									<th><?=$this->lang->line('view_levels')?></th>
								</tr>
								</thead>
								<tbody>
									<?php 
									$c = 0;
									foreach($dataItem['UserIbList'] as $UserIbListValue){
										$c++;
									 ?>
									<tr>
										<td><?php echo $c ?></td>
										<td><?php echo $UserIbListValue->username ?></td>
										<td><?php echo $UserIbListValue->email ?></td>
										<td><?php echo $UserIbListValue->mt5_login_id ?></td>
										<td><?php echo $UserIbListValue->country_name ?></td>
										<td><?php echo $UserIbListValue->mobile ?></td>
										<td><?php echo $UserIbListValue->group_name ?></td>
										<td><?php echo $UserIbListValue->downline_share ?></td>
										<td><?php echo $UserIbListValue->master_ib ?></td>
										<!-- <td><?php echo $UserIbListValue->master_gname ?></td> -->
										<td><?php echo $UserIbListValue->created_at ?></td>
										<td><?php echo $UserIbListValue->total_ib_commission ?></td>
										<td>
											<a class="btn btn-outline-secondary btn-sm edit"
											   title="<?=$this->lang->line('user_view')?>"
											   href="<?=base_url()?>ib-client-list/<?=$UserIbListValue->unique_id?>">
												<i class="fas fa-eye"></i> <?=$this->lang->line('view_levels')?>
											</a>
											<a class="btn btn-outline-secondary btn-sm edit"
											   title="<?=$this->lang->line('resend_ib_mail')?>"
											   href="<?=base_url()?>resend-ib-mail/<?=$UserIbListValue->unique_id?>">
												<i class="fas fa-undo-alt"></i> <?=$this->lang->line('resend_ib_mail')?>
											</a>
										</td>

										<?php } ?>
									</tr>


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


