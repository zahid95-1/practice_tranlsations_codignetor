<?php
$ticketId=$dataItem->ticket_id;
$ticketDetails		=$this->db->query("SELECT * FROM `tickets_feedback` WHERE ticket_id='$ticketId'")->result();
?>
<div class="main-content" id="result">
	<div class="page-content">
		<div class="container-fluid">
			<div class="d-lg-flex mb-4">
				<div class="w-100 user-chat mt-4 mt-sm-0">
					<?php if (isset($_SESSION['success_ticket_creations'])):?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?=$_SESSION['success_ticket_creations']?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						<?php unset($_SESSION['success_ticket_creations']); endif; ?>

					<div class="p-3 px-lg-4 user-chat-border">
						<div class="row">
							<div class="col-md-4 col-6">
								<h5 class="font-size-15 mb-1 text-truncate">Ticket For : <?=$dataItem->title?></h5>
								<p class="text-muted text-truncate mb-0">Ticket Id: <?=$dataItem->ticket_id?> [<?php if ($dataItem->status==1){echo "Open";}else{echo "<span style='color: red'>Close</span>";} ?>]</p>
							</div>
							<div class="col-md-8 col-6">
								<ul class="list-inline user-chat-nav text-end mb-0">
									<li class="list-inline-item">
										<div class="dropdown">
											<button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="mdi mdi-dots-horizontal"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#">Close Ticket</a>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="px-lg-2">
						<div class="chat-conversation p-3">
							<ul class="list-unstyled mb-0 pe-3" data-simplebar="init" style="max-height: 450px;">
								<div class="simplebar-wrapper" style="margin: 0px -16px 0px 0px;">
									<div class="simplebar-height-auto-observer-wrapper">
										<div class="simplebar-height-auto-observer"></div>
									</div>
									<div class="simplebar-mask">
										<div class="simplebar-offset" style="right: -17px; bottom: 0px;">
											<div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;">
												<div class="simplebar-content" style="padding: 0px 16px 0px 0px;">
													<?php
													if ($dataItem){ ?>
													<li class="right">
														<div class="conversation-list">
															<div class="ctext-wrap">
																<div class="conversation-name"><?=$dataItem->first_name?> <?=$dataItem->last_name?></div>
																<div class="ctext-wrap-content">
																	<p class="mb-0">
																		<?=$dataItem->descriptions?>
																	</p>
																</div>
																<?php if ($dataItem->ticket_attachment): ?>
																<div style="text-align: right">
																	<a href="<?=base_url().$dataItem->ticket_attachment?>" download>
																		<button class="btn-sm btn-primary "><i class="fa fa-download"></i> Attachment</button>
																	</a>
																</div>
																<?php endif; ?>
																<p class="chat-time mb-0" style="text-align: right"><i class="mdi mdi-clock-outline me-1"></i> <?=date ('M-d H:s',strtotime ($dataItem->created_at))?></p>
															</div>
														</div>
													</li>
													<?php } ?>

													<?php
													 if ($ticketDetails){
														 foreach ($ticketDetails as $key=>$itemSingle){
															 if ($itemSingle->created_by==1){
													?>
													<li>
														<div class="conversation-list">
															<div class="chat-avatar">
																<img src="<?=base_url()?>assets/images/users/avatar-1.jpg" alt="avatar-2">
															</div>
															<div class="ctext-wrap">
																<div class="conversation-name">Admin</div>
																<div class="ctext-wrap-content">
																	<p class="mb-0"><?=$itemSingle->comment?></p>
																</div>
																<p class="chat-time mb-0"><i class="mdi mdi-clock-outline me-1"></i> <?=date ('M-d H:s',strtotime ($itemSingle->created_at))?></p>
															</div>
														</div>
													</li>
													 <?php }else{ ?>
													<li class="right">
														<div class="conversation-list">
															<div class="ctext-wrap">
																<div class="conversation-name"><?=$dataItem->first_name?> <?=$dataItem->last_name?></div>
																<div class="ctext-wrap-content">
																	<p class="mb-0">
																		<?=$itemSingle->comment?>
																	</p>
																</div>
																<p class="chat-time mb-0"><i class="mdi mdi-clock-outline me-1"></i><?=date ('M-d H:s',strtotime ($itemSingle->created_at))?></p>
															</div>
														</div>
													</li>
													<?php } }  }?>
												</div>
											</div>
										</div>
									</div>
									<div class="simplebar-placeholder" style="width: auto; height: 886px;"></div>
								</div>
								<div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
									<div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
								</div>
								<div class="simplebar-track simplebar-vertical" style="visibility: visible;">
									<div class="simplebar-scrollbar" style="height: 228px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
								</div>
							</ul>
						</div>
					</div>
					<?php if ($dataItem->status==1): ?>
					<div class="px-lg-3">
						<div class="p-3 chat-input-section">
							<div class="row">
								<div class="col">
									<div class="position-relative">
										<form class="" action="<?php echo base_url()."admin/ticket/add-admin-feedback"?>" method="post" id="createTicket" enctype="multipart/form-data">
											<input type="hidden" value="<?=$dataItem->id?>" name="main_id">
											<input type="hidden" value="<?=$dataItem->ticket_id?>" name="ticket_id">
											<textarea class="form-control" name="comment" style="height: 100px;" placeholder="Enter Comments..." required></textarea>
											<select class="form-control" name ="status" style="margin-top: 20px;">
												<option value="">Select Ticket Status</option>
												<option value="2">Close Ticket</option>
												<option value="1">Reopen Ticket</option>
											</select>
										<button type="submit" class="btn btn-primary chat-send w-md waves-effect waves-light mt-3"><span class="d-none d-sm-inline-block me-2">Submit</span> <i class="mdi mdi-send"></i></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php  endif;?>
				</div>
			</div>
			<!-- end row -->
		</div>
	</div>
</div>
