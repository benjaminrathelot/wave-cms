controllers.controller("WvNewsletterSuscribeCtrl", function($scope, $wave, $state) {
	$scope.msg = "";

	$scope.suscribe = function() {
		var d = {name:$scope.name, surname:$scope.surname, email:$scope.email};
		var search = {email:$scope.email};
		var id = $scope.email;
		id = id.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
		$wave.request("set_only_wv_newsletter:"+id, function(data) {
			if(data.error) {
			$wave.insert("set_only_wv_newsletter:"+id, d, function(data) {
				if(data.error) {
					$scope.msg = data.msg;
				}
				else
				{
					$state.go("newsletter_suscribed");
				}
			});
			}
			else
			{
				$scope.msg = "Already registred user.";
			}
		}, search);
	}
});