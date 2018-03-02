<div class="col-md-3 col-sm-4 col-xs-12 sidebar">
        						<div class="widget">
        							<div class="panel-group custom-accordion sm-accordion" id="category-filter">
										<div class="panel">
											<div class="accordion-header">
												<div class="accordion-title"><span><?php echo lang('category')?></span></div>
											<a class="accordion-btn opened"  data-toggle="collapse" data-target="#category-list-1"></a>
											</div>
								
										<div id="category-list-1" class="collapse in">
											<div class="panel-body">
												<ul class="category-filter-list">
												<?php 
												$parent = $this->m_client->get_menu_header(array('id_parent'=>0,'id_level'=>0));
												foreach ($parent as $row){
													$permalink0 = base_url(FOMODULE.'/'.$row->url.'-1.0.html');
													echo '<li><a href="'.$permalink0.'"><b>'.strtoupper($row->name_category).'</b></a>
																<ul>';
													$level1 = $this->m_client->get_menu_header(array('id_parent'=>$row->id_category,'id_level'=>1));
													foreach ($level1 as $lv1){
														$permalink1 = base_url(FOMODULE.'/'.$lv1->url.'-1.1.html');
														echo '<li><a href="'.$permalink1.'"><i class="fa fa-chevron-circle-right"></i> '.strtoupper($lv1->name_category).'</a>
																  <ul>';
														$level2 = $this->m_client->get_menu_header(array('id_parent'=>$lv1->id_category,'id_level'=>2));
														foreach ($level2 as $lv2){
															$permalink2 = base_url(FOMODULE.'/'.$lv2->url.'-1.2.html');
															echo '<li style="margin-left:20px"><a href="'.$permalink2.'">'.strtoupper($lv2->name_category).'</a></li>';
														}
														echo '</ul></li>';
													}
																														
													echo '</ul></li>';
												}
												?>													
													
												</ul>
											</div>
										</div>
										</div>
        								
        								<div class="panel">
											<div class="accordion-header">
												<div class="accordion-title"><span>TOP BRANDS</span></div>
											<a class="accordion-btn opened"  data-toggle="collapse" data-target="#brands"></a>
											</div>								
										<div id="brands" class="collapse in">
											<div class="panel-body">
											<ul class="category-filter-list">
												<?php 
												$brand = $this->m_client->get_category_brand();
												foreach ($brand as $v){
													$permalink = base_url(FOMODULE.'/'.$v->url.'-2.html');
													echo '<li><a href="'.$permalink.'">'.strtoupper($v->name).'</a></li>';
												}
												?>											
												
											</ul>
											</div><!-- End .panel-body -->
										</div><!-- #collapse -->
										</div><!-- End .panel -->        												
        							</div><!-- .panel-group -->
        						</div><!-- End .widget -->
        						
        							
        						<div class="widget banner-slider-container">
        							<div class="banner-slider flexslider">
        								<ul class="banner-slider-list clearfix">
        								<?php 
        								$sql = $this->m_client->get_banner(array('id_banner_position'=>1));
        								foreach ($sql as $row){
											echo '<li><a href="#"><img src="'.base_url().'assets/images/banner/'.$row->image_banner.'" alt="'.$row->image_banner.'" class="img-responsive"></a></li>';
										}
        								?>
        									
        								</ul>
        							</div>
        						</div><!-- End .widget -->        						
        					</div><!-- End .col-md-3 -->