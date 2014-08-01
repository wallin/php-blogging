<?php

abstract class BlogpostTestCase extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    Blogpost::$basePath = join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'posts'));
  }
}

require(dirname(__FILE__) . '/../lib/Blogpost.php');

