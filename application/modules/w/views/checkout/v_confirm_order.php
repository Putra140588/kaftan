							<div class="panel">
								<div class="accordion-header">
									<div class="accordion-title">5 <?php echo lang('step')?>: <span><?php echo lang('confirmord')?></span></div><!-- End .accordion-title -->
									<a class="accordion-btn opened"  data-toggle="collapse" data-target="#confirm"></a>
								</div><!-- End .accordion-header -->								
								<div id="confirm" class="collapse in">
								  <div class="panel-body">	
								  <p class="title-desc"><?php echo lang('sumtotal')?></p>	
								  <div class="row">								  	  
									  <div class="col-md-6 col-sm-12 col-xs-12">        	
									  	<?php $x = $this->m_public->generate_sum_cart();		
												$total_qty = $x['total_qty'];
												$total_price = $x['total_price'];
												$total_price_num = $x['total_price_num'];
												$symbol = $this->session->userdata('symbol_fo');
												?>					
			        						<table class="table total-table">
			        							<tbody>
			        								<tr>
			        									<td class="total-table-title"><?php echo lang('subtot')?>:</td>
			        									<td><?php echo $symbol?> <span id="subtotal2"><?php echo $total_price?></span></td>
			        								</tr>
			        								<?php 
			        								//generate tax
			        								$tx = $this->m_public->generate_tax($total_price_num);
			        								if ($tx['rate_tax'] > 0){
			        									echo '<tr>
																	<td class="total-table-title">'.lang('tax').' '.$tx['rate_tax'].'%:</td>
																	<td>'.$symbol.' <span id="amount_tax2">'.$tx['amount_tax_format'].'</span></td>
																</tr>';
			        								}
			        								?>
			        								<tr>
			        									<td class="total-table-title"><?php echo lang('costshipp')?>:</td>
			        									<td id="costshipp2">0</td>
			        								</tr>			        								
			        							</tbody>
			        							<tfoot>
			        								<tr>
														<td><?php echo lang('total')?>:</td>
														<td id="totalshopp2">0</td>
			        								</tr>
			        							</tfoot>
			        						</table>			        						
			        				</div>
			        				
			        				<div class="col-md-6 col-sm-12 col-xs-12">		
			        				<div class="md-margin visible-xs"></div><!-- space -->						  	  	
		                                <div class="input-group textarea-container">
		                                    <span class="input-group-addon"><span class="input-icon input-icon-message"></span><span class="input-text"><?php echo lang('notes')?></span></span>
		                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5" placeholder="<?php echo lang('exnote')?>"></textarea>
		                                </div>
								  	</div>
								  	<div class="col-md-12 col-sm-12 col-xs-12">
								  		<div class="md-margin"></div>
			        						<?php echo $this->load->view('v_alert_boxes')?>	
			        						<a href="<?php echo base_url(FOMODULE)?>" class="btn btn-custom"><?php echo strtoupper(lang('continueshopp'))?></a>
			        						<input type="submit" id="btn-submit" class="btn btn-custom-2" value="<?php echo lang('confirmord')?>">
								  	</div>
			        		</div>					  
									
								  </div>
								</div>						  
						  	</div>