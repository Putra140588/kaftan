							<div class="col-md-12 col-sm-12 col-xs-12">        						
        						<div class="tab-container left product-detail-tab clearfix">
        								<ul class="nav-tabs">										  
										  <li class="active"><a href="#desc" data-toggle="tab"><?php echo lang('desc')?></a></li>										  
										  <?php 
										  if (!empty($specification)){
										  	echo '<li><a href="#spc" data-toggle="tab">'.lang('spec').'</a></li>';
										  }
										  if (count($video) > 0){
										  	echo '<li><a href="#video" data-toggle="tab">'.lang('video').'</a></li>';
										  }
										  if (count($attachment) > 0){
										  	echo '<li><a href="#down" data-toggle="tab">'.lang('down').'</a></li>';
										  }
										 ?>
										</ul>
        								<div class="tab-content clearfix">        									
        									<div class="tab-pane active" id="desc">
        										<?php echo $description_product?>
        									</div>        									
        									<div class="tab-pane" id="spc">
												<?php echo $specification?>
        									</div><!-- End .tab-pane -->
        									<?php if (count($video) > 0){?>
        									<div class="tab-pane" id="video">
        										<div class="video-container">
        											<?php foreach ($video as $row){
        												echo '<strong>'.$row->video_title.'</strong><hr>
        													  <iframe width="560" height="315" src="'.$row->video_url.'"></iframe>
        													  <div class="xs-margin"></div>';
        												
        											}?>
        											    											
        										</div>        										
        									</div>        									
        									<?php }?>
        									<?php if (count($attachment) > 0){
        										echo '<div class="tab-pane" id="down">
													 <strong>'.lang('downthis').'</strong><br>';
        											foreach ($attachment as $v){
														echo '<a href="'.base_url(FOMODULE.'/product/download_attach/'.$v->file).'">'.$v->file.'</a><br>';
													}
        										echo '</div>';        										
        									}?>        									
        								</div>
        						</div>
        						<div class="lg-margin visible-xs"></div>
        					</div>