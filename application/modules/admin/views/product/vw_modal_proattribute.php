				
									<div class="modal-dialog">
										<div class="modal-content" style="width:800px;top:10%;left:50%;margin-left:-400px;">											
											<div class="modal-body">												
												<?php $this->load->view('vw_alert_notif')?>	
												<form id="<?php echo $id_form?>" class="form-horizontal" value="<?php echo base_url(MODULE.'/'.$class.'/save_edit_proattribute')?>#prodattr">
													<input type="hidden" name="id_product_attribute" value="<?php echo isset($id_product_attribute) ? $id_product_attribute : ''?>">
													<input type="hidden" name="id_product" value="<?php echo isset($id_product) ? $id_product : ''?>">
													<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">													
													 <?php $this->load->view('vw_button_form')?>	
													 <div class="widget-body">
														<div class="widget-main padding-6 no-padding-left no-padding-right">
															<div class="space-6"></div>
															<div class="form-group">
																<label class="col-sm-4 control-label">Group</label>
																<div class="col-sm-5">
																	<input type="text" class="col-sm-10" disabled value="<?php echo $name_group?>">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label">Attribute</label>
																<div class="col-sm-5">
																	<input type="text" class="col-sm-10" disabled value="<?php echo $name?>">
																</div>
															</div>															
															<div class="form-group">
																<label class="col-sm-4 control-label"> Impact Price </label>
																<div class="col-sm-5">
																	<input type="text" placeholder="<?php echo $_SESSION['symbol']?>" name="impactprice" class="col-xs-10 col-sm-5"  value="<?php echo $price_impact?>" id="impact1" onkeypress="return decimals(event,this.id)"/>
																</div>
															</div>															
													</div>
												</form>
											</div>
											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>												
											</div>
										</div>
									</div>
						