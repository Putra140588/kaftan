<?php $totalpayment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;
		$total_cost_shipp = ($iso_code == 'IDR') ? formatnum($total_cost_shipping) : $total_cost_shipping;
		$total_product = ($iso_code == 'IDR') ? formatnum($total_product) : $total_product;
		$amount_tax = ($iso_code == 'IDR') ? formatnum($amount_tax) : $amount_tax;
		$total_amount_pay = ($iso_code == 'IDR') ? formatnum($total_amount_pay) : $total_amount_pay;
		$total_balance = ($iso_code == 'IDR') ? formatnum($total_balance) : $total_balance;?>
<section>
		<div class="details clearfix">
			<div class="client left">
				<p>INVOICE TO:</p>
				<p class="name"><?php echo $first_name.' '.$last_name?></p>
				<p>
					<?php echo 					
						$address.'<br>';
						if ($country_code == 'IDR'){
						  echo $districts_name.', '.$cities_name.' '.$postcode.'<br>
						  	  '.$province_name.','.$country_name.'<br>';
						}else{
							echo $postcode.', '.$country_name.'<br>';
						}
						echo 'Phone : '.$phone_addr;
					?>
				</p>
				<?php echo '<a href="mailto:'.$email.'">'.$email.'</a>';?>
				
			</div>
			<div class="data right">
				<p><?php echo 'Invoice #'.$id_order.'<br>
							Order Date: '.long_date_time($date_add_order).'<br>							
							Shipp Via: '.$name_courier.'<br>
							Pay Methods: '.$method_name.'<br>
							Currency : '.$iso_code.'<br>
							Total : '.$symbol.' '.$totalpayment
					?>
				</p>
			</div>
		</div>
		<div class="container">
			<div class="table-wrapper">
				<table>
					<tbody>
						<tr>
							<th class="no">#</th>
							<th class="desc"><div>PRODUCT</div></th>
							<th class="qty"><div>QUANTITY</div></th>
							<th class="unit"><div>UNIT PRICE</div></th>
							<th class="total"><div>TOTAL</div></th>
						</tr>
					</tbody>
					<tbody class="body">
						<?php 
						$no=1;
						foreach ($detail as $row){
							$unit_price = ($row->iso_code == 'IDR') ? number_format($row->unit_price,0,'.','.') : $row->unit_price;
							$subtotal_price = ($row->iso_code == 'IDR') ? number_format($row->total_price,0,'.','.') : $row->total_price;
							$cancel = ($row->active == 0) ? '<span class="label label-important arrowed-in-right arrowed">Cancel</label>':'<span class="label label-success arrowed-in-right arrowed">Active</span>';
							echo '<tr>
								  <td class="no">'.$no++.'</td>								 
								  <td class="desc">'.$row->name.'<br> '.$row->name_group.': '.$row->name_attribute.'</td>
								  <td class="qty">'.$row->product_qty.'</td>
								  <td class="unit">'.$row->symbol.' '.$unit_price.'</td>
						          <td class="total">'.$row->symbol.' '.$subtotal_price.'</td>
						  		</tr>';							
						}
						?>
												
					</tbody>
				</table>
			</div>
			<div class="no-break">
				<table class="grand-total">
					<tbody>
						<tr>							
							<td class="qty"></td>
							<td class="qty"></td>
							<td class="unit" rowspan="4">TOTAL QTY: <?php echo $total_qty?></td>							
							<td class="unit">SUBTOTAL:</td>
							<td class="total"><?php echo $symbol.' '.$total_product?></td>
						</tr>
						<tr>
							
							<td class="desc"></td>
							<td class="qty"></td>
							<td class="unit">TAX <?php echo $rate_tax?>%:</td>
							<td class="total"><?php echo $symbol.' '.$amount_tax?></td>
						</tr>
						<tr>
							
							<td class="desc"></td>
							<td class="qty"></td>
							<td class="unit">SHIPPING COST:</td>
							<td class="total"><?php echo $symbol.' '.$total_cost_shipp?></td>
						</tr>
						<tr>
							
							<td class="desc"></td>
							<td class="qty"></td>
							<td class="unit">GRAND TOTAL:</td>
							<td class="total"><?php echo $symbol.' '.$totalpayment?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<footer>
		<div class="container">			
			<div class="notice">
				<div>NOTE TO SELLER:</div>
				<div><?php echo $notes?></div>
			</div>
			<div class="thanks">Thank you!</div>
			<div class="end">Invoice was created on a computer and is valid without the signature and seal.</div>
		</div>
	</footer>