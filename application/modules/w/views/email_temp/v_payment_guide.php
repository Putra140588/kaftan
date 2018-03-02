<?php if ($pay_code == 8801){
	//transfer via bank
	$total_payment = ($iso_code == 'IDR') ? formatnum($total_payment) : $total_payment;
echo '<table>
			<tr>
				<td>
					<h6>Untuk melakukan pembayaran, ikuti langkah dibawah ini.</h6>
					<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">
					1. Lakukan pembayaran sejumlah <b>'.$symbol.' '.$total_payment.'</b><br>
					2. Pembayaran Via <b>'.$method_name.'</b><br>
					3. No Rekening : <b>'.$content.'</b><br>
					4. Atas Nama : <b>'.$name_owner.'</b><br>											 											
					</p>
					<p>Catatan : <br>
					 * Kamu dapat melakukan pembayaran melalui bank lainnya dan mungkin dikenakan biaya tambahan sesuai dengan kebijakan masing- masing bank<br>
					 * Jika menggunakan m-banking, e-banking atau ATM non tunai, di kolom berita, cantumkan no pesanan: <b>'.$id_order.'</b><br>
					 * Setelah melakukan transfer, segera lakukan konfirmasi pembayaran dengan cara klik link ini <a href="'.base_url(FOMODULE.'/customer/pay_confirm.html').'">"Konfirmasi Pembayaran"</a> agar pesanan kamu dapat kami proses secepatnya untuk segera dikirim.<br>
					 * Setelah melakukan konfirmasi pembayaran, mohon tunggu paling lama 1 hari untuk proses verifikasi. Kamu akan menerima Email konfirmasi setelah pembayaran terverifikasi.										   		 										   		
				</td>
			</tr>
		</table>';
}else if ($pay_code == 8802){
	//cod
	echo '<table>
			<tr>
				<td>
					<h6>Catatan penting untuk Pembayaran di Tempat.</h6>
					<p style="padding:15px;background-color:#ECF8FF;margin-bottom:15px">					
					 * Siapkan uang pas saat melakukan pembayaran pada Kurir untuk mempercepat proses transaksi<br>
					 * Selesaikan proses pembayaran pada Kurir sebelum kamu membuka paket dan mencoba produk yang dipesan<br>
					 * Kami akan mengirim ulang pesanan kamu jika tidak ada yang menerima paket saat pengiriman pertama<br>
					</p>
				</td>
			</tr>
		</table>';
}?>
