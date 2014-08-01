<!DOCTYPE html>
<html lang="en">
<head>
  <title>The Blog</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
</head>
<body ng-app="BlogApp" style="padding-top: 70px;">
  <header class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand">The blog</a>
      </div>
      <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
        <ul class="nav navbar-nav">
          <li>
            <a href="#/posts">Posts</a>
          </li>
          <li>
            <a href="#/create">Write new</a>
          </li>
          <li>
            <a href="#/media">Uploads</a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="?logout=1">Log out</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="container" ng-view></div>

</body>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.16/angular.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.16/angular-route.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.16/angular-resource.min.js"></script>
  <script src="//cdn.jsdelivr.net/angular.bootstrap/0.11.0/ui-bootstrap-tpls.min.js"></script>
  <script src="angular-file-upload.min.js"></script>
  <script src="app.js"></script>
</html>