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
	.profile-image{
		border-radius: 76%;
		border: 2px solid #5664d2;
		width: 100px;
		height: 100px;
		position: absolute;
		right: 70px;
		top: -56px;
		padding: 0;
	}
	.group-listing {
		display: flex;
	}
	.form-check {
		margin-left: 16px!important;
	}
	.d-none{
		display: none;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">
			<div class="alert alert-success alert-dismissible fade d-none show" role="alert" id="SuccessfullMessage">
				Successfully Update Permission
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<h3 class="card-title">Create Permission</h3>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<?php if (isset($dataItem) && $dataItem['mainModiules']){
							foreach ($dataItem['mainModiules'] as $key=>$item){
								$mId=$item->id;
								$uId=$dataItem['userItem']->user_id;

								$subModiules	= $this->db->query("SELECT * FROM `sub_modules` where modules_id=$mId")->result();
							?>
							<div class="accordion ecommerce" id="accordionExample_<?=$key?>">
								<div class="accordion-item">
									<h2 class="accordion-header" id="headingOne_<?=$key?>">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
												data-bs-target="#collapseOne_<?=$key?>" aria-expanded="true" aria-controls="collapseOne_<?=$key?>">
											<i class="mdi mdi-desktop-classic font-size-16 align-middle me-2"></i>
											<?=$item->modules_name?> <span id="successMessage_<?=$mId?>" style="margin-left: 20px;color: green" class="d-none">Successfully Update Permisison</span>
										</button>
									</h2>
									<div id="collapseOne_<?=$key?>" class="accordion-collapse collapse" aria-labelledby="headingOne_<?=$key?>"
										 data-bs-parent="#accordionExample_<?=$key?>">
										<div class="accordion-body">
											<div class="card">
												<div class="card-body">
													<div class="group-listing">
														<?php  if($subModiules){ foreach ($subModiules as $keySecond=>$secondItem):
															$subID=$secondItem->id;
															$authSingle	= $this->db->query("SELECT * FROM `auth` where modules_id=$mId and sub_modules_id=$subID and user_id=$uId")->row();

															$checked='';
															if ($authSingle){
																if ($authSingle->status) {
																	$checked = 'checked';
																}
															}
															?>
														<div class="form-check">
															<input type="checkbox" class="form-check-input subModiulesCheckBox"  value="<?=$secondItem->id?>" data-modiuleid="<?=$mId?>" <?=$checked?>>
															<label class="form-check-label"><?=$secondItem->label_name?></label>
														</div>
														<?php endforeach; } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } } ?>
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
	$(document).on('click', '.subModiulesCheckBox', function () {
		var modiulesId=$(this).data('modiuleid');

		var statusData='';
		if ($(this).is(':checked')) {
			statusData=1;
		} else {
			statusData=0;
		}
		var url = "<?php echo base_url(); ?>admin/manager/manager-management/auth/status-change";

		var post_data = {
			'subModulesId': $(this).val(),
			'status': statusData,
			'modulesId': modiulesId,
			'userId': '<?=$dataItem['userItem']->user_id?>',
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};

		$.ajax({
			url : url,
			type : 'POST',
			data: post_data,
			success : function(response)
			{
				$('#successMessage_'+modiulesId+'').removeClass('d-none');
				setTimeout(function() {
					$('#successMessage_'+modiulesId+'').addClass('d-none');
				}, 1000);
			}
		});

	});
</script>
