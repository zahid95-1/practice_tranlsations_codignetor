<?php
/*===================GetUserInfo=========================*/
$unique_id=$_SESSION['unique_id'];
$getCountry = $this->db->query("SELECT id,nicename FROM country");

$accountName=$accountNo=$bankTrxCode=$internationalBankAccount=$bankName=$bankAddress=$country=$swiftCode='';
$disableClass='';
if (isset($dataItem) && !empty($dataItem)){
	$disableClass				='disable-input';
	$accountName				=$dataItem->account_name;
	$accountNo					=$dataItem->account_number;
	$bankTrxCode				=$dataItem->trx_code;
	$internationalBankAccount	=$dataItem->international_bank_account_number;
	$bankName					=$dataItem->bank_name;
	$bankAddress				=$dataItem->bank_address;
	$country					=$dataItem->country_id;
	$swiftCode					=$dataItem->swift_code;

}
?>
<style>
	.bg-danger {
		background-color: #ff3d60!important;
		width: 43%;
		padding: 8px;
	}
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
	.disable-input {
		background: #80808012;
		pointer-events: none;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0"><?= lang('add_edit_bank_details') ?></h4>

						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?= lang('home') ?></a></li>
								<li class="breadcrumb-item active"><?= lang('add_edit_bank_details') ?></li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">

							<!-- <?php if ($disableClass): ?>
								<div class="alert alert-danger" role="alert">
									Due to security reason you cannot edit bank details once you submitted. Please contact our support team in order to change bank details.
								</div>
							<?php endif; ?> -->

							<?php if ($this->session->flashdata("msg")): ?>
								<div class="alert alert-success" role="alert">
									<?=$this->session->flashdata("msg")?>
								</div>
							<?php endif; ?>

							<form method="post" class="form-control" autocomplete="off" action="<?php echo base_url(); ?>submit-bank-details" id="changePassword"  data-key-index="0">

					<input type="hidden" value="<?php echo $unique_id;?>" name="uid" id="uid">
					<div class="wrapping">
						<div class="row mb-3">
							<label for="account_name" class="col-sm-2 col-form-label"><?= lang('account_holder_name') ?>:<span style="color:red;">
								*<span/></label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="account_name" name="account_name" required placeholder="<?= lang('enter_account_holder_name') ?>" value="<?=$accountName?>">
							</div>
						</div>
						<div class="row mb-3">
							<label for="bank_name" class="col-sm-2 col-form-label"><?= lang('bank_name') ?>:<span style="color:red;">
								*<span/></label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="bank_name" name="bank_name" required  placeholder="Enter Bank Name" value="<?=$bankName?>">
							</div>
						</div>
						<div class="row mb-3">
							<label for="account_number" class="col-sm-2 col-form-label"><?= lang('account_number') ?>:<span style="color:red;">
								*<span/></label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="account_number" name="account_number" required placeholder="Enter Account Number" value="<?=$accountNo?>">
							</div>
						</div>

						<div class="row mb-3">
							<label for="bank_trx_code" class="col-sm-2 col-form-label"><?= lang('bank_trx_code') ?>:<span style="color:red;">
								*<span/>
							</label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="bank_trx_code" name="bank_trx_code" required  placeholder="<?= lang('bank_trx_code') ?>" value="<?=$bankTrxCode?>">
							</div>
						</div>

						<div class="row mb-3">
							<label for="bank_address" class="col-sm-2 col-form-label"><?= lang('swift_code') ?>:</label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="bank_address" name="swift_code"  placeholder="<?= lang('swift_code') ?>" value="<?=$swiftCode?>">
							</div>
						</div>

						<div class="row mb-3">
							<label for="bank_address" class="col-sm-2 col-form-label"><?= lang('bank_branch') ?>:<span style="color:red;">
								*<span/> </label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="bank_address" name="bank_address" required  placeholder="<?= lang('bank_branch') ?>" value="<?=$bankAddress?>">
							</div>
						</div>
						<div class="row mb-3">
							<label for="country_id" class="col-sm-2 col-form-label"><?= lang('country') ?>:<span style="color:red;">
								*<span/>
							</label>
							<div class="col-sm-4">
								<select class="form-select " id="country_id" name="country_id" required>
									<option selected disabled value=""><?= lang('select_country') ?></option>
									<?php
									foreach($getCountry->result() as $Country){
										$countryId = $Country->id ;
										$countryName = $Country->nicename ;
										?>
										<option value="<?php echo $countryId ?>" <?php if ($country==$countryId){echo "selected";} ?>><?php echo $countryName ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>

<!--						<div class="row mb-3">-->
<!--							<label for="international_bank_account_no" class="col-sm-2 col-form-label">International Bank Account Num:<span style="color:red;">-->
<!--								*<span/></label>-->
<!--							<div class="col-sm-4">-->
<!--								<input type="text" class="form-control" id="international_bank_account_no" name="international_bank_account_no" required  placeholder="Enter International bank account No" value="--><?//=$internationalBankAccount?><!--">-->
<!--							</div>-->
<!--						</div>-->

					</div>
					
						<div class="row" style="margin-left: 259px;margin-top: 25px;width: 100%">
							<span style="color:green;"></span>
							<button class="btn btn-primary" type="submit" id="uploadKycBtn-0" style="width: 180px;"><?= lang('add_bank_details') ?></button>
						</div>
					
				</form>
						</div>
					</div>
				</div>
			</div>
			<!-- end row -->
		</div> <!-- container-fluid -->
	</div>
</div>
<!-- end main content-->




