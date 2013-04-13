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
        $scope.error = data.error;
        if (!data.error) {
            $scope.memoryValues = [
                ['memory', parseInt($scope.monitorInfo.Memory.used_memory)],
                ['memory_lua', parseInt($scope.monitorInfo.Memory.used_memory_lua)],
                ['memory_peak', parseInt($scope.monitorInfo.Memory.used_memory_peak)],
                ['memory_rss', parseInt($scope.monitorInfo.Memory.used_memory_rss)]
            ]

            $scope.stats = [
                ['connections_received', parseInt($scope.monitorInfo.Stats.total_connections_received)],
                ['commands_processed', parseInt($scope.monitorInfo.Stats.total_commands_processed)]
            ]

            $scope.cmd = new Object();
            $scope.cmd.categories = new Object();
            $scope.cmd.values = new Array();
            var index = 0;
            for (var cmd in $scope.monitorInfo.Commandstats) {
                var info = $scope.monitorInfo.Commandstats[cmd];
                var command = cmd.split('_');
                $scope.cmd.categories[index] = command[1];

                var value = info.match(/([0-9]+(.[0-9]+)?)+/g);
                var name = info.match(/([a-z](_[a-z])?)+/g);

                for (var i = 0; i < value.length; i++) {
                    if (!$scope.cmd.values[i]) {
                        $scope.cmd.values[i] = new Object();
                    }
                        
                    if (!$scope.cmd.values[i].data) {
                        $scope.cmd.values[i].data = new Array();
                    }

                    $scope.cmd.values[i].data.push(parseFloat(value[i]));
                    $scope.cmd.values[i].name = name[i];
                }

                index++;
            }
        }

    });    
}

function MonitorStatsCtrl($scope, $routeParams, $http) {
    $scope.monitorId = $routeParams.monitorId;
}