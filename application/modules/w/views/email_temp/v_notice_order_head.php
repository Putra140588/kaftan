<?php if ($pay_code == 8801){
	//payment method with transfer bank
	echo '<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">
			'.lang('dopay').'
		</p>';
}elseif ($pay_code == 8809){
	//paypal payment method
	echo '<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">
			'.lang('shipproses').'
		</p>';
}?>