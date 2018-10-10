<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class DosenListType extends ObjectType {
  public $description = 'Daftar Dosen';

  public function __construct() {
    $config = [
      'name' => 'DosenList',
      'description' => $this->description,
      'fields' => [
        'total' => Type::int(),
        'dosen' => Type::listOf(Types::getType('dosen'))
      ]
    ];
    parent::__construct($config);
  }

  public function getArguments() {
    return [
      'limit' => [
        'type' => Type::int(),
        'description' => 'Jumlah data per page',
        'defaultValue' => 10
      ],
      'page' => [
        'type' => Type::int(),
        'description' => 'Halaman yang diminta',
        'defaultValue' => 1
      ],
      'prodi' => [
        'type' => Type::int(),
        'description' => 'Prodi dosen',
        'defaultValue' => 0
      ],
      'search' => [
        'type' => Type::string(),
        'description' => 'Kata pencarian Dosen',
        'defaultValue' => ''
      ],
      'sort' => [
        'type' => Type::string(),
        'description' => 'Request urutan data',
        'defaultValue' => 'id'
      ]
    ];
  }

  public function resolveRows(array $params) {
    $result = [
      'total' => 0,
      'dosen' => []
    ];

    \extract($params);

    \extract($args);
    \extract($context);

    $where = $params = [];
    // filtering berdasarkan prodi dan search
    if ( ! empty($prodi)) {
      $where[] = "id_prodi = ?"; $params[] = $prodi;
    }
    if ( ! empty($search)) {
      $where[] = "(nama_dosen LIKE ? OR nidn_dosen LIKE ?)";
      $params = \array_merge(["%{$search}%", "%{$search}%"]);
    }

    // total data
    

    return $result;
  }
}