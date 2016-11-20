/* global commonApiUrl */

'use strict';

generator.app.controller('BuilderCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {

        $http.get(commonApiUrl + 'listdirs').success(function (data) {

            $scope.pdfDocuments = data;
        });
        $scope.openPDF = function (pdfUrl) {

            var win = window.open(commonApiUrl + pdfLocation + pdfUrl, '_blank');
            win.focus();

        };

    }]);
