# Memasukkan Template ke Angular 6+

### Memasukkan CSS dan Javascript

* Pastikan server graphql sudah berjalan dengan perintah:

```sh
php -S localhost:8181
```

* Buat project baru dengan perintah:

```sh
ng new project --style=scss
```

* Jalankan aplikasi dengan perintah:

```sh
ng serve -o
```


* Copykan semua file template yang akan digunakan ke dalam folder __src__

* Buka template di browser dan lihat _source_ nya, untuk file-file bagian style di import di file __src/style.scss__ sedangkan file-file javascript dimasukkan di file __angular.json__.

* Jika di head _source code_ nya seperti berikut:

```html
<!-- App css -->
<link rel="stylesheet" href="assets/plugins/morris/morris.css">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
<link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/style.css" rel="stylesheet" type="text/css" />
```

* Maka di file __src/style.scss__ menjadi seperti berikut:

```scss
@import 'light/assets/plugins/morris/morris.css';
@import 'light/assets/css/bootstrap.min.css';
@import 'light/assets/css/icons.css';
@import 'light/assets/css/metismenu.min.css';
@import 'light/assets/css/style.css';
```

* Selanjutnya file javascript di bawah body seperti berikut:

```html
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/metisMenu.min.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael-min.js"></script>
<script src="assets/pages/jquery.dashboard.js"></script>
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
```

* Maka di file __angular.json__ di bagian scripts diubah menjadi seperti berikut:
```json
...
            "styles": [
              "src/styles.scss"
            ],
            "scripts": [
              "src/light/assets/js/jquery.min.js",
              "src/light/assets/js/popper.min.js",
              "src/light/assets/js/bootstrap.min.js",
              "src/light/assets/js/metisMenu.min.js",
              "src/light/assets/js/waves.js",
              "src/light/assets/js/jquery.slimscroll.js",
              "src/light/assets/pages/jquery.dashboard.js",
              "src/light/assets/js/jquery.core.js",
              "src/light/assets/js/jquery.app.js"            ]
          },
...
```

* Copy semua isi file __index.html__ dibagian __body__ di template yang kamu gunakan ke file __src/app/app.component.html__

* Untuk semua file gambar yang muncul dalam tag __img__ maka _copy_ file tersebut dari template ke folder __src/assets/images__ / __src/assets/img__ dan kemudian edit file __app.component.html__ bagian __img__ sesuaikan __src__ dengan path yang baru.

* Contohnya jika di template kode img seperti:

```html
<img src="assets/images/logo.png" alt="">
```

diubah menjadi:

```html
<img src="assets/images/logo.png" alt="">
```

* Jika ada beberapa fungsi jQuery yang tidak berfungsi seperti contoh tombol hide/show sidebar maka perbaiki file javascript di template sampai fungsinya dapat berjalan dengan baik. Contohnya adalah dengan membungkus fungsi dalam ```$(window).ready(..)```.

* Sekarang analisa tampilan index yang sudah jadi, kita harus menemukan bagian yang selalu muncul dan dapat dipecah-pecah dalam satu komponen yang lebih ringkas dan komponen yang berganti-ganti sesuai dengan routing urlnya.

* Pada umumnya terdapat tiga komponen utama yaitu __header__, __sidebar__ dan __content__. Sekarang buat component baru mengunakan perintah berikut:

```sh
ng g component components/header --module=app
ng g component components/sidebar --module=app
ng g component components/pages/dashboard --module=app
ng g component components/pages/login --module=app
```

* _Cut_ bagian header di file __app.component.html__ dan _paste_ ke file __components/header/header.component.html__ dan ganti dengan perintah: ```<app-header></app-header>```

* _Cut_ bagian sidebar di file __app.component.html__ dan _paste_ ke file __components/sidebar/sidebar.component.html__ dan ganti dengan perintah: ```<app-sidebar></app-sidebar>```

### Angular Routing

* Buat file baru untuk menampung routes dengan nama file __src/app/routes.ts__ dengan isi seperti berikut:

```ts
import { Routes } from '@angular/router';
import { DashboardComponent } from './components/pages/dashboard/dashboard.component';
import { LoginComponent } from './components/pages/login/login.component';

export const appRoutes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'dashboard', component: DashboardComponent },
  { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
];

```

* Tambahkan RouterModule ke dalam file __app.module.ts__ dengan perintah:

```
...
import { RouterModule } from '@angular/router';
...
import { appRoutes } from './routes';
...
   imports: [
    ...
    RouterModule.forRoot(
      appRoutes, { enableTracing: false }
    )
  ],
```

* Maka seharusnya alamat di browsermu sekarang sudah menjadi: ```http://localhost:4200/dashboard```. Selanjutnya _cut_ bagian content di file __app.component.html__ dan _paste_ ke file __components/pages/dashboard.component.html__ dan ganti dengan perintah: ```<router-outlet></router-outlet>```.

* Angular Routing sudah berfungsi, yang harus diperhatikan adalah ganti semua perintah __a href=""__ dengan __a routerLink="/path"__ seperti contoh berikut: ```<a routerLink="/login">Beranda</a>```

* Untuk berpindah halaman dari file typescript maka bisa menggunakan perintah seperti berikut:

```ts
// pastikan router sudah dimasukkan di constructor
this.router.navigate(['/home']);
```


* Pada umumnya halaman login tidak membutuhkan header dan sidebar sehingga kita perlu memodifikasi file __app.component.html__ menjadi seperti berikut:

```html
<!-- Begin page -->
<div id="wrapper" *ngIf="getPageUrl() !== 'login'">

  <!-- Top Bar Start -->
  <app-header></app-header>
  <!-- Top Bar End -->

  <!-- ========== Left Sidebar Start ========== -->
  <app-sidebar></app-sidebar>
  <!-- Left Sidebar End -->

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <router-outlet></router-outlet>

  <!-- ============================================================== -->
  <!-- End Right content here -->
  <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- UNTUK LOGIN -->
<div *ngIf="getPageUrl() === 'login'">
  <router-outlet></router-outlet>
</div>
```

* Disitu kita menggunakan fungsi __getPageUrl()__ yang mendeteksi url yang sedang aktif. Kita perlu membuat fungsi tersebut di file __app.component.ts__:

```ts
...
import { Router } from '@angular/router';

...
  constructor(
    private router: Router
  ) {}

  getPageUrl() {
    return this.router.url;
  }
}

```

* Pindahkan alamat url ke ```http://localhost:4200/login``` maka seharusnya muncul tampilan login tanpa header dan sidebar

### Menggunakan Apollo GraphQL

### Angular Template Form




