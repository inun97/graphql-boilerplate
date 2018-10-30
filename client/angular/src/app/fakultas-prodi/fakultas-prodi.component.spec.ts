import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FakultasProdiComponent } from './fakultas-prodi.component';

describe('FakultasProdiComponent', () => {
  let component: FakultasProdiComponent;
  let fixture: ComponentFixture<FakultasProdiComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FakultasProdiComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FakultasProdiComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
