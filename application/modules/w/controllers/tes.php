<?php
session_start();
if($_POST) {
// MEMPERSIAPKAN STEP 1, DATA DAN PERSIAPAN REDIRECT
// KONFIGURASI PAYPAL
$url = 'https://api-3t.sandbox.paypal.com/nvp'; // UNTUK PRODUCTION, GANTI DENGAN URL PRODUCTION
$gatepaypal = 'https://www.sandbox.paypal.com/cgi-bin/webscr?'; // UNTUK PRODUCTION, GANTI DENGAN URL PRODUCTION

$desc = "Anda akan membeli {$_POST['namabarang']}";
$amount = $_POST['harga'];

// MENYIMPAN DATA FORM KE DALAM SESSION PHP
$_SESSION['desc'] = $desc;
$_SESSION['amount'] = $amount;

// ISI DATA YANG AKAN DIKIRIM KE PAYPAL
$data = array(
            'USER' => 'riki.r_1355726662_biz_api1.gmail.com',
            'PWD' => '1355726683',
            'SIGNATURE' => 'AHRInAafYLlccBzbJ4ajNVxkUtcAAoQsaH5X0fWTJba2WGL8-wiQ9ZxX',
            'METHOD' => 'SetExpressCheckout',
            'VERSION' => '89',
            'cancelUrl' => 'http://localhost/paypalcancel.php',
            'returnUrl' => 'http://localhost/paypalkonfirmasi.php',
            'BRANDNAME' => 'BLANJAMUDAH.COM',
            'HDRIMG' => 'http://www.blanjamudah.com/img/logo-top.gif',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Order',
            'PAYMENTREQUEST_0_DESC' => $desc,
            'PAYMENTREQUEST_0_AMT' => $amount,
            'PAYMENTREQUEST_0_QTY' => '1',
            'PAYMENTREQUEST_0_ITEMAMT' => $amount,
        );
$req = http_build_query($data);

// MENGHUBUNGI PAYPAL UNTUK MENDAPATKAN TOKEN DENGAN KONEKSI SOCKET
$response_paypal = do_post_request($url,$req);
$arr_response = explode('&',$response_paypal);
if (isset($arr_response[3]) && $arr_response[3] == 'ACK=Success') {
            // redirect ketika kita mendapatkan lampu hijau dari Paypal
            $token = str_replace('TOKEN=', '', $arr_response[0]);
            $target = $gatepaypal."cmd=_express-checkout&token={$token}";
            header('Location: ' . $target);
            die();
        } else {
        // TERJADI ERROR KETIKA MENGONTAK PAYAPAL
                    echo "Error ketika menghubungi paypal:".var_dump($arr_response);die;
        }
}

// FUNGSI UNTUK MENGHUBUNGI API PAYPAL
function do_post_request($url, $data, $optional_headers = null)
{
  $params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}

?>
<html>
<head><title>Form Paypal</title></head>
<body>
<form action="" method="post">
Nama Barang <input type="text" name="namabarang"><br/>
Harga (dalam usd/dollar) <input type="text" name="harga" value="8"><br/>
<input type="submit" value="beli">
</form>
</body>
</html>