<?php
require '../../lib/Blogpost.php';
Blogpost::$basePath = '../posts';

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

function respondJSON($result = null)
{
  header('Content-Type: application/json');
  die(json_encode($result));
}

function respond404($msg = 'Not found')
{
  header("HTTP/1.0 404 Not Found");
  header('Content-Type: application/json');
  die(json_encode(array('message' => $msg)));
}

function postToJson($post)
{
  return array(
    'id' => $post->getId(),
    'title' => $post->title,
    'date' => $post->date,
    'published' => $post->isPublished(),
    'source' => $post->getSource(),
    'image' => $post->image,
    'link' => '/blog?c='.$post->getId()
  );
}

function getPost($id)
{
  try {
    $post = Blogpost::find($id);
  } catch (Exception $e) {
    respond404();
  }
  return postToJson($post);
}

function getPosts()
{
  $posts = Blogpost::all();
  $rv = array();
  foreach ($posts as $post) {
    $rv[]=array(
      'id' => $post->getId(),
      'title' => $post->title,
      'date' => $post->date,
      'published' => $post->isPublished()
    );
  }
  return $rv;
}

function getImages()
{
  $uploadPath = '../uploads/';
  $images = glob("$uploadPath*.{jpg,png}", GLOB_BRACE);
  $rv = array();
  foreach ($images as $image) {
    $size = getimagesize($image);
    $rv[]= array(
      'filename' => str_replace($uploadPath, '', $image),
      'created_at' => date(DATE_ATOM, filectime($image)),
      'width' => $size[0],
      'height' => $size[1]
    );
  }
  return $rv;
}

function deleteImage($image)
{
  if (!unlink('../uploads/'.basename($image))) {
    respond404();
  };
}

///////////////
///
/// Handle uploads
///

if ( !empty( $_FILES ) ) {

    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
    $uploadPath = '../uploads' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];

    move_uploaded_file( $tempPath, $uploadPath );

    $answer = array( 'answer' => 'File transfer completed' );
    $json = json_encode( $answer );

    die($json);

}

///////////////
///
/// Handle ajax requests
///

$method = $_SERVER['REQUEST_METHOD'];
$id = $_REQUEST['id'];

if ($method == 'POST' || $method == 'PUT') {
  // Parse JSON and replace _REQUEST params
  $body = @file_get_contents('php://input');
  if($body) $_REQUEST = array_merge($_REQUEST, json_decode($body, true));
}

switch ($_REQUEST['r']) {
  case 'posts':
    switch ($method) {
      case 'GET':
        if (!empty($id)) {
          $post = getPost($_GET['id']);
        }
        else {
          $post = getPosts();
        }
        break;
      case 'PUT':
        if (empty($id)) {
          respond404();
        }
        try {
          $post = Blogpost::find($id);
        } catch (Exception $e) {
          respond404($e->getMessage());
        }
        //break; // Intentional
      case 'POST':
        if (!isset($post)) $post = new Blogpost();
        if ($_REQUEST['source']) $post->setSource($_REQUEST['source']);
        if ($_REQUEST['title']) $post->title = $_REQUEST['title'];
        if ($_REQUEST['date']) $post->date = $_REQUEST['date'];
        if (isset($_REQUEST['image'])) $post->image = $_REQUEST['image'];
        if (isset($_REQUEST['published'])) $post->published = $_REQUEST['published'];
        try {
          $post->save();
        } catch (Exception $e) {
          respond404($e->getMessage());
        }
        $post = postToJson($post);
        break;
      case 'DELETE':
        if (empty($id)) {
          respond404();
        }
        $post = Blogpost::find($id);
        try {
          $post->destroy();
          $post = null;
        } catch (Exception $e) {
          respond404($e->getMessage());
        }
        break;
    }
    respondJSON($post);
    break;
  case 'uploads':
    switch ($method) {
      case 'GET':
        $images = getImages();
        respondJSON($images);
        break;
      case 'DELETE':
        if ($_REQUEST['id']) {
          deleteImage($_REQUEST['id']);
        }
        respondJSON(null);
        break;
    }
}