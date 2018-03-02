<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="smaller">Delivery Address</h4>
			</div>
			<div class="widget-body">
				<div class="widget-main">					
					<p class="muted">
					<?php echo 
						'<b>'.$name_received.'</b><br>
						  '.$address.'<br>';
						if ($country_code == 'ID'){
							echo $districts_name.', '.$cities_name.' '.$postcode.'<br>
						  		'.$province_name.','.$country_name.'<br>Phone : '.$phone_addr;
						}else{
							echo $postcode.', '.$country_name.'<br>';
						}
						echo 'Phone : '.$phone_addr;?>
					</p>
				</div>				
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-6">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="smaller">Billing Address</h4>
			</div>
			<div class="widget-body">
				<div class="widget-main">					
					<p class="muted">
					<?php echo 
						'<b>'.$name_received.'</b><br>
						  '.$address.'<br>';
						if ($country_code == 'ID'){
							echo $districts_name.', '.$cities_name.' '.$postcode.'<br>
						  		'.$province_name.','.$country_name.'<br>Phone : '.$phone_addr;
						}else{
							echo $postcode.', '.$country_name.'<br>';
						}
						echo 'Phone : '.$phone_addr;?>
					</p>
				</div>				
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="smaller">Shipping Information</h4>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<div class="profile-user-info">		
						<div class="profile-info-row">
						<div class="profile-info-name"> Courier </div>
							<div class="profile-info-value">							
								<?php echo $name_courier?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name"> URL Tracking </div>
							<div class="profile-info-value">							
								<?php echo ($tracking_url == '') ? '...' : $tracking_url?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">Delay </div>
							<div class="profile-info-value">							
								<?php echo ($delay == '') ? '...' : $delay?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">Total Weight </div>
							<div class="profile-info-value">							
								<?php echo $total_weight?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">Total Volume </div>
							<div class="profile-info-value">							
								<?php echo $total_volume?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">Shipping Cost </div>
							<div class="profile-info-value">							
								<?php 
								$total_cost_shipp = ($iso_code == 'IDR') ? formatnum($total_cost_shipping) : $total_cost_shipping;
								echo $symbol.' '.$total_cost_shipp?>
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">No Resi </div>
							<div class="profile-info-value">							
								<span id="shippnumber"><?php echo ($awb_delivery == '') ? '...' :  $awb_delivery?></span> <a data-toggle="modal" href="#myModal" role="button" class="btn btn-mini btn-warning" title="Edit AWB" id="<?php echo $id_order?>" onclick="modalShow('<?php echo base_url('superadmin/admorders/modal/shipp')?>',this.id,'myModal')"><i class="fa fa-pencil-square-o"></i></a>	  		
							</div>
						</div>
						<div class="profile-info-row">
						<div class="profile-info-name">Delivery Slip </div>
							<div class="profile-info-value">							
								#<?php echo $id_order_delivery?> <a class="btn btn-mini btn-info" href="<?php echo base_url(MODULE.'/letter/orderslip/deliv/'.$id_order)?>" target="_blank" title="Generate Delivery Slip"><i class="fa fa-road"></i></a>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-6">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="smaller">Payment Information</h4>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<div class="profile-user-info">								
						<div class="profile-info-row">
							<div class="profile-info-name"> Beneficiary Name</div>
							<div class="profile-info-value">
								<span><?php echo $name_owner?></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> Beneficiary Bank</div>
							<div class="profile-info-value">
								<span><?php echo $method_name?></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> Branch</div>
							<div class="profile-info-value">
								<span><?php echo replace_p($address_pay)?></span>
							</div>
						</div>	
						<div class="profile-info-row">
							<div class="profile-info-name"> Account No</div>
							<div class="profile-info-value">		
								<span><?php echo $content?></span>
							</div>
						</div>						
						<div class="profile-info-row">
							<div class="profile-info-name"> Total</div>
							<div class="profile-info-value">		
								<span><?php echo $symbol.' '.$totalpayment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;?></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> Paid</div>
							<div class="profile-info-value">		
								<span><?php echo $symbol.' '.$total_amount_pay = ($iso_code == 'IDR') ? formatnum($total_amount_pay) : $total_amount_pay;?></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> Balanced</div>
							<div class="profile-info-value">		
								<span><?php echo $symbol.' '.$total_balance = ($iso_code == 'IDR') ? formatnum($total_balance) : $total_balance;?></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> Invoice Slip</div>
							<div class="profile-info-value">		
								<span>#<?php echo $id_order?></span> <a class="btn btn-mini btn-info" href="<?php echo base_url(MODULE.'/letter/orderslip/inv/'.$id_order)?>" target="_blank" title="Generate Invoice"><i class="fa fa-file"></i></a>
							</div>
						</div>
					</div>		
				</div>
			</div>
		</div>
	</div>
</div>