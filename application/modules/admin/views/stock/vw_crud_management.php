		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_product" value="<?php echo isset($id_product) ? $id_product : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
											<div class="row">
												<div class="col-md-9">
													<div class="form-group">
														<label class="col-sm-4 control-label">ID Product</label>
														<div class="col-sm-8">
															<input type="text" name="id_product" readonly value="<?php echo $id_product?>" class="col-sm-3">
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Product Name</label>
														<div class="col-sm-8">
															<input type="text" name="productname" readonly value="<?php echo $name?>" class="col-sm-10">
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Category</label>
														<div class="col-sm-8">
															<input type="text" name="category" readonly value="<?php echo $name_category?>" class="col-sm-10">
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Global Stock</label>
														<div class="col-sm-2">
															<input type="text" readonly value="<?php echo isset($total) ? $total : 'N/A'?>" class="col-sm-10">
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label">Movement</label>
														<div class="col-sm-6">
															<select name="movement" class="chosen-select form-control" data-placeholder="Choose Movement..." onchange="ajaxcall('<?php echo base_url(MODULE.'/'.$class.'/show_label/'.$id_product)?>',this.value,'formstock')" required>
																<option value=""/>
																<?php foreach ($movement as $row){
																	echo '<option value="'.$row->id_label_movement.'">'.$row->name_movement.'</option>';
																}?>
															</select>
														</div>
													</div>
													<div id="formstock">
														
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Stock Available</label>
														<div class="col-sm-8">
															<table class="table table-striped table-hover df-tables">
																<thead>
																	<tr><th>#</th>																							
																		<th>Group</th>
																		<th>Attribute</th>		
																		<th>QtyDefault</th>																															
																		<th>QtySold</th>
																		<th>QtyAvailable</th>
																		<th>Warehouse</th>
																		<th>Location</th>
																		<th>Actions</th>
																	</tr>								
																</thead>
																<tbody id="stockavailable">
																	<?php echo $this->m_content->table_stock_available($sql)?>
																</tbody>
															</table>	
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<ul class="ace-thumbnails clearfix">
														<?php foreach ($rowimage as $row){
															$image = ($row->image_name != "") ? $row->image_name : 'no-image.jpg';
															echo '<li><a href="'.base_url('assets/images/product/'.$image).'" data-rel="colorbox">
																		<img src="'.base_url('assets/images/product/'.$image).'" alt="'.$image.'" width="150" height="150">
																		<div class="text">
																			<div class="inner">'.$image.'</div>
																		</div>
																	</a></li>';
														}?>
														
														
														
													</ul>
												</div>
											</div>
											<div class="space-20"></div>
											<div class="row">
												<div class="col-md-12">
													
												</div>
											</div>
									 </div>
								</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>
