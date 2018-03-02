			<form id="formmodal" class="form-horizontal" action="<?php echo base_url(FOMODULE.'/checkout/save_address#deliveryaddr')?>">			
				<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
				<input type="hidden" name="id_address" value="<?php echo isset($id_address) ? $id_address : ''?>">
				<input type="hidden" name="default" value="<?php echo isset($default) ? $default :''?>">
				<?php $select="";$id_districts = isset($id_districts) ? $id_districts : '';
						$id_cities = isset($id_cities) ? $id_cities : '';
						$id_province = isset($id_province) ? $id_province : '';
						$id_country = isset($id_country) ? $id_country : '';?>	
								<div class="modal-dialog">
                                        <div class="modal-content">
                                         	<div class="modal-body">     
                                         		<div class="row">
                                         			<div class="col-md-12 col-sm-12 col-xs-12">					   		
												   		<h2 class="checkout-title"><?php echo lang('addaddress')?></h2>												   		
												   		<fieldset>		
												   			<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-company""></span><span class="input-text"><?php echo lang('aliasaddr')?>&#42;</span></span>
																<input type="text" id="alias" autocomplete="off" name="alias" class="form-control input-lg"  value="<?php echo isset($alias_name) ? $alias_name : ''?>" placeholder="(alamat rumah,alamat kantor,dll)">
															</div>			
												   			<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-user"></span><span class="input-text"><?php echo lang('recvname')?>&#42;</span></span>
																<input type="text" id="receiver" autocomplete="off" name="receiver" class="form-control input-lg"  value="<?php echo isset($name_received) ? $name_received : ''?>" placeholder="<?php echo lang('recvname')?>">
															</div>			
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-phone"></span><span class="input-text"><?php echo lang('phone')?>&#42;</span></span>
																<input type="text" autocomplete="off" name="phone" class="form-control input-lg"  value="<?php echo isset($phone_addr) ? $phone_addr : ''?>" placeholder="<?php echo lang('phone')?>">
															</div>	
															
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-address"></span><span class="input-text"><?php echo lang('fulladdr')?>&#42;</span></span>
																<input type="text"autocomplete="off"  class="form-control input-lg" name="address" value="<?php echo isset($address) ? $address : ''?>" placeholder="<?php echo lang('fulladdr')?>">
															</div>																						
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-postcode"></span><span class="input-text"><?php echo lang('postcode')?></span></span>
																<input type="text" autocomplete="off" class="form-control input-lg" name="postcode" value="<?php echo isset($postcode) ? $postcode : ''?>" placeholder="<?php echo lang('postcode')?>">
															</div><!-- End .input-group -->
															<div class="input-group">
						                                        <span class="input-group-addon"><span class="input-icon input-icon-country"></span><span class="input-text"><?php echo lang('country')?>&#42;</span></span>
						                                        <div class="large-selectbox clearfix">
						                                            <select id="country" name="country" class="form-control" onchange="ajaxcall('<?php echo base_url(FOMODULE.'/'.$class.'/show_province/'.$id_province)?>',this.value,'province')">
						                                            	<option value="" selected disabled>-<?php echo lang('chscountry')?>-</option>
						                                                <?php foreach ($country as $row){
						                                                	if (isset($id_country)){$select = ($row->id_country == $id_country) ? 'selected' : '';}
						                                                	echo '<option value="'.$row->id_country.'#'.$row->country_code.'" '.$select.'>'.$row->country_name.'</option>';
						                                                }?>
						                                            </select>
						                                        </div>
						                                    </div>
															<div id="districtarea">
																<?php if ($country_code == 'ID'){
																	//show for edit data
																	$this->load->view($class.'/v_districts');
																}?>
															</div>																													
															<?php $this->load->view('v_alert_boxes_modal')?>			                                    						
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
 <script src="<?php echo base_url()?>assets/fo/js/jquery.selectbox.min.js"></script>
 <script src="<?php echo base_url()?>assets/fo/js/main.js"></script>