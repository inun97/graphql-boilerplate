# GraphQL Client Example (Angular)

## Instalasi

Disini kita menggunakan Angular CLI untuk membuat project baru dengan perintah:

```sh
ng new NamaProject blank --style=scss
```

Bagian __NamaProject__ bisa diganti sesuai nama project yang Anda inginkan. Tunggu beberapa saat hingga proses instalasi selesai lalu masukkan perintah berikut:

```sh
cd NamaProject
ng serve --open
```

Sebuah jendela browser akan terbuka dengan fitur _livereload_. Jika Anda ingin menggunakan bootstrap atau framework css lain maka Anda dapat menginstallnya seperti contoh berikut:

```sh
npm install --save bootstrap jquery popper.js
```

Masukkan bootstrap scss kedalam file style umum/global yaitu file __src/styles/scss__ seperti berikut:

```scss
@import '~bootstrap/scss/bootstrap';
```

Selanjutnya install apollo dan graphql client dengan perintah berikut:

```sh
npm install --save apollo-angular apollo-angular-link-http apollo-link apollo-client apollo-cache-inmemory graphql-tag graphql
```

Masukkan variabel __apiUrl__ yang berisi _endpoint_ dari GraphQL server ke environment di file __src/environments/environment.prod.ts__ dan __src/environments/environment.ts__ seperti contoh berikut:

```ts
...
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8181/graphql'
};
...
```

### Troubleshooting

```ERROR in node_modules/apollo-angular/types.d.ts(9,58): error TS2304: Cannot find name 'Exclude'.```

* Edit file __tsconfig.json__ dibagian __lib__ ditambah ```"esnext.asynciterable"```:

```json
...
    "lib": [
      "es2017",
      "dom",
      "esnext.asynciterable"
    ]
...
```
* Upgrade typescript ke versi 2.8.3 dengan perintah:

```sh
npm install --save-dev typescript@^2.8.3
```

## Setup

Buat module __graphql__ dengan perintah:

```sh
ng generate module graphql
```

Kemudian edit file __src/app/graphql/graphql.module.ts__ menjadi seperti berikut:

```ts
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { GraphqlModule } from './graphql/graphql.module';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    GraphqlModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
```




 

