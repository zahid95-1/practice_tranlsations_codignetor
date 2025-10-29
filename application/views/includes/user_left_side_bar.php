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
        							<li><a href="<?=base_url()?>user/ib-commission-withdraw">IB Commission Withdraw</a></li>
        						    <?php endif; ?>
        							<li><a href="<?=base_url()?>user-ib-commission-group"><?= lang('ib_commission_setting') ?></a></li>
        							<!-- <li><a href="<?=base_url()?>user-ib-commission-ref">IB Commission Ref setting</a></li> -->
        							
        						</ul>
        					<?php }else{ ?>
        						<ul class="sub-menu mm-collapse" aria-expanded="false">
        							<li><a href="<?=base_url()?>user/ib-request">IB Request</a></li>
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
						<li><a href="<?php echo base_url() ?>user/my-mt5-demo-account-list">My MT5 Demo Account</a></li>
						<li><a href="<?php echo base_url() ?>user/open-demo-account">Open Demo Account</a></li>
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
						<li><a href="<?=base_url()?>user/internal-transfer-history"><?= lang('internal_transfer') ?> History</a></li>
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
							<li><a href="<?=base_url()?>user/ib-request">IB Request</a></li>
						</ul>
					<?php } ?>
				</li>

				<?php if (ConfigData['prefix']=='SSM'): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
						<span>My Trades</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="<?=base_url()?>user/live-traders">Live Trades</a></li>
						<li><a href="<?=base_url()?>user/close-traders">Close Trades</a></li>
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
						<span>Trading Platform</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<?php if (ConfigData['mt5_desktop_app']): ?>
							<li><a href="<?=ConfigData['mt5_desktop_app']?>">MT5 Desktop</a></li>
						<?php endif; ?>
						<?php if (ConfigData['mt5_android_app']): ?>
							<li><a href="<?=ConfigData['mt5_android_app']?>">MT5 Android</a></li>
						<?php endif; ?>
						<?php if (ConfigData['mt5_ios_app']): ?>
							<li><a href="<?=ConfigData['mt5_ios_app']?>">MT5 iOS</a></li>
						<?php endif; ?>
					</ul>
				</li>


				<?php if (ConfigData['prefix']=='SSM'): ?>
				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="ri-book-mark-fill"></i>
						<span>Mobile Apps
						</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="https://play.google.com/store/apps/details?id=com.shining_star_markets">Android </a></li>
						<li><a href="https://apps.apple.com/in/app/shining-star-markets/id6450272619">iOS</a></li>
					</ul>
				</li>
				<?php endif; ?>
				
				<?php if (ConfigData['prefix']=='IWY'): ?>
					<li>
						<a href="javascript: void(0);" class="has-arrow waves-effect">
							<i class="ri-book-mark-fill"></i>
							<span>Social Connections</span>
						</a>
						<ul class="sub-menu mm-collapse" aria-expanded="false">
							<li><a href="https://t.me/infowaymarkets" target="_blank">Telegram</a></li>
							<li><a href="https://wa.me/+971506706355" target="_blank">WhatsApp</a></li>
						</ul>
					</li>
				<?php endif; ?>

	<?php endif; ?>
			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
