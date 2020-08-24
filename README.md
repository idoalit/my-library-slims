# My Library for SLiMS
`My Library` merupakan bot yang akan membantu menghubungkan `SLiMS` kamu dengan pemustaka menjadi lebih dekat. 
Memberikan notifikasi jatuh tempo pengembalian koleksi, struk transaksi, melakukan penelusuran koleksi, pemensanan koleksi, dll.

## System requirement
- SLiMS 9 - Bulian

## Installation
1. Unduh pustaka dari repository ini dengan tautan berikut:
```https://github.com/idoalit/my-library-slims/archive/master.zip```
2. Ekstrak file yang baru saja diunduh ( `my-library-slims-master.zip` ) 
3. Pindahkan folder `klaras` ke dalam folder `{ROOT_FOLDER_SLiMS}/lib/`
4. Pindahkan folder `messenger` ke dalam folder `{ROOT_FOLDER_SLiMS}/api/`
5. Pindahkan berkas `circulation_action.php` dan `circulation_base_lib.inc.php` ke dalam folder `{ROOT_FOLDER_SLiMS}/admin/modules/circulation/`  
   _*)Lakukan backup terlebih dahulu sebelum memindahkan berkas tersebut_
6. Tambahkan konfigurasi berikut pada berkas `{ROOT_FOLDER_SLiMS}/config/sysconfig.local.inc.php`  
```
// set false untuk menonaktifkan my library
$sysconf['my_library']['enable']   = true;

// isikan akun My Library anda
$sysconf['my_library']['username'] = 'alamat.email.anda';
$sysconf['my_library']['password'] = 'password.anda';
```
