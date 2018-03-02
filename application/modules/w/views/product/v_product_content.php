<section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active">Product</li>
						<li class="active"><?php echo $name?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">   
        			 <?php $this->load->view('v_alert_boxes')?>     				
        				<div class="row">        				
        					<?php $this->load->view('product/v_product_carosel')?>
        					<?php $this->load->view('product/v_product_attribute')?>
        				</div>        				
        				<div class="lg-margin2x"></div>        				
        				<div class="row">        		
        					<?php $this->load->view('product/v_product_description')?>			
        					<div class="lg-margin2x visible-sm visible-xs"></div>       
        					<?php //$this->load->view('product/v_product_rightbar')?> 					
        				</div>
        				<div class="lg-margin2x"></div>
        				<?php $this->load->view('product/v_product_related')?>
        			</div>
        		</div>
			</div>        
        </section>
        <!-- widget left fb,twitter,print,mail -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52b2197865ea0183"></script>