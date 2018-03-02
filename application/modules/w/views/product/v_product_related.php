				<div class="latest-items carousel-wrapper">
                       <header class="content-title">
                           <div class="title-bg">
                                <h2 class="title"><?php echo $this->lang->line('related')?></h2>
                           </div>                           
                       </header>
                       <div class="carousel-controls">
                            <div id="latest-items-slider-prev" class="carousel-btn carousel-btn-prev"></div>                                
                            <div id="latest-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>                                
                       </div>
                       <div class="latest-items-slider owl-carousel">
                           <?php echo $related?>
                       </div>
                </div>