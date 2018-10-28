import { Component, OnInit } from '@angular/core';

import { Apollo } from 'apollo-angular';
import gql from 'graphql-tag';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'app';
  constructor(private apollo: Apollo) {}

  ngOnInit() {
    const fakultas = gql`
      {
        fakultasList {
          id
          nama
        }
      }
    `;
    this.apollo.watchQuery({
      query: fakultas
    })
    .valueChanges.subscribe(result => {
      console.log(result.data);
    });
  }
}
