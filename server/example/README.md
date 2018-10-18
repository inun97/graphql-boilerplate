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

Pastikan konfigurasi database di file __dbconfig.ini__ sudah terisi sesuai dengan database yang Anda gunakan. Untuk client yang menguji server graphql contoh ini kita gunakan aplikasi [Insomnia](https://insomnia.rest/download/). Sedangkan untuk menjalankan GraphQL endpoint yang telah kita buat jika menggunakan PHP local server ketikkan perintah:

```sh
php -S localhost:8181
```

Port 8181 bisa diganti sesuai keinginan Anda. Kemudian buka aplikasi insomnia dan buat Request baru dengan method POST dan dilanjutkan dengan memilih GraphQL Query. Untuk meminta data dari graphql server kita menggunakan query yang kita masukkan  ke aplikasi Insomnia. Contoh query fakultas adalah seperti berikut:

![Query Fakultas](https://github.com/NazirArifin/graphql-boilerplate/blob/master/server/example/resources/query.png "Query Fakultas")

### Query

Di folder types sudah terdapat beberapa "types" (selain QueryType, MutationType dan Types) antara lain Fakultas, Prodi dan Dosen. Berikut ini adalah contoh langkah-langkah dalam pembuatan FakultasType:

1. Lihat file __FakultasType.php__. Di dalamnya terdapat class baru FakultasType yang meng-_extends_ ObjectType. Di _constructor_ bagian variabel config kita masukkan beberapa bagian seperti name, description dan fields. Pastikan memasukkan variabel config ke dalam parent constructor dengan menggunakan ```parent::__constructor($config);```.
Query juga bisa memiliki argument seperti pada contoh kita menggunakan argumen __id__ untuk menentukan fakultas yang ingin ditampilkan datanya.

2. Setiap kali Anda menambahkan Type baru maka otomatis terdeteksi dan untuk mendapatkan _instance_ dari class Type yang Anda buat maka dapat menggunakan __Types::getType('namatype')__.

2. Tambahkan field 'fakultas' di file __Types/QueryType.php__ bagian config yang berisi fields __type, description, args, dan resolve__. Bagian resolve berisi fungsi anonim yang akan melakukan pencarian data dan mereturn data hasil query berupa array yang kemudian ditampilkan kedalam bentuk json.

### Circular Types

Seringkali di aplikasi yang kita buat terdapat data yang "circular", contohnya adalah di ProdiType yang memiliki field __ketua__ dengan type __dosen__ dimana dosen juga meliki field __prodi__ tersebut. Hal ini akan membuat data berputar-putar karena tiap type saling merujuk satu sama lain. Jika PHP memunculkan error "alokasi memory terlampaui" maka Anda pasti telah membuat circular type seperti ini. Untuk mengatasinya adalah dengan menggunakan callable function seperti contoh di ProdiType:

```php
...
'fields' => function() {
  return [
    'id' => Type::int(),
    ...
```

yang mana berbeda dengan fields di FakultasType yang seperti ini:

```php
...
'fields' => [
   'id' => Type::int(),
...
```

## Mutation







