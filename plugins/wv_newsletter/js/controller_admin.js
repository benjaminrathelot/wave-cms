controllers.controller("WvNewsletterAdminCtrl", function($rootScope, $scope, $wave, $state) {
	var p = "admin=wv_newsletter&";
	$wave.plugin(p+"list", function(data) { 
		$scope.datas = data;
	});
	$('#summernote').summernote({height:250,
			onImageUpload: function(files, editor, welEditable) {
            $rootScope.sendFile(files[0], editor, welEditable);
        	}
			});
	$scope.send = function() {
		var content = $('#summernote').summernote().code();
		$wave.plugin(p+"send="+content);
		$('#summernote').html("<center>Your newsletter has been sent.</center>");
	};
});