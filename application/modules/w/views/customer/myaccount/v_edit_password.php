			<form id="formmodal" class="form-horizontal" action="<?php echo base_url(FOMODULE.'/customer/save_edit_password')?>">			
				<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">							
								<div class="modal-dialog">
                                        <div class="modal-content">
                                         	<div class="modal-body">     
                                         		<div class="row">
                                         			<div class="col-md-12 col-sm-12 col-xs-12">					   		
												   		<h2 class="checkout-title"><?php echo lang('editpass')?></h2>
												   		<?php $this->load->view('v_alert_boxes_modal')?>												   		
												   		<fieldset>	
												   			
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text"><?php echo lang('newpass')?>&#42;</span></span>
																<input type="password" name="password" class="form-control input-lg"  placeholder="<?php echo lang('password')?>">
															</div>
															
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-password"></span><span class="input-text"><?php echo lang('repeatpass')?>&#42;</span></span>
																<input type="password" name="repeatpass" class="form-control input-lg"  placeholder="<?php echo lang('repeatpass')?>">
															</div>			                                    						
														</fieldset>
											   	</div>			
											   		</div>
			                                         	</div>                                  
			                                            <div class="modal-footer">
			                                           	 	<button type="button" class="btn btn-custom" data-dismiss="modal"><?php echo strtoupper(lang('cancel'))?></button>  
			                                           	 	<button class="btn btn-custom-2"><?php echo lang('save')?></button>                                         
			                                            </div>
			                                        </div>
			                                    </div>
			                                 </form>    