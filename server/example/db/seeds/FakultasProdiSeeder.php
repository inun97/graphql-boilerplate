<?php


use Phinx\Seed\AbstractSeed;

class FakultasProdiSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [
            [ 'id_fakultas' => 1, 'nama_fakultas' => 'Hukum' ],
            [ 'id_fakultas' => 2, 'nama_fakultas' => 'Ekonomi' ],
            [ 'id_fakultas' => 3, 'nama_fakultas' => 'Ilmu Administrasi' ],
            [ 'id_fakultas' => 4, 'nama_fakultas' => 'Pertanian' ],
            [ 'id_fakultas' => 5, 'nama_fakultas' => 'Teknik' ],
            [ 'id_fakultas' => 6, 'nama_fakultas' => 'Keguruan dan Ilmu Pendidikan' ],
        ];
        $fakultas = $this->table('fakultas');
        $fakultas->insert($data)->save();

        $data = [
            [ 'id_prodi' => 1, 'id_fakultas' => 1, 'nama_prodi' => 'Ilmu Hukum', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 2, 'id_fakultas' => 2, 'nama_prodi' => 'Manajemen', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 3, 'id_fakultas' => 2, 'nama_prodi' => 'Akuntansi', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 4, 'id_fakultas' => 3, 'nama_prodi' => 'Ilmu Administrasi Negara', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 5, 'id_fakultas' => 4, 'nama_prodi' => 'Peternakan', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 6, 'id_fakultas' => 5, 'nama_prodi' => 'Teknik Sipil', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 7, 'id_fakultas' => 5, 'nama_prodi' => 'Teknik Informatika', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 8, 'id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Indonesia', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 9, 'id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Matematika', 'jenjang_prodi' => 'S1' ],
            [ 'id_prodi' => 10, 'id_fakultas' => 6, 'nama_prodi' => 'Pendidikan Bahasa Inggris', 'jenjang_prodi' => 'S1' ],
        ];
        $prodi = $this->table('prodi');
        $prodi->insert($data)->save();
    }
}
