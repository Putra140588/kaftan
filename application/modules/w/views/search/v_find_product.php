        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>
						<li class="active"><?php echo $tab_title?></li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<div class="row">
        					<?php $this->load->view('category/v_category_grid')?>
        					<?php $this->load->view('category/v_category_right_panel')?>      					
        				</div>  
        			</div>
        		</div>        		
			</div>       
        </section>
        
        