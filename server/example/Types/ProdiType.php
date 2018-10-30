<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProdiType extends ObjectType {
  public $description = 'Data Prodi';
  
  public function __construct() {
    $config = [
      'name' => 'Prodi',
      'description' => $this->description,

      // edit bagian fields untuk menambah / menghapus fields

      // PERHATIAN: Khusus untuk prodi karena ketua prodi juga memiliki prodi maka 
      // bisa mengakibatkan infinite link, karenanya fields harus return function
      'fields' => function() {
        return [
          'id' => Type::int(),
          'nama' => Type::string(),
          'fakultas' => [
            'type' => Types::getType('fakultas'),
            'resolve' => function($values, $args, $context) {
              return Types::getType('fakultas')->resolve(['values' => $values['fakultas'], 'args' => $args, 'context' => $context]);
            }
          ],
          'jenjang' => Type::string(),
          'ketua' => [
            'type' => Types::getType('dosen'),
            'resolve' => function($values, $args, $context) {
              \extract($context);
              \extract($values);
              $result = $qb->table('jabatankaprodi')->find($id, 'id_prodi');
              if (empty($result)) return null;
              return Types::getType('dosen')->resolve(['values' => $result->id_dosen, 'args' => $args, 'context' => $context]);
            }
          ]
        ];
      }
    ];
    parent::__construct($config);
  }

  public function getArguments() {
    return [
      'id' => [
        'type' => Type::int(),
        'description' => 'ID Prodi',
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

    return $this->map($qb->table('prodi')->find($id, 'id_prodi'));
  }

  public function map($item = null) {
    if (\is_null($item)) return null;
    return [
      'id' => $item->id_prodi,
      'nama' => $item->nama_prodi,
      'fakultas' => $item->id_fakultas,
      'jenjang' => $item->jenjang_prodi
    ];
  }
}