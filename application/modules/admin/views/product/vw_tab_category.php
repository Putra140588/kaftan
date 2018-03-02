													<div class="form-group required">
														<label class="col-sm-4 control-label"> Parent Category </label>														
														<div class="col-sm-2">
															<div id="loadparent">
																<?php 
																$id_parent_category = isset($id_parent_category) ? $id_parent_category : '';
																$where = array('id_level'=>0,'id_parent'=>0);
																echo $this->m_content->chosen_category($id_parent_category,$where,'parent','required')?>	
															</div>																																																										
														</div>																																																								
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Category Level 1 </label>														
														<div class="col-sm-2">
															<div id="parent">
																<?php 
																$id_category_level1 = isset($id_category_level1) ? $id_category_level1 : '';
																$where = array('id_parent'=>$id_parent_category);
																echo $this->m_content->chosen_category($id_category_level1,$where,'level1')?>	
															</div>																																																										
														</div>																																																								
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Category Level 2 </label>														
														<div class="col-sm-2">
															<div id="level1">
																<?php 
																$id_category_level2 = isset($id_category_level2) ? $id_category_level2 : '';
																$where = array('id_parent'=>$id_category_level1);
																echo $this->m_content->chosen_category($id_category_level2,$where,'level2')?>	
															</div>																																																										
														</div>																																																								
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Category Level 3 </label>														
														<div class="col-sm-2">
															<div id="level2">
																<?php 
																$id_category_level3 = isset($id_category_level3) ? $id_category_level3 : '';
																$where = array('id_parent'=>$id_category_level2);
																echo $this->m_content->chosen_category($id_category_level3,$where,'level3')?>	
															</div>																																																										
														</div>																																																								
													</div>