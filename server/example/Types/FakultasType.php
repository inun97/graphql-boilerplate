<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class FakultasType extends ObjectType {
  public $description = 'Data Fakultas';
  
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
}