# GraphQL Server Example

## Instalasi

Pastikan sudah terinstal composer lalu ketikkan perintah berikut:

```sh
composer install
```

## Migrasi dengan Phinx

Sebagai contoh kita akan membuat struktur tabel sesuai dengan diagram ER berikut:

![ER Diagram](https://github.com/NazirArifin/graphql-boilerplate/blob/master/server/example/resources/erd.png "ER Diagram")

Pada diagram tersebut terdapat enam tabel antara lain fakultas, prodi, dosen, mahasiswa, dsb. Tabel-tabel ini akan dimasukkan di database menggunakan migration dari phinx.

Di contoh ini saya sudah membuatkan file migrasi dan seeder yang bisa langsung Anda eksekusi, namun bila Anda ingin tahu langkah-langkah dalam pembuatan file migrasi dan seeder Anda dapat mengikuti petunjuk berikut:

___** Langkah-langkah berikut tidak harus Anda lakukan karena saya sudah membuat file migrasi dan seedernya!___

1. Langkah pertama adalah menjalankan perintah _phinx init_: (saya menggunakan Windows CMD)

```
vendor\bin\phinx init
```

Dengan perintah tersebut maka ada dibuat sebuah file __phinx.yml__. Dalam file tersebut terdapat tiga environment yaitu __production__, __development__, dan __testing__ (defaultnya adalah "development") yang bisa kita ubah konfigurasi databasenya. Pastikan Anda sudah membuat database dan kemudian baru Anda edit file ini sesuai dengan nama database yang telah Anda buat.

2. Selanjutnya kita buat file migration dengan perintah:

```
vendor\bin\phinx create NewMigration
```

Sebuah file __php__ akan dibuat di folder __db/migrations__ dengan nama ***_new_migration.php yang mana tanda *** akan diganti dengan timestamp. Selanjutnya kita edit file ini dibagian _method_ __change()__. Untuk contohnya dapat Anda lihat di file [__db/migrations/20180930064431_new_migration.php__](http://google.com).

3. Setelah disimpan, maka eksekusi file migrasi tadi dengan perintah:

```sh
vendor\bin\phinx migrate
```

Jika berhasil maka beberapa tabel sudah dibuat di database yang telah Anda tentukan.

4. Sekarang untuk mengisi tabel dengan data _dummy_ kita akan menggunakan Seeder. Untuk membuat seeder menggunakan perintah:

```sh
vendor\bin\phinx seed:create NamaSeeder
```

Sebuah file __php__ akan dibuat di folder __db/seeds__ yang bisa Anda modifikasi. Di contoh ini saya sudah membuat beberapa file seeder di folder __db/seeds__ antara lain: __FakultasProdiSeeder__, __DosenJabatanSeeder__, dan __MahasiswaSeeder__.

5. Untuk menjalankan seeder yang telah dibuat digunakan perintah:

```sh
vendor\bin\phinx seed:run
```

Jika berhasil maka tabel-tabel yang telah dibuat sebelumnya akan terisi dengan data _dummy_ hasil dari eksekusi seeder yang telah dibuat.

## GraphQL Server

Pastikan konfigurasi database di file __dbconfig.ini__ sudah terisi sesuai dengan database yang Anda gunakan. Kita akan membuat tiga "types" secara bersamaan yaitu Fakultas, Prodi dan Dosen. Untuk client yang menguji server graphql contoh ini kita gunakan aplikasi [Insomnia](http://google.com).

### Query

Untuk meminta data dari graphql server kita menggunakan query yang kita masukkan ke aplikasi Insomnia. Contoh query fakultas adalah seperti berikut:

![Query Fakultas](/path/to/img.jpg "Query Fakultas")

Agar query diatas bisa berjalan maka kita perlu mengubah file __Types/QueryType.php__ dan sekaligus membuat file baru __FakultasType.php__

* _QueryType.php_


