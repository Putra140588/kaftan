						<?php $sql = $this->m_admin->get_confirm_notif();?>
						<li class="blue dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-credit-card bigger-150"></i>
								<span class="badge badge-blue"><?php echo count($sql)?></span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									New Payment Confirmation
								</li>								
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<?php if (count($sql) > 0){
											echo '<li class="dropdown-footer">
													<a href="'.base_url(MODULE.'/payconfirm/index/read').'">
														See All Payment Confirm
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												  </li>';
											foreach ($sql as $row){
											$total = ($row->iso_code == 'IDR') ? formatnum($row->amount_transfer) : $row->amount_transfer;
												echo '<li>
														<a href="'.base_url(MODULE.'/payconfirm/index/read').'">
															<div class="clearfix">
																<span class="pull-left red">'.$row->account_by.'</span>
																<span class="pull-right green">#'.$row->id_order.'</span>
															</div>		
															<div class="clearfix">
																<span class="pull-left grey">'.$row->bank_from.'</span>
												  				<span class="pull-right blue">'.$row->name_method_transfer.'</span>																
															</div>
													  		<div class="clearfix">
													  			<span class="pull-left grey">Amount Trans : '.$row->symbol.' '.$total.'</span>
															</div>
															<span class="msg-time">
																<i class="ace-icon fa fa-clock-o"></i>
																<span>'.last_time($row->date_add).'</span>
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