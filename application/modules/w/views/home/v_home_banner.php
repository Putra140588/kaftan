						<div class="row home-banners">
							<?php 
							/*
							 * top banner
							 */							
							$sql = $this->m_client->get_banner(array('id_banner_position'=>1));
							foreach ($sql as $row){
								echo '<div class="col-md-4 col-sm-4 col-xs-12">
                                			<a href="#"><img src="'.base_url().'assets/images/banner/'.$row->image_banner.'" alt="'.$row->image_banner.'" class="img-responsive"></a>
                            		 </div>';
							}
							?>                           
                        </div><!-- End .home-banners -->