<?php
$exchangerList		=isset($dataItem['exchangerlist'])?$dataItem['exchangerlist']:'';
$currencyList	=isset($dataItem['currencylist'])?$dataItem['currencylist']:'';
$bankList	=isset($dataItem['banklist'])?$dataItem['banklist']:'';

 ?>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">Transfer Exchanger</h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
								<li class="breadcrumb-item active">Transfer Exchanger</li>
							</ol>
						</div>

					</div>
				</div>
			</div>
			<!-- end page title -->
			<!-- end row -->
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<form class="custom-validation" action="<?php echo base_url()."save-transfer-exchanger"?>" method="post">
								
								<div class="auth-form-group-custom mb-4">
									<label for="transfer_from">Transfer From</label>
									<select class="form-control" name = "transfer_from" id="transfer_from">
										<option>Transfer from</option>
										<?php if ($exchangerList){
										foreach ($exchangerList as $key=>$item):
										?>
											<option value="<?=$item->exchanger_id?>"><?=$item->name?></option>
										<?php endforeach; } ?>
									</select>			
								</div>

								<div class="auth-form-group-custom mb-4">
									<label for="transfer_to">Transfer To</label>
									<select class="form-control" name = "transfer_to" id="transfer_to">
										<option>Transfer To</option>
										<?php if ($exchangerList){
										foreach ($exchangerList as $key=>$item):
										?>
											<option value="<?=$item->exchanger_id?>"><?=$item->name?></option>
										<?php endforeach; } ?>
									</select>
								</div>

								<div class="auth-form-group-custom mb-4">
									<label for="transfer_type">Transfer type</label>
									<select class="form-control" name = "transfer_type" id="transfer_type">
										<option value="1">Deposit</option>
										<option value="2">Withdraw</option>
									</select>			
								</div>

								<div class="auth-form-group-custom mb-4">
									<label for="amount">Amount</label>
									<input type="number" class="form-control" id="amount" placeholder="Amount" name="amount" required>
								</div>

								<div class="auth-form-group-custom mb-4">									<label for="first_name">Currrency</label>
									<select class="form-control" name = "from_currency" id="from_currency">
										<option>Currency</option>
										<?php if ($currencyList){
										foreach ($currencyList as $key=>$currency):
										?>
											<option value="<?=$currency->code?>"><?=$currency->code?></option>
										<?php endforeach; } ?>
									</select>
								</div>

								<div class="auth-form-group-custom mb-4">
									<label for="coverage_account_no">Coverage Account No</label>
									<input type="text" class="form-control" id="coverage_account_no" placeholder="Coverage Account No" name="coverage_account_no" required>
								</div>

								<div class="auth-form-group-custom mb-4">									<label for="bank_account_no">Bank Account No</label>
									<select class="form-control" name = "bank_account_no" id="from_currency">
										<option>Bank Account No</option>
										<?php if ($bankList){
										foreach ($bankList as $key=>$bank):
										?>
											<option value="<?=$bank->id?>"><?=$bank->account_no?></option>
										<?php endforeach; } ?>
									</select>
								</div>


								<div class="auth-form-group-custom mb-4">
									<label for="first_name">Note</label>
									<input type="text" class="form-control" id="note" placeholder="Note" name="note" required>
								</div>

								

								<div class="mb-0 col-md-6">
									<div>
										<button type="submit" class="btn btn-primary waves-effect waves-light me-1">
											Submit
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->

		</div> <!-- container-fluid -->
	</div>
</div>
