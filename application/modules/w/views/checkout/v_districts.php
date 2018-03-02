															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-region"></span><span class="input-text"><?php echo lang('province')?>&#42;</span></span>
																<div class="large-selectbox clearfix">
						                                            <select id="province" name="province" class="form-control" onchange="ajaxcall('<?php echo base_url(FOMODULE.'/'.$class.'/show_city/')?>',this.value,'city')">	
						                                            	<?php echo $this->m_public->chosen_province($id_country,$id_province,$class);?>                                              
						                                            </select>
						                                        </div>
															</div>	
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-city"></span><span class="input-text"><?php echo lang('city')?>&#42;</span></span>
																<div class="large-selectbox clearfix">
						                                            <select id="city" name="city" class="form-control" onchange="ajaxcall('<?php echo base_url(FOMODULE.'/'.$class.'/show_districts/')?>',this.value,'districts')">
						                                               <?php echo $this->m_public->chosen_city($id_province,$id_cities,$class);?>
						                                            </select>
						                                        </div><!-- End .large-selectbox-->
															</div><!-- End .input-group -->	
															<div class="input-group">
																<span class="input-group-addon"><span class="input-icon input-icon-city"></span><span class="input-text"><?php echo lang('distr')?>&#42;</span></span>
																<div class="large-selectbox clearfix">
						                                            <select id="districts" name="districts" class="form-control">
						                                                <?php echo $this->m_public->chosen_districts($id_cities,$id_districts,$class);?>
						                                            </select>
						                                        </div>
															</div>	
															<div class="clearfix"></div>	