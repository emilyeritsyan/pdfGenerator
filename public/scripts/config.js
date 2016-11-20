'use strict';

var apiUrl = '';
var commonApiUrl = '';
var pdfLocation = '/generate/'

var generatorConfig = generatorConfig || {
    dependencies: [
        'ngRoute'
    ],
    requests: {
        login: apiUrl + '/auth'
    }

};

