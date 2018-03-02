					<div id="brand-slider-container" class="carousel-wrapper">
        					<header class="content-title">
								<div class="title-bg">
									<h2 class="title">Brands</h2>
								</div>
							</header>
                                <div class="carousel-controls">
                                    <div id="brand-slider-prev" class="carousel-btn carousel-btn-prev">
                                    </div>
                                    <div id="brand-slider-next" class="carousel-btn carousel-btn-next carousel-space">
                                    </div>
                                </div>
                                <div class="sm-margin"></div>
                                <div class="row">
                                    <div class="brand-slider owl-carousel">
                                    	<?php foreach ($brands as $row){
                                    		echo '<a href="#"><img src="'.base_url('assets/images/manufacture/'.image($row->image)).'" alt="'.$row->name.'"></a>';
                                    	}?>                                        
                                    </div>
                                </div>
        				</div>
        				<div class="md-margin"></div>