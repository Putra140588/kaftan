		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<input type="hidden" name="id_banner" value="<?php echo isset($id_banner) ? $id_banner : ''?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Banner Name </label>
											<div class="col-sm-5">
												<input type="text" placeholder="Banner Name"  name="name" class="col-xs-10 col-sm-10" value="<?php echo isset($banner_name) ? $banner_name : ''?>" required/>
											</div>
										</div>																
										<div class="form-group required">
											<label class="col-sm-4 control-label">Position</label>
											<div class="col-sm-3">
												<select name="position" class="form-control" required>
													<option value="" selected disabled>Choose a position...</option>
													<?php foreach ($banner_pos as $row){
														if (isset($id_banner_position)){$select=($id_banner_position == $row->id_banner_position) ? 'selected' : '';}
														echo '<option value="'.$row->id_banner_position.'" '.$select.'>'.$row->name_position.'</option>';
													}?>
												</select>
											</div>
										</div>											
										<div class="form-group">
											<label class="col-sm-4 control-label"> Page Link</label>
											<div class="col-sm-5">
												<input type="text" placeholder="Page Link" name="link" class="col-xs-10 col-sm-10" value="<?php echo isset($link_url) ? $link_url : ''?>"/>
											</div>
										</div>											
										<div class="form-group required">
											<label class="col-sm-4 control-label"> Sort Position </label>
											<div class="col-sm-2">
												<select class="chosen-select form-control" name="sort" data-placeholder="Choose a sort..." required>
													<option value="" />
													<?php for($i=1; $i <= 100; $i++ ){
														if (isset($sort)){$select = ($sort == $i) ? 'selected' : '';}
														echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
													}?>															
												</select>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-sm-4 control-label">Banner</label>
											<div class="col-sm-3">
												<input type="file" class="input-file" name="image">
												<div class="space-6"></div>
												<?php if (isset($image_banner)){?>
													<img style="width:150px" src="<?php echo base_url()?>assets/images/banner/<?php echo $image_banner?>">
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
									 </div>
								</div>
						</div>																	
				  </form>
			 </div>
		  </div>
	  </div>
	</div>
</div>