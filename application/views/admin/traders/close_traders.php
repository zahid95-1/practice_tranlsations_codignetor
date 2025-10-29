<?php
$filterId=$fromdate=$toDate='';
if (isset($dataItem['filter'])) {
    $filterId=$dataItem['filter']['filterId'];
    $fromdate=$dataItem['filter']['from_date'];
    $toDate=$dataItem['filter']['to_date'];
}
?>

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
	div#datatable-buttons_info,div#datatable-buttons_paginate{
		display: none;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title mb-4"> Close Traders Listing</h4>
							<form class="form-control" action="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>" method="post">
							    <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-select" id="validationCustom04" name="filtering_options">
                                            <option value="">Select Close Date</option>
                                            <option value="1" <?=($filterId==1)?'selected':''?>>Today</option>
                                            <option value="2" <?=($filterId==2)?'selected':''?>>Last 3 Days</option>
                                            <option value="3" <?=($filterId==3)?'selected':''?>>Last Week</option>
                                            <option value="4" <?=($filterId==4)?'selected':''?>>Last Month</option>
                                            <option value="5" <?=($filterId==5)?'selected':''?>>Last 3 Month</option>
                                            <option value="6" <?=($filterId==6)?'selected':''?>>Last 6 Month</option>
                                            <option value="8" <?=($filterId==8)?'selected':''?>>Last 1 Year</option>
                                            <option value="7" <?=($filterId==7)?'selected':''?>>Custom Date</option>
                                        </select>
                                    </div>

							        <div class="col-sm-3 <?php if ($filterId!=7){echo 'd-none';} ?>" id="from_date">
							            <input class="form-control" type="text" onfocus="(this.type='date')" name="from_date" id="" value="<?php if(isset($_REQUEST['from_date'])){ echo $_REQUEST['from_date']; }?>" placeholder="From Date">
							        </div>
							        <div class="col-sm-3 <?php if ($filterId!=7){echo 'd-none';} ?>" id="to_date">
							            <input class="form-control " type="text" onfocus="(this.type='date')"  name="to_date" id="" value="<?php if(isset($_REQUEST['to_date'])){ echo $_REQUEST['to_date']; }?>" placeholder="To Date">
							        </div>
							        <div class="col-sm-3">
							            <input class="form-control btn-primary" type="submit" name="search" id="search" value="Search">
							        </div>
							    </div>
							</form>
							<br>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>SR No.</th>
									<th>MT5 AC</th>
									<th>Username</th>
									<th>Symbol</th>
									<th>Volume</th>
									<th>Open Price</th>
									<th>Trade Open Time</th>
									<th>Closed Price</th>
									<th>Trade Closed Time</th>
									<th>Profhit</th>
									<th>Status</th>
								</tr>
								</thead>
								<tbody>
									<?PHP
									$i=0;
									if ($dataItem['closeTrade']){
									foreach ($dataItem['closeTrade'] as $item){
									    $i++;
									?>
									<tr>
										<td><?=$i?></td>
										<td><?=$item->mt5_login_id?></td>
										<td><?=$item->first_name.' '.$item->last_name?></td>
										<td><?=$item->symbol?></td>
										<td><?=number_format($item->volume , 2)?></td>
										<td>$<?=floatval($item->open_price)?></td>
										<td><?=$item->trade_open_datetime?></td>
										<td>$<?=floatval($item->close_price)?></td>
										<td><?=$item->trade_close_datetime ?></td>
										<td><?=$item->profit?></td>
										<td>
											<?php
											echo "<span style='color: green'>Sold Out</span>";
											?>
										</td>
									</tr>
								<?php  }  }?>
								</tbody>
							</table>

							<!-- Custom Paginations -->
							<div class="dataTables_wrapper dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-sm-12 col-md-5">
										<div class="dataTables_info" style="display: block!important;" id="datatable-buttons_info" role="status" aria-live="polite">Showing <?=($dataItem['current_page'])?$dataItem['current_page']:0?> to 10 of <?=($dataItem['total_pages'])?$dataItem['total_pages']:'0'*10?> entries</div>
									</div>
									<div class="col-sm-12 col-md-7">
										<div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate" style="display: block!important;">
											<ul class="pagination pagination-rounded">
												<?php if ($dataItem['current_page'] > 1) { ?>
													<li class="paginate_button page-item previous" id="datatable-buttons_previous">
														<a href="?page=<?php echo $dataItem['current_page'] - 1; ?>&filtering_options=<?php if ($filterId){echo "".$filterId."";}?>&from_date=<?php if(isset($_REQUEST['from_date'])){ echo $_REQUEST['from_date'].''; }?>&to_date=<?php  if(isset($_REQUEST['to_date'])){ echo $_REQUEST['to_date']; }?>" aria-controls="datatable-buttons" data-dt-idx="0" tabindex="0" class="page-link">
															<i class="mdi mdi-chevron-left"></i>
														</a>
													</li>
												<?php } ?>
												<?php for ($i = 1; $i <= $dataItem['total_pages']; $i++) { ?>
													<?php if ($i == $dataItem['current_page']) { ?>
														<li class="paginate_button page-item active">
															<a href="#" aria-controls="datatable-buttons" data-dt-idx="1" tabindex="0" class="page-link"><?php echo $i; ?></a>
														</li>
													<?php } else { ?>
														<?php if ($i == 1 || $i == $dataItem['total_pages'] || ($i >= $dataItem['current_page'] - 2 && $i <= $dataItem['current_page'] + 2)) { ?>
															<li class="paginate_button page-item">
																<a href="?page=<?php echo $i; ?>&filtering_options=<?php if ($filterId){echo "".$filterId."";}?>&from_date=<?php if(isset($_REQUEST['from_date'])){ echo $_REQUEST['from_date'].''; }?>&to_date=<?php  if(isset($_REQUEST['to_date'])){ echo $_REQUEST['to_date']; }?>" aria-controls="datatable-buttons" data-dt-idx="1" tabindex="0" class="page-link"><?php echo $i; ?></a>
															</li>
														<?php } else if ($i == $dataItem['current_page'] - 3 || $i == $dataItem['current_page'] + 3) { ?>
															<li class="paginate_button page-item disabled" id="datatable-buttons_ellipsis"><a href="#" aria-controls="datatable-buttons" data-dt-idx="2" tabindex="0" class="page-link">…</a></li>
														<?php } ?>
													<?php } ?>
												<?php } ?>

												<?php if ($dataItem['current_page'] < $dataItem['total_pages']) { ?>
													<li class="paginate_button page-item next" id="datatable-buttons_next">
														<a href="?page=<?php echo $dataItem['current_page'] + 1; ?><?php if ($filterId){echo "&filtering_options=".$filterId."";}?>" aria-controls="datatable-buttons" data-dt-idx="2" tabindex="0" class="page-link">
															<i class="mdi mdi-chevron-right"></i>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- Custom Paginations End -->

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
    $(document).on('change', 'select#validationCustom04', function() {
        if (Number($(this).val())===7){
            $('#from_date').removeClass('d-none');
            $('#to_date').removeClass('d-none');
        }else {
            $('#from_date').addClass('d-none');
            $('#to_date').addClass('d-none');
        }
    });
</script>
