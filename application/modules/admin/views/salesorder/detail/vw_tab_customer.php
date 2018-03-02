								<div class="row">
				   						<div class="col-xs-12 col-sm-2 center">
											<span class="profile-picture">											
												<img class="editable img-responsive editable-click editable-empty" alt="<?php echo $first_name?>" id="avatar" src="<?php echo base_url()?>assets/images/avatars/<?php echo $criteria.'.png'?>" />
											</span>	
											<div class="space-4"></div>
											<div class="width-80 label label-info label-large arrowed-in arrowed-in-right">
												<span class="white middle bigger-120"><?php echo $first_name.' '.$last_name?></span>														
											</div>										
										</div><!--/span-->
										<div class="col-xs-12 col-sm-5 center">
											<h4 class="blue">
												<span class="middle">Account</span>													
											</h4>
														<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">
																<div class="profile-info-name"> Gender </div>
																<div class="profile-info-value">
																	<span><?php echo ($gender != "") ? $gender : 'N/A'?></span>
																</div>
															</div>																														
															<div class="profile-info-row">
																<div class="profile-info-name"> Email </div>
																<div class="profile-info-value">
																	<i class="icon-envelope  light-orange bigger-110"></i>
																	<span><?php echo ($email != "") ? $email : 'N/A';?></span>
																	
																</div>
															</div>

															<div class="profile-info-row">
																<div class="profile-info-name"> Birthday </div>

																<div class="profile-info-value">
																	<span><?php echo ($birthdate != "") ? short_date($birthdate) : 'N/A'?></span>
																</div>
															</div>

															<div class="profile-info-row">
																<div class="profile-info-name"> Joined </div>

																<div class="profile-info-value">
																	<span><?php echo long_date_time($date_add)?></span>
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Group </div>
																<div class="profile-info-value"><span>
																<?php echo ($name_group != "") ? $name_group : 'N/A';?>
																</span>																	
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Add By </div>
																<div class="profile-info-value"><span>
																<?php echo ($add_by != "") ? $add_by : 'N/A';?>
																</span>																	
																</div>
															</div>
														</div>														
												</div><!--/span-->
												<div class="col-xs-12 col-sm-5">																	
													<?php
													$no=1;
													foreach ($rowaddress as $row){?>	
														<h4 class="blue">
															<span class="middle">Address <?php echo $no++?></span>													
														</h4>						
														<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">
																<div class="profile-info-name">Name Receiver</div>
																<div class="profile-info-value">
																	<span><?php echo $row->name_received?></span>
																</div>
															</div>
																													
															<div class="profile-info-row">
																<div class="profile-info-name"> Email </div>
																<div class="profile-info-value">
																	<i class="icon-envelope  light-orange bigger-110"></i>
																	<span><?php echo $row->email?></span>
																	
																</div>
															</div>

															<div class="profile-info-row">
																<div class="profile-info-name">Phone </div>
																<div class="profile-info-value">
																<i class="icon-phone  light-green bigger-110"></i>
																	<span><?php echo ($row->phone_addr != "") ? $row->phone_addr : 'N/A' ?></span>
																</div>
															</div>															
															<div class="profile-info-row">
																<div class="profile-info-name"> Company </div>
																<div class="profile-info-value">
																	<span><?php echo ($row->company != '') ? $row->company : ''?></span>
																</div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Address </div>
																<div class="profile-info-value"><span>
																	<?php echo $row->address.'<br>';
																	if ($row->country_code == 'ID'){
																		echo $row->districts_name.', '.$row->cities_name.' '.$row->postcode.'<br>
																			'.$row->province_name.','.$row->country_name;
																	}else{
																		echo $row->postcode.', '.$row->country_name;
																	}
																	?>
																</span>																	
																</div>
															</div>
														</div>
														<?php }?>														
														
												</div>
				   					</div>			   					
				   													