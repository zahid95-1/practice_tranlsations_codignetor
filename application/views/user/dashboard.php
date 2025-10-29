<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<style>
	.referal-sections{
		display: flex;
		justify-content: space-between;
	}
	.referal{
		margin-top: -9px;
		margin-bottom: 3px;
	}
	input#referral_link {
		line-height: 0!important;
		pointer-events: none;
	}
</style>
<div class="main-content" id="result">

	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0"><?= lang('dashboard') ?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
                                                                <li class="breadcrumb-item"><a href="user_dashboard.html"><?= lang('home') ?></a></li>
                                                                <li class="breadcrumb-item active"><?= lang('dashboard') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->
			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<p style="color:green;"><?php echo $this->session->flashdata('d_msg'); ?></p> 
						<div class="col-md-4">
						    <a href="<?php echo base_url()."user/my-mt5-account-list" ?>">
						        <div class="card">
    								<div class="card-body">
    									<div class="d-flex">
    										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('total_mt5_account') ?></p>
    											<h4 class="mb-0"><?=$dashboardData['totalLiveTradingAccount']?></h4>
    										</div>
    										<div class="text-primary ms-auto">
    											<i class="ri-stack-line font-size-24"></i>
    										</div>
    									</div>
    								</div>
							    </div>
						    </a>
							
						</div>
						<div class="col-md-4">
						    <a href="<?php echo base_url()."user/withdraw" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('balance') ?></p>
											<h4 class="mb-0">$ <?=$dashboardData['currentBalance']?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-store-2-line font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>
						<div class="col-md-4">
						    <a href="<?php echo base_url()."user/deposit/history" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('deposit') ?></p>
											<h4 class="mb-0">$ <?=$dashboardData['depositBalance']?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-briefcase-4-line font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>

						<div class="col-md-4">
						    <a href="<?php echo base_url()."user/withdraw/history" ?>">
							<div class="card">
								<div class="card-body">
									<div class="d-flex">
										<div class="flex-1 overflow-hidden">
                                                                               <p class="text-truncate font-size-14 mb-2"><?= lang('withdraw') ?></p>
											<h4 class="mb-0">$ <?=($dashboardData['withdrawBalance'])?$dashboardData['withdrawBalance']:0?></h4>
										</div>
										<div class="text-primary ms-auto">
											<i class="ri-account-circle-fill font-size-24"></i>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>

						<!-- <?php if ($dashboardData['is_ib']==1): ?>
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div style="display:flex;">
										<input style="width: 85%;" class="form-control" type="text" value="<?php echo base_url(); ?>register?reffid=<?php echo $_SESSION['unique_id'] ?>" id="referral_link">
										<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="button" aria-pressed="false" style="padding: 5px;" onclick="copyLink()">
											<i class="fas fa-copy"></i> Copy
										</button>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>
 -->
					</div>
					<!-- end row -->
				</div>
			</div>
			<!-- end row -->

			<div class="row">
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">

                                                        <h4 class="card-title mb-4"><?= lang('my_live_account') ?></h4>

							<div class="table-responsive">
								<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead class="table-light">
									<tr>
                                                                               <th><?= lang('sr_no') ?></th>
                                                                               <th><?= lang('account_no') ?></th>
                                                                               <th><?= lang('account_type') ?></th>
                                                                               <th><?= lang('leverage') ?></th>
                                                                               <th><?= lang('create_at') ?></th>
									</tr>
									</thead>
									<tbody>
									<?php if (isset($dashboardData['liveTradingInfo']) && $dashboardData['liveTradingInfo']):
										foreach ($dashboardData['liveTradingInfo'] as $key=>$data):
										?>
									<tr>
										<td><?=++$key;?></td>
										<td><?=$data->mt5_login_id?></td>
										<td><?=$data->group_name?></td>
										<td><?=(int)$data->leverage;?></td>
										<td><?=date('d-m-Y',strtotime($data->created_at))?></td>

									</tr>
									<?php endforeach; endif; ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">

                                                        <h4 class="card-title mb-4"><?= lang('transactions') ?></h4>

							<div class="table-responsive">
								<table class="table table-centered datatable dt-responsive nowrap" data-bs-page-length="5"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead class="table-light">
									<tr>
                                                                               <th><?= lang('sr_no') ?></th>
                                                                               <th><?= lang('mt5_account_id') ?></th>
                                                                               <th><?= lang('amount') ?></th>
                                                                               <th><?= lang('payment_type') ?></th>
                                                                               <th><?= lang('create_at') ?></th>
                                                                               <th><?= lang('status') ?></th>
									</tr>
									</thead>
									<tbody>
									<?php if (isset($dashboardData['transactions']) && $dashboardData['transactions']):
									foreach ($dashboardData['transactions'] as $key=>$item):
									?>
										<tr>
											<td><?=++$key?></td>
											<td><?=$item->mt5_login_id?></td>
											<td>$<?=$item->entered_amount?></td>
											<td>
												<?php
												if ($item->payment_mode==1){
                                                                               echo "<span style='color: green'>" . lang('wire_transfer') . "</span>";
												}elseif ($item->payment_mode==2){
                                                                               echo "<span style='color: green'>" . lang('crypto_coin') . "</span>";
												}elseif ($item->payment_mode==3){
                                                                               echo "<span style='color: #0a53be'>" . lang('paypal') . "</span>";
												}elseif ($item->payment_mode==4){
                                                                               echo "<span style='color: #0f0f0f'>" . lang('cash') . "</span>";
												}elseif ($item->payment_mode==5){
                                                                               echo "<span style='color: #00CC00'>" . lang('internal_transfer') . "</span>";
												}elseif ($item->payment_mode==6){
                                                    echo "<span style='color: #00CC00'>" . lang('commission_transfer') . "</span>";
                                                }elseif ($item->payment_mode==7){
                                                                               echo "<span style='color: forestgreen'>" . lang('stripe') . "</span>";
												}
												?>
											</td>
											<td><?=date('Y-m-d H:s',strtotime($item->created_at))?></td>
											<td>
												<?php if ($item->status==0): ?>
                                                                               <span class="badge rounded-pill bg-danger"><?= lang('pending') ?></span>
												<?php elseif($item->status==1): ?>
                                                                               <span class="badge rounded-pill bg-success"><?= lang('approved') ?></span>
												<?php elseif($item->status==2): ?>
                                                                               <span class="badge rounded-pill bg-danger"><?= lang('rejected') ?></span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach;endif; ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<script>
	function copyLink() {
		var copyGfGText = document.getElementById("referral_link");
		copyGfGText.select();
		document.execCommand("copy");
		$('#copiedMessage').html("Copied!");
		setTimeout(function() {
			$('#copiedMessage').html("");
		}, 2000);
	}
</script>
