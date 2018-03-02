						<div id="slider-rev-container">
                            <div id="slider-rev">
                                <ul>
                                	<?php $sql = $this->m_client->get_table('tb_slider_banner','',array('active'=>1,'deleted'=>0));
                                	foreach ($sql as $row){
										echo '<li data-transition="cube-horizontal" data-saveperformance="on"  data-title="'.$row->caption.'">
                                				<img src="'.base_url().'assets/fo/images/revslider/dummy.png"  alt="'.$row->caption.'" data-lazyload="'.base_url().'assets/images/slider_banner/'.$row->image.'" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                				<div class="tp-caption rev-title sft stt" data-x="300" data-y="100" data-speed="800" data-start="900" data-easing="Power3.easeIn" data-endspeed="300"> 
                                     			   '.$row->caption.'
                                				</div>
                                				<div class="tp-caption sft stt" data-x="540" data-y="180" data-speed="900" data-start="1300" data-easing="Power3.easeIn">
                                					<div class="rev-hr">
                                            			<img src="'.base_url().'assets/fo/images/sprites/slider-icon.png" alt="slider-icon">
                                       				</div>
                                				</div>
                                				<div class="tp-caption rev-subtitle skewfromleftshort stl" data-x="250" data-y="215" data-speed="1200" data-start="1700" data-easing="Power3.easeIn" data-endspeed="300">
                                         			'.$row->title.'
                                				</div>
                                				<div class="tp-caption rev-text skewfromrightshort str" data-x="340" data-y="270" data-speed="1000" data-start="2100" data-easing="Power3.easeIn" data-endspeed="300">
                                       				'.$row->description.'
                                				</div>';
                                		if ($row->btn_name != "" && $row->link){
                                			echo '<div class="tp-caption sfb stb" data-x="510" data-y="345" data-speed="1000" data-start="2500" data-easing="Power3.easeIn" data-endspeed="300">
		                                            <a href="'.base_url($row->link).'" class="btn btn-custom-3">'.$row->btn_name.'</a>
		                                         </div>';                                			 
                                		}				
                                	echo '</li>';
									}?>                                	
                                    
                                </ul>
                            </div><!-- End #slider-rev -->
                        </div><!-- End #slider-rev-container -->