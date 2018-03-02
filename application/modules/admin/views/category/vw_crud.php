		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_category" value="<?php echo isset($id_category) ? $id_category : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";$select0='';$select1='';$select2='';$select3='';?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
							<div class="widget-body">
								<div class="widget-main padding-6 no-padding-left no-padding-right">
									<div class="space-6"></div>
										<div class="tabbable">		
											<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
												<li class="active">
													<a data-toggle="tab" href="#info">Information</a>
												</li>
												<li>
													<a data-toggle="tab" href="#pagedescript">Page Description</a>
												</li>
												<li>
													<a data-toggle="tab" href="#seo">SEO</a>
												</li>
											</ul>
											<div class="tab-content">
												<div id="info" class="tab-pane in active">
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Category Name </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Category Name" id="categoryname" name="name" class="col-xs-10 col-sm-5" value="<?php echo isset($name_category) ? $name_category : ''?>" required/>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Parent Category </label>
														<div class="col-sm-5">
															<select class="chosen-select form-control" name="parent" data-placeholder="Choose a parent" required>
																<option value="" />
																<?php if (isset($id_category)){$select0 = ($id_parent == 0) ? 'selected':'';}?>												
																<option value="0" <?php echo $select0?>>[PARENT]</option>		
																<?php $parent = $this->m_admin->get_category(array('id_parent'=>0,'id_level'=>0));
																	foreach ($parent as $row){
																		if (isset($id_category)){$select1 = ($id_parent == $row->id_category) ? 'selected':'';}
																		echo '<option value="'.$row->id_category.'" '.$select1.'>['.$row->name_category.']</option>';
																		$level1 = $this->m_admin->get_category(array('id_level'=>1));
																		foreach ($level1 as $val)
																		{																			
																			if ($row->id_category == $val->id_parent)
																			{
																				if (isset($id_category)){$select2 = ($id_parent == $val->id_category) ? 'selected':'';}
																				echo '<option value="'.$val->id_category.'" '.$select2.'>['.$row->name_category.'] -> ['.$val->name_category.']</option>';
																				
																				$level2 = $this->m_admin->get_category(array('id_level'=>2));
																				foreach ($level2 as $item)
																				{																					
																					if ($val->id_category == $item->id_parent)
																					{
																						if (isset($id_category)){$select3 = ($id_parent == $item->id_category) ? 'selected':'';}
																						echo '<option value="'.$item->id_category.'" '.$select3.'>['.$row->name_category.'] -> ['.$val->name_category.'] -> ['.$item->name_category.']</option>';
																					}
																				}	
																																							
																			}																				
																		}																	
																		
																	}
																?>														
															</select>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Level Category </label>
														<div class="col-sm-2">
															<select class="chosen-select form-control" name="level" data-placeholder="Choose a parent" required>
																<option value="" />
																<?php $level = array(
																	0 =>'PARENT',
																	1 =>'Level 1',
																	2 =>'Level 2',
																	3 =>'Level 3');																																											
																for ($i=0; $i <= 3; $i++){
																	if (isset($id_level)){$select = ($id_level == $i) ? 'selected' : '';}
																	echo '<option value='.$i.' '.$select.'>'.$level[$i].'</option>';
																}?>									
															</select>
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
														<label class="col-sm-4 control-label"> Descriptions </label>
														<div class="col-sm-6">
															<textarea name="desc" class="textareas" id="desc"><?php echo isset($description) ? $description : ''?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Active</label>
														<div class="col-sm-5">
															<label>
																<?php if (isset($active)){$select = ($active == 1) ? 'checked' : '';}?>
																<input class="ace ace-switch ace-switch-5" name="active" <?php echo $select?> type="checkbox">
															<span class="lbl"></span>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Display</label>
														<div class="col-sm-5">
															<label>
																<?php if (isset($display)){$select = ($display == 1) ? 'checked' : '';}?>
																<input class="ace ace-switch ace-switch-5" name="display" <?php echo $select?> type="checkbox">
															<span class="lbl"></span>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Banner</label>
														<div class="col-sm-3">
															<input type="file" class="input-file" name="banner">
															<div class="space-6"></div>
															<?php if (isset($image)){?>
																<img style="width:150px" src="<?php echo base_url()?>assets/images/category/<?php echo image($image)?>">
															<?php }?>
														</div>											
													</div>	
													
												</div>
												<div id="pagedescript" class="tab-pane">
													<div class="form-group">
														<label class="col-sm-4 control-label"> Page Link </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Page Link" name="pagelink" class="col-xs-10 col-sm-5" value="<?php echo isset($page_link) ? $page_link : ''?>"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Button Caption </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Button Caption" name="buttonname" class="col-xs-10 col-sm-5" value="<?php echo isset($page_btn) ? $page_btn : ''?>"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Page Descriptions </label>
														<div class="col-sm-6">
															<textarea name="pagedesc" class="textareas" id="pagedesc"><?php echo isset($page_description) ? $page_description : ''?></textarea>
														</div>
													</div>
												</div>
												<div id="seo" class="tab-pane">
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Title </label>
														<div class="col-sm-8">
															<input type="text" placeholder="Meta Title" name="metatitle" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_title) ? $meta_title : ''?>"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Descriptions </label>
														<div class="col-sm-6">
															<textarea name="metadesc" class="textareas" id="metadesc"><?php echo isset($meta_description) ? $meta_description : ''?></textarea>															
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Keywords </label>
														<div class="col-sm-8">
															<input type="text" placeholder="Meta Keywords" name="metakeyword" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_keywords) ? $meta_keywords : ''?>"/>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Friendly URL </label>
														<div class="col-sm-8">
															<input type="text" placeholder="Friendly URL" name="friendlyurl" id="friendlyurl" class="col-xs-10 col-sm-5" value="<?php echo isset($url) ? $url : ''?>" required/>
														</div>
													</div>
													<div class="form-group">
													<label class="col-sm-4 control-label"></label>
														<div class="col-sm-5">
															<button class="btn btn-white btn-info" type="button" onclick="ajaxcall('<?php echo base_url(MODULE.'/category/generate_url')?>',$('#categoryname').val(),'friendlyurl')">Generate Friendly URL</button>
														</div>
													</div>
												</div>
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