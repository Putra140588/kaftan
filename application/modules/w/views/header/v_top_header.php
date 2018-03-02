<div id="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header-top-left">
                                <ul id="top-links" class="clearfix">
                                <?php 
                                	$wishlist = $this->lang->line('wishlist');                                	                              	
                                	$checkout = $this->lang->line('checkout');
                                	echo '<!--<li><a href="#" title="'.$wishlist.'"><span class="top-icon top-icon-pencil"></span><span class="hide-for-xs">'.$wishlist.'</span></a></li>-->                         	
                                    	  <li><a href="'.base_url(FOMODULE.'/customer/pay_confirm.html').'" title="'.lang('tf6').'"><span class="top-icon top-icon-check"></span><span class="hide-for-xs"> '.lang('tf6').'</span></a></li>
                                		  <li><a href="'.base_url(FOMODULE.'/checkout/proses').'" title="'.$checkout.'"><span class="top-icon top-icon-check"></span><span class="hide-for-xs">'.$checkout.'</span></a></li>
                                    	  <li><a href="'.base_url(FOMODULE.'/help.html').'" title="Bantuan"><span class="top-icon top-icon-check"></span><span class="hide-for-xs">'.lang('help').'</span></a></li>';
                                    
                                ?>                                    
                                </ul>
                            </div>
                            <div class="header-top-right">                                
                                <div class="header-top-dropdowns pull-right">
                                    <div class="btn-group dropdown-money">
                                   		 <?php $sess_curr_name = $this->session->userdata('name_fo');
                                    		   $sess_curr_symbol = $this->session->userdata('symbol_fo');
                                    		   $sess_id_curr = $this->session->userdata('id_currency_fo');
                                    	?>
                                        <button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
                                            <span class="hide-for-xs"><?php echo $sess_curr_name?></span><span class="hide-for-lg"><?php echo $sess_curr_symbol?></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                        	<?php 
                                        	$sql = $this->m_client->get_table('tb_currency','',array('used'=>'fo','deleted'=>0,'id_currency <>'=>$sess_id_curr));
                                        	foreach ($sql as $v){
												echo '<li><a href="'.base_url(FOMODULE.'/change_currency/'.$v->id_currency).'"><span class="hide-for-xs">'.$v->name.'</span><span class="hide-for-lg">'.$v->symbol.'</span></a></li>';
											}
                                        	?>                                                       
                                        </ul>
                                    </div>
                                    <div class="btn-group dropdown-language">
                                    	<?php $sess_lang_name = $this->session->userdata('name_language');
                                    		  $sess_lang_flag = $this->session->userdata('flag');
                                    		  $sess_id_lang = $this->session->userdata('id_language');
                                    	?>
                                    	<button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
                                            <span class="flag-container"><img src="<?php echo base_url()?>assets/images/flag/<?php echo $sess_lang_flag?>" alt="<?php echo $sess_lang_name?>"></span>
                                            <span class="hide-for-xs"><?php echo $sess_lang_name?></span>
                                        </button>                                   	          
                                        <ul class="dropdown-menu pull-right" role="menu">
	                                        <?php 	                                        
	                                        $sql = $this->m_client->get_table('tb_language','',array('active'=>1,'deleted'=>0,'id_language <>'=>$sess_id_lang));
	                                    	foreach ($sql as $row){
												echo ' <li><a href="'.base_url(FOMODULE.'/change_lang/'.$row->id_language).'"><span class="flag-container"><img src="'.base_url().'assets/images/flag/'.$row->flag.'" alt="'.$row->name_language.'"></span><span class="hide-for-xs">'.$row->name_language.'</span></a></li>';
											}?>                                                                                   
                                        </ul>
                                    </div>
                                    <?php if ($this->session->userdata('login_user') == true){?>  
                                    <div class="btn-group dropdown-language">                                    	
                                    	<button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
                                            Hi, <?php echo $this->session->userdata('first_name_fo')?>
                                        </button>                                   	          
                                        <ul class="dropdown-menu pull-right" role="menu">
	                                      <li><a href="<?php echo base_url(FOMODULE.'/customer/my_account.html')?>"><span class="glyphicon glyphicon-user"></span> <?php echo lang('myprof')?></a></li>	                                    
	                                      <li><a href="<?php echo base_url(FOMODULE.'/customer/my_order.html')?>"><span class=" glyphicon glyphicon-tag"></span> <?php echo lang('myorder')?></a></li>
	                                      <li><a href="<?php echo base_url(FOMODULE.'/account/logout')?>"><span class="glyphicon glyphicon-off"></span> <?php echo lang('logout')?></a></li>
                                        </ul>
                                    </div>
                                    <?php }else{?>
                                    <div class="btn-group dropdown-language">                                    	
                                    	<button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown">
                                           Member Area
                                        </button>                                   	          
                                        <ul class="dropdown-menu pull-right" role="menu">
	                                      <li><a href="<?php echo base_url(FOMODULE.'/account/login.html')?>"><span class="glyphicon glyphicon-log-in"></span> <?php echo lang('login')?></a></li>
	                                      <li><a href="<?php echo base_url(FOMODULE.'/account/register.html')?>"><span class="glyphicon glyphicon-edit"></span> <?php echo lang('register')?></a></li>
	                                                            
                                        </ul>
                                    </div>
                                    <?php }?>
                                </div>    
                                                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>