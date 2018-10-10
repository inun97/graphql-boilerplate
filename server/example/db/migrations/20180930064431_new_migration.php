<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class NewMigration extends AbstractMigration
{
    public function change()
    {
        $fakultas = $this->table('fakultas', ['id' => false, 'primary_key' => 'id_fakultas']);
        $fakultas->addColumn('id_fakultas', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                 ->addColumn('nama_fakultas', 'string', ['limit' => 100])
                 ->save();

        $prodi = $this->table('prodi', ['id' => false, 'primary_key' => 'id_prodi']);
        $prodi->addColumn('id_prodi', 'integer', ['limit' => MysqlAdapter::INT_TINY])
              ->addColumn('id_fakultas', 'integer', ['limit' => MysqlAdapter::INT_TINY])
              ->addColumn('nama_prodi', 'string', ['limit' => 100])
              ->addColumn('jenjang_prodi', 'string', ['limit' => 40])
              ->addForeignKey('id_fakultas', 'fakultas', 'id_fakultas', $this->myConstraint('fakultas_prodi'))
              ->save();

        $dosen = $this->table('dosen', ['id' => false, 'primary_key' => 'id_dosen']);
        $dosen->addColumn('id_dosen', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
              ->addColumn('id_prodi', 'integer', ['limit' => MysqlAdapter::INT_TINY])
              ->addColumn('nidn_dosen', 'string', ['limit' => 20])
              ->addColumn('nama_dosen', 'string', ['limit' => 100])
              ->addColumn('alamat_dosen', 'string', ['limit' => 255])
              ->addColumn('tempat_lahir_dosen', 'string', ['limit' => 40])
              ->addColumn('tanggal_lahir_dosen', 'date')
              ->addIndex(['nidn_dosen'], ['unique' => true])
              ->addForeignKey('id_prodi', 'prodi', 'id_prodi', $this->myConstraint('prodi_dosen'))
              ->save();

        $jabatandekan = $this->table('jabatandekan', ['id' => false, 'primary_key' => 'id_jabatandekan']);
        $jabatandekan->addColumn('id_jabatandekan', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                     ->addColumn('id_fakultas', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                     ->addColumn('id_dosen', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
                     ->addForeignKey('id_fakultas', 'fakultas', 'id_fakultas', $this->myConstraint('fakultas_jabatandekan'))
                     ->addForeignKey('id_dosen', 'dosen', 'id_dosen', $this->myConstraint('dosen_jabatandekan'))
                     ->save();

        $jabatankaprodi = $this->table('jabatankaprodi', ['id' => false, 'primary_key' => 'id_jabatankaprodi']);
        $jabatankaprodi->addColumn('id_jabatankaprodi', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                       ->addColumn('id_prodi', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                       ->addColumn('id_dosen', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
                       ->addForeignKey('id_prodi', 'prodi', 'id_prodi', $this->myConstraint('prodi_jabatankaprodi'))
                       ->addForeignKey('id_dosen', 'dosen', 'id_dosen', $this->myConstraint('dosen_jabatankaprodi'))
                       ->save();

        $mahasiswa = $this->table('mahasiswa', ['id' => false, 'primary_key' => 'id_mahasiswa']);
        $mahasiswa->addColumn('id_mahasiswa', 'integer', ['limit' => MysqlAdapter::INT_BIG])
                  ->addColumn('id_prodi', 'integer', ['limit' => MysqlAdapter::INT_TINY])
                  ->addColumn('nim_mahasiswa', 'string', ['limit' => 20])
                  ->addColumn('nama_mahasiswa', 'string', ['limit' => 100])
                  ->addColumn('alamat_mahasiswa', 'string', ['limit' => 255])
                  ->addColumn('tempat_lahir_mahasiswa', 'string', ['limit' => 40])
                  ->addColumn('tanggal_lahir_mahasiswa', 'date')
                  ->addIndex(['nim_mahasiswa'], ['unique' => true])
                  ->addForeignKey('id_prodi', 'prodi', 'id_prodi', $this->myConstraint('prodi_mahasiswa'))
                  ->save();
    }

    private function myConstraint($constraint) {
        return ['delete' => 'RESTRICT', 'update' => 'CASCADE', 'constraint' => $constraint];
    }
}
