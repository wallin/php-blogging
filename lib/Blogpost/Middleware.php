<?php

abstract class Blogpost_Middleware
{
  public static $wares = array();

  public static function insert($middleware, $prio = null)
  {
    if (!empty($prio)) {
      array_splice($wares, $prio, 0, $middleware);
    }
    else {
      array_push(self::$wares, $middleware);
    }
  }

  public static function remove($middleware = null)
  {
    if (empty($middleware)) {
      self::$wares = array();
    }
    if (($key = array_search($middleware, self::$wares)) !== false) {
        unset(self::$wares[$key]);
    }
  }

  public static function process($operation, &$data, $options = null)
  {
    $wares = array();
    foreach (self::$wares as $idx => $middleware) {
      $w = new $middleware();
      if (method_exists($w, 'priority')) {
        $prio = $w->priority($operation);
      }
      if (empty($prio)) {
        $prio = $idx;
      }
      $wares[$idx] = $w;
    }
    ksort($wares);
    foreach ($wares as $w) {
      $w->call($operation, $data, $options);
    }
  }
}