<?php

class Blogpost_Query
{
  public static $defaultExtension = '';

  protected $limit = 50;
  protected $offset = 0;
  protected $order = 'desc';
  protected $sort  = 'timestamp';
  protected $where = null;

  public $posts = null;
  public $extension = null;
  public $path  = null;

  public function __construct($path, $extension = null)
  {
    $this->path = $path;
    if (empty($extension)) {
      $extension = self::$defaultExtension;
    }
    $this->extension = $extension;
    return $this;
  }

  public function limit($number)
  {
    $this->limit = $number;
    return $this;
  }

  public function offset($number)
  {
    $this->offset = $number;
    return $this;
  }

  public function order($sort, $order = null)
  {
    $this->sort = $sort;
    if (!empty($order)) {
      $this->order = $order;
    }
    return $this;
  }

  public function result()
  {
    $files = glob($this->path."/*.".$this->extension);
    $this->posts = array();
    foreach ($files as $path) {
      // Process where conditions
      $post = Blogpost::read($path);
      if (!$this->where) {
        $this->posts[]=$post;
        continue;
      }
      $matches = 0;
      foreach ($this->where as $key => $value) {
        if ($post->getAttribute($key) == $value) {
          $matches++;
        }
      }
      if ($matches == count($this->where)) {
        $this->posts[]=$post;
      }
    }

    // Process ordering
    usort($this->posts, create_function('$a,$b', 'return $b->getAttribute("'.$this->sort.'") - $a->getAttribute("'.$this->sort.'");'));
    if ($this->order == 'asc') {
      $this->posts = array_reverse($this->posts);
    }

    // Limit + Offset
    $this->posts = array_slice($this->posts, $this->offset, $this->limit);
    return $this->posts;
  }

  public function where($key, $value)
  {
    if ($this->where == null) {
      $this->where = array();
    }
    $this->where[$key] = $value;
    return $this;
  }
}