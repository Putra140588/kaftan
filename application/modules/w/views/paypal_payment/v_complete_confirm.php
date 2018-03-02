        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active"><?php echo lang('tf6')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12" id="steporder">
						<header class="content-title">
						<img src="<?php echo base_url('assets/images/logo/headlogo.png')?>" alt="headerlogo">
							<h1 class="title"><?php echo lang('tf6')?></h1>											
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div><!-- space -->
						<form action="<?php echo base_url(FOMODULE.'/checkout/confirm_order')?>#steporder" id="form-ajax">
						<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
	        				<?php echo $this->m_public->review_order_paypal()?>	
	        				<div class="md-margin"></div>
			        		<?php echo $this->load->view('v_alert_boxes')?>				        						
			        		<input type="submit" id="btn-submit" class="btn btn-custom-2" value="<?php echo lang('confirmpayment')?>">									
        				</form>
        			</div>
        		</div>
			</div>       
        </section>
        
        