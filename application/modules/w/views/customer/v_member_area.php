        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active">Member Area</li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<header class="content-title">
							<h1 class="title">Member Area</h1>													
						</header>
						<?php $this->load->view('v_alert_boxes')?>
        				<div class="xs-margin"></div>
						<?php $this->load->view('customer/'.$page)?>
        			</div>
        		</div>
        		<div class="lg-margin"></div>
			</div>       
        </section>
        
        