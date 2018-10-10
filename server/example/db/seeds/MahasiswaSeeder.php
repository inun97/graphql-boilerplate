<?php


use Phinx\Seed\AbstractSeed;

class MahasiswaSeeder extends AbstractSeed
{
    public function run()
    {
        // mahasiswa
        $faker = Faker\Factory::create('id_ID');
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $data[] = $this->generate_mahasiswa($faker, $i);
            }
        }

        $mahasiswa = $this->table('mahasiswa');
        $mahasiswa->insert($data)->save();
    }

    private $nomor = 0;
    private function generate_mahasiswa($faker, $prodi) {
        $this->nomor += 1;
        return [ 'id_mahasiswa' => $this->nomor, 'id_prodi' => $prodi, 'nim_mahasiswa' => $faker->ean8, 'nama_mahasiswa' => $faker->name, 'alamat_mahasiswa' => $faker->address, 'tempat_lahir_mahasiswa' => $faker->city, 'tanggal_lahir_mahasiswa' => $faker->date($format = 'Y-m-d', $max = 'now') ];
    }
}
