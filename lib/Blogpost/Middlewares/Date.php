<?php

class Blogpost_Date
{
  public function call($operation, $post, $options)
  {
    switch($operation) {
      case 'read':
        $file = $post->getFile();
        date_default_timezone_set('UTC');
        if (!$post->date && $file) {
          $post->date = date(DATE_ATOM, $file->createdAt);
        }
        $post->timestamp = strtotime($post->date);
        break;
      case 'write':
        $post->setAttribute('timestamp', null);
    }
  }
}