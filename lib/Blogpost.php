<?php

require(dirname(__FILE__) . '/Frontmatter/Frontmatter.php');
require(dirname(__FILE__) . '/Parsedown/Parsedown.php');

require(dirname(__FILE__) . '/Blogpost/Blogpost.php');
require(dirname(__FILE__) . '/Blogpost/Query.php');
require(dirname(__FILE__) . '/Blogpost/Middleware.php');
require(dirname(__FILE__) . '/Blogpost/Middlewares/Date.php');
require(dirname(__FILE__) . '/Blogpost/Middlewares/Excerpt.php');
require(dirname(__FILE__) . '/Blogpost/Middlewares/Frontmatter.php');
require(dirname(__FILE__) . '/Blogpost/Middlewares/Markdown.php');
require(dirname(__FILE__) . '/Blogpost/Middlewares/TitleSlug.php');
require(dirname(__FILE__) . '/Blogpost/Storage.php');

Blogpost_Middleware::insert('Blogpost_Frontmatter');
Blogpost_Middleware::insert('Blogpost_Date');
Blogpost_Middleware::insert('Blogpost_TitleSlug');
Blogpost_Middleware::insert('Blogpost_Markdown');
Blogpost_Middleware::insert('Blogpost_Excerpt');