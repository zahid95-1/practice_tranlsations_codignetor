<?php

/*===================GetUserInfo=========================*/
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
	.page-content {
		/*padding: calc(20px + 24px) calc(24px / 2) 60px calc(24px / 2) !important;*/
	}
	.level-info {
		display: flex;
		justify-content: center;
		align-content: space-between;
	}
	.level-info  .single-btn {
		margin-right: 23px;
	}
	.reportSections {
		display: flex;
		justify-content: space-around;
		margin: 34px 282px;
	}
</style>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">.
		

			<div class="row">
				<div class="col-12">
					<div class="card">

						<div class="card-body">
							<!--<h4 class="card-title mb-4">Refferal Clients of IB :- Abdul Kazi</h4>-->
							<!--<?php foreach($IbClientLevel as $IbClientLevelvalue) { ?>
								<p><?php echo "Level ".$IbClientLevelvalue->level ."-" .$IbClientLevelvalue->ib_commission ?></p>
							<?php } ?>-->
							
							<form class="form-control" action="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>" method="post">
							    <div class="row">
							        <div class="col-sm-2">
							            <input class="form-control" type="number" name="level_no" id="level_no" value="<?php if(isset($_POST['level_no'])){ echo $_POST['level_no']; }?>" placeholder="Level #">
							        </div>
							        <div class="col-sm-2">
							            <input class="form-control" type="text" name="username" id="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; }?>" placeholder="Name">
							        </div>
							        <div class="col-sm-1">
							            <input class="form-control btn-primary" type="submit" name="search" id="search" value="Search">
							        </div>
							    </div>
							</form>
							<br>
							

							<div class="common-table-area" id="levelOneBtn">
								<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
									   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th>Account ID</th>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Level</th>
										<th>Country</th>
										<th>Calculated commission</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach($IbClient as $IbClientValue){ ?>
										<tr>
											<td><?php echo $IbClientValue->ib_account ?></td>
											<td><?php echo $IbClientValue->username ?></td>
											<td><?php echo $IbClientValue->email ?></td>
											<td><?php echo $IbClientValue->mobile ?></td>
											<td><?php echo "Level ".$IbClientValue->level_no ?></td>
											<td><?php echo $IbClientValue->country_name ?></td>
											<td><?php echo "$ ". $IbClientValue->calculated_commission ?></td>
										</tr>
									<?php } ?>

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

