import { Component, OnInit } from '@angular/core';
import { faPlusCircle, faSearch } from '@fortawesome/free-solid-svg-icons';
import { Apollo } from 'apollo-angular';
import gql from 'graphql-tag';

class SearchModel {
  constructor(
    public nama: string,
    public prodi: number,
    public page: number,
    public limit: number,
    public total: number
  ) { }
}

@Component({
  selector: 'app-dosen',
  templateUrl: './dosen.component.html',
  styleUrls: ['./dosen.component.scss']
})
export class DosenComponent implements OnInit {
  faPlus = faPlusCircle;
  faSearch = faSearch;

  constructor(private apollo: Apollo) { }

  loading = true;
  error: any;

  search: SearchModel = new SearchModel('', 0, 1, 7, 0);
  prodiList: any[];
  dosenList: any[];

  pageRange(): any {
    const totalPage = Math.ceil(this.search.total / this.search.limit);
    return Array.apply(null, { length: totalPage }).map(Number.call, Number);
  }

  loadProdi(): void {
    this.apollo.watchQuery<any>({
      query: gql`
        query {
          prodiList {
            prodi {
              id
              nama
            }
          }
        }
      `
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.prodiList = result.data.prodiList.prodi;
    });
  }

  queryDosen() {
    return gql`
      query {
        dosenList(search: "${this.search.nama}", prodi: ${this.search.prodi}, page: ${this.search.page}, limit: ${this.search.limit}) {
          total
          dosen {
            nidn
            nama
            alamat
            tempat_lahir
            tanggal_lahir(format: "d F Y")
            prodi {
              nama
            }
          }
        }
      }
    `;
  }

  loadDosen(): void {
    this.apollo.watchQuery<any>({
      query: this.queryDosen()
    })
    .valueChanges.subscribe(result => {
      this.loading = result.loading;
      this.error = result.errors;
      this.dosenList = result.data.dosenList.dosen;
      this.search.total = result.data.dosenList.total;
    });
  }

  prevPage() {
    this.search.page -= 1;
    this.loadDosen();
  }
  gotoPage(page: number): void {
    this.search.page = page;
    this.loadDosen();
  }
  nextPage() {
    this.search.page += 1;
    this.loadDosen();
  }

  ngOnInit() {
    this.loadProdi();
    this.loadDosen();
  }
}
