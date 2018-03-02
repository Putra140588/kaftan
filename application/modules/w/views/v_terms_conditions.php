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
							<?php echo '<p>'.sprintf(lang('termtxt1'),'<b>'.$this->session->userdata('company_name').'</b>').'</p><p>'.lang('termtxt2').'</p><p>'.lang('termtxt3').'</p><p>'.lang('termtxt4').'</p><p>'.lang('termtxt5').'</p>'?>
						</header>						
        				<div class="tab-container clearfix">
                                    <ul id="products-tabs-list" class="tab-style-1 clearfix">
                                    	<?php foreach ($sql as $row){
                                    		$active = ($row->sort == 1) ? 'class="active"' : '';
                                    		echo '<li '.$active.'><a href="#'.$row->id_terms_conditions.'" data-toggle="tab">'.$row->name.'</a></li>';
                                    	}?>                                      
                                    </ul>
                                    <div id="products-tabs-content" class="tab-content">
                                    <?php foreach ($sql as $row){
                                    		$active = ($row->sort == 1) ? 'class="tab-pane active"' : 'class="tab-pane"';
                                    		echo '<div '.$active.' id="'.$row->id_terms_conditions.'">
		                                            <h3>'.$row->name.'</h3><p>'.$row->content.'</p>
		                                        </div>';
                                    	}?>                                                                                                               
                                        
                                    </div>
                        </div>                
        			</div>
        		</div>        		
			</div>       
        </section>
        
        