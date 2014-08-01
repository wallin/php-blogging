<?php

class Blogpost_Storage
{
  public $path;
  public $createdAt;
  public $updatedAt;
  public $content;

  public function __construct($path, $content = null)
  {
    $this->path    = $path;
    $this->content = $content;
  }

  public function delete()
  {
    $path = $this->path;
    if (!file_exists($path)) {
      throw new Exception("File does not exist: '$path'");
    }
    $success = unlink($path);

    if (!$success) {
      throw new Exception("Could not remove file: '$path'");
    }
    return true;
  }

  public function read()
  {
    $path = $this->path;
    if (!file_exists($path)) {
      throw new Exception("File does not exist: '$path'");
    }
    if (!is_readable($path)) {
      throw new Exception("File is not readable: '$path'");
    }

    $this->createdAt = filemtime($path);
    $this->updatedAt = filectime($path);
    $this->content = file_get_contents($path);

    return $this;
  }

  public function write($content)
  {
    $bytes = file_put_contents($this->path, $content);
    if ($bytes == false) {
      throw new Exception("File is not writable: '$path'");
    }
    $this->read();
    return $bytes;
  }
}