<?php
namespace Types;

use \GraphQL\Type\Definition\Type;

trait Types {
  private static $types = [];

  public static function getType($type) {
    if ( ! \array_key_exists($type, static::$types)) {
      $className = __NAMESPACE__ . '\\' . \ucfirst($type) . 'Type';
      static::$types[$type] = new $className();
    }
    return static::$types[$type];
  }
}