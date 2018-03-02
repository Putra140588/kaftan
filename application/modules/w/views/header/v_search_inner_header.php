 				<div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 logo-container">
                            <h1 class="logo clearfix">                                
                                <a href="<?php echo base_url()?>" title="<?php echo $this->session->userdata('company_name')?>"><img src="<?php echo base_url()?>assets/images/logo/<?php echo $this->session->userdata('logo_company')?>" alt="<?php echo $this->session->userdata('logo_company')?>"></a>
                            </h1>
                        </div>                        
                        <div class="col-md-12 col-sm-12 col-xs-12 header-inner-right">
                            <div id="quick-access">
                                <form id="search-form" class="form-inline quick-search-form" role="form" >
                                <input type="hidden" class="msp_token" name="<?php echo csrf_token()['name']?>" value="<?php echo csrf_token()['hash']?>">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="<?php echo $this->lang->line('search')?>" required>
                                    </div>
                                    <button type="submit" id="quick-search" class="btn btn-custom"></button>
                                </form>
                            </div>
                             <div class="dropdown-cart-menu-container pull-right" id="list-cart">
                                <?php echo $this->m_public->top_list_cart()?>
                             </div>                                
                        </div>
                    </div>
                </div>