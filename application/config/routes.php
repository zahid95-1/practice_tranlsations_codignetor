<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'HomeController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['csv/upload/users'] = 'CSV/uploadUsers';
$route['csv/upload/group'] = 'CSV/uploadGroup';
$route['csv/upload/trading'] = 'CSV/uploadTradingAccount';

$route['csv/view'] = 'CSV/view';

/*------Frontend action event--------------------------*/
$route['login']														="LoginController";
$route['login-validate']											="LoginController/loginValidate/";
$route['login-validate-2']											="LoginController/loginValidateNewDesign/";
$route['logout']													="LoginController/logout";

$route['register']['get']											="RegistrationController";
$route['user-create-second-one']['post']							="RegistrationController/step_registrations";
$route['register-second-step']['get']								="RegistrationController/secondStepView";
$route['user-create-v2']											="RegistrationController/save_register_v2";
$route['user-registration']['post']									="RegistrationController/save_register_v2"; //for trimula

$route['user-create']												="RegistrationController/save_register";
$route['user-save-login-v2']										="RegistrationController/save_register_new_ui";
$route['forgot-password']['get']									="LoginController/forgotPassword";
$route['forgot-password']['post']									="LoginController/enterEmail";
$route['reset-password']['get']										="LoginController/changePassword";
$route['reset-password']['post']									="LoginController/enterPassword";
$route['forgot-password-2']['post']									="LoginController/enterEmailV2";

$route['reset-password-2']['get']									="LoginController/changePasswordV2";
$route['reset-password-2']['post']									="LoginController/enterPasswordV2";

/*------Backend action event--------------------------*/

$route['admin/dashboard']											="DashboardController/dashboard";
$route['currency-symbols']											="PartnersController/currencySymbol";
$route['currency-group']											="PartnersController/currencyGroup";
$route['email-template']											="MarketingController/index";
$route['email-template-create']										="MarketingController/createTemplate";

$route['error/404']													="ErrorController/NotFound";

/*----Admin Account routes----------*/
$route['admin/account/registered-account']							="RegisteredAccountController/registerAccount";
$route['admin/account/export-csv-account']							="RegisteredAccountController/exportCSV";
$route['admin/account/get-registered-data']							="RegisteredAccountController/getRegisterData";
$route['admin/account/get-registered-user-restall-data']			="RegisteredAccountController/getRestRegisterData";
$route['admin/account/get-kyc-data']								="RegisteredAccountController/getKycData";

$route['admin/account/add-new-user']								="RegisteredAccountController/createUser";
$route['admin/account/user-mt5-account-create']['get']				="RegisteredAccountController/openNewAccount";
$route['admin/account/user-mt5-account-create']['post']				="RegisteredAccountController/createLiveAccount";
$route['admin/account/user-trading-account-list']['get']			="RegisteredAccountController/Mt5AccountList";

/*----Currency Wise routes----------*/
$route['admin/account/add-symbol-share']['post']				="RegisteredAccountController/add_symbol_share";


/*----Ib management routes----------*/

//currencywise ib cal
$route['admin/ib-management/add-symbol-value']								="IBManagementController/add_symbol_value";
//currencywise ib cal


$route['admin/ib-management/old-ib-data']							="IBManagementController/oldibList";
$route['admin/ib-management/ib-users-list']							="IBManagementController/ibUserList";
$route['ib-client-list/(:any)']										="IBManagementController/ibClient/$1";
$route['admin/ib-management/ib-user-request']						="IBManagementController/ibRequest";
$route['admin/ib-management/rejected-ib-user-request']				="IBManagementController/rejectedIbRequest";
$route['admin/ib-management/ib-plan']								="IBManagementController/ibPlan";
$route['admin/ib-management/edit-ib-plan']							="IBManagementController/ibPlanEdit";
$route['save-commission-group']										="IBManagementController/saveIbCommissionGroup";
$route['save-commission-group-admin']								="IBManagementController/saveIbCommissionGroupAdmin";
$route['admin/ib-management/assign-ib']								="IBManagementController/assignIB";
$route['remove-ib']													="IBManagementController/removeIB";
$route['save-ib-plan'] 												="IBManagementController/ibSaveData";
$route['admin/ib-management/commission-setting']					="IBManagementController/ibCommissionSetting";
$route['admin/ib-management/commission-group']						="IBManagementController/ibCommissionGroup";

$route['admin/ib-management/commission-group-by-level']				="IBManagementController/UserIBCommGroupByLevel";
$route['admin/ib-management/commission-group-level']['get']			="IBManagementController/ibCommissionGroupLevelView";
$route['admin/ib-management/commission-group-level']['post']		="IBManagementController/saveIbCommisionLevel";



$route['view-commission-group-level/(:any)/(:any)']					= "IBManagementController/viewLevelCommissionGroup/$1/$2";



/*=======Start IB Commission Generation Based on Refereal Link=======*/
$route['admin/ib-management/commission-ref-by-level']				="IBManagementController/UserIBCommRefByLevel";
$route['admin/ib-management/commission-ref-level']['get']			="IBManagementController/ibCommissionRefLevelView";
$route['admin/ib-management/commission-ref-level']['post']		="IBManagementController/saveIbCommisionRef";
$route['view-commission-ref-level/(:any)/(:any)']					= "IBManagementController/viewLevelCommissionRef/$1/$2";


$route['admin/ib-management/commission-ref-level-more/(:any)/(:any)']['get']			="IBManagementController/ibCommissionRefLevelViewAddMore/$1/$2";
$route['admin/ib-management/commission-ref-level-more/']['post']		="IBManagementController/saveIbCommisionRefMore/";


$route['user-ib-commission-ref']									="IBManagementController/UserIBCommRef";
$route['save-commission-ref']										="IBManagementController/saveIbCommissionRef";
/*=======End IB Commission Generation Based on Refereal Link=======*/


$route['admin/ib-management/edit-commission-group']					="IBManagementController/ibEditCommissionGroup";
$route['view-commission-group/(:any)/(:any)']						= "IBManagementController/viewCommissionGroup/$1/$2";

$route['view-commission-plan/(:any)']						= "IBManagementController/viewCommissionPlan/$1";

/*$route['view-commission-group-master/(:any)/(:any)/(:any)']			= "IBManagementController/viewCommissionGroupMaster/$1/$2/$3";
*/
$route['view-commission-group-master/(:any)/(:any)']			= "IBManagementController/viewCommissionGroupMaster/$1/$2";

$route['approved-ib-request']										="IBManagementController/approveIbRequest";
$route['approved-ib-admin-request']										="IBManagementController/approveIbAdminRequest";
$route['rejected-ib-request']										="IBManagementController/RejectedRequest";
$route['user-ib-commission-group']									="IBManagementController/UserIBCommGroup";
$route['add-more-ref-link/(:any)/(:any)']									="IBManagementController/AddMoreRefLink/$1/$2";


$route['user-ib-downline-share']									="IBManagementController/GetDownlineCommissionShare";

/*----exchange management routes----------*/
$route['admin/exchanger-management/exchanger-list']['get']					="ExchangerManagementController/exchangerList";

$route['admin/exchanger-management/add-exchanger']				="ExchangerManagementController/addExchanger";

$route['save-new-exchanger'] = "ExchangerManagementController/save_new_exchanger";
$route['edit-exchanger'] = "ExchangerManagementController/edit_exchanger";

$route['save-edit-exchanger'] = "ExchangerManagementController/save_edit_exchanger";

$route['admin/exchanger-management/transfer-exchanger']				="ExchangerManagementController/transfer_exchanger";

$route['save-transfer-exchanger']				="ExchangerManagementController/save_transfer_exchanger";
$route['admin/exchanger-management/add-bank-details']				="ExchangerManagementController/add_bank_details";

$route['admin/exchanger-management/exchanger-deposit']				="ExchangerManagementController/exchanger_deposit";

$route['admin/exchanger-management/exchanger-withdraw']				="ExchangerManagementController/exchanger_withdraw";





$route['save-bank-details']				="ExchangerManagementController/save_bank_details";


/*----exchange management routes----------*/


/*----group management routes----------*/
$route['admin/group-management/group-list']['get']					="GroupManagementController/groupList";
$route['admin/group-management/group-user-list/(:num)']['get']		="GroupManagementController/viewDetails/$1";
$route['admin/group-management/create-group']['get']				="GroupManagementController/createGroup";
$route['store-group']['post']										="GroupManagementController/storeGroup";
$route['edit-group-user-list/(:num)']['get']						="GroupManagementController/editGroup/$1";
$route['update-group']['post']										="GroupManagementController/updateGroup";
$route['admin/group-management/update-client-group']['get']			="GroupManagementController/updateClientGroup";
$route['change-client-group']['post']								="GroupManagementController/changeClientGroup";

/*--------Admin-Withdraw Routes----------*/
//$route['admin/withdraw/user-request-withdraw-list']['get']			="WithdrawManagementController/requestWithdrawList";
$route['admin/withdraw/user-reject-withdraw-list']['get']			="WithdrawManagementController/rejectedWithdrawList";
$route['admin/withdraw/user-withdraw-create']['get']				="WithdrawManagementController/userWithdrawAmount";
$route['admin/withdraw/user-withdraw-create']['post']				="WithdrawManagementController/paidUserWithdrawAmount";
$route['get-user-bank-account-details']['post']						="WithdrawManagementController/getUserBankAndAccountDetails";
$route['get-ib-user-bank-account-details']['post']					="WithdrawManagementController/getIbUserBankAndAccountDetails";
$route['user-single-withdraw-item-details/(:num)']['get']			="WithdrawManagementController/withDrawDetails/$1";
$route['change-withdraw-status']['post']							="WithdrawManagementController/changeWithdrawStatus";
$route['admin/withdraw/approve-withdraw-list']['get']				="WithdrawManagementController/approveWithdrawList";
$route['admin/withdraw/user-ib-withdraw-create']['get']				="WithdrawManagementController/userIbWithdrawAmount";
$route['admin/withdraw/user-ib-withdraw-create']['post']			="WithdrawManagementController/paidUserIbWithdrawAmount";

/*--------Admin-Deposit Routes----------*/
$route['admin/deposit/pending-deposit-list']['get']					="DepositManagementController/pendingDepositList";
$route['admin/deposit/approve-deposit-list']['get']					="DepositManagementController/approveDepositList";
$route['admin/deposit/rejected-deposit-list']['get']				="DepositManagementController/rejectedDepositList";
$route['user-single-deposit-item-details/(:num)']['get']			="DepositManagementController/depositDetails/$1";
$route['change-payment-status']['post']								="DepositManagementController/changePaymentStatus";
$route['admin/deposit/user-deposit-create']['get']					="DepositManagementController/userDepositAmount";
$route['admin/deposit/user-deposit-create']['post']					="DepositManagementController/approveDepositAmount";

/*------------Admin Transactions Routes-----------*/
$route['admin/transaction/mt5-transactions-summery']['get']			="TransactionsController/mt5TransactionsSummary";
$route['admin/transaction/user-internal-transfer']['get']			="TransactionsController/internalTransfer";
//$route['admin/transaction/user-internal-transfer']['post']			="TransactionsController/saveInternalTransfer";
$route['admin/transaction/add-bonus']['get']						="TransactionsController/addBonus";
$route['create-bonus']['post']										="TransactionsController/createBonus";
$route['admin/transaction/bonus-list']['get']						="TransactionsController/bonusList";
$route['admin/transaction/internal-transfer-data-list']['get']		="TransactionsController/internalTransferHistory";
$route['admin/transaction/commission-transfer-list']['get']			="TransactionsController/commissionTransferHistory";
$route['commission-transfer-details/(:num)']['get']					="TransactionsController/commissionTransferHistoryDetails/$1";
$route['change-commission-transfer-status']['post']					="TransactionsController/changeCommissionTransferStatus";

$route['admin/transaction/user-wise-internal-transfer']['get']			="TransactionsController/internalTransferUserWise";
//$route['admin/transaction/user-wise-internal-transfer']['post']			="TransactionsController/saveInternalTransferUserWise";

/*----------Admin Traders---------------------*/
$route['admin/traders/live-traders']['get']							="LiveTradeController/liveTrades";
$route['admin/traders/close-traders']           					="LiveTradeController/closeTraders";

/*----------Managers---------------------*/
$route['admin/manager/add-new-manager']['get']						="ManagerController/addManager";
$route['admin/create-manager']['post']								="ManagerController/createManager";
$route['admin/manager/manager-management']							="ManagerController/managementManager";
$route['admin/manager/manager-management/asin-permission/(:any)']	="ManagerController/assignPermission/$1";
$route['admin/manager/manager-management/auth/status-change']		="ManagerController/authStatusChange";

/*-----------Settings----------------*/
$route['admin/settings']										 	="SettingsController/getSettings";
$route['save-email-configurations']['post']				 			="SettingsController/saveEmailConfigurations";
$route['save-sms']['post']				 				 			="SettingsController/saveSms";
$route['save-logo']['post']				 							="SettingsController/saveLogo";
$route['save-currency']['post']				 			 			="SettingsController/saveCurrency";
$route['save-withdraw-deposit-rate']['post']				 		="SettingsController/saveWithdrawDepositRate";
$route['save-min-withdraw']['post']				 					="SettingsController/saveMinWithdrawAmt";
$route['save-paypal-settings']['post']				 			    ="SettingsController/savePaypalSettings";

$route['save-copy-right']['post']				 		 			="SettingsController/saveCopyRight";
$route['save-meta-data']['post']				 		 			="SettingsController/saveMetaData";
$route['save-bg-image']['post']				 		      			="SettingsController/saveBgImage";

/*----Role modiules-------------*/
$route['admin/role/create-role']['get']								="RoleController/createRoleView";
$route['admin/role/create-role']['post']							="RoleController/storeRole";
$route['update-role']['post']										="RoleController/updateRole";
$route['admin/role/role-list']['get']								="RoleController/roleList";
$route['admin/role/edit-role/(:num)']['get']						="RoleController/editRoleView/$1";

/*----Register Account-------------*/
$route['save-new-user']												="RegisteredAccountController/save_new_user";
$route['add-manager']												="RegisteredAccountController/add_manager";
$route['uploaded-kyc']												="RegisteredAccountController/upload_kyc";
$route['kyc-attachment-verified']									="RegisteredAccountController/kyc_attachment_verified";
$route['kyc-residency-attachment-verified']							="RegisteredAccountController/kyc_residency_attachment_verified";
$route['change-ib-status']											="RegisteredAccountController/change_ib_status";
$route['edit-user-profile']											="RegisteredAccountController/edit_user_profile";
$route['update-user-info']											="RegisteredAccountController/update_user_info";
$route['login-user-profile']										="RegisteredAccountController/login_user";
$route['activate-account']										="RegisteredAccountController/activate_account";
$route['login-admin-profile']										="DashboardController/login_admin";

/*----------Activity log Traders---------------------*/
$route['admin/activity/activity-logs']['get']							="ActivityLogController/getActivityLog";
$route['admin/activity/activity-logs-data']['get']						="ActivityLogController/getTableData";


################################################# User All Listed Routes ################################################

$route['user/dashboard']				="UserDashboardController/userDashboard";
$route['user/kyc']						="ProfileController/kyc";
$route['user/change-crm-password']		="ProfileController/change_password";
$route['user/change-crm-pin']			="ProfileController/change_pin";
$route['user/update-crm-password']		="ProfileController/update_password";
$route['user/update-crm-pin']		="ProfileController/update_pin";
$route['user/bank-details']				="ProfileController/bank_details";

$route['submit-bank-details']			="ProfileController/submit_bank_details";
$route['user/coinpayment-address']		="ProfileController/coinpayment_address";
$route['submit-coinpayment-address']	="ProfileController/submit_coinpayment_address";
$route['get-active-coins']				="ProfileController/get_active_coins";


/*---------------------Payment----------------*/
$route['crypto-payment']="PaymentController/crypto_payment";
$route['checkout-coinpayment-amount']="PaymentController/checkout_coinpayment_amount";
$route['my-transaction']="PaymentController/my_transaction";


/*--------------User Routes-----------------*/

/*--------------Trading Route-----------------*/
$route['user/open-account']['get']					="TradingController/openNewAccount";
$route['user/user-create-live-account']['post']		="TradingController/createLiveAccount";
$route['user/my-mt5-account-list']['get']			="TradingController/myMt5AccountList";
$route['user/my-mt5-account-list/details']['post']	="TradingController/getAccountDetails";
$route['user/change-leverage']['get']				="TradingController/changeLeverage";
$route['user/update-leverage']['post']				="TradingController/updateLeverage";
$route['user/change-mt5-pass']['get']				="TradingController/changeMt5Password";
$route['user/update-mt5-password']['post']			="TradingController/updateMt5Password";
$route['user/account-list']							="TradingController/accountList";

/*------------------Funding Routes--------------*/
$route['user/deposit']								="FundingController/deposit";
$route['user/deposit/wire-transfer']['get']			="FundingController/depositWireTransfer";
$route['user/deposit/deposit-wire-transfer']['post']="FundingController/saveDepositWireTransfer";
$route['user/deposit/paypal']						="FundingController/depositPaypal";
$route['user/deposit/history']['get']				="FundingController/depositHistory";
$route['user/deposit/deposit-paypal']['post']		="FundingController/saveDepositPaypal";
$route['user/deposit/execute-payment'] 				= 'FundingController/executePayment';

$route['user/deposit/execute-payment-stripe'] 		= 'FundingController/executePaymentStripe';

//For API Only
$route['user/deposit/initializations']['post']					="FundingController/initialDeposit";
$route['user/deposit/execute-initializations-payment']['post'] 	='FundingController/initialDepositExecuted';


$route['user/deposit/deposit-stripe']['post']		="FundingController/saveDepositStripe";
$route['user/deposit/stripe']						="FundingController/depositStripe";


$route['user/withdraw']['get']							="FundingController/withdrawAmount";
$route['user/withdraw']['post']							="FundingController/saveWithdrawAmount";
$route['user/withdraw/get-account-balance']['post']		="FundingController/getAvailableBalance";
$route['user/withdraw/history']['get']					="FundingController/withdrawHistory";
//$route['user/internal-transfer-history']['get']			="FundingController/internalTransferHistory";
$route['user/ib-commission-withdraw']['get']			="FundingController/withdrawIbCommission";
$route['user/ib-commission-withdraw']['post']			="FundingController/saveWithdrawIbCommission";

$route['user/internal-transfer']['get']					="FundingController/internalTransfer";
$route['user/get-trading-account']['post']				="FundingController/getTradingAccount";
//$route['user/internal-transfer']['post']				="FundingController/saveInternalTransfer";


$route['user/ib-request']['get']						="IBRequestController/ibRequest";
$route['user/change-ib-status']['post']				     ="IBRequestController/changeIbStatus";


/*------------------CRON JOB Routes For Getting Pull Live Trade--------------*/
$route['get-live-trade-latest']['get']							="LiveTradeController";
$route['get-live-trade-manual']['get']					="LiveTradeController/manualLoad";
$route['get-live-balance-latest']['get']					    ="LiveTradeController/getLiveBalance";
$route['get-trigger-live-trade']['get']					="LiveTradeController/getLiveTrade";

/*-----------Ib Dashboard------------*/
$route['user/my-clients']['get']						="IBRequestController/myClient";
$route['user/ib-dashboard']['get']						="IBRequestController/ibDashboard";
$route['user/my-sub-ibs']['get']						="IBRequestController/mySubIbs";
$route['user/my-commission']        					="IBRequestController/myCommission";
$route['user/ib-withdraw']['get']						="IBRequestController/ibWithdraw";
$route['user/get-commission-amount']['post']			="IBRequestController/getCommissionAmount";
$route['user/commission-transfer']['post']				="IBRequestController/commissionTransfer";

$route['user/level-wise-deposit-history']						="IBRequestController/LevelWiseDepositHistory";
$route['user/level-wise-withdrawal-history']						="IBRequestController/LevelWiseWithdrawalHistory";


/*-------------My live Trades------------------------*/
$route['user/live-traders']									="MyLiveTradeController/liveTrades";
$route['user/close-traders']            							="MyLiveTradeController/closeTraders";


/*---for admin demo account list-----*/
$route['admin/account/user-trading-demo-account-list']['get']		="RegisteredAccountController/Mt5DemoAccountList";

/*---User Panel Demo Account---------------------*/
$route['user/open-demo-account']['get']								="TradingController/openNewDemoAccount";
$route['user/user-create-demo-live-account']['post']				="TradingController/createLiveDemoAccount";
$route['user/my-mt5-demo-account-list']['get']						="TradingController/myMt5DemoAccountList";

/*--Bulk EMail Send*/
$route['admin/bulk-email']            				   				="BulkEmailController/send";

$route['admin/import-bank-details']            				   		="ImportController/importbankDetails";
$route['admin/remove-trading-acount']            				   	="ImportController/removeTradingAccount";

/*-----Common API list--------*/
$route['api/get-country-list']										 ="UserDashboardController/getCountryList";


/*-----Common API list--------*/
$route['user/add-ticket']['get']										="TicketsController/createTicketView";
$route['user/add-ticket']['post']										="TicketsController/saveTicket";
$route['user/add-feedback']['post']										="TicketsController/saveFeedback";
$route['user/ticket-list']['get']										="TicketsController/ticketListView";
$route['user/close-ticket']['get']										="TicketsController/closeTicket";
$route['user/ticket-list/(:num)']['get']								="TicketsController/detailsTicket/$1";

//For Admin
$route['admin/ticket/ticket-list']['get']								="AdminTicketsController/ticketListView";
$route['admin/ticket/close-ticket']['get']								="AdminTicketsController/closeTicket";
$route['admin/ticket/ticket-list/(:num)']['get']						="AdminTicketsController/detailsTicket/$1";
$route['admin/ticket/add-admin-feedback']['post']						="AdminTicketsController/saveFeedback";

//API
$route['api/get-deposit-rate']										 	="SettingsController/getDepositRate";


//Delete Account
$route['delete-account']					="ProfileController/deleteUserAccount";
//KYC
$route['admin/user-kyc-list']['get']									="RegisteredAccountController/kycVerifiedList";
$route['admin/blank-kyc-upload-user-list']['get']						="RegisteredAccountController/blankKycUploadList";

$route['kyc-residency-back-part-attachment-verified']				="RegisteredAccountController/kyc_residency_attachment_verified_back";


$route['get-trigger-symbols']['get']									="LiveTradeController/getLiveSymbols";
$route['user/web-trade']												="UserDashboardController/userWeBTrade";

$route['otp-less-account-create']										="RegistrationController/save_otp_less";
$route['otp-less-account-redirect']										="RegistrationController/setSession";

$route['authorize-gmail']												="LoginController/authorizeGmail";
$route['authorize-facebook']											="LoginController/authorizeFacebook";
$route['check-email']												    ="LoginController/CheckAuth";


$route['user/open-ib-panel']['get']						    ="IBRequestController/OpenIbDashboard";
$route['user/back-user-panel']['get']						="IBRequestController/CloseIbDashboard";


$route['resend-email']											="RegisteredAccountController/resendEmail";
$route['resend-ib-mail/(:any)']									="IBManagementController/resendIbEmail/$1";

$route['resend-trading-account-opening-mail/(:any)']			="RegisteredAccountController/resendTradingAccountOpeningMail/$1";

