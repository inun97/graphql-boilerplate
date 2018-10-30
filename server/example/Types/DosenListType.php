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
      'fakultas' => [
        'type' => Type::int(),
        'description' => 'Fakultas dosen',
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
    // filtering berdasarkan prodi, fakultas dan search
    $where[] = "a.id_prodi = b.id_prodi";
    if ( ! empty($prodi)) {
      $where[] = "a.id_prodi = ?"; $params[] = $prodi;
    }
    if ( ! empty($fakultas)) {
      $where[] = "b.id_fakultas = ?"; $params[] = $fakultas;
    }
    if ( ! empty($search)) {
      $where[] = "(a.nama_dosen LIKE ? OR a.nidn_dosen LIKE ?)";
      $params = \array_merge(["%{$search}%", "%{$search}%"]);
    }

    // total data
    $sql = "SELECT COUNT(a.id_dosen) AS total FROM dosen a, prodi b" . ( ! empty($where) ? " WHERE " . implode(" AND ", $where) : '');
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
          $order[] = "a.id_dosen $ord"; break;
        case 'nama':
          $order[] = "a.nama_dosen $ord"; break;
        case 'prodi':
          $order[] = "a.id_prodi $ord"; break;
      }
    }

    $sql = "SELECT * FROM dosen a, prodi b" . ( ! empty($where) ? " WHERE " . implode(" AND ", $where) : '') . " ORDER BY " . implode(", ", $order) . " LIMIT $start, $limit";
    $rows = $qb->query($sql, $params)->get();
    if ( ! empty($rows)) {
      foreach ($rows as $row) {
        $result['dosen'][] = Types::getType('dosen')->map($row);
      }
    }

    return $result;
  }
}