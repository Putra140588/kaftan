<div id="main-nav-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 clearfix">   
                            <?php $group = $this->m_client->get_table('tb_group',array('id_group','name_group'),array('default'=>1));
                           if (count($group) > 0){                             
                             echo '<nav id="main-nav">
                                    <div id="responsive-nav">
                                        <div id="responsive-nav-button">
                                            Menu <span id="responsive-nav-button-icon"></span>
                                        </div>
                                    </div>                                                                        	
									<ul class="menu clearfix">  
                            				<li class="mega-menu-container"><a href="#">SHOP</a>
                                            <div class="mega-menu clearfix">
                                                    <div class="col-5">
                                                        <a href="category.html" class="mega-menu-title">Clothing</a><!-- End .mega-menu-title -->
                                                        <ul class="mega-menu-list clearfix">
                                                            <li><a href="#">Dresses</a></li>
                                                            <li><a href="#">Jeans &amp; Trousers</a></li>
                                                            <li><a href="#">Blouses &amp; Shirts</a></li>
                                                            <li><a href="#">Tops &amp; T-Shirts</a></li>
                                                            <li><a href="#">Jackets &amp; Coats
                                                                <span class="new-box">New</span>
                                                            </a></li>
                                                            <li><a href="#">Skirts</a></li>
                                                        </ul>
                                                    </div><!-- End .col-5 -->
                                                    <div class="col-5">
                                                        <a href="category.html" class="mega-menu-title">Shoes</a><!-- End .mega-menu-title -->
                                                        <ul class="mega-menu-list clearfix">
                                                            <li><a href="#">Formal Shoes</a></li>
                                                            <li><a href="#">Casual Shoes
                                                                <span class="hot-box">hot</span>
                                                            </a></li>
                                                            <li><a href="#">Sandals</a></li>
                                                            <li><a href="#">Boots</a></li>
                                                            <li><a href="#">Wide Fit</a></li>
                                                            <li><a href="#">Slippers</a></li>
                                                        </ul>
                                                    </div><!-- End .col-5 -->                                                    
                                                    <div class="col-5">
                                                       <img src="http://kaftan.dev/assets/images/category/pakaian_pria_wanita.jpg" alt="pakaian_pria_wanita.jpg"/>
                                                    </div><!-- End .col-5 -->                                                                                   
                                            </div><!-- End .mega-menu -->
                                        </li></ul></nav>';
										}else{
											echo alert_public($this->lang->line('default_group'), 'warning');
										}?>                              
                                                                                       
                                                                        
                                
                                
                            </div><!-- End .col-md-12 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
                    
                </div><!-- End #nav -->