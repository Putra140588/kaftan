		<div class="main-content">
				<div class="main-content-inner">
				 <?php $this->load->view('vw_header_form')?>
		<div class="page-content">				
			<div class="row">
				<div class="col-xs-12">
					<?php $this->load->view('vw_alert_notif')?>																														
					<form class="form-horizontal" id="form-ajax" value="<?php echo base_url(MODULE.'/'.$class.'/proses')?>">
					<?php $id_product = isset($id_product) ? $id_product : '';?>
					<input type="hidden" name="id_product" value="<?php echo $id_product?>">
					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
					<?php $select="";
						?>
						<div class="widget-box transparent">
							   <?php $this->load->view('vw_button_form')?>																
								<div class="widget-body">
									<div class="widget-main padding-6 no-padding-left no-padding-right">
										<div class="space-6"></div>
										<div class="tabbable">
											<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
												<li class="active">
													<a data-toggle="tab" href="#info">INFORMATION</a>
												</li>
												<li>													
													<a data-toggle="tab" href="#cate">CATEGORIES</a>
												</li>
												<li>												
													<a data-toggle="tab" href="#prc">PRICES</a>
												</li>
												<li>
													<a data-toggle="tab" href="#ship">SHIPPING</a>
												</li>
												<li>
													<a data-toggle="tab" href="#img">IMAGES</a>
												</li>
												<li>
													<a data-toggle="tab" href="#atch">ATTACHMENTS</a>
												</li>
												<li>
													<a data-toggle="tab" href="#seo">SEO</a>
												</li>
												<li>
													<a data-toggle="tab" href="#vid">VIDEO</a>
												</li>
												<li>
													<a data-toggle="tab" href="#attr">ATTRIBUTE</a>
												</li>
											</ul>
											<div class="tab-content">
												<!-- tab information -->
												<div id="info" class="tab-pane in active"><?php $this->load->view($class.'/vw_tab_information')?></div>																									
												<!-- Tab categories -->
												<div id="cate" class="tab-pane"><?php $this->load->view($class.'/vw_tab_category')?></div>	
												<!-- Tab price -->
												<div id="prc" class="tab-pane"><?php $this->load->view($class.'/vw_tab_price')?></div>	
												<!-- Tab shipping -->
												<div id="ship" class="tab-pane"><?php $this->load->view($class.'/vw_tab_shipping')?></div>
												<!-- Tab images -->
												<div id="img" class="tab-pane"><?php $this->load->view($class.'/vw_tab_image')?></div>				
												<!-- Tab attachment -->
												<div id="atch" class="tab-pane"><?php $this->load->view($class.'/vw_tab_attachment')?></div>		
												<!-- Tab seo -->
												<div id="seo" class="tab-pane"><?php $this->load->view($class.'/vw_tab_seo')?></div>	
												<!-- Tab video -->
												<div id="vid" class="tab-pane"><?php $this->load->view($class.'/vw_tab_video')?></div>		
												<!-- Tab attribute -->
												<div id="attr" class="tab-pane"><?php $this->load->view($class.'/vw_tab_attribute')?></div>																																																																									
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