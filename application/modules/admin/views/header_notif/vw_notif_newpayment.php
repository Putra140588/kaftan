						<?php $sql = $this->m_admin->get_payment_notif();?>
						<li class="green dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-money bigger-150"></i>
								<span class="badge badge-green"><?php echo count($sql)?></span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									New Payment Transactions
								</li>								
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<?php if (count($sql) > 0){
											echo '<li class="dropdown-footer">
													<a href="'.base_url(MODULE.'/transaction/index/read').'">
														See All Payment
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												  </li>';
											foreach ($sql as $row){
											$total = ($row->iso_code == 'IDR') ? formatnum($row->total_pay) : $row->total_pay;
												echo '<li>
														<a href="'.base_url(MODULE.'/transaction/index/read').'">
															<div class="clearfix">
																<span class="pull-left red">'.$row->first_name.' '.$row->last_name.'</span>
																<span class="pull-right green">#'.$row->id_order.'</span>
															</div>		
															<div class="clearfix">
																<span class="pull-left grey">'.$row->payment_method.'</span>
												  				<span class="pull-right blue">'.$row->status_pay.'</span>																
															</div>
													  		<div class="clearfix">
													  			<span class="pull-left grey">Total Pay : '.$row->symbol.' '.$total.'</span>
															</div>
															<span class="msg-time">
																<i class="ace-icon fa fa-clock-o"></i>
																<span>'.last_time($row->date_add_pay).'</span>
															</span>
														</a>
													</li>';
											}
										}else{
											echo '<li>No new payment transaction</li>';
										}?>																		
									</ul>
								</li>								
							</ul>
						</li>