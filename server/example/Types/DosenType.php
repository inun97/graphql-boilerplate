<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class DosenType extends ObjectType {
  public $description = 'Data Dosen';
  
  public function __construct() {
    $config = [
      'name' => 'Dosen',
      'description' => $this->description,

      'fields' => [
        'id' => Type::int(),
        'nidn' => Type::string(),
        'nama' => Type::string(),
        'alamat' => Type::string(),
        'tempat_lahir' => Type::string(),
        'tanggal_lahir' => [
          'type' => Type::string(),
          'args' => [
            'format' => [
              'type' => Type::string(),
              'description' => 'Format tanggal diminta',
              'defaultValue' => 'Y-m-d'
            ]
          ],
          'resolve' => function($value, $args) {
            return \Helper\DateHelper::dateDB2Tanggal($value['tanggal_lahir'], $args['format']);
          }
        ],
        'prodi' => [
          'type' => Types::getType('prodi'),
          'resolve' => function($values, $args, $context) {
            return Types::getType('prodi')->resolve(['values' => $values['prodi'], 'args' => $args, 'context' => $context]);
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
        'description' => 'ID Dosen',
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

    return $this->map($qb->table('dosen')->find($id, 'id_dosen'));
  }

  public function map($item = null) {
    if (\is_null($item)) return null;
    return [
      'id' => $item->id_dosen,
      'prodi' => $item->id_prodi,
      'nidn' => $item->nidn_dosen,
      'nama' => $item->nama_dosen,
      'alamat' => $item->alamat_dosen,
      'tempat_lahir' => $item->tempat_lahir_dosen,
      'tanggal_lahir' => $item->tanggal_lahir_dosen
    ];
  }
}