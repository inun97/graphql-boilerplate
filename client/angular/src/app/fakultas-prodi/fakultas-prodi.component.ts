import { Component, OnInit, OnDestroy } from '@angular/core';
import { Apollo } from 'apollo-angular';
import gql from 'graphql-tag';
import { faEdit, faTrashAlt, faList, faPlusCircle, faChevronLeft, faSave } from '@fortawesome/free-solid-svg-icons';

class FakultasModel {
  constructor(
    public id: number,
    public nama: string,
    public dekan?: number
  ) { }
}

@Component({
  selector: 'app-fakultas-prodi',
  templateUrl: './fakultas-prodi.component.html',
  styleUrls: ['./fakultas-prodi.component.scss']
})
export class FakultasProdiComponent implements OnInit {
  faEdit = faEdit;
  faTrashAlt = faTrashAlt;
  faList = faList;
  faPlus = faPlusCircle;
  faChevronLeft = faChevronLeft;
  faSave = faSave;

  constructor(private apollo: Apollo) { }

  loading = true;
  error: any;
  currentFakultas: any;
  fakultasList: any[];
  fakultas: FakultasModel;
  prodiList: any[];
  dosenList: any[];
  viewState = 'fakultas';

  loadFakultas(): void {
    const fakultas = gql`
      {
        fakultasList {
          id
          nama
          dekan {
            id
            nama
          }
        }
      }
    `;
    this.apollo.watchQuery<any>({
      query: fakultas
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.fakultasList = result.data.fakultasList;
    });
  }

  loadProdi(): void {
    const prodi = gql`
      {
        prodiList(fakultas: ${this.currentFakultas.id}) {
          prodi {
            id
            nama
            ketua {
              id
              nama
            }
          }
        }
      }
    `;
    this.apollo.watchQuery<any>({
      query: prodi
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.prodiList = result.data.prodiList.prodi;
    });
  }

  loadDosen(id: number, type: string) {
    const dosen = gql`
      {
        dosenList(${type}: ${id}, limit: 500) {
          dosen {
            id
            nama
          }
        }
      }
    `;
    this.apollo.watchQuery<any>({
      query: dosen
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.dosenList = result.data.dosenList.dosen;
    });
  }

  editFakultas(fakultas?: any) {
    this.viewState = 'editfakultas';
    if (fakultas) {
      this.currentFakultas = fakultas;
      const dekan = fakultas.dekan ? fakultas.dekan.id : 0;
      this.fakultas = new FakultasModel(fakultas.id, fakultas.nama, dekan);
      this.loadDosen(fakultas.id, 'fakultas');
    } else {
      this.currentFakultas = null;
      this.fakultas = new FakultasModel(0, '', 0);
    }
  }

  viewProdi(fakultas: any) {
    this.viewState = 'prodi';
    this.currentFakultas = fakultas;
    this.loadProdi();
  }

  ngOnInit() {
    this.loadFakultas();
  }
}
