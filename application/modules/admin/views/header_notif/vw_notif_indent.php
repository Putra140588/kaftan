						<?php $sql = $this->m_admin->get_order_indent();?>
						<li class="red dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-laptop bigger-150"></i>
								<span class="badge badge-red"><?php echo count($sql)?></span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									Order Indent
								</li>								
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<?php if (count($sql) > 0){
											echo '<li class="dropdown-footer">
													<a href="'.base_url(MODULE.'/stockindent/index/read').'">
														See All Order Indent
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												  </li>';
											foreach ($sql as $row){			
												$where = array('A.id_product'=>$row->id_product,'A.id_product_attribute'=>$row->id_product_attribute,'B.id_branch'=>$row->id_branch);
												$stockready = $this->m_admin->get_qty_available_attribute($where);
												echo '<li>
														<a href="'.base_url(MODULE.'/stockindent/index/read').'">
															<div class="clearfix">
																<span class="pull-left red">'.$row->name.'</span>																
															</div>
															<div class="clearfix">																
																<span class="pull-right green">Attribute: '.$row->name_group.':'.$row->name_attribute.'</span>
															</div>	
															<div class="clearfix">
																<span class="pull-left grey">'.$row->first_name.' '.$row->last_name.'</span>
																<span class="pull-right green">#'.$row->id_order.'</span>
															</div>		
															<div class="clearfix">
																<span class="pull-left grey">Order: '.$row->qty_buy_now.'</span>	
																<span class="pull-right grey">Indent: '.$row->qty_minus.'</span>																
															</div>
															<div class="clearfix">
																<span class="pull-left grey">Stock Last: '.$row->qty_available_last.'</span>	
																<span class="pull-right grey">Stock Now: '.$stockready.'</span>																
															</div>
															<span class="msg-time">
																<i class="ace-icon fa fa-clock-o"></i>
																<span class="orange">'.last_time($row->date_add).'</span>
															</span>
														</a>
													</li>';
											}
										}else{
											echo '<li>No Order Indent</li>';
										}?>																		
									</ul>
								</li>								
							</ul>
						</li>