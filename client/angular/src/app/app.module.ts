import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { GraphqlModule } from './graphql/graphql.module';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { appRoutes } from './routes';
import { FakultasProdiComponent } from './fakultas-prodi/fakultas-prodi.component';
import { DosenComponent } from './dosen/dosen.component';
import { MahasiswaComponent } from './mahasiswa/mahasiswa.component';
import { HomeComponent } from './home/home.component';

@NgModule({
  declarations: [
    AppComponent,
    FakultasProdiComponent,
    DosenComponent,
    MahasiswaComponent,
    HomeComponent
  ],
  imports: [
    RouterModule.forRoot(
      appRoutes, { enableTracing: false }
    ),
    BrowserModule,
    FormsModule,
    GraphqlModule,
    FontAwesomeModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
