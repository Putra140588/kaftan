				
									<div class="modal-dialog">
										<div class="modal-content" style="width:800px;top:10%;left:50%;margin-left:-400px;">											
											<div class="modal-body">												
												<?php $this->load->view('vw_alert_notif')?>	
												<form id="<?php echo $id_form?>" class="form-horizontal" value="<?php echo base_url(MODULE.'/'.$class.'/save_edit_sp')?>#listsp">
													<input type="hidden" name="id_specific_price" value="<?php echo isset($id_specific_price) ? $id_specific_price : ''?>">
													<input type="hidden" name="id_product" value="<?php echo isset($id_product) ? $id_product : ''?>">
													<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">													
													 <?php $this->load->view('vw_button_form')?>	
													 <div class="widget-body">
														<div class="widget-main padding-6 no-padding-left no-padding-right">
															<div class="space-6"></div>
															<div class="form-group">
																<label class="col-sm-4 control-label">Product</label>
																<div class="col-sm-5">
																	<input type="text" class="col-sm-10" disabled value="<?php echo $name_product?>">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label">Attribute</label>
																<div class="col-sm-5">
																	<input type="text" class="col-sm-10" disabled value="<?php echo $attr_group.' '.$name?>">
																</div>
															</div>
															<div class="form-group required">
																<label class="col-sm-4 control-label">Available From</label>
																<div class="col-sm-3">
																	<div class="input-group">
																		<input type="text" class="date-picker input-sm form-control" name="spfrom" placeholder="Date From" data-date-format="yyyy-mm-dd" value="<?php echo $date_from?>"/>
																		<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
																	</div>
																</div>				
																<div class="col-sm-3">
																	<div class="input-group">
																		<input type="text" class="date-picker input-sm form-control" name="spto" placeholder="Date To" data-date-format="yyyy-mm-dd" value="<?php echo $date_to?>"/>
																		<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label"> Specific Price </label>
																<div class="col-sm-5">
																	<input type="text" placeholder="<?php echo $_SESSION['symbol']?>" name="specificprice" class="col-xs-10 col-sm-5"  value="<?php echo isset($price_sp) ? $price_sp : ''?>" id="sprice1" onkeypress="return decimals(event,this.id)"/>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label"> Discount </label>
																<div class="col-sm-3">
																	<input type="text" placeholder="%" name="specificdisc" class="col-xs-10 col-sm-5"  value="<?php echo $disc_sp?>" id="spdisc1" onkeypress="return decimals(event,this.id)"/>
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
<script type="text/javascript">
$('.date-picker').datepicker({
	autoclose: true,
	todayHighlight: true
})
</script>							