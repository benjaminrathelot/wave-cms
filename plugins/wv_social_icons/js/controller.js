controllers.controller("WvSocialIconsCtrl", function($scope,$wave) {
	$wave.request("wv_social_icons:links", function(data) {
		$scope.links = data;
	});
});