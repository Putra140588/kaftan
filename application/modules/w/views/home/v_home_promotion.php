<div id="testimonials-section" class="home-testimonials-section">
                        <div class="container">
                            <div class="row">
                            <div class="col-md-12">                  
                                <h3>
                                    <?php echo lang('promosi')?>
                                    <span class="small-bottom-border big"></span>
                                </h3>
                            <div class="about-us-testimonials flexslider">
                                <ul class="slides">
	                                <?php foreach ($promotion as $row){
	                                	echo '<li>
		                                        <span class="testimonial-title">'.$row->title_promo.'</span>
		                                        <p>'.$row->description.'</p>		                                       
	                                   	     </li>';
	                                }?>                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>             
            </div>