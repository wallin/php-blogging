<?php

class Blogpost
{

  public static $basePath = './posts';
  public static $defaultExtension = 'md';
  public static $defaultFileFormat = '{year}-{month}-{slug}';


  protected $id = null;
  protected $attributes = array();
  protected $output = null;
  protected $source = '';
  protected $file = null;

  public function __get($key)
  {
    return $this->getAttribute($key);
  }

  public function __set($key, $value)
  {
    $this->setAttribute($key, $value);
  }

  public function __tostring()
  {
    return $this->output;
  }

  public function getAttribute($key)
  {
    if (array_key_exists($key, $this->attributes))
    {
      return $this->attributes[$key];
    }
    return null;
  }

  public function setAttribute($key, $value)
  {
    if (is_null($value)) {
      unset($this->attributes[$key]);
    }
    else {
      $this->attributes[$key] = $value;
    }
  }

  public function getAttributes()
  {
    return $this->attributes;
  }
  public function setAttributes($attributes)
  {
    if (!isset($attributes)) return;
    foreach ($attributes as $key => $value) {
      $this->setAttribute($key, $value);
    }
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getOutput()
  {
    return $this->output;
  }

  public function setOutput($output)
  {
    $this->output = $output;
  }

  public function getSource()
  {
    return $this->source;
  }

  public function setSource($source)
  {
    $this->source = $source;
  }

  public function getFile()
  {
    return $this->file;
  }

  public function setFile($file)
  {
    $this->file = $file;
  }


  public function render()
  {
    $this->output = $this->source;
    Blogpost_Middleware::process('render', $this);
    return $this;
  }

  public function isPublished()
  {
    return !!$this->published;
  }

  public function publish()
  {

  }

  public static function create($attrs, $content)
  {
    $post = new self($content, $attrs);
    $post->save();
    return $post;
  }

  public static function all()
  {
    $posts = self::find();
    return $posts->result();
  }

  public static function find($slug = null)
  {
    if (!empty($slug)){
      return self::read(self::idToPath($slug));
    }
    return new Blogpost_Query(self::$basePath, self::$defaultExtension);
  }


  public static function read($path)
  {
    $file = new Blogpost_Storage($path);
    $file->read();
    $post = new self();
    $post->setSource($file->content);
    $post->setFile($file);
    $basename = basename($path, ".".self::$defaultExtension);
    $post->setId($basename);
    Blogpost_Middleware::process('read', $post);
    return $post;
  }

  public function destroy()
  {
    $file = $this->getFile();
    $file->delete();
    $this->setFile(null);
  }

  public function save()
  {
    Blogpost_Middleware::process('write', $this);
    $currentId = $this->getId();
    if (empty($currentId)) {
      throw new Exception('Missing id');
    }
    $file = $this->getFile();
    if (!$file) {
      $file = new Blogpost_Storage(self::idToPath($this->getid()));
    }
    $file->write($this->getSource());
    Blogpost_Middleware::process('read', $this);
  }

  public static function idToPath($id)
  {
    return join(DIRECTORY_SEPARATOR, array(self::$basePath, $id.'.'.self::$defaultExtension));
  }
}