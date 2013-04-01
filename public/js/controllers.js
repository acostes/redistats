'use strict';

/* Controllers */

function MonitorListCtrl($scope, $http, $location) {
    $http.get('api/monitors').success(function(data) {
        $scope.monitors = data;
    });

    $scope.isActive = function($route) {
        return $route == $location.path();
    };
}

function MonitorInfoCtrl($scope, $routeParams, $http) {
    $scope.monitorId = $routeParams.monitorId;
    $http.get('api/info/' + $scope.monitorId).success(function(data) {
        $scope.monitorInfo = data;
    });
}

function MonitorStatsCtrl($scope, $routeParams, $http) {
    $scope.monitorId = $routeParams.monitorId;
}