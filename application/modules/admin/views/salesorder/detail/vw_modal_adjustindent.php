				
									<div class="modal-dialog">
										<div class="modal-content" style="width:800px;top:10%;left:50%;margin-left:-400px;">											
											<div class="modal-body">												
												<?php $this->load->view('vw_alert_notif')?>	
												<form id="<?php echo $id_form?>" class="form-horizontal" value="<?php echo base_url(MODULE.'/'.$class.'/save_adjust_indent')?>#content">													
													<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
													<input type="hidden" name="id_stock_indent" value="<?php echo $id_stock_indent?>">
													 <?php $this->load->view('vw_button_form')?>	
													 <div class="widget-body">
														<div class="widget-main padding-6 no-padding-left no-padding-right">
															<div class="space-6"></div>															
															<div class="form-group required">
																<label class="col-sm-4 control-label"> Qty Indent </label>
																<div class="col-sm-8">
																	<input type="text" class="col-xs-3 col-sm-3" value="<?php echo $qty_minus?>" disabled/>
																</div>
															</div>	
															<div class="form-group required">
																<label class="col-sm-4 control-label">Warehouse</label>
																<div class="col-sm-3">
																	<select name="warehouse" required class="form-control clear" onchange="ajaxcall('<?php echo base_url(MODULE.'/stockmgm/choose_warehouse')?>',this.value,'whlocfrom')">
																		<option value="" selected disabled>--</option>
																		<?php $wh = $this->m_admin->get_warehouse();
																		foreach ($wh as $v){
																			echo '<option value="'.$v->id_warehouse.'">'.$v->name_warehouse.'</option>';
																		}?>	
																	</select>																				
																</div>							
															</div>
															<div class="form-group required">
																<label class="col-sm-4 control-label">Location</label>
																<div class="col-sm-3">
																	<select name="location" required class="form-control clear" id="whlocfrom">
																		<option value="" selected disabled>--</option>
																	</select>																									
																</div>							
															</div>
															<div class="form-group required">
																<label class="col-sm-4 control-label"> Qty Adjust</label>
																<div class="col-sm-8">
																	<input type="text" placeholder="Qty Adjust"  id="adjust" onkeypress="return decimals(event,this.id)" name="qtyadjust" class="col-xs-3 col-sm-3" required/>
																</div>
															</div>																																																											
														</div>
													</div>
												</form>
												*notes : Dipastikan stock sudah diinput dahulu.
											</div>											
											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>												
											</div>
										</div>
									</div>
									