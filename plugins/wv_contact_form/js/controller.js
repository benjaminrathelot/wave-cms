controllers.controller("ContactFormCtrl", function($wave, $scope, $state){
	$scope.formData = [];
	$scope.formData.field = [];
	$wave.request("wv_contact_form:config", function(data) {
		$scope.fields = data.fields;
	});

	$scope.send = function() {
		var formdata = $scope.formData;
		console.log(formdata);
		$wave.plugin("module=wv_contact_form", function(data) {
			$scope.msg = data.msg;
		}, formdata);
	};

});