<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
					<div class="page-content">						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<?php $this->load->view('vw_alert_notif')?>	
								<form class="form-horizontal" id="form-ajax">
								<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
								<input type="hidden" name="id_order" value="<?php echo $id_order?>">
								<input type="hidden" name="amount" value="<?php echo $total_payment?>">
								<input type="hidden" name="balance" value="<?php echo $total_balance?>">
								<input type="hidden" name="iso_code" value="<?php echo $iso_code?>">
								<input type="hidden" name="pay_method" value="<?php echo $method_name?>">
								<input type="hidden" name="id_branch" value="<?php echo $id_branch?>">
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="tabbable">
											<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab">
												<li class="active">
													<a data-toggle="tab" href="javascript:void(0)" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','cust','content')">
														<i class="green ace-icon fa fa-user bigger-120"></i>
														CUSTOMER
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="javascript:void(0)" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','ordet','content')">
														<i class="orange ace-icon fa fa-rss bigger-120"></i>
														ORDER DETAIL
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="javascript:void(0)" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','status','content')">
														<i class="pink ace-icon fa fa-comment bigger-120"></i>
														STATUSES
													</a>
												</li>
												<li>
													<a data-toggle="tab" href="javascript:void(0)" onclick="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/view/'.$id_order)?>','pay','content')">
														<i class="red ace-icon fa fa-credit-card bigger-120"></i>
														PAYMENT TRANSACTION
													</a>
												</li>
											</ul>
											<div class="tab-content no-border padding-24">
												<div id="content" class="tab-pane fade in active">
													<?php $this->load->view($class.'/detail/vw_tab_customer')?>
												</div>																				
											</div>
										</div>		
									</div>
								</div>
								</form>						
								<!-- PAGE CONTENT ENDS -->
							</div>
						</div>
					</div>
				</div>
			</div>
