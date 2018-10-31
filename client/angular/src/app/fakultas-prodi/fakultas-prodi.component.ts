import { Component, OnInit } from '@angular/core';
import { Apollo } from 'apollo-angular';
import gql from 'graphql-tag';
import { faEdit, faTrashAlt, faList, faPlusCircle, faChevronLeft, faSave } from '@fortawesome/free-solid-svg-icons';
import swal from 'sweetalert2';

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

  loading = true;
  error: any;
  viewState = 'fakultas';

  currentFakultas: any;
  fakultasList: any[];
  fakultas: FakultasModel;
  prodiList: any[];
  dosenList: any[];

  constructor(private apollo: Apollo) { }

  queryFakultasList() {
    return gql`
      query {
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
  }
  loadFakultas(): void {
    this.apollo.watchQuery<any>({
      query: this.queryFakultasList()
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.fakultasList = result.data.fakultasList;
    });
  }

  queryProdiList() {
    return gql`
      query {
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
  }
  loadProdi(): void {
    this.apollo.watchQuery<any>({
      query: this.queryProdiList()
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.prodiList = result.data.prodiList.prodi;
    });
  }

  loadDosen(id: number, type: string) {
    const dosen = gql`
      query {
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

  saveFakultas() {
    let save = null;
    if (this.fakultas.id === 0) {
      save = gql`
        mutation {
          tambahFakultas(fakultas: { nama: "${this.fakultas.nama}" }) {
            status
            errors
          }
        }
      `;
    } else {
      const data = [`nama: "${this.fakultas.nama}"`];
      if (this.fakultas.dekan > 0) {
        data.push(`dekan: ${this.fakultas.dekan}`);
      }
      save = gql`
        mutation {
          updateFakultas(fakultas: { id: ${this.fakultas.id}, data: { ${data.join(', ')} } }) {
            status
            errors
          }
        }
      `;
    }
    this.apollo.mutate({
      mutation: save, refetchQueries: [{ query: this.queryFakultasList() }]
    }).subscribe(result => {
      const field = this.fakultas.id === 0 ? 'tambahFakultas' : 'updateFakultas';
      if (result.data[field].status) {
        this.viewState = 'fakultas';
        swal('Tersimpan!', 'Fakultas berhasil disimpan', 'success');
      }
    });
  }

  deleteFakultas(id: number) {
    swal({
      title: 'Apakah Anda yakin?',
      text: 'Fakultas yang dihapus tidak dapat dikembalikan!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, saya yakin!',
      cancelButtonText: 'Batalkan'
    }).then(result => {
      if (result.value) {
        this.apollo.mutate({
          mutation: gql`
            mutation {
              hapusFakultas(id: ${id})
            }
          `, refetchQueries: [{ query: this.queryFakultasList() }]
        }).subscribe(rest => {
          if (rest) {
            swal('Terhapus!', 'Fakultas berhasil dihapus', 'success');
          }
        });
      }
    });
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
