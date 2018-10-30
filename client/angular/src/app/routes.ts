import { Routes } from '@angular/router';

import { FakultasProdiComponent } from './fakultas-prodi/fakultas-prodi.component';
import { DosenComponent } from './dosen/dosen.component';
import { MahasiswaComponent } from './mahasiswa/mahasiswa.component';
import { HomeComponent } from './home/home.component';

/**
 * Simple routes
 */
export const appRoutes: Routes = [
  { path: 'fakultasprodi', component: FakultasProdiComponent },
  { path: 'dosen', component: DosenComponent },
  { path: 'mahasiswa', component: MahasiswaComponent },
  { path: 'home', component: HomeComponent },
  { path: '', redirectTo: '/home', pathMatch: 'full' }
];

