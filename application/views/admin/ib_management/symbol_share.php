	<?php
	/*===================GetUserInfo=========================*/
	?>
	<link href="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />


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
		span.select2.select2-container.select2-container--default {
			width: 100%!important;
		}
	</style>
	<div class="main-content" id="result">
		<div class="page-content">
			<div class="container-fluid">

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title mb-4"><?=$this->lang->line('add_symbol_share')?></h4>
                              <p style="color:green;"><?php echo $this->session->flashdata('msg'); ?></p>
								<form class="" action="<?php echo base_url()."admin/ib-management/add-symbol-value"?>" method="post"  enctype="multipart/form-data" >
								<div class="row">

									<div class="col-sm-6">
										<select class="form-control select2" name="symbol_id" id="symbol_id">
											<option value=""><?=$this->lang->line('select_symbol_name')?></option>
											<?php foreach($symbols as $symbol): ?>
												<option value="<?=$symbol->id?>"><?=$symbol->symbol_name?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="col-sm-6">
										<input type="number"
											   placeholder="<?=$this->lang->line('enter_value')?>"
											   name="symbol_value"
											   id="symbol_value"
											   class="form-control"
											   required>
									</div>
									
								</div>

									<button type="submit"
											class="btn btn-outline-success btn-sm edit waves-effect waves-light mt-3"
											id="">
										<?=$this->lang->line('submit')?>
									</button>
								</form>


								<div class="row">
								<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th><?=$this->lang->line('sr_no')?></th>
										<th><?=$this->lang->line('symbol_name')?></th>
										<th><?=$this->lang->line('share_value')?></th>
									</tr>
									</thead>
									<tbody>
										<?php
										$c = 1;	
										 foreach($symbolShareList as $getsymbolShareList){
										
										 ?>
										<tr>
											<td><?php echo $c ?></td>
											<td><?php echo $getsymbolShareList->symbol_name ?></td>
											<td><?php echo $getsymbolShareList->symbol_value ?></td>
										</tr>
										<?php $c++; } ?>
									</tbody>
								</table>
								</div>
							</div>
						</div>
					</div> <!-- end col -->
				</div> <!-- end row -->
			</div>

		</div>
		<!-- End Page-content -->
	</div>
	<!-- end main content-->

	<!-- Sweet Alerts js -->
	<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

	<!-- Sweet alert init js-->
	<script src="<?=base_url()?>assets/js/pages/sweet-alerts.init.js"></script>


