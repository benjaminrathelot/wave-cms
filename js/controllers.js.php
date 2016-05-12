<?php
session_start();
?>

var controllers = angular.module("Wave.controllers",['ngSanitize']);

controllers.controller('DefaultCtrl', function($scope) {
	
})

<?php if(!isset($_GET['admin'])) { ?>
	controllers.controller('MainCtrl', function($scope, $wave) {
		$wave.request("articles", function(articles) {
		if(articles.error) {
			$scope.articles = {};
		}
		else
		{
			$scope.articles=articles;
		}
		
		});

	})

	controllers.controller('ArticleCtrl', function($scope, $wave,$stateParams) {
		$wave.request("articles:"+$stateParams.id, function(article) {
		if(article.error) {
			$scope.article = {"title":"Error: Not Found"};
		}
		else {
			$scope.article=article;
		}
		});
		})


	<?php
	require_once '../core/lib/jdb.lib.php';
	$db = new JDB("../JsonDB/");

	$g = $db->get("wave:controllers");
	if($g) {
		foreach($g as $url) {
			echo file_get_contents("../".str_replace("://","",$url));
		}
	}
	?>

<?php }
elseif(isset($_GET['admin'],$_SESSION['admin'])) {
?>
	controllers.controller('MainCtrl', function($scope, $wave) {

	})

	controllers.controller('ArticlesCtrl', function($scope, $wave, $state) {
		$wave.request("articles", function(articles) {
			if(articles.error) {
				$scope.articles = [];
			}
			else
			{
				$scope.articles=articles;
			}
		});
		$scope.delete = function(id) {
			$wave.delete("articles:"+id, function(){
				$state.reload();
			})
		}
	})

	controllers.controller('PagesCtrl', function($scope, $wave, $state) {
		$wave.getPages(function(pages) {
			if(pages.error) {
				$scope.pages = [];
			}
			else
			{
				$scope.pages=pages;
			}
		});
		$scope.delete = function(id) {
			$wave.deletePage(id, function(){
				$state.reload();
			})
		}
	})

	controllers.controller('ArticleCtrl', function($rootScope, $scope, $wave,$stateParams,$state) {
		$wave.request("articles:"+$stateParams.id, function(article) {
			if(article.error) {
				$scope.article = {"title":"Error: Not Found", "content":""};
			}
			else
			{
				$scope.article=article;
			}
			$('#summernote').summernote({height:250,
			onImageUpload: function(files, editor, welEditable) {
            $rootScope.sendFile(files[0], editor, welEditable);
        	}
			}).code(article.content);
			$scope.edit = function() {
				article.content = $('#summernote').summernote().code();
				$wave.set("articles:"+article.id, article,{id:article.id}, function(data){
					$state.go("articles");
				})
			}
		});
	})

	controllers.controller('NewArticleCtrl', function($rootScope, $scope, $wave,$stateParams,$state) {
			$scope.article = {"id":"", "title":"", content:"", "date":($.now()/1000), "author":"<?php echo $_SESSION['admin']; ?>"};
			$('#summernote').summernote({height:250,
			onImageUpload: function(files, editor, welEditable) {
            $rootScope.sendFile(files[0], editor, welEditable);
        	}});
			$scope.edit = function() {
				var article = $scope.article;
				article.content = $('#summernote').summernote().code();
				article.id = article.title.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
				$wave.insert("articles:"+article.id, JSON.stringify(article), function(data){
					$state.go("articles");
				})
			}
	})

	controllers.controller('PageCtrl', function($rootScope, $scope, $wave,$stateParams, $state) {
		$wave.requestPage($stateParams.id, function(page) {
			$scope.id = $stateParams.id;
			if(page.error) {
				$scope.page = page = "Error: Not Found";
			}
			else
			{
				$scope.page=page;
			}
			$('#summernote').summernote({height:250,
			onImageUpload: function(files, editor, welEditable) {
            $rootScope.sendFile(files[0], editor, welEditable);
        	}}).code(page);
			$scope.edit = function() {
				var id = $stateParams.id;
				var content = $('#summernote').summernote().code();
				$wave.editPage(id, content, function(data){
					$state.go("pages");
				})
			}
		});
	})

	controllers.controller('NewPageCtrl', function($rootScope, $scope, $wave,$stateParams,$state) {
			$scope.id = "";
			$('#summernote').summernote({height:250,
			onImageUpload: function(files, editor, welEditable) {
            	sendFile(files[0], editor, welEditable);
        	}
			});
			$scope.edit = function() {
				var id = $scope.id;
				id = id.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
				var content = $('#summernote').summernote().code();
				$wave.editPage(id, content, function(data){
					$state.go("pages");
				})
			}
	})

	controllers.controller('ParamsCtrl', function($scope, $wave, $state, $stateParams, $anchorScroll, $location) {
		$scope.new_menu_title = "";
		$scope.new_menu_state = "";
		$scope.new_route = {};
		$scope.new_route_id = "";
		if(!$stateParams.cat) { 
			$state.go("params", {cat:"main"});
		}
		$scope.scrollTo = function(id) {
		     $location.hash(id);
		     $anchorScroll();
  		}
		$wave.request("wave:site_info", function(info) {
			$scope.site = info;
		});
		$wave.plugin("list", function(plugins) {
			$scope.plugins = plugins;
		});
		$wave.request("wave:admins", function(us) {
			$scope.users = us;
		});
		$wave.request("wave:main_menu", function(us) {
			$scope.main_menu = us;
		});
		$wave.request("wave:routes", function(us) {
			$scope.routes = us;
		});
		$scope.update = function() {
			var site = $scope.site;
			$wave.set("wave:site_info", site, null, function(){
				$state.reload();
			});
		}

		$scope.delete = function(user) {
			$wave.delete("wave:admins:"+user, function(){
				$state.reload();
			})
		}

		$scope.delmenu = function(id) {
			$wave.delete("wave:main_menu:"+id, function(){
				$state.reload();
			})
		}

		$scope.delroute = function(id) {
			$wave.delete("wave:routes:"+id, function(){
				$state.reload();
			})
		}

		$scope.editmenu = function(old, newx, oldt, title) {
			var d = {};
			d[newx] = title;
			var k = {};
			k[old] = oldt;
			$wave.set("wave:main_menu", d, k, function(data) {
				if(data.error) {
					alert("Erreur de connexion.");
				}
				else
				{
					$state.reload();
				}
			});
		}

		$scope.editroute = function(old_id, id, templateUrl, controller, url) {
			var d = {};
			d[id] = {"url":url, "templateUrl":templateUrl, "controller":controller};
			$wave.delete("wave:routes:"+old_id, function(){
			$wave.set("wave:routes", d, null, function(data) {
				if(data.error) {
					alert("Erreur de connexion.");
				}
				else
				{
					$state.reload();
				}
			});
			});
		}

		$scope.addmenu = function() {
			var d = {};
			var ux = $scope.new_menu_state;
			d[ux] = $scope.new_menu_title;
			$wave.set("wave:main_menu", d, null, function(data) {
				if(data.error) {
					alert("Erreur de connexion.");
				}
				else
				{
					$state.reload();
				}
			});			
		}

		$scope.addroute = function() {
			var d = {};
			var ux = $scope.new_route_id;
			d[ux] = $scope.new_route;
			$wave.set("wave:routes", d, null, function(data) {
				if(data.error) {
					alert("Erreur de connexion.");
				}
				else
				{
					$state.reload();
				}
			});
			
		}
	})

	controllers.controller('NewUserCtrl', function($scope, $wave, $state) {
		$scope.username = "";
		$scope.type = "--";

		$scope.add = function() {
			var username = $scope.type + $scope.username;
			var data = {};
			data[username]="d7ce74466d54133cbc5e0e85ef3be8e8d840790a";
			$wave.set("wave:admins", data, null, function() {
				$state.go('params');
			})
		}
	})

	controllers.controller('UserCtrl', function($scope, $wave, $state) {
		$scope.pass_actuel = "";
		$scope.pass_new = "";
		$scope.pass_confirm = "";
		$scope.msg="";
		$scope.edit = function() {
			if($scope.pass_new == $scope.pass_confirm) {
			$wave.hash($scope.pass_actuel, function(data) {
			$scope.old = data.msg;
				$wave.hash($scope.pass_new, function(data) {
					$scope.new = data.msg;
					$wave.set("wave:admins", {'<?php echo $_SESSION['admin']; ?>':$scope.new}, {'<?php echo $_SESSION['admin']; ?>':$scope.old}, function(data){
					if(data.error) {
						$scope.msg = "Mauvais identifiants.";
					}
					else
					{
						$scope.msg = "Mot de passe modifié.";
					}
					});
				});
			});
		}
		else
		{
			$scope.msg = "Les mots de passe ne correspondent pas.";
		}
		}
	})
	<?php
	require_once '../core/lib/jdb.lib.php';
	$db = new JDB("../JsonDB/");

	$g = $db->get("wave:controllers_admin");
	if($g) {
		foreach($g as $url) {
			echo file_get_contents("../".str_replace("://","",$url));
		}
	}
	?>

<?php
}
?>

;