						<div class="col-md-9 col-sm-8 col-xs-12 main-content">        						
        						<div class="category-toolbar clearfix">
									<div class="toolbox-filter clearfix">									
										<div class="sort-box">
											<span class="separator">sort by:</span>
											<div class="btn-group select-dropdown">
												<button type="button" class="btn select-btn">Position</button>
												<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
												<?php if ($this->uri->segment(4)){$sortclass="sortbysearch";}else if ($this->uri->segment(2)){$sortclass="sortby";}?>												
												
													<li><a href="javascript:void(0)" class="<?php echo $sortclass?>" id="name-ASC" name="<?php echo $permalink?>">Name A - Z</a></li>
													<li><a href="javascript:void(0)" class="<?php echo $sortclass?>" id="name-DESC" name="<?php echo $permalink?>">Name Z - A</a></li>
													<li><a href="javascript:void(0)" class="<?php echo $sortclass?>" id="final_price-DESC" name="<?php echo $permalink?>"><?php echo lang('sortexp')?></a></li>
													<li><a href="javascript:void(0)" class="<?php echo $sortclass?>" id="final_price-ASC" name="<?php echo $permalink?>"><?php echo lang('sortcheap')?></a></li>
												</ul>
											</div>
										</div>										
										<div class="view-box">
											<a href="category.html" class="active icon-button icon-grid"><i class="fa fa-th-large"></i></a>
											<a href="category-list.html" class="icon-button icon-list"><i class="fa fa-th-list"></i></a>
										</div>
										
									</div>
									<div class="toolbox-pagination clearfix">
										<ul class="pagination">
											<?php echo $this->front_pagination->create_links()?>
										</ul>
										<div class="view-count-box">
											<span class="separator">view:</span>
											<div class="btn-group select-dropdown">
												<?php 
												$limit_default = replace_freetext($this->session->userdata('limit_default'));												
												$limit_select = $this->session->userdata('limit_select');
												$limit = (!$limit_select) ?  $limit_default : $limit_select;//jika tidak ada limit yg dipilih maka menggunakan limit default
												$limited = ($limit > $limit_default) ? 'All' : $limit_default;//jika limit yg dipilih > limit default
												?>
												<button type="button" class="btn select-btn"><?php echo $limited?></button>
												<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<?php 
													echo '<li><a href="'.base_url(FOMODULE.'/change_limit/'.$limit_default).'" class="limitview">'.$limit_default.'</a></li>';
													echo '<li><a href="'.base_url(FOMODULE.'/change_limit/'.count($rowsql)).'" class="limitview">All</a></li>';													
												?>
												</ul>
											</div>
										</div>									
									</div>       							        							
        						</div>
        						<div class="md-margin"></div>
        						<div class="category-item-container" id="produk">
        							<?php echo $product_category?>
        						</div>        						
        						<div class="pagination-container clearfix">
        							<div class="pull-right">
										<ul class="pagination">
											<?php echo $this->front_pagination->create_links()?>
										</ul>
        							</div>   							        							
        						</div>						      						
        					</div><!-- End .col-md-9 -->