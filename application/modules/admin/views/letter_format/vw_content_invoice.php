<div class="title-letter"><?php echo $title_letter?></div>
<div class="invoice-date">Date : <?php echo date('d M Y',strtotime($_SESSION['date']))?></div>
	 <div id="details" class="clearfix">
        <div id="client">
          <div class="to">CUSTOMER</div>
          <?php 
			//get address billing			
			 foreach ($get_billing_address->result() as $row){
				echo '<h2 class="name">'.$row->first_name_ord.' '.$row->last_name_ord.'</h2>
					 '.$row->company.'<br>
			    	 '.$row->address.'<br>
					 '.$row->postcode.' '.$row->city_name.'<br>
					 '.$row->province_name.'<br>
					 '.$row->home_phone.' / '.$row->mobile_phone;	 
			}?>          
        </div>
        <div id="invoice">
          <h1>Ref No : <?php echo $reference;?></h1>
          <?php if ($payment_transfer == 'UnPaid'){?>
     	<div class="status-unpaid">
     		<?php echo $payment_transfer?>
     	</div>
	     <?php }elseif($payment_transfer == 'Matched'){?>
	     <div class="status-paid">
     		<?php echo $payment_transfer?>
     	</div>
	     <?php }?>
          <div class="date">Order Date : <?php echo date("d M Y",strtotime($date_add_order));?></div>  
         <?php $shipping = ($id_carrier != 0) ? $name_carrier : $name_carrier_other;
         if ($shipping != ''){?>
         <div class="date">Ship Via : <?php echo $shipping;?></div>   
         <?php }?>             
          <div class="date">Pay Methods : <?php echo $name_bank;?></div>          
          <?php if ($virtual_account != ''){?>
          <div class="date">Virtual Account : <?php echo $virtual_account;?></div> 
          <?php }?>
        </div>
     </div><!-- END header details -->     
     <!-- description content -->
     <table>	
		<thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="qty">QTY</th>
            <th class="total">AMOUNT</th>
          </tr>
        </thead>
        <tbody>
	<?php $no=1;foreach ($order_detail->result() as $row){?>
		<tr><td class="no"><?php echo $no++?></td>
			<td class="desc"><?php echo $row->product_name.'<br>'.$row->name_attribute;?></td>
			<td class="unit"><?php echo $row->iso_code.' '.number_format($row->unit_price_reduc_incl,$_SESSION['format_price']);?></td>
			<td class="qty"><?php echo $row->product_qty;?></td>
			<td class="total"><?php echo $row->iso_code.' '.number_format($row->subtotal_price,$_SESSION['format_price']);?></td>
		</tr>
		
		<?php }?>
		<?php if ($voucher_type == 'am'){
			$voucher ='<tr><td colspan=2></td>
							<td Colspan=2>Disc</td>
							<td>- '.$iso_code.' '.number_format($voucher_reduction,$_SESSION['format_price']).'</td>
						</tr>';
			$total_product_incl_vcr = '<tr><td colspan=2></td>
											<td colspan=2>Total Amount</td>
											<td>'.$iso_code.' '.number_format($total_product_incl_vcr,$_SESSION['format_price']).'</td>
								     	</tr>';
		}else if ($voucher_type == 'disc'){		
			$voucher ='<tr><td colspan=2></td>
							<td colspan=2>Disc</td>
							<td>- '.$iso_code.' '.number_format($voucher_reduction,$_SESSION['format_price']).' %</td>
						</tr>';
			$total_product_incl_vcr = '<tr><td colspan=2></td>
											<td colspan=2>Total Amount</td>
											<td>'.$iso_code.' '.number_format($total_product_incl_vcr,$_SESSION['format_price']).'</td>
									  </tr>';	
		}else{
			$voucher='';
			$total_product_incl_vcr='';
		} 
		
		$shipprice = ($id_carrier != 0) ? number_format($total_shipping,$_SESSION['format_price']) : number_format($price_shipping_other,$_SESSION['format_price']);?>
		<tfoot>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">Sub-Total</td>
            <td><?php echo $iso_code.' '.number_format($total_product,$_SESSION['format_price'])?></td>
          </tr>
          <?php echo $voucher; ?>
		  <?php echo $total_product_incl_vcr;?>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TAX <?php echo $rate_tax?>%</td>
            <td><?php echo $iso_code.' '.number_format($amount_tax);?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">Shipp Cost</td>
            <td><?php echo $iso_code.' '.$shipprice;?></td>
          </tr>
          <?php if ($methodpay_code == '01')//credit card pay
			{
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="2">Credit Card '.$cc_precentase.'%</td>
							<td>'.$iso_code.' '.number_format($cc_amount).'</td>
						</tr>';
			}?>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td><?php echo $iso_code.' '.number_format($total_paid,$_SESSION['format_price'])?></td>
          </tr>
        </tfoot>		
	</tbody>
	</table>	
	<?php $this->load->view('letter_format/vw_notice')?>




	



		

