controllers.controller("WvSocialIconsAdminCtrl", function($scope,$wave) { 
	$scope.msg = "";
	$wave.request("wv_social_icons:links", function(data) {
		$scope.links = data;
	})
	$scope.save = function() {
		var l = $scope.links;
		$wave.set("wv_social_icons:links", l, "null", function(data){
			if(!data.error) {
				$scope.msg = "Settings saved.";
			}
			else
			{
				$scope.msg = data.msg;
			}
		})
	}
});