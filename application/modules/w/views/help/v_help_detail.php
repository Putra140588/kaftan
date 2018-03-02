								<div class="tab-container clearfix">
								  	<ul class="nav-tabs clearfix">
										<?php foreach ($querydetail as $row){
											$active = ($row->sort == 1) ? 'class="active"' : '';
											echo '<li '.$active.'><a href="javascript:void(0)" data-toggle="tab" onclick="ajaxcall(\''.base_url(FOMODULE.'/help/show_content').'\',\''.$row->id_helper_detail.'\',\'help-content\')">'.$row->title_helper_detail.'</a></li>';
										}?>		                                  
		                           	</ul>		
		                           		<div class="md-margin"></div>
		                             <div id="help-content">		                                                                                 
		                                 <?php foreach ($querydetail as $row){
											echo '<h3>'.$row->title_helper_detail.'</h3><p>'.$row->content.'</p>';
										}?>	       
		                             </div>
                                </div>