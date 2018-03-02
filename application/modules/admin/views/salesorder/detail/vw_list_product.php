<table id="sample-table-1" class="table table-striped table-bordered table-hover">
	<thead>
		<tr><th>#</th>
			<th colspan=2 class="center">Product</th>
			<th>Base Price</th>
			<th>Tax</th>
			<th>Final Price</th>			
			<th>Event</th>
			<th>Impact Price</th>
			<th>Quantity</th>
			<th>Unit Price</th>
			<th>Sub Total</th>														
			<th colspan=2 class="center">Status</th>			
		</tr>
	</thead>
	<tbody>
	<?php 
		$no=1;
		foreach ($detail as $row){
			$sp_price = ($row->iso_code == 'IDR') ? number_format($row->sp_price,0,'.','.') : $row->sp_price;
			$base_price = ($row->iso_code == 'IDR') ? number_format($row->base_price,0,'.','.') : $row->base_price;
			$impact_price = ($row->iso_code == 'IDR') ? number_format($row->impact_price,0,'.','.') : $row->impact_price;
			$final_price = ($row->iso_code == 'IDR') ? number_format($row->final_price,0,'.','.') : $row->final_price;
			$unit_price = ($row->iso_code == 'IDR') ? number_format($row->unit_price,0,'.','.') : $row->unit_price;
			$subtotal_price = ($row->iso_code == 'IDR') ? number_format($row->total_price,0,'.','.') : $row->total_price;
			$cancel = ($row->active == 0) ? '<span class="label label-important arrowed-in-right arrowed">Canceled</label>':'<span class="label label-success arrowed-in-right arrowed">Active</span>';
			$indent = ($row->preorder == 0) ? '<span class="label label-info arrowed-in-right arrowed">Ready</label>':'<span class="label label-warning arrowed-in-right arrowed">Indent</span>';
			
			if ($row->sp_price > 0 && $row->disc_value > 0){
				$ev = 'PROMO <br>'.$row->symbol.' '.$sp_price.'<br> Discount '.$row->disc_value.'%';
			}else if ($row->sp_price > 0 && $row->disc_value < 1){
				$ev = 'PROMO <br>'.$row->symbol.' '.$sp_price;
			}else{
				$ev = 'N/A';
			}
			if (!empty($row->name_group)){
				$product = $row->name.'<br><span class="label label-yellow arrowed-in arrowed-in-right">'.$row->name_group.': '.$row->name_attribute.'</span>';
			}else{$product = $row->name;}
			echo '<tr><td>'.$no++.'</td>				 
					  <td><img src="'.base_url().'assets/images/product/'.image($row->image_name).'" alt="'.$row->name.'" width=80px; height=100px></td>									  
					  <td>'.$product.'</td>								  
					  <td class="center">'.$row->symbol.' '.$base_price.'</td>
					  <td class="center">'.$row->name_tax.'</td>
					  <td class="center">'.$row->symbol.' '.$final_price.'</td>				  
					  <td class="center">'.$ev.'</td>
					  <td class="center">'.$row->symbol.' '.$impact_price.'</td>
					  <td class="center">'.$row->product_qty.'</td>
					  <td class="center">'.$row->symbol.' '.$unit_price.'</td>
			          <td class="center">'.$row->symbol.' '.$subtotal_price.'</td>';				  
				echo  '<td class="center">'.$cancel.'</td>';
				$impl = implode(",", array('id_order'=>$row->id_order,'id_product'=>$row->id_product,'id_product_attribute'=>$row->id_product_attribute));
				$btn_adjust = ($row->preorder == 1) ? '<a class="btn btn-mini btn-info" data-toggle="modal" role="button" href="#modal-form" title="Adjust Qty Indent" onclick="ajaxModal(\''.base_url(MODULE.'/salesorder/adjustindent').'\',\''.$impl.'\',\'modal-form\')"><i class="ace-icon fa fa-external-link"></i></a>' : '';
				echo  '<td class="center">'.$indent.'<br><br>
					  		'.$btn_adjust.'
					   </td>';
			echo '</tr>';
			$x = $this->m_admin->get_qty_indent(array('id_order'=>$row->id_order,'id_product'=>$row->id_product,'id_product_attribute'=>$row->id_product_attribute));
			
			$qty_booking = isset($x[0]->qty_available_last) ? $x[0]->qty_available_last : $row->product_qty;
			$qty_indent = isset($x[0]->qty_minus) ? $x[0]->qty_minus : 0;
			$where = array('A.id_product'=>$row->id_product,'A.id_product_attribute'=>$row->id_product_attribute,'B.id_branch'=>$row->id_branch);
			$stockready = $this->m_admin->get_qty_available_attribute($where);
			echo '<tr><td colspan=5</td>
					  <td class="center">Qty Indent:</td>
					  <td class="center">'.$qty_indent.'</td>
					  <td class="center">Qty Booking:</td>
					  <td class="center">'.$qty_booking.'</td>
					  <td class="center">last Stock:</td>
					  <td class="center">'.$row->stock_last.'</td>
					  <td class="center">Current Stock:</td>
					  <td class="center">'.$stockready.'</td>
				 </tr>';
		}
		$totalpayment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;
		$total_cost_shipp = ($iso_code == 'IDR') ? formatnum($total_cost_shipping) : $total_cost_shipping;
		$total_product = ($iso_code == 'IDR') ? formatnum($total_product) : $total_product;
		$amount_tax = ($iso_code == 'IDR') ? formatnum($amount_tax) : $amount_tax;
		$total_amount_pay = ($iso_code == 'IDR') ? formatnum($total_amount_pay) : $total_amount_pay;
		$total_balance = ($iso_code == 'IDR') ? formatnum($total_balance) : $total_balance;
		$notesss = ($notes != "") ? '<p class="muted"><b>Notes :</b> <br>'.$notes.'</p>' : 'N/A';
		echo '<tr><td colspan=8 class="center">Total Qty:</td>
				  <td class="center">'.$total_qty.'</td>				  
				  <td colspan=2 class="center">Total Products</td>
				  <td colspan=2 class="center">'.$symbol.' '.$total_product.'</td>
			 </tr>';
		echo '<tr>
				<td colspan=9 rowspan=5 class="center">Notes for seller:<br>'.$notesss.'</td>
				<td colspan=2 class="center">Total Tax '.$rate_tax.'%</td>
				<td colspan=2 class="center">'.$symbol.' '.$amount_tax.'</td>
			</tr>';
		echo '<tr>
				  <td colspan=2 class="center">Shipping Cost</td>
				  <td colspan=2 class="center" align="right" >'.$symbol.' '.$total_cost_shipp.'</td>
			 </tr>';
		echo '<tr>
				  <td colspan=2 class="center"><h4 class="red">TOTAL</h4></td>
				  <td colspan=2 class="center"><h4 class="red">'.$symbol.' '.$totalpayment.'</h4></td>
			  </tr>';
		echo '<tr>
				  <td colspan=2 class="center"><h4 class="red">PAID</h4></td>
				  <td colspan=2 class="center"><h4 class="red">'.$symbol.' '.$total_amount_pay.'</h4></td>
			  </tr>';
		echo '<tr>
				  <td colspan=2 class="center"><h4 class="red">BALANCED</h4></td>
				  <td colspan=2 class="center"><h4 class="red">'.$symbol.' '.$total_balance.'</h4></td>
			  </tr>';
		$total_terbilang = ($iso_code == 'IDR') ? ucwords(num_to_words($total_payment, 'rupiah', 0, '')) : ucwords(num_to_words($total_payment, 'dolar', 2, 'sen'));
		echo '<tr><td colspan=13 class="center"> <h5>Spelled Out : <b>'.$total_terbilang.'</b></h5></td></tr>';
	?>
	</tbody>
</table>