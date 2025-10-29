
    <!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="main-content" id="result">
		<div class="page-content">
			<div class="container-fluid">

				<!-- start page title -->
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between">
							<h4 class="mb-0"><?=$this->lang->line('dashboard')?></h4>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="javascript: void(0);">Forex</a></li>
									<li class="breadcrumb-item active"><?=$this->lang->line('dashboard')?></li>
								</ol>
							</div>

						</div>
					</div>
				</div>
				<!-- end page title -->
				<div class="row">
					<div class="col-xl-12">
						
						<div class="row">
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('clients')?></p>
												<h4 class="mb-0"><?php echo $totalClients->total_clients ?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-stack-line font-size-24"></i>
											</div>
										</div>
									</div>

									
								</div>
							</div>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('partners_ib')?></p>
												<h4 class="mb-0"><?php echo $ibClients->total_ib_clients ?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-store-2-line font-size-24"></i>
											</div>
										</div>
									</div>
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('live_account')?></p>
												<h4 class="mb-0"><?php echo $liveAccounts->total_live_accounts ?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-briefcase-4-line font-size-24"></i>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('total_fund')?></p>
												<h4 class="mb-0">$<?=($totalFund->total_fund)?$totalFund->total_fund:0?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-briefcase-line font-size-24"></i>
											</div>
										</div>
									</div>
									
								</div>
							</div>

							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('total_withdrawal')?></p>
												<h4 class="mb-0">$<?=($totalWithdrawal->total_withdrawal)?number_format((float)$totalWithdrawal->total_withdrawal, 2, '.', ''):0?>	
												</h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-repeat-2-fill font-size-24"></i>
											</div>
										</div>
									</div>
								
								</div>
							</div>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('total_ib_commission')?></p>

												<!-- <h4 class="mb-0">$<?php echo number_format((float)$totalFund->total_fund-$totalWithdrawal->total_withdrawal, 2, '.', ''); ?></h4> -->
												<h4 class="mb-0">$<?php echo number_format((float)$totalIBCommission->total_ib_commission, 2, '.', ''); ?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-repeat-2-fill font-size-24"></i>
											</div>
										</div>
									</div>
								
								</div>
							</div>

							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex">
											<div class="flex-1 overflow-hidden">
												<p class="text-truncate font-size-14 mb-2"><?=$this->lang->line('net_balance')?></p>
												 <h4 class="mb-0">$<?php echo number_format((float)$totalFund->total_fund-$totalWithdrawal->total_withdrawal, 2, '.', ''); ?></h4>
											</div>
											<div class="text-primary ms-auto">
												<i class="ri-repeat-2-fill font-size-24"></i>
											</div>
										</div>
									</div>

								</div>
							</div>

						</div>
						<!-- end row -->
					</div>
				</div>
				<!-- end row -->

				<div class="row">
					<div class="col-lg-12">
						<div class="col-xl-12">
							<div class="card">
								<div class="card-body">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
												<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
												<span class="d-none d-sm-block"><?=$this->lang->line('deposit')?></span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
												<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
												<span class="d-none d-sm-block"><?=$this->lang->line('withdrawal')?> </span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#messages1" role="tab">
												<span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
												<span class="d-none d-sm-block"><?=$this->lang->line('internal_transfer')?></span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab">
												<span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
												<span class="d-none d-sm-block"><?=$this->lang->line('ib_payment')?> </span>
											</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content p-3 text-muted">
										<div class="tab-pane active" id="home1" role="tabpanel">
											<div class="table-responsive">
												<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
													   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
													<thead class="table-light">
													<tr>
														<th style="width: 20px;">
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck">
																<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
															</div>
														</th>
														<th><?=$this->lang->line('tx_time')?> </th>
														<th><?=$this->lang->line('email')?></th>
														<th><?=$this->lang->line('account_no')?></th>
														<th><?=$this->lang->line('payment_method')?></th>
														<th><?=$this->lang->line('net_amount')?></th>
														
													</tr>
													</thead>
													<tbody>
														<?php foreach($depositData as $getdepositData){ ?>
													<tr>
														<td>
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck1">
																<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
															</div>
														</td>

														<td>
															
															<?php echo date("d-m-Y H:i:s", strtotime($getdepositData->created_at ));    ?>

														</td>

														<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getdepositData->email  ?></a> </td>

														<td><?php echo $getdepositData->mt5_login_id  ?></td>

														<td>
															<?php echo $getdepositData->payment_mode  ?>
														</td>
														<td>
															<?php echo $getdepositData->entered_amount  ?>
														</td>
														
													</tr>
												<?php } ?>
													
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="profile1" role="tabpanel">
											<div class="table-responsive">
												<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
													   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
													<thead class="table-light">
													<tr>
														<th style="width: 20px;">
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck">
																<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
															</div>
														</th>
														<th><?=$this->lang->line('tx_time')?></th>
														<th><?=$this->lang->line('email')?></th>
														<th><?=$this->lang->line('account_no')?></th>
														<th><?=$this->lang->line('net_amount')?></th>
														<th><?=$this->lang->line('status')?></th>
													</tr>
													</thead>
													<tbody>
														<?php foreach($withdrawData as $getwithdrawData){ ?>
													<tr>
														<td>
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck1">
																<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
															</div>
														</td>

														<td>
															
															<?php echo date("d-m-Y H:i:s", strtotime($getwithdrawData->requested_datetime ));    ?>

														</td>

														<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getwithdrawData->email  ?></a> </td>

														<td><?php echo $getwithdrawData->mt5_login_id  ?></td>

														<td><?php echo $getwithdrawData->requested_amount  ?></td>
														
														<td>
															<div class="badge badge-soft-success font-size-12"><?php echo $getwithdrawData->withdraw_status  ?></div>
														</td>

														
													</tr>
												<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="messages1" role="tabpanel">
											<div class="table-responsive">
												<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
													   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
													<thead class="table-light">
													<tr>
														<th style="width: 20px;">
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck">
																<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
															</div>
														</th>

														<th><?=$this->lang->line('tx_time')?></th>
														<th><?=$this->lang->line('email')?></th>
														<th><?=$this->lang->line('account_no')?></th>
														<th><?=$this->lang->line('net_amount')?></th>
														<th><?=$this->lang->line('status')?></th>
													</tr>
													</thead>
													<tbody>
														<?php foreach($internalTransferData as $getinternalTransferData){ ?>
													<tr>
														<td>
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck1">
																<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
															</div>
														</td>

														<td>
															<?php echo date("d-m-Y H:i:s", strtotime($getinternalTransferData->request_datetime));    ?>

														</td>

														<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getinternalTransferData->email ?></a> </td>

														<td><?php echo $getinternalTransferData->mt5_login_id ?></td>

														<td>
															<?php echo $getinternalTransferData->transfer_amount ?>
														</td>
														<td>
															<div class="badge badge-soft-success font-size-12"><?php echo $getinternalTransferData->internaltransfer_status ?></div>
														</td>

														
													</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="settings1" role="tabpanel">
											<div class="table-responsive">
												<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
													   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
													<thead class="table-light">
													<tr>
														<th style="width: 20px;">
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck">
																<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
															</div>
														</th>

														<th><?=$this->lang->line('tx_time')?></th>
														<th><?=$this->lang->line('email')?></th>
														<th><?=$this->lang->line('account_no')?></th>
														<th><?=$this->lang->line('net_amount')?></th>
													</tr>
													</thead>
													<tbody>
													<?php foreach($IBCommissionData as $getIBCommissionData){ ?>
													<tr>
														<td>
															<div class="form-check">
																<input type="checkbox" class="form-check-input" id="ordercheck1">
																<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
															</div>
														</td>

														<td>
															<?php echo date("d-m-Y H:i:s", strtotime($getIBCommissionData->created_datetime));    ?>

														</td>

														<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getIBCommissionData->email  ?></a> </td>

														<td><?php echo $getIBCommissionData->mt5_login_id  ?></td>

														<td>
														<?php echo $getIBCommissionData->calculated_commission;    ?>

														</td>

													</tr>
													<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								

								<h4 class="card-title mb-4"><?=$this->lang->line('client')?></h4>

								<div class="table-responsive">
									<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead class="table-light">
										<tr>
											<th style="width: 20px;">
												<div class="form-check">
													<input type="checkbox" class="form-check-input" id="ordercheck">
													<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
												</div>
											</th>
											<th><?=$this->lang->line('reg_time')?></th>
											<th><?=$this->lang->line('name')?></th>
											<th><?=$this->lang->line('email')?></th>
											<th><?=$this->lang->line('country')?></th>
											<th><?=$this->lang->line('phone')?></th>
										</tr>
										</thead>
										<tbody>
											<?php foreach($Clients as $getClients){ ?>
										<tr>
											<td>
												<div class="form-check">
													<input type="checkbox" class="form-check-input" id="ordercheck1">
													<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
												</div>
											</td>

											<td>
												<?php echo date("d-m-Y H:i:s", strtotime($getClients->created_datetime));    ?>

											</td>

											<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getClients->username ?></a> </td>

											<td><?php echo $getClients->email ?></td>

											<td>
												<div class="badge badge-soft-success font-size-12"><?php echo $getClients->country_name ?></div>
											</td>
											<td>
												<?php echo $getClients->mobile ?>
											</td>
										</tr>
											<?php } ?> 
										
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								

								<h4 class="card-title mb-4"><?=$this->lang->line('partners')?></h4>

								<div class="table-responsive">
									<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
										   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead class="table-light">
										<tr>
											<th style="width: 20px;">
												<div class="form-check">
													<input type="checkbox" class="form-check-input" id="ordercheck">
													<label class="form-check-label mb-0" for="ordercheck">&nbsp;</label>
												</div>
											</th>
											<th><?=$this->lang->line('reg_time')?></th>
											<th><?=$this->lang->line('name')?></th>
											<th><?=$this->lang->line('email')?></th>
											<th><?=$this->lang->line('ib_code')?></th>
											<th><?=$this->lang->line('country')?></th>
											<th><?=$this->lang->line('phone')?></th>
										</tr>
										</thead>
										<tbody>
											<?php foreach($IBpartners as $getIBpartners){ ?>
										<tr>
											<td>
												<div class="form-check">
													<input type="checkbox" class="form-check-input" id="ordercheck1">
													<label class="form-check-label mb-0" for="ordercheck1">&nbsp;</label>
												</div>
											</td>

											<td>
												<?php echo date("d-m-Y H:i:s", strtotime($getIBpartners->created_at));    ?>

											</td>

											<td><a href="javascript: void(0);" class="text-dark fw-bold"><?php echo $getIBpartners->first_name ?> <?php echo $getIBpartners->last_name ?></a> </td>

											<td><?php echo $getIBpartners->email ?></td>

											<td>
												<?php echo $getIBpartners->mt5_login_id ?>
											</td>
											<td>
												<div class="badge badge-soft-success font-size-12"><?php echo $getIBpartners->country_name ?></div>
											</td>
											<td>
												<?php echo $getIBpartners->mobile ?>
											</td>
											
										</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end row -->
			</div>

		</div>
		<!-- End Page-content -->
	</div>
	<!-- end main content-->



