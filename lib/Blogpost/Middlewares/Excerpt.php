<?php

class Blogpost_Excerpt
{
  public function truncate($text, $length = 255)
  {
    $text = strip_tags($text);
    if (strlen($text) > $length) {
      $text = substr($text, 0, 252);
      $text.= '&hellip;';
    }
    return $text;
  }

  public function call($operation, $post, $options)
  {
    switch ($operation) {
      case 'render':
        $post->excerpt = $this->truncate($post->getOutput());
        break;
      case 'write':
        // Excerpt shouldn't be persisted
        $post->setAttribute('excerpt', null);
        break;
    }
  }
}