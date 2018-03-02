<?php 
$title = isset($title) ? $title : lang('statepay');
$sql = $this->m_client->get_order(array('A.id_customer'=>$this->session->userdata('id_customer'),
						 				'A.active'=>1,'A.payment_receive'=>0));
	echo '<h3>'.$title.'</h3><p>'.sprintf(lang('waitpay'),$this->session->userdata('company_name')).'<p>';
	echo '<div class="panel-group custom-accordion">';
	if (count($sql) > 0){
		foreach ($sql as $row){
			$sumberpay = ($row->pay_code == 8801) ? 'No Rekening : <b>'.$row->content.'</b>' : $row->content;
			$total_payment = ($row->iso_code == 'IDR') ? formatnum($row->total_payment) : $row->total_payment;
			echo '<div class="panel">
                        <div class="accordion-header">
                                        <div class="accordion">
											<div class="row">
												<div class="col-md-3 col-sm-6 col-xs-6">
													<p>
														'.lang('text4').'<br>
														<strong>#'.$row->id_order.'</strong>
												    </p>
													<p>
														'.lang('date').'<br>
														<strong>'.nama_hari($row->date_add_order).', '.tgl_indo($row->date_add_order).'</strong>
													</p>
												</div>
												<div class="col-md-3 col-sm-6 col-xs-6">													
													<p>
														'.lang('amountpay').'<br>
														<strong>'.$row->symbol.' '.$total_payment.'</strong>
													</p>
													<p>
														'.lang('sourcepay').'<br>
														<strong>'.$sumberpay.'</strong>
													</p>
												</div>
												<div class="col-md-3 col-sm-6 col-xs-6">													
													<p>
														'.lang('destpay').'<br>
														<strong>'.$row->method_name.'</strong>
													</p>
													<p>
														'.lang('rekeningfrom').'<br>
														<strong>'.$row->name_owner.'</strong>
													</p>
												</div>
												<div class="col-md-3 col-sm-6 col-xs-6">		
													<p>
														'.lang('remark').'<br>
														<span class="'.$row->label_color.'">'.$row->payment_result.'</span>
													</p>
													<p><strong><a href="'.base_url(MODULE.'/letter/orderslip/inv/'.$row->id_order).'" target="_blank" title="Download Invoice">Invoice</a></strong></p>											
													<p>														
														<a href="javascript:void(0)" data-toggle="modal" data-target="#MyModal" onclick="ajaxModal(\''.base_url(FOMODULE.'/customer/cancel_order').'\',\''.$row->id_order.'\',\'MyModal\')"><i class="fa fa-times"></i> '.lang('cancel').'</a>
													</p>
												</div>
											</div>								
                                      
                                    </div>
								</div>                                    
             				</div>';
		}
	}else{
		echo alert_warning(lang('nostatepay'));
	}
	echo '</div>';	
?>