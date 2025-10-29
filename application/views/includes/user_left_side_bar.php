<div class="vertical-menu">

	<div data-simplebar class="h-100">

		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">
			    	<?php if (isset($_SESSION['open_ib']) && $_SESSION['open_ib']==true) :?>
			    		<li>
        					<a href="javascript: void(0);" class="has-arrow waves-effect">
        						<i class="ri-user-3-fill"></i>
        						<span><?= lang('ib_management') ?></span>
        					</a>
        					<?php
        					if (isset($_SESSION['ib_status']) && $_SESSION['ib_status']==1){
        					?>
        						<ul class="sub-menu mm-collapse" aria-expanded="false">
        							<li><a href="<?=base_url()?>user/ib-dashboard"><?= lang('ib_dashboard') ?></a></li>
        							<li><a href="<?=base_url()?>user/my-sub-ibs"><?= lang('my_sub_ibs') ?></a></li>
        								<li><a href="<?=base_url()?>user/my-clients"><?= lang('my_clients') ?></a></li>
        							<li><a href="<?=base_url()?>user/my-commission"><?= lang('my_commission') ?></a></li>
        
                                                                <li><a href="<?=base_url()?>user/level-wise-deposit-history"><?= lang('level_wise_deposit_history') ?></a></li>
                                                                <li><a href="<?=base_url()?>user/level-wise-withdrawal-history"><?= lang('level_wise_withdrawal_history') ?></a></li>
        
        							<li><a href="<?=base_url()?>user/ib-withdraw"><?= lang('commission_transfer_to_mt5') ?></a></li>
                                                                <?php if (ConfigData['enable_disable_ib_withdraw']==true) :?>
                                                                <li><a href="<?=base_url()?>user/ib-commission-withdraw"><?= lang('ib_commission_withdraw') ?></a></li>
                                                            <?php endif; ?>
        							<li><a href="<?=base_url()?>user-ib-commission-group"><?= lang('ib_commission_setting') ?></a></li>
        							<!-- <li><a href="<?=base_url()?>user-ib-commission-ref">IB Commission Ref setting</a></li> -->
        							
        						</ul>
        					<?php }else{ ?>
        						<ul class="sub-menu mm-collapse" aria-expanded="false">
                                                                <li><a href="<?=base_url()?>user/ib-request"><?= lang('ib_request') ?></a></li>
        						</ul>
        					<?php } ?>
        				</li>
			    		<?php else: ?>
			    		
				<li>
					<a href="<?php echo base_url() ?>user/dashboard" class="waves-effect">
						<i class="ri-dashboard-line"></i>
						<span><?= lang('dashboard') ?></span>
					</a>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-account-box-line"></i>
						<span><?= lang('my_profile') ?></span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<!-- <li><a href="ecommerce-products.html">View Profile</a></li> -->
				    	<li><a href="<?=base_url()?>user/kyc"><?= lang('kyc') ?></a></li>
						<li><a href="<?=base_url()?>user/change-crm-password"><?= lang('change_password_title') ?></a></li>
						<li><a href="<?=base_url()?>user/change-crm-pin"><?= lang('change_pin_title') ?></a></li>
						<li><a href="<?=base_url()?>user/bank-details"><?= lang('add_edit_bank_details') ?></a></li>
						<!--<li><a href="<?=base_url()?>user/coinpayment-address">Crypto Payment Address</a></li>-->
					</ul>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-briefcase-line"></i>
						<span><?= lang('trading') ?></span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?php echo base_url() ?>user/open-account"><?= lang('open_new_account') ?></a></li>
						<li><a href="<?php echo base_url() ?>user/my-mt5-account-list"><?= lang('my_mt5_account') ?></a>
							<?php if (ConfigData['prefix']!='TG'):?>
						<li><a href="<?php echo base_url() ?>user/change-leverage"><?= lang('change_leverage') ?></a></li>
						<?php endif; ?>
						<li><a href="<?php echo base_url() ?>user/change-mt5-pass"><?= lang('change_trading_password') ?></a></li>
						<?php if (ConfigData['enable_disable_demo_account']==true):?>
                                                <li><a href="<?php echo base_url() ?>user/my-mt5-demo-account-list"><?= lang('my_mt5_demo_account') ?></a></li>
                                                <li><a href="<?php echo base_url() ?>user/open-demo-account"><?= lang('open_demo_account') ?></a></li>
						<?php endif; ?>
					</ul>
				</li>


				<li>
					<a href="<?php echo base_url() ?>user/web-trade" class="waves-effect">
						<i class="ri-chrome-fill"></i>
						<span><?= lang('web_traders') ?></span>
					</a>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-folder-transfer-line"></i>
						<span><?= lang('funding') ?></span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>user/deposit"><?= lang('deposit') ?></a></li>
						<li><a href="<?=base_url()?>user/deposit/history"><?= lang('deposit_history_listing') ?></a></li>
						<li><a href="<?=base_url()?>user/withdraw"><?= lang('withdraw') ?></a></li>
						<li><a href="<?=base_url()?>user/withdraw/history"><?= lang('withdraw_history') ?></a></li>
						<li><a href="<?=base_url()?>user/internal-transfer"><?= lang('internal_transfer') ?> </a></li>
                                                <li><a href="<?=base_url()?>user/internal-transfer-history"><?= lang('internal_transfer_history') ?></a></li>
					</ul>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-user-3-fill"></i>
						<span><?= lang('ib_management') ?></span>
					</a>
					<?php
					if (isset($_SESSION['ib_status']) && $_SESSION['ib_status']==1){
					?>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="<?=base_url()?>user/open-ib-panel"><?= lang('ib_dashboard') ?></a></li>
						</ul>
					<?php }else{ ?>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
                                                        <li><a href="<?=base_url()?>user/ib-request"><?= lang('ib_request') ?></a></li>
						</ul>
					<?php } ?>
				</li>

				<?php if (ConfigData['prefix']=='SSM'): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
                                                <span><?= lang('my_trades') ?></span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="<?=base_url()?>user/live-traders"><?= lang('live_trades') ?></a></li>
                                                <li><a href="<?=base_url()?>user/close-traders"><?= lang('close_trades') ?></a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if (ConfigData['ticket_enable_disable']==true): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
						<span><?= lang('ticket') ?></span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>user/add-ticket"><?= lang('create_ticket') ?></a></li>
						<li><a href="<?=base_url()?>user/ticket-list"><?= lang('ticket_list') ?></a></li>
						<li><a href="<?=base_url()?>user/close-ticket"><?= lang('close_ticket') ?></a></li>
					</ul>
				</li>
				<?php endif; ?>




				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
                                                <span><?= lang('trading_platform') ?></span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <?php if (ConfigData['mt5_desktop_app']): ?>
                                                        <li><a href="<?=ConfigData['mt5_desktop_app']?>"><?= lang('mt5_desktop') ?></a></li>
                                                <?php endif; ?>
                                                <?php if (ConfigData['mt5_android_app']): ?>
                                                        <li><a href="<?=ConfigData['mt5_android_app']?>"><?= lang('mt5_android') ?></a></li>
                                                <?php endif; ?>
                                                <?php if (ConfigData['mt5_ios_app']): ?>
                                                        <li><a href="<?=ConfigData['mt5_ios_app']?>"><?= lang('mt5_ios') ?></a></li>
                                                <?php endif; ?>
                                        </ul>
                                </li>


				<?php if (ConfigData['prefix']=='SSM'): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
                                                <span><?= lang('mobile_apps') ?>
                                                </span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="https://play.google.com/store/apps/details?id=com.shining_star_markets"><?= lang('android_app') ?> </a></li>
                                                <li><a href="https://apps.apple.com/in/app/shining-star-markets/id6450272619"><?= lang('ios_app') ?></a></li>
                                        </ul>
                                </li>
				<?php endif; ?>
				
				<?php if (ConfigData['prefix']=='IWY'): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
                                                        <span><?= lang('social_connections') ?></span>
                                                </a>
                                                <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                        <li><a href="https://t.me/infowaymarkets" target="_blank"><?= lang('telegram') ?></a></li>
                                                        <li><a href="https://wa.me/+971506706355" target="_blank"><?= lang('whatsapp') ?></a></li>
						</ul>
					</li>
				<?php endif; ?>

	<?php endif; ?>
			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
