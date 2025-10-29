<?php
/*===================GetUserInfo=========================*/
?>
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
	.page-content {
		/*padding: calc(20px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.level-info {
		display: flex;
		justify-content: center;
		align-content: space-between;
	}
	.level-info  .single-btn {
		margin-right: 23px;
	}
	.reportSections {
		display: flex;
		justify-content: space-around;
		margin: 34px 282px;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">.


			<div class="row">
				<div class="col-12">
					<div class="card">

						<div class="card-body">
							<!--	<h4 class="card-title mb-4">Refferal Clients of IB :- Abdul Kazi</h4>-->
							<!--<?php foreach($dataItem['IbClientLevel'] as $IbClientLevelvalue) { ?>
								<p><?php echo "Level ".$IbClientLevelvalue->level ."-" .$IbClientLevelvalue->ib_commission ?></p>
							<?php } ?>-->




							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?php echo $this->lang->line('action'); ?></th>
									<th><?php echo $this->lang->line('trading_account_id') ? $this->lang->line('trading_account_id') : 'Trading Account ID'; ?></th>
									<th><?php echo $this->lang->line('name'); ?></th>
									<th><?php echo $this->lang->line('upline_ib') ? $this->lang->line('upline_ib') : 'Upline IB'; ?></th>
									<th><?php echo $this->lang->line('email'); ?></th>
									<th><?php echo $this->lang->line('phone'); ?></th>
									<th><?php echo $this->lang->line('level') ? $this->lang->line('level') : 'Level'; ?></th>
									<th><?php echo $this->lang->line('country'); ?></th>

									<th><?php echo $this->lang->line('details') ? $this->lang->line('details') : 'Details'; ?></th>

								</tr>
								</thead>
								<tbody>
								<?php
								if(!empty($dataItem['IbClient'])){ foreach($dataItem['IbClient'] as $key=>$IbClientValue){

									$userId=$IbClientValue->user_id;
									$balanceMt5	   =$this->db->query("SELECT mt5_login_id,count(mt5_login_id) as mt5_login_id_count  FROM `trading_accounts` WHERE `user_id` = $userId")->row();
                                    $traddingAccount	   =$this->db->query("SELECT mt5_login_id FROM `trading_accounts` WHERE `user_id` = $userId")->result();
                                   $mt5LoginIdsCommaSeparated='-';
                                    if (!empty($traddingAccount)) {
                                        $mt5LoginIds = array_column($traddingAccount, 'mt5_login_id');
                                        $mt5LoginIdsCommaSeparated = implode(',', $mt5LoginIds);
                                    }

									?>
									<tr>
										<td>
											<a class="btn btn-outline-secondary btn-sm edit"  data-bs-toggle="modal" data-bs-target="#deposit_history-<?=$userId?>">
												<i class="fas fa-arrow-alt-circle-up" title="Deposit History"></i>
											</a>
											<!-- First modal dialog -->
											<div class="modal fade" id="deposit_history-<?=$userId?>" aria-hidden="true" aria-labelledby="..." tabindex="-1">
												<div class="modal-dialog modal-md modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Deposit History</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$userId?>"></button>
														</div>
														<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
															   style="border-collapse: collapse; border-spacing: 0; width: 100%;">

															<thead>
															<tr>
																<th>Mt5 Login ID</th>
																<th>Amount</th>
																<th>Date</th>

															</tr>
															</thead>
															<tbody>
															<?php
															$total_amount = 0;
															$deposithistory=$this->db->query("SELECT *  FROM `payments` WHERE `user_id` = $userId and status = 1")->result();
															foreach($deposithistory as $getdeposithistory){

																$total_amount = $total_amount + $getdeposithistory->entered_amount ;
																?>
																<tr>
																	<td> <?php echo $getdeposithistory->mt5_login_id ?></td>
																	<td> <?php echo $getdeposithistory->entered_amount ?></td>
																	<td><?php echo date("d-m-Y H:i:s", strtotime($getdeposithistory->created_at));    ?></td>
																</tr>

															<?php } ?>
															<tr>
																<th>Total Deposit 总存款</th>
																<th><?php echo $total_amount; ?></th>
															</tr>
															</tbody>
														</table>

													</div>
												</div>
											</div>

											<a class="btn btn-outline-secondary btn-sm edit"  data-bs-toggle="modal" data-bs-target="#withdrawal_history-<?=$userId?>">
												<i class="fas fa-arrow-alt-circle-down" title="Withdraw History"></i>
											</a>
											<!-- First modal dialog -->
											<div class="modal fade" id="withdrawal_history-<?=$userId?>" aria-hidden="true" aria-labelledby="..." tabindex="-1">
												<div class="modal-dialog modal-md modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Withdrawal History 取款历史</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$userId?>"></button>
														</div>
														<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
															   style="border-collapse: collapse; border-spacing: 0; width: 100%;">

															<thead>
															<tr>
																<th>MT5 Login ID MT5登录ID</th>
																<th>Amount 金额</th>
																<th>Date 请求时间</th>

															</tr>
															</thead>
															<tbody>
															<?php
															$total_amount = 0;
															$withdrawalhistory=$this->db->query("SELECT *  FROM `withdrawal` w inner join users u on u.unique_id = w.unique_id WHERE u.`user_id` = $userId and w.status = 2")->result();
															foreach($withdrawalhistory as $getwithdrawalhistory){

																$total_amount = $total_amount + $getwithdrawalhistory->requested_amount ;
																?>
																<tr>
																	<td> <?php echo $getwithdrawalhistory->mt5_login_id ?></td>
																	<td> <?php echo $getwithdrawalhistory->requested_amount ?></td>																			<td><?php echo date("d-m-Y H:i:s", strtotime($getwithdrawalhistory->requested_datetime));    ?></td>
																</tr>

															<?php } ?>
															<tr>
																<th>Total Withdrawal 总取款</th>
																<th><?php echo $total_amount; ?></th>
															</tr>
															</tbody>
														</table>

													</div>
												</div>
											</div>

										</td>
										<td><?php echo $IbClientValue->ib_account ?></td>
										<td><?php echo $IbClientValue->username ?></td>
										<td><?php echo $IbClientValue->upline_ib ?></td>
										<td><?php echo $IbClientValue->email ?></td>
										<td><?php echo $IbClientValue->mobile ?></td>
										<td><?php echo "Level ".$IbClientValue->level_no ?></td>
										<td><?php echo $IbClientValue->country_name ?></td>
										<!-- 											<td>
												$<?=($balanceMt5->totalBalanceMt5)?$balanceMt5->totalBalanceMt5:'0.00'?>
											</td>
 -->
										<td>

											<?php if($balanceMt5->mt5_login_id_count > 0){ ?>
												<a class="btn btn-outline-secondary btn-sm edit" title="User View" style="margin-right: 1px;" href="javascript:void(0)" id="mt5AccountListDetails" data-key-index="<?=$key?>" data-account-id="<?=$IbClientValue->ib_account?>">
													<i class="fas fa-eye"></i> View Details
												</a>
											<?php }else{ ?>
												<?php echo 'No Trading Account'; }  ?>
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
<div class="modal fade" id="accountListDetails" aria-hidden="true" aria-labelledby="..." tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Account Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal-<?=$key?>"></button>
			</div>

			<table class="table mb-0" id="accountDetailsTable">

			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('#datatable1').DataTable({
			"language": {
				"paginate": {
					"previous": "<i class='mdi mdi-chevron-left'>",
					"next": "<i class='mdi mdi-chevron-right'>"
				}
			},
			"drawCallback": function () {
				$('.dataTables_paginate > .pagination').addClass('pagination-rounded');
			}

		});

		// Account Live Details
		$(document).on('click', 'a#mt5AccountListDetails', function () {

			var currentIndex	=$(this).data('key-index');
			var accountId		=$(this).data('account-id');
			let loader=`<div class="loading-data">
					<span class="loader"></span>
				</div>`;

			$('#accountDetailsTable').html(loader);

			$('#accountListDetails').modal('show');

			var url = "<?php echo base_url(); ?>user/my-mt5-account-list/details";
			var post_data = {
				'accountId': accountId,
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};

			$.ajax({
				url : url,
				type : 'POST',
				data: post_data,
				success : function(response)
				{
					var obj = JSON.parse(response);
					if (response!=0){
						let html=`<tbody>
									<tr>
										<th scope="row">Login :</th>
										<td>${obj.Login}</td>
										<th scope="row">Balance :</th>
										<td>$${obj.Balance}</td>
										<td>Margin Free :</td>
										<td>${obj.MarginFree}</td>
									</tr>
									<tr>
										<td>Margin Leverage  :</td>
										<td>${obj.MarginLeverage}</td>
										<th scope="row">Credit  :</th>
										<td>${obj.Credit}</td>
										<th scope="row">Equity   :</th>
										<td>${obj.Equity}</td>
									</tr>
									</tbody>`;

						$('#accountDetailsTable').html(html)
					}
				}
			});
		});

	});
</script>
