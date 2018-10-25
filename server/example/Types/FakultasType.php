<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class FakultasType extends ObjectType {
  public $description = 'Data Fakultas';
  
  /** QUERY */
  public function __construct() {
    $config = [
      'name' => 'Fakultas',
      'description' => $this->description,

      // edit bagian fields untuk menambah / menghapus fields
      'fields' => [
        'id' => Type::int(),
        'nama' => Type::string(),
        'dekan' => [
          'type' => Types::getType('dosen'),
          'resolve' => function($values, $args, $context) {
            \extract($values);
            \extract($context);
            $result = $qb->table('jabatandekan')->find($id, 'id_fakultas');
            return Types::getType('dosen')->resolve(['values' => $result->id_dosen, 'args' => $args, 'context' => $context]);
          }
        ]
      ]

    ];
    parent::__construct($config);
  }

  public function getArguments() {
    return [
      'id' => [
        'type' => Type::int(),
        'description' => 'ID Fakultas',
        'defaultValue' => 0
      ]
    ];
  }

  public function resolve(array $params) {
    \extract($params);

    \extract($args);
    \extract($context);
    if ( ! \is_null($values) && \is_numeric($values)) {
      $id = $values;
    }

    return $this->map($qb->table('fakultas')->find($id, 'id_fakultas'));
  }

  public function resolveRows(array $params) {
    \extract($params);
    \extract($context);

    $query = $qb->table('fakultas')->select('*');
    $rows = $query->get();
    $result = [];
    if ( ! empty($rows)) {
      foreach ($rows as $row) {
        $result[] = $this->map($row);
      }
    }
    return $result;
  }

  public function map($item = null) {
    if (\is_null($item)) return null;
    return [
      'id' => $item->id_fakultas,
      'nama' => $item->nama_fakultas
    ];
  }

  /** MUTATION */
  public function getNewInputArguments() {
    $input = new InputObjectType([
      'name' => 'TambahFakultasInput',
      'fields' => [
        'nama' => Type::nonNull(Type::string())
      ]
    ]); 
    return [
      'fakultas' => Type::nonNull($input)
    ];
  }

  public function getUpdateInputArguments() {
    $input = new InputObjectType([
      'name' => 'UpdateFakultasInput',
      'fields' => [
        'id' => Type::nonNull(Type::int()),
        'data' => new InputObjectType([
          'name' => 'DataUpdateFakultasInput',
          'fields' => [
            'nama' => Type::nonNull(Type::string()),
            'dekan' => Type::int()
          ]
        ])
      ]
    ]);
    return [
      'fakultas' => Type::nonNull($input)
    ];
  }

  public function getOutputType($type) {
    return new ObjectType([
      'name' => \ucfirst($type) . 'Fakultas', // TambahFakultas atau UpdateFakultas
      'description' => "Output $type fakultas",
      'fields' => [
        'status' => Type::boolean(),
        'errors' => Type::listOf(Type::string()),
        'fakultas' => Types::getType('fakultas')
      ]
    ]);
  }

  public function addFakultas(array $params) {
    \extract($params);
    \extract($args);
    \extract($context);

    $nama = $fakultas['nama'];
    $sukses = true;
    $errors = [];
    // cek nama fakultas harus diisi
    if (empty($nama) || \strlen($nama) < 3) {
      $sukses = false;
      $errors[] = 'Nama Fakultas Harus Diisi!';
    }
    // cek apakah sudah ada di database
    $query = $qb->table('fakultas')->where('nama_fakultas', 'LIKE', "%{$nama}%");
    $dbfakultas = $query->first();
    if ( ! empty($dbfakultas)) {
      $sukses = false;
      $errors[] = 'Fakultas Sudah Ada!';
    }

    // jika ! $sukses
    if ( ! $sukses) {
      return [
        'status' => $sukses,
        'errors' => $errors,
        'fakultas' => null
      ];
    }

    // insert
    $insertId = $qb->table('fakultas')->insert([
      'id_fakultas' => 0,
      'nama_fakultas' => $nama
    ]);

    return [
      'status' => $sukses,
      'errors' => $errors,
      'fakultas' => Types::getType('fakultas')->resolve(['values' => $insertId, 'args' => $args, 'context' => $context])
    ];
  }

  public function updateFakultas(array $params) {
    \extract($params);
    \extract($args);
    \extract($context);


  }

  public function deleteFakultas(array $params) {
    \extract($params);
    \extract($args);
    \extract($context);


  }
}