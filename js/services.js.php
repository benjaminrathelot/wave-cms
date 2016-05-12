angular.module('Wave.services', [])

.factory('$wave', ['$http', '$rootScope','$state', function($http, $rootScope,$state) {
  return {
    request: function(rq, callback, where, order, limit) {
        var order = order || 1;
        var where = where || null;
        var limit = limit || 0;
        $http.get("request.php?get&rq="+rq+"&where="+JSON.stringify(where)+"&order="+order+"&limit="+limit).success(callback);
    },
    set: function(rq, set, where, callback) {
     var where = where || null;
      $http.post("request.php?rq="+rq+"&where="+JSON.stringify(where), {"set":JSON.stringify(set)}).success(callback);
    },
    insert: function(rq,data,callback) {
      $http.post("request.php?insert&rq="+rq,{"data":JSON.stringify(data)}).success(callback);      
    },
    delete: function(rq,callback) {
      $http.get("request.php?delete&rq="+rq).success(callback);      
    },
    requestPage: function(id,callback) {
        $http.get("templates/"+id+".html").success(callback).error(function(){
            var r = {"error":1};
            return r;
        });
    },
    editPage: function(editTemplate, content, callback) {
        $http.post("request.php?editTemplate="+editTemplate, {"content":content}).success(callback);

    },
    getPages: function(callback) {
        $http.get("request.php?getTemplates").success(callback);        
    },
    deletePage: function(id,callback) {
        $http.get("request.php?deleteTemplate="+id).success(callback);        
    },
    hash: function(str, callback) {
        $http.get("request.php?hash="+str).success(callback);
    },
    plugin: function(rq,callback, post) {
    if(post) {
        $http.post("plugin.php?"+rq, {'data':post}).success(callback);
    }
    else
    {
        $http.get("plugin.php?"+rq).success(callback);
    }
    }

  }
}])

<?php
require_once '../core/lib/jdb.lib.php';
$db = new JDB("../JsonDB/");

if(isset($_GET['admin'], $_SESSION['admin'])) {
  $g = $db->get("wave:services_admin");
}
else
{
  $g = $db->get("wave:services");
}

if($g) {
	foreach($g as $url) {
		echo file_get_contents("../".str_replace("://","",$url));
	}
}
?>

;