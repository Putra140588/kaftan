		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">
		<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">									
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																																		
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">					
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>						
										<div class="col-xs-6">																				
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Supplier </label>
												<div class="col-sm-5">
													<select class="chosen-select form-control" name="supplier" data-placeholder="Choose a Supplier..." onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/create_sess_cart')?>','','')" required>
														<option value="" />
														<?php foreach ($supplier as $row){
															echo '<option value="'.$row->id_supplier.'">'.$row->name_supplier.'</option>';
														}?>															
													</select>
												</div>
											</div>	
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Shipp Via </label>
												<div class="col-sm-5">
													<select class="chosen-select form-control" name="courier" data-placeholder="Choose a courier..." required>
														<option value="" />
														<?php foreach ($courier as $row){
															echo '<option value="'.$row->id_courier.'">'.$row->name.'</option>';
														}?>															
													</select>
												</div>
											</div>
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Shipp To </label>
												<div class="col-sm-5">
													<select class="chosen-select form-control" name="branch" data-placeholder="Choose a branch..." required>
														<option value="" />
														<?php foreach ($branch as $row){
															echo '<option value="'.$row->id_branch.'">'.$row->name_branch.'</option>';
														}?>															
													</select>
												</div>
											</div>	
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Tax </label>
												<div class="col-sm-5">
													<select class="chosen-select form-control" name="tax" data-placeholder="Choose a tax..." required>
														<option value="" />
														<?php foreach ($tax as $row){
															echo '<option value="'.$row->id_tax.'#'.$row->rate.'">'.$row->name.'</option>';
														}?>															
													</select>
												</div>
											</div>
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Currency </label>
												<div class="col-sm-5">
													<select class="chosen-select form-control" name="currency" data-placeholder="Choose a currency..." required>
														<option value="" />
														<?php foreach ($currency as $row){
															echo '<option value="'.$row->id_currency.'">'.$row->name.'</option>';
														}?>															
													</select>
												</div>
											</div>
											<div class="form-group required">
												<label class="col-sm-4 control-label"> Purchase Date </label>
												<div class="col-sm-5">
													<div class="input-group">
														<input type="text" class="date-picker-sub input-sm form-control" name="date" placeholder="yyy-mm-dd" data-date-format="yyyy-mm-dd" required/>
														<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>					
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label"> Description </label>
												<div class="col-sm-8">
													<textarea rows="3" cols="3" name="description" id="description" class="textareas"></textarea>
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-4 control-label"> Product Cart </label>
											</div>
											<div class="space-6"></div>
											<table class="df-tables table table-striped">
												<thead>
													<tr>
											            <th>#</th>												            
											            <th>ID Product</th>		            																																																
														<th>Product</th>	
														<th>Attribute</th>	
														<th>Qty</th>
														<th>Price</th>
														<th>Description</th>	
														<th>Actions</th>																																																																																																																																																		
													</tr>
												</thead>
												<tbody id="pocart">
													<?php echo $this->m_content->table_po_cart()?>
												</tbody>
											</table>
											<div class="space-6"></div>
											<div class="center">
												<button type="button" onclick="ajaxform('<?php echo base_url(MODULE.'/'.$class.'/create_summary')?>','cartsum',$('#form-ajax')[0])" class="btn btn-white btn-info btn-bold ace-icon glyphicon glyphicon-align-center bigger-120 btnSubmit" data-rel="tooltip" data-placement="top" title="Create Summary"> Create Summary</button>	
											</div>
											
										</div>
										<div class="col-xs-6">
											<?php $this->load->view($class.'/vw_table_product')?>
										</div>							
									 </div>
								</div>
						</div>																			  
			 	</div>
		  </div>
		  <div class="row">
		  	<div class="col-xs-12">
		  		<div id="cartsum"></div>
		  	</div>
		  </div>		  
		  </form>
	  </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){		
	loaddatatable(".dt-tables");		
});
</script>  