'use strict';

generator.app = angular.module('generatorApp', generatorConfig.dependencies);

generator.app.config(['$routeProvider','$locationProvider', function ($routeProvider, $locationProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'views/builder.html',
                    controller: 'BuilderCtrl'
                })
                .when('/about', {
                    templateUrl: 'views/about.html',
                    controller: 'BuilderCtrl'
                })
//                .when('/generate', {
//                    redirectTo: function() {
//                        window.location = "/generate";
//                    }
//                })
                
                .otherwise({
                    redirectTo: 'views/404.html'
                });
                $locationProvider.html5Mode({
                enabled: true,
                requireBase: false
            });
    }]);

var checkRouting = function ($q, $rootScope, $location) {
    if ($rootScope.userProfile) {
        return true;
    }
    return false;
};


