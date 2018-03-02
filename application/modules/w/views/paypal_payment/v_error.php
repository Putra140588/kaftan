        <section id="content">
        	<div id="breadcrumb-container">
        		<div class="container">
					<ul class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active">Error</li>
					</ul>
        		</div>
        	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
						<div class="xlg-margin"></div>
						<div class="no-content-comment" style="text-align:center">
						<h2><i class="ace-icon glyphicon glyphicon-remove"></i></h2>
						 <?php
			                foreach($errors as $error)
			                {
			                	echo '<h1>'.$error[0]['L_LONGMESSAGE'].'</h1>';
			                	echo '<h3> Error Code : <b>'.$error[0]['L_ERRORCODE'].'</b></h3>';
			                   
			                }
			                ?>	
			                <input type="button" onclick="window.location.href='<?php echo base_url(FOMODULE.'/checkout/proses')?>'" class="btn btn-custom-2" value="Go Back">					        
						</div>
        			</div>
        		</div>
			</div>       
        </section>
        
        