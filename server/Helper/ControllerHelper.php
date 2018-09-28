<?php
namespace Helper;

class ControllerHelper {
  private static $blade;

  public static function view(string $file, array $values = [], bool $display = true) {
    if (\is_null(self::$blade)) self::$blade = new \Helper\MyBlade('view', 'cache');

    $view = ( ! empty($values) ? self::$blade->view()->make($file, $values)->render() : self::$blade->view()->make($file)->render());
    if ($display) print $view;
    else return $view;
  }
}