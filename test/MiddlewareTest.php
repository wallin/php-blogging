<?php

class TestMiddleware
{
  public function call($operation, $data)
  {
    $data->test = true;
  }
}

class MiddlewareTest extends BlogpostTestCase
{
  public function testProcess()
  {
    $post = new Blogpost();
    Blogpost_Middleware::insert('TestMiddleware');
    Blogpost_Middleware::process('read', $post);
    $this->assertTrue($post->test);
  }

  public function testRemoveWithoutArgument()
  {
    Blogpost_Middleware::insert('TestMiddleware');
    Blogpost_Middleware::remove('TestMiddleware');
    //$this->assertEmpty(Blogpost_Middleware::$wares);
  }

  public function testMarkdown()
  {
    $post = new Blogpost();
    $post->setSource('#title');
    $this->assertEquals('<h1>title</h1>', $post->render()->getOutput());
  }

  public function testTitleSlug()
  {
    $post = new Blogpost();
    $post->title = "The Post";
    Blogpost_Middleware::process('write', $post);
    $this->assertEquals('the-post', $post->getId());
  }

  public function testDateTimestamp()
  {
    $post = new Blogpost();
    $post->date = '2014-07-30';
    Blogpost_Middleware::process('read', $post);
    $this->assertEquals('1406678400', $post->timestamp);

    Blogpost_Middleware::process('write', $post);
    $this->assertNull($post->timestamp);
  }
}
