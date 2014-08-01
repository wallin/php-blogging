<?php

class Blogpost_Frontmatter
{
  public function call($operation, $post, $options)
  {
    switch ($operation) {
      case 'read':
        $fm = new FrontMatter($post->getSource());
        $post->setAttributes($fm->fetchKeys());
        $post->setSource($fm->fetch('content'));
        break;
      case 'write':
        $attribs = $post->getAttributes();
        $yaml = "---\n";
        foreach ($attribs as $key => $value) {
          $yaml.= "$key: $value\n";
        }
        $yaml.= "---\n";
        $source = $post->getSource();
        $post->setSource($yaml.$source);
        break;
    }
  }

  public function priority($operation)
  {
    if ($operation == 'write') return 1000;
  }
}