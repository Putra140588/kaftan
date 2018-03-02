<main>
<div class="title-letter"><?php echo $title_letter?></div>
<div class="invoice-date">Date : <?php echo date('d M Y',strtotime($_SESSION['date']))?></div>
	 <div id="details" class="clearfix">
        <div id="client">
          <div class="to">CUSTOMER</div>
          <?php 
			//get address billing			
			 foreach ($get_delivery_address->result() as $row){
				echo '<h2 class="name">'.$row->first_name_ord.' '.$row->last_name_ord.'</h2>
					 '.$row->company.'<br>
			    	 '.$row->address.'<br>
					 '.$row->postcode.' '.$row->city_name.'<br>
					 '.$row->province_name.'<br>
					 '.$row->home_phone.' / '.$row->mobile_phone;	 
			}?>          
        </div>
        <div id="invoice">
          <h1> No: #<?php echo $id_order_delivery;?></h1>
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
          <div class="va">Virtual Account : <?php echo $virtual_account;?></div> 
          <?php }?>
        </div>
     </div><!-- END header details -->
     
     <!-- description content -->
     <table>	
		<thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPTION</th>
            <th class="unit">SERIAL NUMBER</th>
            <th class="qty">QTY</th>            
          </tr>
        </thead>
        <tbody>
	<?php $no=1;foreach ($order_detail->result() as $row){
		$serial_number = $this->model_content_dua->show_serialnumber($row->id_order_detail);?>
		<tr><td class="no"><?php echo $no++?></td>
			<td class="desc"><?php echo $row->product_name.'<br>'.$row->name_attribute;?></td>
			<td class="unit"><?php foreach ($serial_number as $val)
				{
					echo $val->serial_number.'; ';
				}?>
		   </td>
			<td class="qty"><?php echo $row->product_qty;?></td>			
		</tr>		
		<?php }?>
		<tfoot>         
          <tr>
            <td colspan="2"></td>
            <td colspan="1">TOTAL QTY</td>
            <td><?php echo $total_qty?></td>
          </tr>
        </tfoot>		
	</tbody>
	</table>	
	<div id="notices">
        <div>NOTICE:</div>
        <div class="notice">Delivery slip was created on a computer and is valid without the signature and seal.</div>
	</div>
</main>



	



		

