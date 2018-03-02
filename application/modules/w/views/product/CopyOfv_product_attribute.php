	<form action="#" class="form-horizontal" method="post" action="<?php echo base_url(FOMODULE.'/account/register')?>">
		<input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">		
				<div class="col-md-6 col-sm-12 col-xs-12 product">
                        <div class="lg-margin visible-sm visible-xs"></div><!-- Space -->
        					<h1 class="product-name"><?php echo strtoupper($name_manufacture)?></h1>
        					<h4><?php echo $name?></h4>
        					<?php 
        					if ($sp > 0 && $disc > 0){
        						/*
        						 * jika ada harga sp dan ada discon sp
        						*/
        						 
        						echo '<div class="item-price-container inline">
	                                             	<span class="old-price">'.$symbol.' '.$this->m_public->convert_price($sp).'</span>
	                                        	</div>';
        					}else if ($sp > 0){
        						/*
        						 * jika hanya ada harga sp
        						*/
        						 
        						echo '<div class="item-price-container inline">
	                                        <span class="old-price">'.$symbol.' '.$this->m_public->convert_price($final_price).'</span>
	                                  </div>';
        						 
        					}
        					?>
        					 <div class="item-price-container inline">
	                               <span class="item-price" id="total_price">
	                                       <?php echo $symbol.' '.$this->m_public->convert_price($total_price)?>
	                               </span>
	                         </div>
	                         <p>
	                         <?php echo $product_information?>
	                         </p>        					
        				<ul class="product-list">
        					<li><span>Availability:</span>In Stock</li>
        					<li><span>Product Code:</span>483512569</li>
        					<li><span>Brand:</span>Apple</li>
        				</ul>
        			   <?php if ($id_attribute_group_default != ''){?>                       
                       <div class="product-size-filter-container">
                      		 <hr> 
                            <span><?php echo lang('size')?></span>
                            <div class="xs-margin"></div>
                           <div class="row">
                           		<div class="col-md-3 col-sm-3 col-xs-12">
                           			<div class="input-group">
                           				<select name="attrgroup" class="form-control">                             					                      					
                           					<?php 
                           					$this->db->group_by('A.id_attribute_group');
                           					$sql1 = $this->m_client->get_product_attribute(array('A.id_product'=>$id_product));
                           					foreach ($sql1 as $row){
                           						$select = ($row->id_attribute_group == $id_attribute_group_default) ? 'selected' : '';
                           						echo '<option value="'.$row->id_attribute_group.'" '.$select.'>'.$row->name_group.'</option>';
                           					}?>
                           				</select>
                           			</div>
                           		</div>
                           		<div class="col-md-4 col-sm-4 col-xs-12">
                           			<div class="input-group">
                           				<select name="selectattr" class="form-control" id="selectattr">
                           					<?php                         					                        				
                           					$sql = $this->m_client->get_product_attribute(array('A.id_product'=>$id_product,'A.id_attribute_group'=>$id_attribute_group_default));
                           					foreach ($sql as $v){
												$select = ($v->id_attribute == $id_attribute_default) ? 'selected' : '';
												echo '<option value="'.$v->id_product_attribute.'" '.$select.'>'.$v->name.'</option>';
											}?>
                           				</select>
                           			</div>
                           		</div>
                           </div>
                        </div>
                        <?php }?>
                        <hr>
							<div class="product-add clearfix">
								<div class="custom-quantity-input">
									<input type="text" name="quantity" value="1">
									<a href="#" onclick="return false;" class="quantity-btn quantity-input-up"><i class="fa fa-angle-up"></i></a>
									<a href="#" onclick="return false;" class="quantity-btn quantity-input-down"><i class="fa fa-angle-down"></i></a>
								</div>
								<button class="btn btn-custom-2">ADD TO CART</button>
							</div><!-- .product-add -->
        					<div class="md-margin"></div><!-- Space -->
        					<div class="product-extra clearfix">
								<div class="product-extra-box-container clearfix">
									<div class="item-action-inner">
										<a href="#" class="icon-button icon-like">Favourite</a>
										<a href="#" class="icon-button icon-compare">Checkout</a>
									</div><!-- End .item-action-inner -->
								</div>
								<div class="md-margin visible-xs"></div>
								<div class="share-button-group">
									<!-- AddThis Button BEGIN -->
									<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
									<a class="addthis_button_facebook"></a>
									<a class="addthis_button_twitter"></a>
									<a class="addthis_button_email"></a>
									<a class="addthis_button_print"></a>
									<a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
									</div>							
																
								</div>
        					</div>
        				</div>
     </form>