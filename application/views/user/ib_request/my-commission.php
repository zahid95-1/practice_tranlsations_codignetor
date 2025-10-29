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
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<h4 class="card-title">Commission Listing</h4><hr/>
<!--							<h5  class="card-title">Total Commission : 153.00</h5>-->
<!--							<h5  class="card-title mb-4">Total Lot : 0.00</h5>-->

                            <form class="form-control" action="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-select" id="validationCustom04" name="filtering_options">
                                            <option value="">Select Date</option>
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
                                        <input class="form-control" type="text" onfocus="(this.type='date')" name="from_date" id="" value="<?php if(isset($_POST['from_date'])){ echo $_POST['from_date']; }?>" placeholder="From Date">
                                    </div>
                                    <div class="col-sm-3 <?php if ($filterId!=7){echo 'd-none';} ?>" id="to_date">
                                        <input class="form-control " type="text" onfocus="(this.type='date')"  name="to_date" id="" value="<?php if(isset($_POST['to_date'])){ echo $_POST['to_date']; }?>" placeholder="To Date">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-control btn-primary" type="submit" name="search" id="search" value="Search">
                                    </div>
                                </div>
                            </form>
                            <br/>

							<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
								   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th>Login Id</th>
									<th>Date</th>
									<th>Client Name</th>
									<th>Level</th>
									<th>Order</th>
									<th>Symbol</th>
									<th>Price</th>
									<th>Profit</th>
									<th>Volume</th>
									<th>My Commission</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($dataItem['lotInformations']) && $dataItem['lotInformations']):
								foreach ($dataItem['lotInformations'] as $key=>$item):
									?>
								<tr role="row" class="odd">

									<td><?=$item->mt5_login_id?></td>
									<td>
										<?php echo date("d-m-Y H:i:s", strtotime($item->created_datetime));    ?>

									</td>
									<td><?=$item->client_name?></td>
									<td><?=$item->level?></td>
									<td><?=$item->deal_id?></td>
									<td class="sorting_1"><?=$item->symbol?></td>
									<td>$<?=number_format($item->price , 2)?></td>
									<td><?=$item->profit?></td>
									<td><?=number_format($item->lot,2)?></td>
									<td>$ <?=$item->calculated_commission?></td>
									<td class="depostst">
										<?php
										if ($item->action==1){echo "Buy";}else{echo "Sell";}
										?>
									</td>
								</tr>
								<?php endforeach; endif; ?>
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
