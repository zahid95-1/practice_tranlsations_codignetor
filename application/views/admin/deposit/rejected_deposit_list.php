<style>
	.rounded-pill {
		padding-right: 2.6em!important;
		padding-left: 2.6em!important;
		padding-top: 6px!important;
		padding-bottom: 4px!important;
	}
	.document-table,.document-table th,.document-table td {
		border: 2px solid gray!important;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<?php if (isset($_SESSION['success_deposit_message'])):?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<?=$_SESSION['success_deposit_message']?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php unset($_SESSION['success_deposit_message']); endif; ?>

							<h4 class="card-title mb-4"><?=isset($dataItem) ? count($dataItem) : '0'?> <?=$this->lang->line('pending_deposit_listing')?></h4>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th><?=$this->lang->line('sr')?></th>
									<th><?=$this->lang->line('user_id')?></th>
									<th><?=$this->lang->line('name')?></th>
									<th><?=$this->lang->line('email')?></th>
									<th><?=$this->lang->line('mt5_id')?></th>
									<th><?=$this->lang->line('amount')?></th>
									<th><?=$this->lang->line('note')?></th>
									<th><?=$this->lang->line('payment_method')?></th>
									<th><?=$this->lang->line('deposit_proof')?></th>
									<th><?=$this->lang->line('create_date')?></th>
									<th><?=$this->lang->line('status')?></th>
									<th><?=$this->lang->line('action')?></th>
								</tr>
								</thead>
								<tbody>

								<?php if (isset($dataItem) && $dataItem){
									foreach ($dataItem as $key=>$item):
									?>
									<tr>
										<td><?=++$key?></td>
										<td><?=$item->unique_id?></td>
                                        <td><?=$item->first_name.' '.$item->last_name?></td>
										<td><?=$item->email?></td>
										<td><?=$item->mt5_login_id?></td>
										<td>$<?=$item->entered_amount?></td>
										<td><?=$item->transaciton_detail?></td>
										<td>
												<?php
												  if ($item->payment_mode==1){
												  	echo "<span style='color: green'>Wire Transfer</span>";
												  }elseif ($item->payment_mode==2){
													  echo "<span style='color: green'>CryptoCoin</span>";
												  }elseif ($item->payment_mode==3){
													  echo "<span style='color: #0a53be'>Paypal</span>";
												  }elseif ($item->payment_mode==4){
													  echo "<span style='color: #0f0f0f'>Cash</span>";
												  }elseif ($item->payment_mode==5){
													  echo "<span style='color: #00CC00'>Internal Transfer</span>";
												  }elseif ($item->payment_mode==7){
													  echo "<span style='color: #00CC00'>Stripe</span>";
												  }
												?>
											</td>
										<td>
											    <?php if ($item->payment_mode<>2){ ?>
												<a href="<?=base_url().$item->transaction_proof_attachment?>" download>
													<button class="btn-sm btn-primary "><i class="fa fa-download"></i> Download</button>
												</a>
												<?php } ?>
												<?php if ($item->payment_mode==2){ ?>
												<a href="<?=$item->gateway_url?>" target="_blank">
													<button class="btn-sm btn-primary "><i class="fa fa-link"></i> Gateway URL</button>
												</a>
												<?php } ?>
											</td>
										<td><?=date('Y-m-d H:s',strtotime($item->created_at))?></td>
										<td>
											<span class="badge rounded-pill bg-danger">Rejected</span>
										</td>
										<td>
											<a class="btn btn-outline-secondary btn-sm edit" title="User View" href="<?=base_url()?>user-single-deposit-item-details/<?=$item->id?>">
												<i class="fas fa-eye"></i> <?=$this->lang->line('view_details')?>
											</a>
										</td>
									</tr>
								<?php endforeach; } ?>

								</tbody>
							</table>

						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->
		</div>

	</div>
	<!-- End Page-content -->
</div>
<!-- end main content-->

<script>

	$("form#add_manager_event").submit(function(e) {
		e.preventDefault();

		var currentIndex=$(this).data('key-index');

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), // serializes the form's elements.
			success: function(response)
			{
				if (response){
					$('#add_manager_'+currentIndex+'').html("Update");
					$('span#successMessage-'+currentIndex+'').html('Successfully Add Manager');
					setTimeout(function() {
						$('button#closeModal-'+currentIndex+'').trigger('click');
						$('td#managerNameTd-'+currentIndex+'').html('<span class="badge rounded-pill bg-success">'+response+'</span>');
					}, 2000);
				}
			},error: function (){
				alert("Something went wrong");
				$('#add_manager_'+currentIndex+'').html("Update");
			},
			beforeSend: function (xhr){
				$('#add_manager_'+currentIndex+'').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$("form#uploadedKycForm").submit(function(e) {
		e.preventDefault();
		var currentIndex=$(this).data('key-index');

		var xhr = new XMLHttpRequest()

		var formData = new FormData($(this)[0]);
		formData.append('file',xhr.file);

		console.log(formData);

		var form = $(this);
		var actionUrl = form.attr('action');

		$.ajax({
			type: "POST",
			url: actionUrl,
			processData: false,
			contentType: false,
			cache: false,
			data: formData, // serializes the form's elements.
			success: function(response)
			{
				if (response){
					var obj = JSON.parse(response);
					$('button#uploadKycBtn-'+currentIndex+'').html("Update");
					$('span#successMessage-kyc-'+currentIndex+'').html('Successfully Save identity and residency proof');
					$('td#identityProofAttachment-'+currentIndex+'').html('<a href="'+obj.identity_proof+'" target="_blank"><img src="'+obj.identity_proof+'" alt="logo-light" height="40"></a>');
					$('td#resiDencyAttachmentProof-'+currentIndex+'').html('<a href="'+obj.residency_proof+'" target="_blank"><img src="'+obj.residency_proof+'" alt="logo-light" height="40"></a>');

					$('td#markResidencyVerifiedTd-'+currentIndex+'').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markResidencyVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html('<button type="button" class="btn btn-secondary btn-sm waves-effect waves-light" id="markIdentityVerified" data-key-index="'+currentIndex+'" data-unique-id="'+obj.user_id+'">Mark Verified</button>');

					setTimeout(function() {
						$('form#uploadedKycForm').trigger("reset");
						$('span#successMessage-kyc-'+currentIndex+'').html('');
						$('button#closeKycModal-'+currentIndex+'').trigger('click');
					}, 2000);

				}
			},error: function (){
				alert("Something went wrong");
				$('button#uploadKycBtn-'+currentIndex+'').html("Update");
			},
			beforeSend: function (xhr){
				$('button#uploadKycBtn-'+currentIndex+'').html("<span class='fa fa-spin fa-spinner'></span> Processing...");
			}
		});
	});

	$(document).on('click', 'button#markResidencyVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-residency-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#markResidencyVerifiedTd-'+currentIndex+'').html(html);
				}
			}
		});

	});

	$(document).on('click', 'button#markIdentityVerified', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');

		var url = "<?php echo base_url(); ?>kyc-attachment-verified";
		var post_data = {
			'userid': userid,
			'type': 1,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (response){
					var html='<span class="badge rounded-pill bg-success">Verified</span>';
					$('td#markIdentityVerifiedTd-'+currentIndex+'').html(html);
				}
			}
		});
	});

	$(document).on('click', 'button#changeIbStatus', function () {

		var currentIndex	=$(this).data('key-index');
		var userid			=$(this).data('unique-id');
		var ibStatus		=$(this).data('ib-status')||0;

		var url = "<?php echo base_url(); ?>change-ib-status";
		var post_data = {
			'userid': userid,
			'ib_status': ibStatus,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				if (Number(response)===1){
					var html		='<button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="'+currentIndex+'" data-unique-id="'+userid+'" data-ib-status="1">Make IB</button>';
					$('td#changeIbStatus-'+currentIndex+'').html(html);
				}else{
					var html		='<button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="changeIbStatus" data-key-index="'+currentIndex+'" data-unique-id="'+userid+'" data-ib-status="0">Make IB</button>';
					$('td#changeIbStatus-'+currentIndex+'').html(html);
				}
			}
		});
	});

</script>
