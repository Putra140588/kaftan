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
						<header class="content-title">
							<h1 class="title"><?php echo $tab_title?></h1>	
							<p><?php echo lang('privopen')?></p>							
						</header>						
        				<div class="tab-container clearfix">
                                    <ul id="products-tabs-list" class="tab-style-1 clearfix">
                                    	<?php foreach ($sql as $row){
                                    		$active = ($row->sort == 1) ? 'class="active"' : '';
                                    		echo '<li '.$active.'><a href="#'.$row->id_privacy_policy.'" data-toggle="tab">'.$row->name.'</a></li>';
                                    	}?>                                      
                                    </ul>

                                    <div id="products-tabs-content" class="tab-content">
                                    <?php foreach ($sql as $row){
                                    		$active = ($row->sort == 1) ? 'class="tab-pane active"' : 'class="tab-pane"';
                                    		echo '<div '.$active.' id="'.$row->id_privacy_policy.'">
		                                            <h3>'.$row->name.'</h3><p>'.$row->content.'</p>
		                                        </div>';
                                    	}?>                                                                                                               
                                        
                                    </div>
                        </div>                
        			</div>
        		</div>
        		
			</div>       
        </section>
        
        