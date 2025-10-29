<?php
$errorObject=$requestData='';
if (isset($_SESSION['error_withdraw'])){
	$errorObject	=json_decode($_SESSION['error_withdraw']);
}
?>

<style>
	.page-content {
		/*padding: calc(39px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	button.disable-btn {
		background: #5664d2bd;
		pointer-events: none;
	}
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<!-- start page title -->
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0"><?=$this->lang->line('add_user_bonus')?></h4>
						<div class="page-title-right">
							<ol class="breadcrumb m-0">
								<li class="breadcrumb-item"><a href="javascript: void(0);"><?=$this->lang->line('home')?></a></li>
								<li class="breadcrumb-item active"><?=$this->lang->line('withdraw')?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-lg-12">

					<?php if (isset($_SESSION['success_withdraw'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_withdraw']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_withdraw']); endif; ?>

					<div class="card">
						<div class="card-body row">
							<div class="col-md-6">

								<form action="<?=base_url('create-bonus')?>" method="post" id="depositWireTransfer" enctype="multipart/form-data">

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('selected_account')?> <span class="error">*</span></label>
										<select class="form-control select2" name="unique_id" id="selectedUser">
											<option value=""><?=$this->lang->line('select_account')?></option>
											<?php if (isset($dataItem)): foreach ($dataItem as $item): ?>
												<option value="<?=$item->unique_id?>">
													<?=$item->first_name.' '.$item->last_name.' ('.$item->mt5_login_id.') ('.$item->group_name.')'?>
												</option>
											<?php endforeach; endif; ?>
										</select>
										<span class="error"><?=isset($errorObject->mt5_login_id) ? $errorObject->mt5_login_id : ''?></span>
									</div>

									<div class="col-sm-12">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('amount_usd')?> <span class="error">*</span></label>
										<input class="form-control" type="text" id="amountWithdraw" name="amount" placeholder="<?=$this->lang->line('enter_amount_usd')?>" required>
										<span class="error" id="amountErr"><?=isset($errorObject->amount) ? $errorObject->amount : ''?></span>
										<?php if (isset($errorObject->verified_status)): ?>
											<span class="error"><?=$errorObject->verified_status?></span>
										<?php endif; ?>
									</div>

									<div class="col-sm-12 mt-3">
										<label class="col-sm-12 col-form-label"><?=$this->lang->line('note')?> <span class="error">*</span></label>
										<textarea class="form-control" name="meta_descriptions" style="height: 100px;"></textarea>
										<span class="error"><?=isset($errorObject->meta_descriptions) ? $errorObject->meta_descriptions : ''?></span>
									</div>

									<input type="hidden" name="totalBalance" id="totalbalance">
									<div class="d-grid mb-3 mt-5">
										<button class="btn btn-primary disable-btn" type="submit" id="depositBtn"><?=$this->lang->line('withdraw_now')?></button>
									</div>

								</form>

							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- end row -->

		</div> <!-- container-fluid -->
	</div>

</div>

<?php unset($_SESSION['error_withdraw']); ?>
