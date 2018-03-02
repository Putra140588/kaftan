		<section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active"><?php echo lang('checkout')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12" id="steporder">
						<?php //$this->load->view('checkout/v_finish_order')
							 $this->load->view('checkout/v_step_order')?>
        			</div>
        		</div>
			</div>      
        </section>