<?php

class Blogpost_Markdown
{
  public function call($operation, $post, $options)
  {
    if ($operation == 'render') {
      $Parsedown = new Parsedown();
      $post->setOutput($Parsedown->text($post->getOutput()));
    }
  }
}