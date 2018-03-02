						<header class="content-title">
							<h1 class="title"><?php echo lang('checkout')?></h1>
							<p class="title-desc"><?php echo lang('steporder')?></p>
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div>
        				<form action="<?php echo base_url(FOMODULE.'/checkout/confirm#steporder')?>" id="form-ajax">
        					<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
	        				<div class="panel-group custom-accordion" id="checkout">
	        					<?php $this->load->view('checkout/v_cart')?>
								<?php $this->load->view('checkout/v_address_delivery')?>							  
								<?php $this->load->view('checkout/v_delivery_method')?>	
								<?php $this->load->view('checkout/v_payment_method')?>	
								<?php $this->load->view('checkout/v_confirm_order')?>																	  
	        				</div>
        				</form>
        				<div class="xlg-margin"></div>