<?php
namespace Types;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;

class QueryType extends ObjectType {
  public function __construct() {
    // instance types
    $fakultas = Types::getType('fakultas');
    $prodi = Types::getType('prodi');
    $prodiList = Types::getType('prodiList');
    $dosen = Types::getType('dosen');
    $dosenList = Types::getType('dosenList');

    $config = [
      'name' => 'Query',
      'fields' => [
        'hello' => Type::string(),

        'fakultas' => [
          'type' => $fakultas,
          'description' => $fakultas->description,
          'args' => $fakultas->getArguments(),
          'resolve' => function($values, $args, $context) use($fakultas) {
            return $fakultas->resolve(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],
        'fakultasList' => [
          'type' => Type::listOf($fakultas),
          'description' => 'Data Semua Fakultas',
          'resolve' => function($values, $args, $context) use($fakultas) {
            return $fakultas->resolveRows(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],

        'prodi' => [
          'type' => $prodi,
          'description' => $prodi->description,
          'args' => $prodi->getArguments(),
          'resolve' => function($values, $args, $context) use($prodi) {
            return $prodi->resolve(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],
        'prodiList' => [
          'type' => $prodiList,
          'description' => $prodiList->description,
          'args' => $prodiList->getArguments(),
          'resolve' => function($values, $args, $context) use($prodiList) {
            return $prodiList->resolveRows(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],

        'dosen' => [
          'type' => $dosen,
          'description' => $dosen->description,
          'args' => $dosen->getArguments(),
          'resolve' => function($values, $args, $context) use($dosen) {
            return $dosen->resolve(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],
        'dosenList' => [
          'type' => $dosenList,
          'description' => $dosenList->description,
          'args' => $dosenList->getArguments(),
          'resolve' => function($values, $args, $context) use($dosenList) {
            return $dosenList->resolveRows(['values' => $values, 'args' => $args, 'context' => $context]);
          }
        ],

        // 'mahasiswa' => [
        //   'type' => Types::getType('mahasiswa'),
        //   'description' => Types::getType('mahasiswa')->description,
        //   'args' => Types::getType('mahasiswa')->getArguments(),
        //   'resolve' => function($values, $args, $context) {
        //     return Types::getType('mahasiswa')->resolve(['values' => $values, 'args' => $args, 'context' => $context]);
        //   }
        // ]

      ]
    ];
    parent::__construct($config);
  }
}