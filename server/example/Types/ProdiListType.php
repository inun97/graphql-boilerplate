<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProdiListType extends ObjectType {
  public $description = 'Daftar Program Studi';

  public function __construct() {
    $config = [
      'name' => 'ProdiList',
      'description' => $this->description,
      'fields' => [
        'total' => Type::int(),
        'prodi' => Type::listOf(Types::getType('prodi'))
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
      'fakultas' => [
        'type' => Type::int(),
        'description' => 'Fakultas untuk Prodi',
        'defaultValue' => 0
      ],
      'search' => [
        'type' => Type::string(),
        'description' => 'Kata pencarian Prodi',
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
      'prodi' => []
    ];

    \extract($params);
    \extract($args);
    \extract($context);

    $where = $params = [];
    // filtering berdasarkan fakultas dan search
    if ( ! empty($fakultas)) {
      $where[] = "id_fakultas = ?"; $params[] = $fakultas;
    }
    if ( ! empty($search)) {
      $where[] = "nama_prodi LIKE ?"; $params[] = "%{$search}%";
    }

    // total data
    $sql = "SELECT COUNT(id_prodi) AS total FROM prodi" . ( ! empty($where) ? " WHERE " . implode(" AND ", $where) : '');
    $result['total'] = $qb->query($sql, $params)->get()[0]->total;

    // limit
    $start = ($page - 1) * $limit;

    // sort
    $order = [];
    foreach (explode(',', $sort) as $col) {
      $col = trim($col);
      $ord = 'ASC';
      // huruf pertama - berarti desc
      if ($col[0] === '-') {
        $col = \substr($col, 1);
        $ord = 'DESC';
      }

      switch ($col) {
        case 'id':
          $order[] = "id_prodi $ord"; break;
        case 'nama':
          $order[] = "nama_prodi $ord"; break;
        case 'fakultas':
          $order[] = "id_fakultas $ord"; break;
      }
    }

    $sql = "SELECT * FROM prodi" . ( ! empty($where) ? " WHERE " . implode(" AND ", $where) : '') . " ORDER BY " . implode(", ", $order) . " LIMIT $start, $limit";
    $rows = $qb->query($sql, $params)->get();
    if ( ! empty($rows)) {
      foreach ($rows as $row) {
        $result['prodi'][] = Types::getType('prodi')->map($row);
      }
    }

    return $result;
  }
}