						<?php $this->load->view('vw_alert_notif')?>		
						<form class="form-horizontal" id="<?php echo $id_form?>" value="<?php echo base_url(MODULE.'/manufacture/proses#loadmanu')?>">
							<input type="hidden" name="id_manufacture" value="<?php echo isset($id_manufacture) ? $id_manufacture : ''?>">
							<input type="hidden" name="id_form" value="<?php echo isset($id_form) ? $id_form: ''?>">
							<input type="hidden" name="id_manu_add" value="<?php echo isset($id_manu_add) ? $id_manu_add : ''?>">
							<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">					
								<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
									<?php $select="";?>									
									<div class="tabbable">
											<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
												<li class="active">
													<a data-toggle="tab" href="#infor">Information</a>
												</li>
												<li>
													<a data-toggle="tab" href="#seos">SEO</a>
												</li>
											</ul>
											<div class="tab-content">
												<div id="infor" class="tab-pane in active">
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Manufacture Name </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Manufacture Name" name="name" id="manuname" class="col-xs-10 col-sm-5" value="<?php echo isset($name) ? $name : ''?>" required/>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-4 control-label"> Language</label>
														<div class="col-sm-2">
															<select class="chosen-select form-control" name="language" data-placeholder="Choose a Language" required>
																<option value="" />
																<?php 
																$language = $this->m_admin->get_table('tb_language',array('id_language','name_language'),array('deleted'=>0));
																foreach ($language as $row){
																	if (isset($id_language)){$select = ($id_language == $row->id_language) ? 'selected' : '';}
																	echo '<option value="'.$row->id_language.'" '.$select.'>'.$row->name_language.'</option>';
																}?> 															
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Description </label>
														<div class="col-sm-5">
															<textarea name="description" id="description" class="textareas"><?php echo isset($description) ? $description : ''?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Sortir </label>
														<div class="col-sm-2">
															<select class="chosen-select form-control" name="sort" data-placeholder="Choose a sortir..">
																<option value="" />
																<?php for($i=1; $i <= 100; $i++ ){
																	if (isset($sort)){$select = ($sort == $i) ? 'selected' : '';}
																	echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
																}?>															
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">Logo</label>
														<div class="col-sm-3">
															<input type="file" class="input-file" name="image[]">
															<div class="space-6"></div>
															<?php if (isset($image)){?>
																<img style="width:150px" src="<?php echo base_url()?>assets/images/manufacture/<?php echo $image?>">
															<?php }?>
														</div>											
													</div>	
													<div class="form-group">
														<label class="col-sm-4 control-label">Banner</label>
														<div class="col-sm-3">
															<input type="file" class="input-file" name="image[]">
															<div class="space-6"></div>
															<?php if (isset($image_banner)){?>
																<img style="width:150px" src="<?php echo base_url()?>assets/images/manufacture/<?php echo $image_banner?>">
															<?php }?>
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
																<?php if (isset($displays)){$select = ($displays == 1) ? 'checked' : '';}?>
																<input class="ace ace-switch ace-switch-5" name="display" <?php echo $select?> type="checkbox">
															<span class="lbl"></span>
															</label>
														</div>
													</div>
												</div>

												<div id="seos" class="tab-pane">
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Title </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Meta Title" name="metatitle" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_title) ? $meta_title : ''?>"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Description </label>
														<div class="col-sm-5">
															<textarea name="metadesc" id="metadesc" class="textareas"><?php echo isset($meta_description) ? $meta_description : ''?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Meta Keyword </label>
														<div class="col-sm-8">
															<input type="text" placeholder="Meta Keyword" name="metakey" class="col-xs-10 col-sm-5" value="<?php echo isset($meta_keywords) ? $meta_keywords : ''?>"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"> Friendly URL </label>
														<div class="col-sm-5">
															<input type="text" placeholder="Friendly URL" name="friendlyurl" id="friendlyurl" class="col-xs-10 col-sm-5" value="<?php echo isset($url) ? $url : ''?>"/>
														</div>
													</div>
													<div class="form-group">
													<label class="col-sm-4 control-label"></label>
														<div class="col-sm-5">
															<button class="btn btn-white btn-info" type="button" onclick="ajaxcall('<?php echo base_url(MODULE.'/manufacture/generate_url')?>',$('#manuname').val(),'friendlyurl')">Generate Friendly URL</button>
														</div>
													</div>
												</div>												
											</div>
										</div>
							 		</div>
								</div>
						</div>																	
				  </form>