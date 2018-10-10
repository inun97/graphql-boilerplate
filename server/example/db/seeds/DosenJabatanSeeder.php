<?php


use Phinx\Seed\AbstractSeed;

class DosenJabatanSeeder extends AbstractSeed
{   
    public function getDependencies() {
        return [
            'FakultasProdiSeeder'
        ];
    }

    public function run()
    {
        // dosen
        $faker = Faker\Factory::create('id_ID');
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $data[] = $this->generate_dosen($faker, $i);
            }
        }

        $dosen = $this->table('dosen');
        $dosen->insert($data)->save();

        // jabatan kaprodi
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $kaprodi = $this->fetchRow("SELECT id_dosen FROM dosen WHERE id_prodi = {$i} ORDER BY RAND() LIMIT 0, 1");
            $data[] = [
                'id_jabatankaprodi' => $i, 'id_prodi' => $i, 'id_dosen' => $kaprodi['id_dosen']
            ];
        }
        $jabatankaprodi = $this->table('jabatankaprodi');
        $jabatankaprodi->insert($data)->save();

        // jabatan dekan
        $data = [];
        for ($i = 1; $i <= 6; $i++) {
            $dekan = $this->fetchRow("SELECT dosen.id_dosen FROM dosen, prodi WHERE dosen.id_prodi = prodi.id_prodi AND prodi.id_fakultas = {$i} ORDER BY RAND() LIMIT 0, 1");
            $data[] = [
                'id_jabatandekan' => $i, 'id_fakultas' => $i, 'id_dosen' =>  $dekan['id_dosen']
            ];
        }
        $jabatandekan = $this->table('jabatandekan');
        $jabatandekan->insert($data)->save();
    }

    private $nomor = 0;
    private function generate_dosen($faker, $prodi) {
        $this->nomor += 1;
        return [ 'id_dosen' => $this->nomor, 'id_prodi' => $prodi,
            'nidn_dosen' => $faker->ean8, 'nama_dosen' => $faker->name, 'alamat_dosen' => $faker->address, 'tempat_lahir_dosen' => $faker->city, 'tanggal_lahir_dosen' => $faker->date($format = 'Y-m-d', $max = 'now') ];
    }
}
