var appRoot = angular.module('myApp', []);

//route config


appRoot.controller('myController', ["$scope","$http", function($scope, $http) {
	console.log("myController");
	var apiUrl = "http://localhost/server/api.php?";

	//To register employee
	$scope.registerEmployee = function() {
		var _serializedData = $.param({email: "subratsarangi71@gamil.com", 
										password: "abc123", fullname: "subrat kumar sarangi",
										address: "Bhubaneswar",
										reqmethod: "addEmployee"});
        var _responsePromise = $http({
                method: 'POST',
                url: apiUrl,
                data: _serializedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
        });

        //var _responsePromise = $http.get(apiUrl+"reqmethod=getEmployee");
        
        _responsePromise.then(function(response) {
        	console.log("response = ", response.data);
        });
	}
}]);