<?php

function hasAccessModulesModules($search)
{
	$sessionAccess		=json_decode($_SESSION['accessModel'],true);
	$found = array_filter($sessionAccess, function ($v, $k) use ($search) {
		return $v['slug_name'] == $search;
	}, ARRAY_FILTER_USE_BOTH);
	if (!empty($found)){
		return true;
	}else{
		return false;
	}
}

function hasAccessSubModulesModules($search)
{
	$sessionAccess		=json_decode($_SESSION['accessSubModel'],true);
	$found = array_filter($sessionAccess, function ($v, $k) use ($search) {
		return $v['route'] == $search;
	}, ARRAY_FILTER_USE_BOTH);
	if (!empty($found)){
		return true;
	}else{
		return false;
	}
}

?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

	<div data-simplebar class="h-100">

		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">
				<?php if (hasAccessModulesModules('dashboard')): ?>
					<li>
						<a href="<?=base_url()?>admin/dashboard" class="waves-effect">
							<i class="ri-dashboard-line"></i>
							<span><?=$this->lang->line('dashboard')?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('account')): ?>
					<li >
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-account-box-line"></i>
							<span><?=$this->lang->line('accounts')?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/account/registered-account')): ?>
								<li><a href="<?=base_url()?>admin/account/registered-account"><?=$this->lang->line('registered_account')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/account/add-new-user')): ?>
								<li><a href="<?=base_url()?>admin/account/add-new-user"><?=$this->lang->line('add_new_user')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/account/user-mt5-account-create')): ?>
								<li><a href="<?=base_url()?>admin/account/user-mt5-account-create"><?=$this->lang->line('open_trading_account')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/account/user-trading-account-list')): ?>
								<li><a href="<?=base_url()?>admin/account/user-trading-account-list"><?=$this->lang->line('trading_account_list')?></a></li>
							<?php endif; ?>
							<?php if (ConfigData['enable_disable_demo_account']==true):?>
								<li><a href="<?=base_url()?>admin/account/user-trading-demo-account-list"><?=$this->lang->line('kyc_list')?></a></li>
							<?php endif; ?>

							<li><a href="<?=base_url()?>admin/user-kyc-list"><?=$this->lang->line('kyc_list')?></a></li>
							<li><a href="<?=base_url()?>admin/blank-kyc-upload-user-list"><?=$this->lang->line('non_kyc')?></a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('ib-management')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-user-3-fill"></i>
							<span><?=$this->lang->line('ib_management')?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/ib-management/ib-user-request')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/ib-user-request"><?=$this->lang->line('ib_request')?></a></li>
							<?php endif; ?>
							<li><a href="<?=base_url()?>admin/ib-management/rejected-ib-user-request"><?=$this->lang->line('rejected_ib_list')?></a></li>
							<?php if (hasAccessSubModulesModules('admin/ib-management/ib-users-list')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/ib-users-list"><?=$this->lang->line('ib_list')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/ib-management/ib-plan')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/ib-plan"><?=$this->lang->line('ib_plan')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/ib-management/commission-group')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/commission-group"><?=$this->lang->line('commission_group')?></a></li>
							<?php endif; ?>

							<?php if (hasAccessSubModulesModules('admin/ib-management/commission-group')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/commission-ref-by-level"><?=$this->lang->line('commission_ref')?></a></li>
							<?php endif; ?>


							<li><a href="<?=base_url()?>admin/ib-management/add-symbol-value"><?=$this->lang->line('add_symbol_share')?></a></li>
							<?php if (hasAccessSubModulesModules('admin/ib-management/commission-setting')): ?>
								<!-- <li><a href="<?=base_url()?>admin/ib-management/commission-setting">Commission Setting </a></li> -->
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/ib-management/assign-ib')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/assign-ib"><?=$this->lang->line('change_upline_ib')?></a></li>
							<?php endif; ?>
							<!-- <li><a href="<?=base_url()?>assign-ib">Move Client under IB </a></li> -->

							<li><a href="<?=base_url()?>remove-ib"><?=$this->lang->line('remove_ib')?></a></li>

							<?php if (hasAccessSubModulesModules('admin/ib-management/commission-group-by-level')): ?>
								<li><a href="<?=base_url()?>admin/ib-management/commission-group-by-level">Level Settings Commission</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('group-management')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-user-3-fill"></i>
							<span><?=$this->lang->line('group_management')?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/group-management/group-list')): ?>
								<li><a href="<?=base_url()?>admin/group-management/group-list"><?=$this->lang->line('group_list')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/group-management/create-group')): ?>
								<li><a href="<?=base_url()?>admin/group-management/create-group"><?=$this->lang->line('add_group')?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/group-management/update-client-group')): ?>
								<li><a href="<?=base_url()?>admin/group-management/update-client-group"><?=$this->lang->line('update_client_group')?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('exchanger-management')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-arrow-right-line"></i>
							<span><?= $this->lang->line('exchanger_mngmt') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/exchanger-management/exchanger-list')): ?>
								<li><a href="<?= base_url('admin/exchanger-management/exchanger-list') ?>"><?= $this->lang->line('exchanger_list') ?></a></li>
							<?php endif; ?>

							<li><a href="<?= base_url('admin/exchanger-management/add-exchanger') ?>"><?= $this->lang->line('add_exchanger') ?></a></li>
							<li><a href="<?= base_url('admin/exchanger-management/transfer-exchanger') ?>"><?= $this->lang->line('transfer_exchanger') ?></a></li>
							<li><a href="<?= base_url('admin/exchanger-management/add-bank-details') ?>"><?= $this->lang->line('add_bank_details') ?></a></li>

							<?php if (hasAccessSubModulesModules('admin/exchanger-management/exchanger-deposit')): ?>
								<li><a href="<?= base_url('admin/exchanger-management/exchanger-deposit') ?>"><?= $this->lang->line('exchanger_deposit') ?></a></li>
							<?php endif; ?>

							<?php if (hasAccessSubModulesModules('admin/exchanger-management/exchanger-withdraw')): ?>
								<li><a href="<?= base_url('admin/exchanger-management/exchanger-withdraw') ?>"><?= $this->lang->line('exchanger_withdraw') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('withdraw')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-briefcase-line"></i>
							<span><?= $this->lang->line('withdrawal') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/withdraw/user-withdraw-create')): ?>
								<li><a href="<?= base_url('admin/withdraw/user-withdraw-create') ?>"><?= $this->lang->line('user_withdraw') ?></a></li>
							<?php endif; ?>

							<?php if (ConfigData['enable_disable_ib_withdraw_admin']==true) :?>
								<li><a href="<?= base_url('admin/withdraw/user-ib-withdraw-create') ?>"><?= $this->lang->line('ib_commission_withdraw') ?></a></li>
							<?php endif; ?>

							<?php if (hasAccessSubModulesModules('admin/withdraw/user-request-withdraw-list')): ?>
								<li><a href="<?= base_url('admin/withdraw/user-request-withdraw-list') ?>"><?= $this->lang->line('request_withdrawal') ?></a></li>
							<?php endif; ?>

							<?php if (hasAccessSubModulesModules('admin/withdraw/user-reject-withdraw-list')): ?>
								<li><a href="<?= base_url('admin/withdraw/user-reject-withdraw-list') ?>"><?= $this->lang->line('rejected_withdrawal') ?></a></li>
							<?php endif; ?>

							<?php if (hasAccessSubModulesModules('admin/withdraw/approve-withdraw-list')): ?>
								<li><a href="<?= base_url('admin/withdraw/approve-withdraw-list') ?>"><?= $this->lang->line('approved_withdrawal') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('deposit')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-briefcase-line"></i>
							<span><?= $this->lang->line('deposit') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/deposit/user-deposit-create')): ?>
								<li><a href="<?= base_url('admin/deposit/user-deposit-create') ?>"><?= $this->lang->line('user_deposit') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/deposit/pending-deposit-list')): ?>
								<li><a href="<?= base_url('admin/deposit/pending-deposit-list') ?>"><?= $this->lang->line('pending_deposit') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/deposit/approve-deposit-list')): ?>
								<li><a href="<?= base_url('admin/deposit/approve-deposit-list') ?>"><?= $this->lang->line('approve_deposit') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/deposit/rejected-deposit-list')): ?>
								<li><a href="<?= base_url('admin/deposit/rejected-deposit-list') ?>"><?= $this->lang->line('cancel_deposit') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>


				<?php if (hasAccessModulesModules('transaction')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-folder-transfer-line"></i>
							<span><?= $this->lang->line('transaction') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/transaction/mt5-transactions-summery')): ?>
								<li><a href="<?= base_url() ?>admin/transaction/mt5-transactions-summery"><?= $this->lang->line('mt5_summary') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/transaction/user-internal-transfer')): ?>
								<li><a href="<?= base_url() ?>admin/transaction/user-internal-transfer"><?= $this->lang->line('internal_transfer') ?></a></li>
							<?php endif; ?>

							<li><a href="<?= base_url() ?>admin/transaction/user-wise-internal-transfer"><?= $this->lang->line('master_internal_transfer') ?></a></li>

							<?php if (hasAccessSubModulesModules('admin/transaction/internal-transfer-data-list')): ?>
								<li><a href="<?= base_url() ?>admin/transaction/internal-transfer-data-list"><?= $this->lang->line('internal_transfer_history') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/transaction/commission-transfer-list')): ?>
								<li><a href="<?= base_url() ?>admin/transaction/commission-transfer-list"><?= $this->lang->line('commission_transfer_history') ?></a></li>
							<?php endif; ?>

							<?php if (ConfigData['prefix'] != 'TG'): ?>
								<?php if (hasAccessSubModulesModules('admin/transaction/add-bonus')): ?>
									<li><a href="<?= base_url() ?>admin/transaction/add-bonus"><?= $this->lang->line('bonus_in') ?></a></li>
								<?php endif; ?>
								<?php if (hasAccessSubModulesModules('admin/transaction/bonus-list')): ?>
									<li><a href="<?= base_url() ?>admin/transaction/bonus-list"><?= $this->lang->line('bonus_list') ?></a></li>
								<?php endif; ?>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('traders') && ConfigData['prefix'] == 'SSM'): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-git-repository-private-fill"></i>
							<span><?= $this->lang->line('traders') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/traders/live-traders')): ?>
								<li><a href="<?= base_url() ?>admin/traders/live-traders"><?= $this->lang->line('live_trade') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/traders/close-traders')): ?>
								<li><a href="<?= base_url() ?>admin/traders/close-traders"><?= $this->lang->line('close_trade') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('manager')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
							<span><?= $this->lang->line('manager') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<?php if (hasAccessSubModulesModules('admin/manager/add-new-manager')): ?>
								<li><a href="<?= base_url() ?>admin/manager/add-new-manager"><?= $this->lang->line('add_manager') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/manager/manager-management')): ?>
								<li><a href="<?= base_url() ?>admin/manager/manager-management"><?= $this->lang->line('manager_list') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (ConfigData['ticket_enable_disable'] == true): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
							<span><?= $this->lang->line('ticket') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url() ?>admin/ticket/ticket-list"><?= $this->lang->line('ticket_list') ?></a></li>
							<li><a href="<?= base_url() ?>admin/ticket/close-ticket"><?= $this->lang->line('close_ticket') ?></a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('settings')): ?>
					<li>
						<a href="<?= base_url() ?>admin/settings" class="has-arrow waves-effect">
							<i class="ri-settings-2-fill"></i>
							<span><?= $this->lang->line('setting') ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('role')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-share-box-line"></i>
							<span><?= $this->lang->line('roles') ?></span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="true">
							<?php if (hasAccessSubModulesModules('admin/role/create-role')): ?>
								<li><a href="<?= base_url() ?>admin/role/create-role"><?= $this->lang->line('create_role') ?></a></li>
							<?php endif; ?>
							<?php if (hasAccessSubModulesModules('admin/role/role-list')): ?>
								<li><a href="<?= base_url() ?>admin/role/role-list"><?= $this->lang->line('role_list') ?></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?= base_url() ?>admin/activity/activity-logs" class="waves-effect">
						<i class="ri-auction-fill"></i>
						<span><?= $this->lang->line('activity_logs') ?></span>
					</a>
				</li>



			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
<!-- Left Sidebar End -->
