							<div class="panel">
								<div class="accordion-header">
									<div class="accordion-title">4 <?php echo lang('step')?>: <span><?php echo lang('paymethod')?></span></div><!-- End .accordion-title -->
									<a class="accordion-btn"  data-toggle="collapse" data-target="#payment-method"></a>
								</div>								
								<div id="payment-method" class="collapse-in">
								  <div class="panel-body">
								 	<p><?php echo lang('chspay')?></p>
								   		<div class="table-responsive">
											<table class="table checkout-table">
												<thead>
													<tr>
														<th class="table-title">Payment</th>														
													</tr>
												</thead>
												<tbody id="paymentlist">
													<?php echo $this->m_public->payment_method()?>
												</tbody>
											</table>
										</div>										
								  </div><!-- End .panel-body -->
								</div><!-- End .panel-collapse -->
							  
						  	</div><!-- End .panel -->