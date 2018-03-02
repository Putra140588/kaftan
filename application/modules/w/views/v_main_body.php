<!DOCTYPE html>
<!--[if IE 8]> <html class="ie8"> <![endif]-->
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
         <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico">
        <title><?php echo strip_tags($tab_title).' | '.tab_title('tab_title')?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo strip_tags($meta_description)?>">
		<meta name="keywords" content="<?php echo $meta_keywords?>">
		<meta name="author" content="">
		<meta name="robots" content="index, follow">		
		<meta property="og:url"           content="<?php echo base_url($this->session->userdata('permalink_sess'))?>"/>
		<meta property="og:type"          content="product" />
		<meta property="og:title"         content="<?php echo strip_tags($tab_title)?>" />
		<meta property="og:description"   content="<?php echo strip_tags($meta_description)?>" />
		<meta property="og:image"         content="<?php echo  isset($meta_image_name) ? base_url().'assets/images/'.image($meta_image_name) : base_url().'assets/images/logo/'.$this->session->userdata('logo_company');?>" />
		<meta property="fb:app_id" content="1490999154341008">
        <meta name="<?php echo csrf_token()['name']?>" content="<?php echo csrf_token()['hash']?>">	     
        <!--  
        <link href='//fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic%7CPT+Gudea:400,700,400italic%7CPT+Oswald:400,700,300' rel='stylesheet' id="googlefont">
        -->
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/bootstrap.min.css">           
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/prettyPhoto.css">        
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/owl.carousel.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/style.css">  
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/responsive.css"> 
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/revslider.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/jquery.selectbox.css">      
        <link rel="stylesheet" href="<?php echo base_url()?>assets/fo/css/footer.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-datepicker3.min.css" />
                      
        <!--- jQuery -->
        <script src="<?php echo base_url()?>assets/fo/js/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url()?>assets/fo/js/jquery-1.11.1.min.js"><\/script>')</script>
        <script src="<?php echo base_url()?>assets/fo/js/modernizr.custom.js"></script>
        <style id="custom-style"></style>
        
        
    </head>
    <body>
    <div id="wrapper">            	
        <?php $this->load->view('header/v_header')?>
        <div id="content-list">
        	<?php $this->load->view($content)?>
        </div>             
        <?php $this->load->view('footer/v_footer')?>
    </div><!-- End #wrapper -->    
    <a href="#" id="scroll-top" title="Scroll to Top"><i class="fa fa-angle-up"></i></a><!-- End #scroll-top -->    
	<!-- END -->
	
    <script src="<?php echo base_url()?>assets/fo/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/smoothscroll.js"></script>
	<script src="<?php echo base_url()?>assets/fo/js/jquery.debouncedresize.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/retina.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/jquery.placeholder.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/jquery.hoverIntent.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/twitter/jquery.tweet.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/jquery.flexslider-min.js"></script>
           
    <script src="<?php echo base_url()?>assets/fo/js/jquery.prettyPhoto.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/jquery.parallax-1.1.3.js"></script>
	<script src="<?php echo base_url()?>assets/js/bootstrap-datepicker.min.js"></script>	
    <script src="<?php echo base_url()?>assets/fo/js/jquery.themepunch.tools.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/jquery.themepunch.revolution.min.js"></script>  
    <script src="<?php echo base_url()?>assets/fo/js/function.js"></script>	
 	<script src="<?php echo base_url()?>assets/fo/js/jquery.selectbox.min.js"></script>
 	<script src="<?php echo base_url()?>assets/fo/js/owl.carousel.min.js"></script>  
   	<script src="<?php echo base_url()?>assets/fo/js/jquery.elastislide.js"></script>
 	<script src="<?php echo base_url()?>assets/fo/js/jquery.elevateZoom.min.js"></script>
    <script src="<?php echo base_url()?>assets/fo/js/main.js"></script>
    <script src="<?php echo base_url()?>assets/js/fungsi.js"></script>
    <script>
    	$(function() {

            // Slider Revolution
            jQuery('#slider-rev').revolution({
                delay:5000,
                startwidth:1170,
                startheight:500,
                onHoverStop:"true",
                hideThumbs:250,
                navigationHAlign:"center",
                navigationVAlign:"bottom",
                navigationHOffset:0,
                navigationVOffset:20,
                soloArrowLeftHalign:"left",
                soloArrowLeftValign:"center",
                soloArrowLeftHOffset:0,
                soloArrowLeftVOffset:0,
                soloArrowRightHalign:"right",
                soloArrowRightValign:"center",
                soloArrowRightHOffset:0,
                soloArrowRightVOffset:0,
                touchenabled:"on",
                stopAtSlide:-1,
                stopAfterLoops:-1,
                dottedOverlay:"none",
                fullWidth:"on",
                spinned:"spinner3",
                shadow:0,
                hideTimerBar: "on",
                // navigationStyle:"preview4"
              });
        
        });
    </script>
    </body>
</html>