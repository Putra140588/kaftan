											<div class="widget-box transparent">
												<div class="widget-header widget-header-small">
													<h4 class="widget-title blue smaller">
														<i class="ace-icon fa fa-rss orange"></i>
														Recent Activities
													</h4>													
												</div>
											<div class="widget-body">
													<div class="widget-main padding-8">
														<div id="historystatus" class="profile-feed">
															<?php echo $this->m_content->recent_activities(array('A.id_order'=>$id_order))?>
														</div>
													</div>
												</div>
											</div>