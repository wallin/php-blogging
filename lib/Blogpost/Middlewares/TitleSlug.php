<?php

class Blogpost_TitleSlug
{
  public static function toSlug($str, $replace=array(), $delimiter='-')
  {
    // http://cubiq.org/the-perfect-php-clean-url-generator
    setlocale(LC_ALL, 'en_US.UTF8');
    if( !empty($replace) ) {
      $str = str_replace((array)$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;
  }

  public function call($operation, $post, $options)
  {
    switch ($operation) {
      case 'write':
        $currentId = $post->getId();
        if (empty($currentId) && $post->title) {
          $post->setId(self::toSlug($post->title));
        }
        break;
    }
  }
}