	<div class="profile-user-info">		
		<div class="profile-info-row">
			<div class="profile-info-name"> ID Order </div>
				<div class="profile-info-value">		
					<span class="label label-large label-inverse arrowed-in arrowed-in-right">#<?php echo $id_order?></span>			     			   					
				</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Status </div>
				<div class="profile-info-value">		
				<span class="<?php echo $label_color?>"><?php echo $name_status?></span>				     			   					
				</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Payment </div>
				<div class="profile-info-value">		
				<?php echo '<span class="'.pay_status($payment_result).'">'.$payment_result.'</span>';?>					     			   					
				</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Order Date </div>
			<div class="profile-info-value">
				<span><?php echo long_date_time($date_add_order)?></span>
			</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Shipp Via </div>
			<div class="profile-info-value">
				<span><?php echo $name_courier?></span>
			</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Pay Methods </div>
			<div class="profile-info-value">
				<span><?php echo $method_name?></span>							
			</div>
		</div>
		<div class="profile-info-row">
			<div class="profile-info-name"> Currency </div>
			<div class="profile-info-value">
				<span><?php echo $iso_code?></span>							
			</div>
		</div>
	</div>