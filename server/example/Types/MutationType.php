<?php
namespace Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class MutationType extends ObjectType {
  public function __construct() {
    $config = [
      
    ];
    parent::__construct($config);
  }
}