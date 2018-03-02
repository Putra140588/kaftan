<?php				 					  	
	echo '<div class="panel-group custom-accordion" id="collapse">';			
	$page = ($received_confirm == true) ? 'confirm_received' : 'transaction_list';	
	//ajax pagination configuration
	$config['div']         = 'historyord'; //parent div tag id for show element table
	$config['base_url']    = base_url(FOMODULE.'/customer/'.$page);//url send to ajax
	$config['total_rows']  = count($sql);
	$config['per_page']    = $limit;
	$config['uri_segment'] = 4;
	$config['param'] = '';//id_category / code & keyword
	$config['limit_class'] = 'limited';//untuk show display limit
	$this->ajax_pagination->initialize($config);
	$data = array_slice($sql, $uri,$limit);
	if (count($sql) > 0){
		foreach ($data as $row){
			$total_cost_shipp = ($row->iso_code == 'IDR') ? formatnum($row->total_cost_shipping) : $row->total_cost_shipping;
			$total_payment = ($row->iso_code == 'IDR') ? formatnum($row->total_payment) : $row->total_payment;
			$total_product = ($row->iso_code == 'IDR') ? formatnum($row->total_product) : $row->total_product;
			$amount_tax = ($row->iso_code == 'IDR') ? formatnum($row->amount_tax) : $row->amount_tax;
			echo '<div class="panel">
                        <div class="accordion-header">
                                        <div class="accordion">
											<div class="row">
												<div class="col-md-3">
													<p class="title-desc">
														'.lang('text4').'<br>
														<strong>#'.$row->id_order.' <a href="'.base_url(MODULE.'/letter/orderslip/inv/'.$row->id_order).'" target="_blank" title="Download Invoice">(Invoice)</a></strong>
												    </p>													
												</div>
												<div class="col-md-3">													
													<p class="title-desc">
														'.lang('text5').'<br>
														<strong>'.nama_hari($row->date_add_order).', '.tgl_indo($row->date_add_order).'</strong>
													</p>
												</div>
												<div class="col-md-3">													
													<p class="title-desc">
														'.lang('text6').'<br>
														<strong>'.$row->symbol.' '.$total_payment.'</strong>
													</p>
												</div>
												<div class="col-md-3">													
													<p class="title-desc">
														Status<br>
														<span class="'.$row->label_color.'">'.$row->name_status.'</span>
													</p>';	
													//jika pada halaman konfirmasi terima barang	
													if ($received_confirm == true){
														echo '<p><button class="btn btn-default btn-sm" type="button" data-toggle="modal" data-target="#MyModal" onclick="ajaxModal(\''.base_url(FOMODULE.'/customer/received').'\',\''.$row->id_order.'\',\'MyModal\')">'.lang('goodsrecv').'</button></p>';	
													}									
										echo '</div>
											</div>								
                                        <a class="accordion-btn opened"  data-toggle="collapse" data-target="#'.$row->id_order.'"></a>
                                    </div>
								</div>
                                    <div id="'.$row->id_order.'" class="collapse">
                                        <div class="panel-body">';
										echo '<h5>'.lang('text3').'</h5>
												<div class="input-desc-box">
													    <div class="row">
													    	<div class="col-md-3 col-sm-6 col-xs-6">
													    		<h5>'.lang('text4').' :</h5>
													    		<p>#'.$row->id_order.'</p>
													    		<h5>'.lang('text5').' :</h5>
													    		<p>'.nama_hari($row->date_add_order).', '.tgl_indo($row->date_add_order).'</p>	
													    		<h5>'.lang('currency').' :</h5>
													    		<p>'.$row->iso_code.'</p>													    		
													    	</div>
													    	<div class="col-md-3 col-sm-6 col-xs-6">
													    		<h5>'.lang('delivaddr').' :</h5>
													    		<p>
													    			<b>'.$row->name_received.'</b><br>
													    			'.$row->address.'</br>';
																if ($row->country_code == 'ID'){
													    		echo $row->districts_name.', '.$row->cities_name.', '.$row->postcode.'<br>
													    			'.$row->province_name.', '.$row->country_name.'<br>';
																}else{
																	echo $row->postcode.', '.$row->country_name.'<br>';
																}						
																echo 'Phone: '.$row->phone_addr.'						    				
													    		</p>			
													    	</div>								
													    	<div class="col-md-3 col-sm-6 col-xs-6">
													    		<h5>'.lang('couriershipp').' :</h5>
													    		<p>'.$row->name_courier.'</p>
													    		<h5>'.lang('text9').' :</h5>
													    		<p>'.$row->symbol.' '.$total_cost_shipp.'</p>
													    	</div>
													    	<div class="col-md-3 col-sm-6 col-xs-6">';			
																if ($row->pay_code == 8801){
																	//transfer via bank rekening
																echo   '<h5>'.lang('paymethod').' :</h5>
																		<p>'.$row->method_name.'</p>
																		<h5>No. Rek :</h5>
																		<p>'.$row->content.'</p>
																		<h5>Owner :</h5>
																		<p>'.$row->name_owner.'</p>';
																}else{																	
																 echo '<h5>'.lang('paymethod').' :</h5>
																		<p>'.$row->method_name.'</p>';
																}
														echo '<h5>'.lang('statepay').' :</h5>
																<p><span class="'.pay_status($row->payment_result).'">'.$row->payment_result.'</span></p>';
														echo '<h5>'.lang('text6').' :</h5>
													    		<p>'.$row->symbol.' '.$total_payment.'</p>';
													 echo '</div>			
													    </div>
													   </div>						   
													   <hr>';        						
                                           echo $this->m_public->list_product(array('A.id_order'=>$row->id_order));
                                           $x = array('symbol'=>$row->symbol,'total_product'=>$total_product,
                                           			  'rate_tax'=>$row->rate_tax,'amount_tax'=>$amount_tax,
                                           			  'total_cost_shipp'=>$total_cost_shipp,'total_payment'=>$total_payment,
                                           			  'notes'=>$row->notes,'total_qty'=>$row->total_qty
                                           	);
                                           echo $this->m_public->summary_product($x); 
                                           echo $this->m_public->order_status_log($row->id_order);
                         		echo '</div>
                                    </div>
             				</div>';
		}		
	}else{
		echo alert_warning('No data transaction');
	}			     	                             
    echo '</div>';      
    $default = 5; 
    $limited = ($limit > $default) ? 'All' : $default;
    echo '<div class="toolbox-pagination clearfix">
    		'.$this->ajax_pagination->create_links().'
    		<div class="view-count-box left">
				<span class="separator">view:</span>
				<div class="btn-group select-dropdown">													
				 <button type="button" class="btn select-btn">'.$limited.'</button>
						<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu" role="menu">';								
							echo '<li><a href="javascript:void(0)" onclick="ajaxcall(\''.base_url(FOMODULE.'/customer/view/'.$received_confirm).'\',\''.$default.'\',\'historyord\')" class="limitview">'.$default.'</a></li>';
							echo '<li><a href="javascript:void(0)" onclick="ajaxcall(\''.base_url(FOMODULE.'/customer/view/'.$received_confirm).'\',\''.count($sql).'\',\'historyord\')" class="limitview">All</a></li>';																		
				 echo '</ul>
				</div>
			</div>	
    	 </div>';
?>