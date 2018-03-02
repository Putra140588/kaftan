        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active"><?php echo lang('help')?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h1 class="title"><?php echo lang('help')?></h1>	
							<p><?php echo lang('helptxt')?></p>												
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div>
						<?php $this->load->view('help/v_tab_help')?>
						<div class="xs-margin"></div>
                      	<?php $this->load->view('help/v_help_detail')?>                 
        			</div>
        		</div>
        		
			</div>       
        </section>
        
        