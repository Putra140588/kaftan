						<?php $sql = $this->m_admin->get_order_notif();?>
						<li class="grey dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-shopping-cart bigger-150"></i>
								<span class="badge badge-grey"><?php echo count($sql)?></span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									New Order
								</li>								
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<?php if (count($sql) > 0){
											echo '<li class="dropdown-footer">
													<a href="'.base_url(MODULE.'/salesorder/index/read').'">
														See All Order
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												  </li>';
											foreach ($sql as $row){
											$total = ($row->iso_code == 'IDR') ? formatnum($row->total_payment) : $row->total_payment;
												echo '<li>
														<a href="'.base_url(MODULE.'/salesorder/view/'.$row->id_order.'/read').'">
															<div class="clearfix">
																<span class="pull-left red">'.$row->first_name.' '.$row->last_name.'</span>
																<span class="pull-right green">#'.$row->id_order.'</span>
															</div>		
															<div class="clearfix">
																<span class="pull-left grey">'.$row->total_qty.' Items</span>
																<span class="pull-right grey">Total : '.$row->symbol.' '.$total.'</span>
															</div>
															<span class="msg-time">
																<i class="ace-icon fa fa-clock-o"></i>
																<span>'.last_time($row->date_add_order).'</span>
															</span>
														</a>
													</li>';
											}
										}else{
											echo '<li>No new order</li>';
										}?>																		
									</ul>
								</li>								
							</ul>
						</li>