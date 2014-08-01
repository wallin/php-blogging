<?php

require '../lib/Blogpost.php';

if ($_GET['c']) {
  try {
    $post = Blogpost::find($_GET['c']);
  }
  catch (Exception $e) {

  }
}
else {
  $posts = Blogpost::find()->where('published', true)->result();
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>The blog</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
  </head>
  <body>
    <div class="container">
      <?php if($posts): ?>
        <div class="row">
          <div class="col-md-12">
            <h1>The blogposts</h1>
          </div>
        </div>
        <?php foreach($posts as $post): ?>
          <div class="row">
            <article class="col-md-12">
              <? if($post->image): ?>
              <div class="post-image">
                <a href="?c=<?= $post->getId(); ?>">
                  <img src="uploads/<?= $post->image ?>">
                </a>
              </div>
              <? endif; ?>
              <h2>
                <a href="?c=<?= $post->getId(); ?>"><?=$post->title ?></a>
              </h2>
              <small class="text-muted"><?= date("F jS, Y @ H:i", strtotime($post->date)) ?></small>
              <div class="post-content">
              <?= $post->render()->excerpt; ?>
              </div>
              <a class="more" href="?c=<?= $post->getId(); ?>">Read more &raquo;</a>
            </article>
          </div>
        <?php endforeach; ?>

      <? elseif($post): ?>

        <div class="row">
          <article class="col-md-12">
            <? if($post->image): ?>
            <div class="full-image">
              <a href="blog/<?= $post->getId(); ?>">
                <img src="uploads/<?= $post->image ?>">
              </a>
            </div>
            <? endif; ?>
            <h2><?= $post->title ?></h2>
            <small class="text-muted"><?= date("F jS, Y @ H:i", strtotime($post->date)) ?></small>
            <div class="post-content">
            <?= $post->render() ?>
            </div>
          </article>
        </div>

      <? else: ?>
        <h4>No posts yet!</h4>
      <? endif; ?>
    </div>
  </body>
</html>