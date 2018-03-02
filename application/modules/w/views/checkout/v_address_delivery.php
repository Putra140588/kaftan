							<div class="panel">
								<div class="accordion-header">
									<div class="accordion-title">2 <?php echo lang('step')?>: <span><?php echo strtoupper(lang('delivaddr'))?></span></div><!-- End .accordion-title -->
									<a class="accordion-btn opened"  data-toggle="collapse" data-target="#checkout-option"></a>
								</div><!-- End .accordion-header -->								
								<div id="checkout-option" class="collapse in">
								  <div class="panel-body" id="deliveryaddr">								  
									   <?php echo $this->m_public->delivery_address();?>					   								   									   								   						   										  												  	   			   
								  </div>
								</div>						  
							  </div>