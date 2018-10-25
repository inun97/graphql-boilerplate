<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class MutationType extends ObjectType {
  public function __construct() {
    // instance types
    $fakultas = Types::getType('fakultas');
    
    $config = [
      'name' => 'Mutation',
      'fields' => [
        'tambahFakultas' => [
          'type' => $fakultas->getOutputType('tambah'),
          'description' => $fakultas->description,
          'args' => $fakultas->getNewInputArguments(),
          'resolve' => function($values, $args, $context) use($fakultas) {
            return $fakultas->addFakultas(['value' => $values, 'args' => $args, 'context' => $context]);
          }
        ],
        'updateFakultas' => [
          'type' => $fakultas->getOutputType('update'),
          'description' => $fakultas->description,
          'args' => $fakultas->getUpdateInputArguments(),
          'resolve' => function($values, $args, $context) use($fakultas) {
            return $fakultas->updateFakultas(['value' => $values, 'args' => $args, 'context' => $context]);
          }
        ],
        'hapusFakultas' => [
          'type' => Type::boolean(),
          'description' => 'Hapus fakultas',
          'args' => [
            'id' => Type::nonNull(Type::int())
          ],
          'resolve' => function($values, $args, $context) use($fakultas) {
            return $fakultas->deleteFakultas(['value' => $values, 'args' => $args, 'context' => $context]);
          }
        ]
      ]
    ];
    parent::__construct($config);
  }
}