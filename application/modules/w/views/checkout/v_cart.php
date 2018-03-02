							<div class="panel">
								<div class="accordion-header">
									<div class="accordion-title">1 <?php echo lang('step')?>: <span><?php echo lang('youcart')?></span></div><!-- End .accordion-title -->
									<a class="accordion-btn opened"  data-toggle="collapse" data-target="#cart"></a>
								</div><!-- End .accordion-header -->								
								<div id="cart" class="collapse in">
								  <div class="panel-body">	
								  <p><?php echo lang('surecart')?></p>						  
									<div class="table-responsive">
										<table class="table checkout-table">
										<thead>
											<tr>
												<th class="table-title"><?php echo lang('prodname')?></th>											
												<th class="table-title"><?php echo lang('unitprc')?></th>
												<th class="table-title"><?php echo lang('prodqty')?></th>
												<th class="table-title"><?php echo lang('subtot')?></th>
												<th class="table-title"><?php echo lang('delete')?></th>
											</tr>
										</thead>
										<span id="itemcart">
											<?php echo $this->m_public->item_cart()?>	
										</span>																
									  </table>								
									</div>									
								  </div><!-- End .panel-body -->
								</div><!-- End .panel-collapse -->							  
						  	</div><!-- End .panel -->