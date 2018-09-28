<?php
namespace Types;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \GraphQL\Type\Definition\EnumType;

class QueryType extends ObjectType {
  public function __construct() {
    $config = [
      'name' => 'Query',
      'fields' => [
        'hello' => Type::string()
      ]
    ];
    parent::__construct($config);
  }
}