<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- CSS eksternal -->
    <link rel="stylesheet" href="css/style.css">
    <title>ORDER TIKET</title>
  </head>
  <body>
    <div class="container">
      <div class="logo">
        <img src="resources/image/logo.png" alt="">
      </div>
      <div class="form">
        <form action="" method="post">
          <table>
            <tr>
              <td>Nama Pemesan</td>
              <td>:</td>
              <td><input type="text" name="namaPemesan" required/></td>
            </tr>
            <tr>
              <td>Lokasi Bioskop</td>
              <td>:</td>
              <td>
                <select name="lokasiBioskop" required>
                  <option value="" selected disabled>Pilih Lokasi</option>
                  <?php
                    // list lokasi
                    $listLokasi = ['FX Sudirman','Central Park','Grand Indonesia','Pacific Place'];
                    sort($listLokasi);
                    foreach($listLokasi as $lL){
                      echo '<option value="'.$lL.'">'.$lL.'</option>';
                    }
                    ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Judul Film</td>
              <td>:</td>
              <td>
                <select name="judulFilm" required>
                  <option value="" selected disabled>Pilih Film</option>
                  <?php 
                    // list film
                    $listFilm = ['Suzume','Shazam','Ant Man','The First Slam Dunk'];
                    sort($listFilm);
                    foreach($listFilm as $lF){
                      echo '<option value="'.$lF.'">'.$lF.'</option>';
                    }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Hari</td>
              <td>:</td>
              <td>
                <input type="date" name="hari" required/>
              </td>
            </tr>
            <tr>
              <td>Waktu</td>
              <td>:</td>
              <td>
                <select name="waktu" required>
                  <option value="" selected disabled>Pilih Waktu</option>
                  <?php 
                    // list waktu
                    $listWaktu = ['12:30','15:00','18:30'];
                    sort($listWaktu);
                    foreach($listWaktu as $lW){
                      echo '<option value="'.$lW.'">'.$lW.'</option>';
                    }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Jumlah</td>
              <td>:</td>
              <td>
                <input type="number" name="jumlahTiket" required/>
              </td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td>
                <input type="submit" value="pesan" name="pesan" class="pesan"/>
              </td>
            </tr>
          </table>
      </div><br><br>

      <?php
              if(isset($_POST["pesan"])){
                  $nama = $_POST["namaPemesan"];
                  $lokasi = $_POST["lokasiBioskop"];
                  $judul = $_POST["judulFilm"];
                  $date = $_POST["hari"];
                  $waktu = $_POST["waktu"];
                  $jumlah = $_POST["jumlahTiket"];
                  
                  function dateToDay($day){
                    $toDay = ["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
                    return $toDay[$day];
                  }

                  function hitung($lokasi, $hari, $jumlah){
                    $harga = 0;
                    if($hari == 1 || $hari == 2 || $hari == 3 || $hari == 4){
                      if($lokasi == 'Pacific Place'){
                        $harga = 60000;
                      }
                      if($lokasi == 'Grand Indonesia' || $lokasi == 'Central Park'){
                        $harga = 50000;
                      }
                      if($lokasi == '0pp0FX Sudirman'){
                        $harga = 45000;
                      }
                    }else{
                      if($lokasi == 'Pacific Place'){
                        $harga = 75000;
                      }
                      if($lokasi == 'Grand Indonesia' || $lokasi == 'Central Park'){
                        $harga = 60000;
                      }
                      if($lokasi == 'FX Sudirman'){
                        $harga = 50000;
                      }
                    }
                    // Kembalikan nilai yang sudah di validasi berdasarkan lokasi
                    return $harga*$jumlah;
                  }

                  // Merubah hari ke dalam bahasa indonesia
                  $hari = dateToDay(date('w',strtotime($date)));
                  
                  // Menghitung Rp berdasarkan jumlah beli
                  $total = hitung($lokasi, $date, $jumlah);
                  
                  // Directory tempat file json berada
                  $file = "resources/json/data.json";
                  
                  // Simpan data dalam bentuk assosiative array
                  $save = array('nama'=>$nama,
                                'lokasi'=>$lokasi,
                                'judul'=>$judul,
                                'hari'=>$hari,
                                'waktu'=>$waktu,
                                'jumlah'=>$jumlah,
                                'total'=>$total);
                                
                  // mengambil data dari file json
                  $getJson = file_get_contents($file);  
                                
                  // Proses merubah data json ke bentuk array
                  $jsonDc = json_decode($getJson, true);
                                
                  // Menambah data baru
                  $add    = array_push($jsonDc, $save);
                  
                  // Proses merubah data dalam bentuk json
                  $jsonEn = json_encode($jsonDc, JSON_PRETTY_PRINT);
                  
                  // Manyimpan data yang sudah di tambah
                  file_put_contents($file, $jsonEn);

                  // Menampilkan header tabel hasil dari submit
                  echo '
                  <h1>Berikut data akun yang tersedia :</h1>
                  <div class="hasil"><table>
                    <tr>
                      <th>Nama Pemesan</th>
                      <th>Lokasi</th>
                      <th>Film</th>
                      <th>Hari</th>
                      <th>Waktu</th>
                      <th>Jumlah</th>
                      <th>Total Harga</th>
                    </tr>';

                  // Menampilkan data dari file json yang sudah di convert 
                  foreach($jsonDc as $e){
                    echo '
                        <tr>
                          <td>'.$e["nama"].'</td>
                          <td>'.$e["lokasi"].'</td>
                          <td>'.$e["judul"].'</td>
                          <td>'.$e["hari"].'</td>
                          <td>'.$e["waktu"].'</td>
                          <td>'.$e["jumlah"].'</td>
                          <td>Rp '.number_format($e["total"], 2,".",".").'</td>
                        </tr>';
                      }
                  echo '</table></div>';
              }
          ?>
      </form>
    </div>
    <!-- Ini footer -->
    <footer>
      <div class="footer">Karta © Copyright 2023 | All rights reserved </div>
    </footer>
  </body>
</html>
