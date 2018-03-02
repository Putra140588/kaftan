<?php 
$this->load->view('email_temp/v_header_office');
$total_payment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;
$total_cost_shipp = ($iso_code == 'IDR') ? formatnum($total_cost_shipping) : $total_cost_shipping;
$total_product = ($iso_code == 'IDR') ? formatnum($total_product) : $total_product;
$amount_tax = ($iso_code == 'IDR') ? formatnum($amount_tax) : $amount_tax;
echo '<table style="width:100%">
		<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">
		<div style="margin:0 auto;max-width:600px;padding:15px;display:block">
			<table>
				<tr>
					<td>
						<h3>Hi,'.$name.'</h3>						
						<p style="font-size:17px;">'.sprintf(lang('text2'),$this->session->userdata('company_name')).'</p>';
						$this->load->view('email_temp/v_notice_order_head');						
			      echo '<p style="font-size:15px;font-weight:bold">'.lang('text3').' :</p>
					</td>				
				</tr>
				<tr><td>
				<p>				
					'.lang('text4').': <b>#'.$id_order.'</b><br/>
					'.lang('text6').': <b>'.$symbol.' '.$total_payment.'</b><br>
					'.lang('couriershipp').': <b>'.$name_courier.'</b>	<br/>
					'.lang('paymethod').': <b>'.$method_name.'</b><br>	
					'.lang('text5').': <b>'.nama_hari($date_add_order).', '.tgl_indo($date_add_order).'</b><br/>											
				</p>
				</td></tr>				
			</table>';					
	echo '<table style="width:100%; font-size : 13px">
				<tr>
					<td>													
						<table align="left" style="height:180px; width:100%; border-top:1px solid #b59353">
							<thead style="background: #b59353; color:white;">
								<tr>									
									<th>'.lang('prodname').'</th>											
									<th>'.lang('unitprc').'</th>
									<th>'.lang('prodqty').'</th>
									<th>'.lang('subtot').'</th>										
        						</tr>												
							</thead>	
							<tbody style="text-align: center;background: #ebebeb;">';		
								$detail = $this->m_client->get_order_detail(array('A.id_order'=>$id_order));
								if (count($detail) > 0){									
									foreach ($detail as $row){
										$permalink = base_url(FOMODULE.'/'.$row->permalink.'-'.$row->id_product.'.'.$row->id_category_level1.'.html');
										$unit_price = ($row->iso_code == 'IDR') ? number_format($row->unit_price,0,'.','.') : $row->unit_price;
										$subtotal_price = ($row->iso_code == 'IDR') ? number_format($row->total_price,0,'.','.') : $row->total_price;
										echo'<tr>';										
										echo'<td>												
												'.$row->name.'<br>
												'.$row->name_group.': '.$row->name_attribute.'
											</td>';
										echo'<td>'.$row->symbol.' '.$unit_price.'</td>';
										echo'<td>'.$row->product_qty.'</td>';
										echo'<td>'.$row->symbol.' '.$subtotal_price.'</td>';
										echo'</tr>';
									}
									echo '<tr style="font-weight:bold">
												<td>'.lang('totqty').':</td>
												<td>'.$total_qty.'</td>
												<td>'.lang('subtot').':</td>
												<td >'.$symbol.' '.$total_product.'</td>
											</tr>';
									if ($rate_tax > 0){
										echo '<tr style="font-weight:bold">
												<td colspan=3>'.lang('tax').' '.formatnum($rate_tax).'%:</td>
												<td>'.$symbol.' '.$amount_tax.'</span></td>
											  </tr>';
									}
									echo '<tr style="font-weight:bold">
												<td colspan=3>'.lang('costshipp').':</td>
												<td>'.$symbol.' '.$total_cost_shipp.'</span></td>
											</tr>';
									echo '<tr style="font-weight:bold">
												<td colspan=3>'.lang('total').':</td>
												<td>'.$symbol.' '.$total_payment.'</span></td>
											</tr>';
								}
					  echo '</tbody>
						</table>			
					</td>
				</tr>
			</table>';
			echo '<p></p>';			
				echo '<table style="width:100%; font-size : 13px;border-bottom:1px solid #000000">
							<tr>
								<td>																	
									<table align="left" style="width: 300px;">
										<tr>
											<td>		
												<h6>'.lang('delivaddr').'</h6>	
												<p>
									    			<b>'.$name_received.'</b><br>
									    				'.$address.'<br>';
												if ($country_code == 'ID'){
													echo $districts_name.', '.$cities_name.', '.$postcode.'<br>
									    				'.$province_name.', '.$country_name.'<br>';
												}else{
													echo $postcode.', '.$country_name.'<br>';									    				
												}									    								
												echo 'Phone: '.$phone_addr;							    				
									 	echo'</p>	
											</td>
										</tr>
									</table>																
								</td>
							</tr>
						</table>';
						echo '<p></p>';
						$this->load->view('email_temp/v_payment_guide');											
				echo '</div>
					</td>
					<td></td>
				</tr>
			</table>';

$this->load->view('email_temp/v_footer_office');?>