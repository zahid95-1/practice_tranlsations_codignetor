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
						<span>Dashboard 仪表盘</span>
					</a>
				</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('account')): ?>
				<li >
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-account-box-line"></i>
						<span>Accounts 账户</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>admin/account/registered-account">Registered Account 注册账户</a></li>
						<li><a href="<?=base_url()?>admin/account/add-new-user">Add New User 添加新用户</a></li>
						<li><a href="<?=base_url()?>admin/account/user-mt5-account-create">Open Trading Account 开设交易账户</a></li>
						<li><a href="<?=base_url()?>admin/account/user-trading-account-list">Trading Account List 交易账户列表</a></li>
						<li><a href="<?=base_url()?>admin/account/user-trading-demo-account-list">KYC List KYC列表</a></li>
						<li><a href="<?=base_url()?>admin/user-kyc-list">KYC List KYC列表</a></li>
						<li><a href="<?=base_url()?>admin/blank-kyc-upload-user-list">Non KYC 未完成KYC</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('ib-management')): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-user-3-fill"></i>
						<span>IB Management IB管理</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>admin/ib-management/ib-user-request">IB Request IB请求</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/rejected-ib-user-request">Rejected IB List 被拒绝的IB列表</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/ib-users-list">IB List IB用户列表</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/ib-plan">IB Plan IB计划</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/commission-group">Commission Group 佣金组</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/commission-ref-by-level">Commission Ref 推荐佣金</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/add-symbol-value">Add Symbol Share 添加符号份额</a></li>
						<li><a href="<?=base_url()?>admin/ib-management/assign-ib">Change Upline IB 更改上级IB</a></li>
						<li><a href="<?=base_url()?>remove-ib">Remove IB 移除IB</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('group-management')): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-user-3-fill"></i>
						<span>Group Management 组管理</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>admin/group-management/group-list">Group List 组列表</a></li>
						<li><a href="<?=base_url()?>admin/group-management/create-group">Add Group 添加组</a></li>
						<li><a href="<?=base_url()?>admin/group-management/update-client-group">Update Client Group 更新客户组</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('exchanger-management')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-arrow-right-line"></i>
							<span>Exchanger Management 兑换管理</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url('admin/exchanger-management/exchanger-list') ?>">Exchanger List 兑换商列表</a></li>
							<li><a href="<?= base_url('admin/exchanger-management/add-exchanger') ?>">Add Exchanger 添加兑换商</a></li>
							<li><a href="<?= base_url('admin/exchanger-management/transfer-exchanger') ?>">Transfer Exchanger 转账兑换商</a></li>
							<li><a href="<?= base_url('admin/exchanger-management/add-bank-details') ?>">Add Bank Details 添加银行信息</a></li>
							<li><a href="<?= base_url('admin/exchanger-management/exchanger-deposit') ?>">Exchanger Deposit 兑换商存款</a></li>
							<li><a href="<?= base_url('admin/exchanger-management/exchanger-withdraw') ?>">Exchanger Withdraw 兑换商取款</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('withdraw')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-briefcase-line"></i>
							<span>Withdrawal 取款</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url('admin/withdraw/user-withdraw-create') ?>">User Withdraw 用户取款</a></li>
							<li><a href="<?= base_url('admin/withdraw/user-ib-withdraw-create') ?>">IB Commission Withdraw IB佣金取款</a></li>
							<li><a href="<?= base_url('admin/withdraw/user-request-withdraw-list') ?>">Request Withdrawal 请求取款</a></li>
							<li><a href="<?= base_url('admin/withdraw/user-reject-withdraw-list') ?>">Rejected Withdrawal 被拒绝的取款</a></li>
							<li><a href="<?= base_url('admin/withdraw/approve-withdraw-list') ?>">Approved Withdrawal 批准的取款</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('deposit')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-briefcase-line"></i>
							<span>Deposit 存款</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url('admin/deposit/user-deposit-create') ?>">User Deposit 用户存款</a></li>
							<li><a href="<?= base_url('admin/deposit/pending-deposit-list') ?>">Pending Deposit 待处理存款</a></li>
							<li><a href="<?= base_url('admin/deposit/approve-deposit-list') ?>">Approve Deposit 批准存款</a></li>
							<li><a href="<?= base_url('admin/deposit/rejected-deposit-list') ?>">Cancel Deposit 取消存款</a></li>
						</ul>
					</li>
				<?php endif; ?>


				<?php if (hasAccessModulesModules('transaction')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-folder-transfer-line"></i>
							<span>Transaction 交易</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url() ?>admin/transaction/mt5-transactions-summery">MT5 Summary MT5汇总</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/user-internal-transfer">Internal Transfer 内部转账</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/user-wise-internal-transfer">Master Internal Transfer 主内部转账</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/internal-transfer-data-list">Internal Transfer History 内部转账历史</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/commission-transfer-list">Commission Transfer History 佣金转账历史</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/add-bonus">Bonus In 奖金入账</a></li>
							<li><a href="<?= base_url() ?>admin/transaction/bonus-list">Bonus List 奖金列表</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('traders') && ConfigData['prefix'] == 'SSM'): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-git-repository-private-fill"></i>
							<span>Traders 交易员</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url() ?>admin/traders/live-traders">Live Trade 实时交易</a></li>
							<li><a href="<?= base_url() ?>admin/traders/close-traders">Close Trade 关闭交易</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('manager')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
							<span>Manager 经理</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url() ?>admin/manager/add-new-manager">Add Manager 添加经理</a></li>
							<li><a href="<?= base_url() ?>admin/manager/manager-management">Manager List 经理列表</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (ConfigData['ticket_enable_disable'] == true): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
							<span>Ticket 工单</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?= base_url() ?>admin/ticket/ticket-list">Ticket List 工单列表</a></li>
							<li><a href="<?= base_url() ?>admin/ticket/close-ticket">Close Ticket 关闭工单</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('settings')): ?>
					<li>
						<a href="<?= base_url() ?>admin/settings" class="has-arrow waves-effect">
							<i class="ri-settings-2-fill"></i>
							<span>Setting 设置</span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (hasAccessModulesModules('role')): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-share-box-line"></i>
							<span>Roles 角色</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="true">
							<li><a href="<?= base_url() ?>admin/role/create-role">Create Role 创建角色</a></li>
							<li><a href="<?= base_url() ?>admin/role/role-list">Role List 角色列表</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?= base_url() ?>admin/activity/activity-logs" class="waves-effect">
						<i class="ri-auction-fill"></i>
						<span>Activity Logs 活动日志</span>
					</a>
				</li>



			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
<!-- Left Sidebar End -->
