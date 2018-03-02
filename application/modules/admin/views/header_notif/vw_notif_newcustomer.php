						<?php $sql = $this->m_admin->get_customer_notif();?>
						<li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-user bigger-150"></i>
								<span class="badge badge-purple"><?php echo count($sql)?></span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									New Customer
								</li>
								
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<?php if (count($sql) > 0){
											echo '<li class="dropdown-footer">
													<a href="'.base_url(MODULE.'/customer/index/read').'">
														See All Customer
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</li>';
											foreach ($sql as $row){
											$gender = ($row->criteria == 'male') ? 'avatar1.png' : 'avatar.png';
												echo '<li>
														<a href="'.base_url(MODULE.'/customer/view/'.$row->id_customer.'/read').'" class="clearfix">
															<img src="'.base_url().'assets/images/avatars/'.$gender.'" class="msg-photo" alt="#" />
															<span class="msg-body">
																<span class="msg-title">
																	<span class="blue">'.$row->first_name.' '.$row->last_name.'</span>																	
																</span>			
																<span class="msg-time">
																	<i class="ace-icon fa fa-clock-o"></i>
																	<span>'.last_time($row->date_add).'</span>
																</span>
															</span>
														</a>
													</li>';
											}
										}else{
											echo '<li>No new customer</li>';
										}?>																		
									</ul>
								</li>								
							</ul>
						</li>