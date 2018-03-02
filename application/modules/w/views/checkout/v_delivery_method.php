							<div class="panel">
								<div class="accordion-header">
									<div class="accordion-title">3 <?php echo lang('step')?>: <span><?php echo lang('couriershipp')?></span></div><!-- End .accordion-title -->
									<a class="accordion-btn"  data-toggle="collapse" data-target="#delivery-method"></a>
								</div><!-- End .accordion-header -->
								
								<div id="delivery-method" class="collapse-in">
								  <div class="panel-body">
								  	<p><?php echo lang('chsshipp')?></p>
								   		<div class="table-responsive">
											<table class="table checkout-table">
												<thead>
													<tr><th class="table-title">Courier</th>														
														<th class="table-title">Cost</th>
													</tr>
												</thead>
												<tbody id="courierlist">
													<?php echo $this->m_public->delivery_method()?>
												</tbody>
											</table>
										</div>
								  </div>
								</div>							  
							  </div>