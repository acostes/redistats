'use strict';

/* App Module */

angular.module('redistats', []).
    
    directive('pie', function() {
        return {
            restrict: 'C',
            replace: true,
            scope: {
                items: '=',
            },
            link: function (scope, element, attrs) {
                var chart = new Highcharts.Chart({
                    colors: ['#3E67AD', '#7AA6FF', '#58BCA5', '#7EBA6A', '#B57C7D', '#B2866E', '#9FAF8B'],
                    chart: {
                        renderTo: attrs.id
                    },
                    title: {
                      text: attrs.title
                    },
                    tooltip: {
                        pointFormat: '<b>{point.y}</b>',
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'left',
                        floating: true,
                        borderRadius: 0,
                        borderWidth: 0,
                        padding: 10
                    },
                    series: [{
                        type: 'pie',
                        data: scope.items
                    }]
                });
                scope.$watch("items", function (newValue) {
                    chart.series[0].setData(newValue, true);
                }, true);
            }
        }
    }).

    directive('bar', function() {
        return {
            restrict: 'C',
            replace: true,
            scope: {
                items: '=',
                categories: '=',
            },
            link: function (scope, element, attrs) {
                var chart = new Highcharts.Chart({
                    colors: ['#688D99', '#7AA6FF', '#58BCA5', '#7EBA6A', '#B57C7D', '#B2866E'],
                    chart: {
                        renderTo: attrs.id,
                        type: 'bar',
                        marginBottom: 25
                    },
                    title: {
                      text: attrs.title,
                      align: 'left'
                    },
                    xAxis: {
                        categories: scope.categories,
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        title: {
                            text: null,
                        },
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        y: 100,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor: '#FFFFFF',
                        shadow: true
                    },
                    plotOptions: {
                        bar: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>',
                    },
                    series: scope.items
                });

                scope.$watch("categories", function (newValue) {
                    chart.xAxis[0].setCategories(newValue);
                }, true);

                scope.$watch("items", function (newValue) {
                    for (var i in newValue) {
                        if (!chart.series[i]) {
                           chart.addSeries(newValue[i]); 
                       }                        
                    }
                }, true);

            }
        }
    }).

    directive('tabs', function() {
        return {
            restrict: 'E',
            transclude: true,
            scope: {},
            controller: function($scope, $element) {
                var panes = $scope.panes = [];

                $scope.select = function(pane) {
                    angular.forEach(panes, function(pane) {
                        pane.selected = false;
                    });
                    pane.selected = true;
                }

                this.addPane = function(pane) {
                    if (panes.length == 0) $scope.select(pane);
                    panes.push(pane);
                }
            },
            template:
                '<div class="tabbable">' +
                    '<ul class="nav nav-tabs">' +
                        '<li ng-repeat="pane in panes" ng-class="{active:pane.selected}">'+
                            '<a href="" ng-click="select(pane)">{{pane.title}}</a>' +
                        '</li>' +
                    '</ul>' +
                '<div class="tab-content" ng-transclude></div>' +
                '</div>',
            replace: true
        };
    }).

    directive('pane', function() {
        return {
            require: '^tabs',
            restrict: 'E',
            transclude: true,
            scope: { title: '@' },
            link: function(scope, element, attrs, tabsCtrl) {
                tabsCtrl.addPane(scope);
            },
            template:
                '<div class="tab-pane" ng-class="{active: selected}" ng-transclude>' +
                '</div>',
            replace: true
        };
    }).

    config(['$routeProvider', function($routeProvider) {
        $routeProvider.
            when('/monitors', {templateUrl: 'view/monitors.html',   controller: MonitorListCtrl}).
            when('/monitors/:monitorId', {templateUrl: 'view/monitors.html',   controller: MonitorStatsCtrl}).
            otherwise({redirectTo: '/monitors'});
    }]);