<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = FOMODULE;
$route['404_override'] = 'w/page_not_found';//redirect method
$route['translate_uri_dashes'] = FALSE;

/*product moduls*/
$route[FOMODULE.'/product/select_attribute/(:any)'] = FOMODULE.'/product/select_attribute/$1';
$route[FOMODULE.'/product/add_cart'] = FOMODULE.'/product/add_cart';
$route[FOMODULE.'/product/delete_cart'] = FOMODULE.'/product/delete_cart';
$route[FOMODULE.'/product/sess_destroy'] = FOMODULE.'/product/sess_destroy';
$route[FOMODULE.'/product/download_attach/(:any)'] = FOMODULE.'/product/download_attach/$1';
/*account moduls*/
$route[FOMODULE.'/account/register'] = FOMODULE.'/account/register/$1';
$route[FOMODULE.'/account/login'] = FOMODULE.'/account/login/$1';
$route[FOMODULE.'/account/logout'] = FOMODULE.'/account/logout/$1';
$route[FOMODULE.'/account/activasi/(:any)/(:any)'] = FOMODULE.'/account/activasi/$1/$2';
/*checkout*/
$route[FOMODULE.'/checkout/proses'] = FOMODULE.'/checkout/proses';
$route[FOMODULE.'/checkout/login_checkout'] = FOMODULE.'/checkout/login_checkout';
$route[FOMODULE.'/checkout/add_address'] = FOMODULE.'/checkout/add_address';
$route[FOMODULE.'/checkout/show_province'] = FOMODULE.'/checkout/show_province';
$route[FOMODULE.'/checkout/show_province/(:num)'] = FOMODULE.'/checkout/show_province/$1';
$route[FOMODULE.'/checkout/show_city'] = FOMODULE.'/checkout/show_city';
$route[FOMODULE.'/checkout/show_city/(:num)'] = FOMODULE.'/checkout/show_city/$1';
$route[FOMODULE.'/checkout/show_districts'] = FOMODULE.'/checkout/show_districts';
$route[FOMODULE.'/checkout/show_districts/(:num)'] = FOMODULE.'/checkout/show_districts/$1';
$route[FOMODULE.'/checkout/save_address'] = FOMODULE.'/checkout/save_address';
$route[FOMODULE.'/checkout/change_address'] = FOMODULE.'/checkout/change_address';
$route[FOMODULE.'/checkout/delete_address'] = FOMODULE.'/checkout/delete_address';
$route[FOMODULE.'/checkout/select_courier/(:num)'] = FOMODULE.'/checkout/select_courier/$1';
$route[FOMODULE.'/checkout/delete_cart'] = FOMODULE.'/checkout/delete_cart';
$route[FOMODULE.'/checkout/confirm'] = FOMODULE.'/checkout/confirm';
$route[FOMODULE.'/checkout/confirm_order'] = FOMODULE.'/checkout/confirm_order';
$route[FOMODULE.'/checkout/edit_qty/(.+)'] = FOMODULE.'/checkout/edit_qty/$1';
$route[FOMODULE.'/checkout/email_order'] = FOMODULE.'/checkout/email_order';
$route[FOMODULE.'/checkout/send_email'] = FOMODULE.'/checkout/send_email';
$route[FOMODULE.'/checkout/pay_confirm'] = FOMODULE.'/checkout/pay_confirm';
$route[FOMODULE.'/checkout/paypalform'] = FOMODULE.'/checkout/paypalform';
/*paypal*/
$route[FOMODULE.'/checkout/SetExpressCheckout'] = FOMODULE.'/checkout/SetExpressCheckout';
$route[FOMODULE.'/checkout/GetExpressCheckoutDetails'] = FOMODULE.'/checkout/GetExpressCheckoutDetails';
$route[FOMODULE.'/checkout/DoExpressCheckoutPayment'] = FOMODULE.'/checkout/DoExpressCheckoutPayment';
/*customer member area*/
$route[FOMODULE.'/customer/(:any)'] = FOMODULE.'/customer/member_area/$1';
$route[FOMODULE.'/customer/(:any)/(:any)'] = FOMODULE.'/customer/member_area/$1/$2';

$route[FOMODULE.'/search/product/(.+)'] = FOMODULE.'/search/product/$1/0';
$route[FOMODULE.'/search/sortby_search'] = FOMODULE.'/search/sortby_search';
/*home*/
$route[FOMODULE.'/(.+)'] = FOMODULE.'/read/$1/0';//(.+) = mengalihkan halaman yang sama
$route[FOMODULE.'/maintenance/index'] = FOMODULE.'/maintenance/index';
/*help*/
$route[FOMODULE.'/help/show_content'] = FOMODULE.'/help/show_content';
