													<?php $id_product = isset($id_product) ? $id_product : '';
													$id_manufacture = isset($id_manufacture) ? $id_manufacture : '0';
													$select='';?>													
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Product Name </label>
														<div class="col-sm-8">
															<input type="text" placeholder="Product Name" name="name" id="productname" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Manufacture | Language </label>														
														<div class="col-sm-2">
															<div id="loadmanu">
																<?php 
																$id_manufacture = isset($id_manufacture) ? $id_manufacture : '0';
																echo $this->m_content->chosen_manufacture($id_manufacture,$id_product)?>	
															</div>														
															<div style="margin-top:5px"></div>
															<a href="#modal-form" data-toggle="modal" role="button" id="addmn" data-rel="tooltip" data-placement="top" title="Add New Manufacture" onclick="ajaxModal('<?php echo base_url(MODULE.'/'.$class.'/add_manufacture')?>',<?php echo $id_manufacture?>,'modal-form')" class="btn btn-white btn-warning btn-round btn-sm">
															<i class="ace-icon fa fa-plus bigger-120 orange"></i>															
															</a>																																		
														</div>																																																								
													</div>														
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Supplier </label>
														<div class="col-sm-2">
															<div id="loadspl">
																<?php
																$id_supplier = isset($id_supplier) ? $id_supplier : '0';
																echo $this->m_content->chosen_supplier($id_supplier)?>	
															</div>	
															<div style="margin-top:5px"></div>
															<a href="#modal-form" data-toggle="modal" role="button" id="addmn" data-rel="tooltip" data-placement="top" title="Add New Supplier" onclick="ajaxModal('<?php echo base_url(MODULE.'/'.$class.'/add_supplier')?>','<?php echo $id_supplier?>','modal-form')" class="btn btn-white btn-warning btn-round btn-sm">
															<i class="ace-icon fa fa-plus bigger-120 orange"></i>															
															</a>																													
														</div>																										
													</div>		
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Language</label>
														<div class="col-sm-2">
															<select class="chosen-select form-control" name="language" data-placeholder="Choose a Language" required>
																<option value="" />
																<?php foreach ($language as $row){
																	if (isset($id_language)){$select = ($id_language == $row->id_language) ? 'selected' : '';}
																	echo '<option value="'.$row->id_language.'" '.$select.'>'.$row->name_language.'</option>';
																}?> 															
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Information</label>
														<div class="col-sm-8">
															<textarea name="information" class="textareas" id="information"><?php echo isset($product_information) ? $product_information : '' ?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Description</label>
														<div class="col-sm-8">
															<textarea name="description" class="textareas" id="description"><?php echo isset($description_product) ? $description_product : '' ?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Specification</label>
														<div class="col-sm-8">
															<textarea name="specification" class="textareas" id="specification"><?php echo isset($specification) ? $specification : '' ?></textarea>
														</div>
													</div>	
													<div class="form-group">																									
														<?php echo $this->m_content->check_button('Active',isset($active) ? $active : '')?>					
													</div>
													<div class="form-group">
														<?php echo $this->m_content->check_button('Display',isset($display) ? $display : '')?>
													</div>
													<div class="form-group">
														<?php echo $this->m_content->check_button('Promo',isset($promo) ? $promo : '')?>
													</div>
													<div class="form-group">
														<?php echo $this->m_content->check_button('Featured Product',isset($featured_product) ? $featured_product : '')?>
													</div>
													<div class="form-group">
														<?php echo $this->m_content->check_button('Product Recomend',isset($product_recomend) ? $product_recomend : '')?>
													</div>	
													<div class="form-group">
														<?php echo $this->m_content->check_button('New Product',isset($new_product) ? $new_product : '')?>
													</div>
													<div class="form-group">
														<?php echo $this->m_content->check_button('Top Seller',isset($top_seller) ? $top_seller : '')?>
													</div>	
													<div class="form-group">
														<?php echo $this->m_content->check_button('Show Price',isset($show_price) ? $show_price : '')?>
													</div>				