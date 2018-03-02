
					<div class="panel">
                             <div class="accordion-header">
                                  <div class="accordion-title"><span><?php echo lang('infcont')?></span></div><!-- End .accordion-title -->
                                   <a class="accordion-btn"  data-toggle="collapse" data-target="#contact"></a>
                               </div>                                    
                                <div id="contact" class="collapse-in">
                                   <div class="panel-body">   
                                   		<div class="table-responsive">                    
                                         <table class="table table-striped compare-item-table">	
                                         		<tr>
													<td class="table-title"><?php echo lang('gender')?></td>
													<td><span ><?php echo $this->session->userdata('name_gender')?></span></td>
												</tr>											
												<tr>
													<td class="table-title"><?php echo lang('name')?></td>
													<td><span ><?php echo $this->session->userdata('first_name_fo').' '.$this->session->userdata('last_name_fo')?></span></td>
												</tr>
												<tr>
													<td class="table-title"><?php echo lang('email')?></td>
													<td><span ><?php echo $this->session->userdata('email')?></span></td>
												</tr>
												<tr>
													<td class="table-title"><?php echo lang('bdate')?></td>
													<td><span ><?php echo short_date($this->session->userdata('birthdate'))?></span></td>
												</tr>
												<tr>
													<td class="table-title">Password</td>
													<td><span >******</span> <a href="javascript:void(0)" onclick="ajaxModal('<?php echo base_url(FOMODULE.'/customer/edit_password')?>','','MyModal')"  data-toggle="modal" data-target="#MyModal"><i class="fa fa-pencil"></i> <?php echo lang('editpass')?></a></td>
												</tr>												
											</table>
										</div>
									<div class="md-margin"></div>
									<a href="<?php echo base_url(FOMODULE.'/customer/my_account/edit')?>" class="btn btn-custom-2"><?php echo lang('editcont')?></a>
                                    </div>
                               </div>
                       </div>
               