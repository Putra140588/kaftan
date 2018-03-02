						<div class="panel">
                             <div class="accordion-header">
                                  <div class="accordion-title"><span><?php echo lang('delivaddr')?></span></div>
                                   <a class="accordion-btn"  data-toggle="collapse" data-target="#address"></a>
                               </div>                                    
                                <div id="address" class="collapse-in">
                                   <div class="panel-body" id="deliveryaddr">   
                                   		<?php echo $this->m_public->delivery_address()?>									
                                    </div>
                               </div>
                       </div>