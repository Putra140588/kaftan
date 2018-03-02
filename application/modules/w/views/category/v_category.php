<section id="content">
        	<?php $this->load->view('category/v_category_header')?>
        	<div id="category-breadcrumb">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url()?>">Home</a></li>	
						<?php if (!empty($name_parent)){
							echo '<li><a href="<?php echo base_url()?>">'.$name_parent.'</a></li>';
						}?>										
						<li class="active"><?php echo $name_page?></li>
					</ul>
        		</div>
        </div>
        <?php $this->load->view('category/v_category_content')?>    
</section>