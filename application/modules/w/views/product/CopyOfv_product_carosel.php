<?php
$link_img = base_url().'assets/images/product/'.image($image_name);
$badge='';
if ($top_seller == 1){
	$badge = badge('best');
}else if ($featured_product == 1){
	$badge = badge('best');
}else if ($promo == 1){
	$badge = badge('promo');
}else if ($new_product == 1){
	$badge = badge('new');
}

?>
					
					
					<div class="col-md-6 col-sm-12 col-xs-12 product-viewer clearfix">
        					<div id="product-image-carousel-container">
        						<ul id="product-carousel" class="celastislide-list">
        							<?php foreach ($image as $row){
        								$active = ($row->sort == 1) ? 'class="active-slide"' : '';
        								$link = base_url().'assets/images/product/'.image($row->image_name);
        								echo '<li '.$active.'><a data-rel="prettyPhoto[product]" href="'.$link.'" data-image="'.$link.'" data-zoom-image="'.$link.'" class="product-gallery-item"><img src="'.$link.'" alt="'.$row->image_name.'"></a></li>';
        							}?>	        						
        						</ul>
        					</div>
        					<div id="product-image-container">
        						<?php     
        						echo $badge;
        							echo '<figure><img src="'.$link_img.'" data-zoom-image="'.$link_img.'" alt="'.$image_name.'" id="product-image">';
		        				if ($disc > 0){
									echo '<figcaption class="item-price-container">
												<span class="item-price id="disc">'.number_format($disc,0).'% OFF</span>
										  </figcaption>';
								}else if ($sp > 0 && $disc < 1){
									echo '<figcaption class="item-price-container">
												 <span class="item-price id="promo">PROMO</span>
										  </figcaption>';
								}
		        				echo '</figure>';
        						?>        						
        					</div>       				 
        				</div>