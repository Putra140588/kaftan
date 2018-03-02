				<div id="main-nav-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 clearfix">   
                            <?php
							//melakukan cek apakah default group sudah di setting
                           $group = $this->m_client->get_table('tb_group',array('id_group','name_group'),array('default'=>1));
                           if (count($group) > 0){                             
                             echo '<nav id="main-nav">
                                    <div id="responsive-nav">
                                        <div id="responsive-nav-button">
                                            '.lang('category').'<span id="responsive-nav-button-icon"></span>
                                        </div>
                                    </div>                                                                        	
									<ul class="menu clearfix">';
                            			$parent = $this->m_client->get_menu_header(array('id_parent'=>0,'id_level'=>0));
                            			foreach ($parent as $row){
                            				$permalink0 = base_url(FOMODULE.'/'.$row->url.'-1.0.html');
                            				//menu parent show
                            				echo '<li class="mega-menu-container"><a href="'.$permalink0.'">'.strtoupper($row->name_category).'</a>';                            				 		
                            					$level1 = $this->m_client->get_menu_header(array('id_parent'=>$row->id_category,'id_level'=>1));
                            					if (count($level1) > 0){
                            						echo '<div class="mega-menu clearfix">';
	                            					foreach ($level1 as $lv1){
	                            						$permalink1 = base_url(FOMODULE.'/'.$lv1->url.'-1.1.html');
	                            						echo '<div class="col-5">
	                            								<a href="'.$permalink1.'" class="mega-menu-title">'.strtoupper($lv1->name_category).'</a>
	                            								<ul class="mega-menu-list clearfix">';
	                            						$level2 = $this->m_client->get_menu_header(array('id_parent'=>$lv1->id_category,'id_level'=>2));
	                            						foreach ($level2 as $lv2){
	                            							$permalink2 = base_url(FOMODULE.'/'.$lv2->url.'-1.2.html');
	                            							echo '<li><a href="'.$permalink2.'">'.strtoupper($lv2->name_category).'</a></li>';
	                            						}
	                            						 echo '</ul>
	                            							</div>';                           						        							
	                            					}       
	                            					//get brand/manufacture by parent category                     					
	                            					echo '<div class="col-5">
	                            									<a href="javascript:void(0)" class="mega-menu-title">TOP BRANDS</a>
	                            									<ul class="mega-menu-list clearfix">';
				                            					$brand = $this->m_client->get_category_brand(array('B.id_parent_category'=>$row->id_category));
				                            					foreach ($brand as $v){
				                            						$permalink = base_url(FOMODULE.'/'.$v->url.'-2.html');
				                            						echo '<li><a href="'.$permalink.'">'.strtoupper($v->name).'</a></li>';
				                            					}
	                            							echo '</ul></div>';
	                            						echo '<div class="col-5">
	                            							<a href="#"><img style="width:400px" src="'.base_url().'assets/images/category/'.$row->image.'" alt="'.$row->name_category.'"/></a>
	                            						 </div>';
	                            					echo '</div>';//end mega-menu clearfix
                            					}//end level1                            				
                            				echo '</li>';//end mega-menu-container
                            			}
                            	
                            	echo '</ul></nav>';
							}else{
								echo alert_public($this->lang->line('default_group'), 'warning');
							}?>                                                                                        
                           </div>
                    </div>
                </div>                    
                </div>