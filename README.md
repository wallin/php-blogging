# php-blogging

In the spirit of the
[anti-wordpress rage](http://www.typeandgrids.com/blog/goodbye-wordpress-2014-will-be-the-year-of-flat-file-cmses),
`php-blogging` is a simple, pluggable blog engine written in PHP that you just
drop right in to any directory. It uses plain/flat file storage for maximum
portability and doesn't need any particular configuration.

It is designed for developers and does not itself supply any UI components
(like CSS/themes) but is merely an API for handling the blog posts themselves.
However, the `example` folder in this repository contains a reference
implementation of a fully working blog (incl. admin) which illustrates how to
use the library.

## Installation

```bash
git clone https://github.com/wallin/php-blogging.git

cd path/to/project
mkdir posts && chmod 777 posts
```

In your PHP Code.
```php
require 'path/to/php-blogging/lib/Blogpost.php';

// Optional configuration of storage directory, default is './posts'
// Blogpost::$basePath = './custom-posts';

// Fetch the latest posts
$posts = Blogpost::all();
```

## Example

See a full example of how to use the library in the  `examples` dir. The
`admin` subdir contains a simple administration tool (written in AngularJS)
where you can create, edit and delete posts. It also illustrates the use of
[attributes](#attributes--frontmatter)


## Usage

Method overview for a `Blogpost` instance.

* $post->render()
* $post->setSource()
* $post->delete()
* $post->save()

### Create a new post

#### Post ID / filename

An ID is everything a post need to exist. It will be translated to a file name
which will be used to store it's content. For example:

```php
$post = new Blogpost();
// All that the post need is an ID, which will translate to a filename
$post->setId('my-first-post');
$post->save();
```

will yield a file `./posts/my-first-post.md`


#### Attributes / Frontmatter

By default, the Frontmatter plugin is enables which means that everything you
assign to the post will be saved as YAML at the beginning of the storage file
content. For example:

```php
$post = new Blogpost();
$post->setSource('Dear Diane! The owls are not what they seem');

// Custom attributes will become YAML
$post->title = "My first post";

// By default, the TitleSlug plugin is enabled, which means that you don't have
// to set and ID manually, but it will instead be set by the plugin by using the
// title. In this case the ID will be 'my-first-post'
// attribute
$post->save();
```

 Will produce a file with content:
```
---
title: My first post
---
Dear Diane! The owls are not what they seem
```

### Find a post and display it's contents

By default, the Markdown processor plugin is enabled which lets you use
markdown as source content. This content will then be rendered into HTML by
the `render()` method of the post.

```php
// The post that we created in the previous example can be read back by using
// the ID (which reads file './posts/my-first-post.md'):

$post = Blogpost::find('my-first-post');
```

In the markup the `render` method is used to produce the actual content of the post:
```html
<h1><?= $post->title ?></h1>
<div class="content">
  <?= $post->render() ?>
</div>
```

### Listing / Quering posts

```php
// List all posts
$posts = Blogpost::all();

// Find posts matching a certain attribute
$posts = Blogpost::find()->where('published', true)->result();
```

By default the Excerpt plugin is enabled which will generate an 255 character
long, html-free excerpt which is convenient to use when listing all posts. Example:

```html
<? foreach($posts as $post): ?>
  <article>
    <h1><?= $post->title ?></h1>
    <div class="content">
      <?= $post->render()->excerpt ?>
    </div>
  <article>
<? endforeach; ?>
```

## Contributing

* Create a fork
* Create code and tests
* Run tests with `grunt phpunit`
* Commit
* Make pull request

## License

MIT
