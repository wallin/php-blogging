var app = angular.module('BlogApp', [
  'ngRoute',
  'ngResource',
  'ui.bootstrap',
  'angularFileUpload'
])
app.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
  .when('/posts', {
    templateUrl: 'posts.html',
    controller: 'PostsCtrl'
  })
  .when('/create', {
    templateUrl: 'edit.html',
    controller: 'NewPostCtrl'
  })
  .when('/posts/:postId', {
    templateUrl: 'edit.html'
  })
  .when('/media', {
    templateUrl: 'media.html',
    controller: 'MediaCtrl'
  })
  .otherwise({
    redirectTo: '/posts'
  });
}]);

app.factory('Posts', ['$resource', function($resource) {
  function parseResponseDates(response) {
    if (response.resource && response.resource.date) {
      response.resource.date = new Date(response.resource.date);
    }
    return response;
  }
  return $resource('ajax.php?r=posts&id=:id', {id: '@id'}, {
    'update': {
      method:'PUT',
      interceptor: {response: parseResponseDates}
    }
  });
}]);

app.factory('Images', ['$resource', function ($resource) {
  return $resource('ajax.php?r=uploads&id=:id', {id: '@filename'});
}])

app.controller('PostsCtrl', ['$scope','Posts',
  function($scope, Posts) {
    $scope.posts = Posts.query();
  }
]);

app.controller('PostCtrl', ['$element', '$location', '$modal', '$routeParams','$scope','Posts',
  function($element, $location, $modal, $routeParams, $scope, Posts) {
    var textArea = $element.find('textarea')[0];
    function insertText (text, position) {
      var val = $scope.post.source;
      $scope.post.source = [val.slice(0, position), text, val.slice(position)].join('');
    }

    function openMediaModal (argument) {
      return $modal.open({
        templateUrl: 'selectImage.html',
        controller: 'MediaCtrl',
        size: 'lg'
      })
    }

    if ($routeParams.postId != null) {
      $scope.post = Posts.get({id: $routeParams.postId}, function(post) {
        $scope.post.date = new Date($scope.post.date);
      }, function() {
        $location.path('/posts');
      });
    }

    $scope.$watch('post.id', function(val) {
      if ($scope.post.$resolved && $scope.post.id == null) {
        $location.path('/posts');
      }
    });

    $scope.delete = function () {
      if (confirm('Are you sure?')) {
        $scope.post.$delete();
      }
    };

    $scope.selectFeatured = function () {
      openMediaModal().result.then(function (image) {
        $scope.post.image = image ? image.filename : '';
      });
    };

    $scope.selectImage = function () {
      openMediaModal().result.then(function (image) {
        if (image) {
          insertText('![Description](uploads/'+image.filename+')', textArea.selectionStart);
        }
      });
    }
  }
]);

app.controller('NewPostCtrl', ['$location', '$scope', 'Posts',
  function ($location, $scope, Posts) {
    $scope.post = new Posts();
    $scope.post.date = new Date();
    $scope.$watch('post.id', function(val) {
      if (val) {
        $location.path('/posts/' + $scope.post.id);
      }
    });
  }
]);

app.controller('MediaCtrl', ['$scope', 'Images', 'FileUploader',
  function ($scope, Images, FileUploader) {
    var refresh = function() {
      $scope.images = Images.query();
    };
    $scope.selected = {};

    $scope.delete = function (img) {
      if (confirm('Are you sure?')) {
        img.$delete(refresh);
      }
    };

    var uploader = $scope.uploader = new FileUploader({
      url: 'ajax.php'
    })
    uploader.onSuccessItem = refresh;
    uploader.onAfterAddingFile = function () {
      uploader.uploadAll();
    };
    uploader.onErrorItem = function () {
      alert('Error uploading');
    };
    refresh();
  }
])