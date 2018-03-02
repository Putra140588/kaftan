<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sandbox / Test Mode
 * -------------------
 *
 * TRUE means you'll be hitting PayPal's sandbox/test servers.  FALSE means you'll be hitting the live servers.
 */
$config['Sandbox'] = TRUE;

/**
 * PayPal API Version
 * --------------------------
 *
 * The library is currently using PayPal API version 119.0.
 * You may adjust this value here and then pass it into the PayPal object when you create it within your scripts to override if necessary.
 */
$config['APIVersion'] = '123.0';

/**
 * PayPal Gateway API Credentials
 * ------------------------------
 *
 * These are your PayPal API credentials for working with the PayPal gateway directly.
 * These are used any time you're using the parent PayPal class within the library.
 *
 * We're using shorthand if/else statements here to set both Sandbox and Production values.
 * Your sandbox values go on the left and your live values go on the right.
 *
 * We have included our sandbox seller account credentials here so the demo will work right away.
 * If you have your own sandbox account you may simply replace these values with your own.
 *
 * You may obtain live credentials by logging into the following with your PayPal account:
 * https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run
 */
$config['APIUsername'] = $config['Sandbox'] ? 'kaftwholesale-facilitator_api1.gmail.com' : 'kaftwholesale_api1.gmail.com';
$config['APIPassword'] = $config['Sandbox'] ? 'NC6BKCKA98NTMCEQ' : 'S9R37V4Q7YFKMHZ2';
$config['APISignature'] = $config['Sandbox'] ? 'AwFBb3JFHSxMdjo2nANAdeqCmzepAAyqy91YUC2iLw33FEPf-0FZmHfk' : 'A.BHl5Kbh3Yob1CI3q3PnFuWynuWAv9ZmsZMAEG-5lcd84uJoZEesLVn';

/**
 * Payflow Gateway API Credentials
 *
 * NOTE: PayFlow credentials are only required for PayFlow specific demo kits.
 * -------------------------------
 *
 * These are the credentials you use for your PayPal Manager:  http://manager.paypal.com
 * These are used when you're working with the PayFlow child class.
 *
 * We're using shorthand if/else statements here to set both Sandbox and Production values.
 * Your sandbox values go on the left and your live values go on the right.
 *
 * You may use the same credentials you use to login to your PayPal Manager,
 * or you may create API specific credentials from within your PayPal Manager account.
 */
/*
$config['PayFlowUsername'] = $config['Sandbox'] ? 'tester' : '';
$config['PayFlowPassword'] = $config['Sandbox'] ? 'Pr0t3ct!' : '';
$config['PayFlowVendor'] = $config['Sandbox'] ? 'angelleye' : '';
$config['PayFlowPartner'] = $config['Sandbox'] ? 'PayPal' : 'PayPal';
*/

/**
 * PayPal Adaptive Payments
 *
 * NOTE:  These settings are only required for Adaptive Payments specific demo kits.
 * ----------------------------------
 *
 * You obtain your application ID but submitting it for approval within your
 * developer account at http://developer.paypal.com
 *
 * We're using shorthand if/else statements here to set both Sandbox and Production values.
 * Your sandbox values go on the left and your live values go on the right.
 * The sandbox value included here is a global value provided for developrs to use in the PayPal sandbox.
 */
$config['ApplicationID'] = $config['Sandbox'] ? 'APP-80W284485P519543T' : 'PRODUCTION_APP_ID_GOES_HERE';
$config['DeviceID'] = '';
$config['DeviceIpAddress'] = $_SERVER['REMOTE_ADDR'];

/**
 * PayPal Developer Account Email Address
 * This is the email address that you use to sign in to http://developer.paypal.com
 */
$config['DeveloperEmailAccount'] = 'zahra.ameliasiti@gmail.com';


/* End of file paypal_sample.php */
/* Location: ./system/application/config/paypal_sample.php */