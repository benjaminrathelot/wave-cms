<?php
session_start();
?>

var app = angular.module('Wave', ['ui.router', 'Wave.controllers', 'Wave.services', 'Wave.directives']);

app.run(function($rootScope, $state, $stateParams){
  $rootScope.$state = $state;
  $rootScope.$stateParams = $stateParams;
<?php if(isset($_GET['admin'],$_SESSION['admin'])) { ?>
$rootScope.sendFile = function(file, editor, welEditable) { 
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: 'POST',
            xhr: function() { 
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) myXhr.upload.addEventListener('progress',$rootScope.progressHandlingFunction, false);
                return myXhr;
            },
            url: 'request.php?upload',
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) { 
                $('#summernote').summernote('editor.insertImage', url);
            },
            error: function(err) {
            	console.log(JSON.stringify(err));
            }
        });
}

$rootScope.progressHandlingFunction =function(e){
	$('.note_editor > modal-body').html($('.note_editor > modal-body').html()+"Your image is being uploaded.");
}

<?php } ?>
})


.config(function($stateProvider, $urlRouterProvider) {


$stateProvider

<?php if(!isset($_GET['admin'])) { ?>
  .state('main', {
    url: "/main",
    templateUrl: 'templates/main.html',
    controller: 'MainCtrl'
  })
<?php
	require_once '../core/lib/jdb.lib.php';
	$db = new JDB("../JsonDB/");
	$routes = $db->get("wave:routes");
	if($routes) {
		foreach($routes as $state=>$route) {
			echo ".state('$state', {url:'".$route->url."', templateUrl:'".$route->templateUrl."', controller:'".$route->controller."'})\n";
		}
	}
?>
  ;

$urlRouterProvider.otherwise('/main');
<?php }
elseif(isset($_GET['admin'],$_SESSION['admin'])) {
	?>
  .state('main', {
    url: "/main",
    templateUrl: 'templates/admin/main.html',
    controller: 'MainCtrl'
  })
   .state('articles', {
    url: "/articles",
    templateUrl: 'templates/admin/articles.html',
    controller: 'ArticlesCtrl'
  })
   .state('article', {
    url: "/article/:id",
    templateUrl: 'templates/admin/article.html',
    controller: 'ArticleCtrl'
  })
   .state('pages', {
    url: "/pages",
    templateUrl: 'templates/admin/pages.html',
    controller: 'PagesCtrl'
  })
   .state('page', {
    url: "/page/:id",
    templateUrl: 'templates/admin/page.html',
    controller: 'PageCtrl'
  })
 <?php if(!preg_match("#^\-\-#", $_SESSION['admin'])) { ?>
   .state('params', {
    url: "/params/:cat",
    templateUrl: 'templates/admin/params.html',
    controller: 'ParamsCtrl'
  })
   .state('user', {
    url: "/user",
    templateUrl: 'templates/admin/user.html',
    controller: 'UserCtrl'
  })
  .state('new_article', {
  	url: "/new_article",
  	templateUrl: "templates/admin/new_article.html",
  	controller: "NewArticleCtrl"
  })  
  .state('new_page', {
  	url: "/new_page",
  	templateUrl: "templates/admin/new_page.html",
  	controller: "NewPageCtrl"
  }) 
  .state('new_user', {
  	url: "/new_user",
  	templateUrl: "templates/admin/new_user.html",
  	controller: "NewUserCtrl"
  }) 
<?php } ?>
	<?php
	require_once '../core/lib/jdb.lib.php';
	$db = new JDB("../JsonDB/");
	$routes = $db->get("wave:routes_admin");
	if($routes) {
		foreach($routes as $state=>$route) {
			echo ".state('$state', {url:'".$route->url."', templateUrl:'".$route->templateUrl."', controller:'".$route->controller."'})\n";
		}
	}
	?>
	;

$urlRouterProvider.otherwise('/main');
<?php
}
?>
})

;