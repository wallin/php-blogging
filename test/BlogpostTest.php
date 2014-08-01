<?php

class BlogpostTest extends BlogpostTestCase
{
  public function testToPath()
  {
    $this->assertStringEndsWith('test/posts/my-first-post.md', Blogpost::idToPath('my-first-post'));
  }

  public function testFind()
  {
    $post = Blogpost::find('2014-07-my-first-post');
    $this->assertInstanceOf('Blogpost', $post);
    $this->assertEquals('<p>My first post!</p>', $post->render());
    $this->assertEquals('2014-07-15', $post->date);
  }
}