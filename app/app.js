/*global angular*/
(function () {
    'use strict';
    angular.module('app', ['ngRoute'])
        .config(appConfig)
        .constant('APIBASE', './server/request.php')
        .run(['$rootScope', '$window', 'fbAuthService', 'googleAuthService', runMethod]);

    function appConfig($httpProvider, $routeProvider, $locationProvider) {
        $routeProvider.when("/", {
            templateUrl: 'views/home.html',
            controller: 'home'
        }).when("/categories", {
            templateUrl: 'views/category-home.html',
            controller: 'categoryHome',
            controllerAs: 'catmgr'
        }).when("/polls/:category", {
            templateUrl: 'views/category-polls.html',
            controller: 'categoryPolls',
            controllerAs: 'catPoll'
        }).when("/polls/:id/:name", {
            templateUrl: 'views/poll-page.html',
            controller: 'pollPage',
            controllerAs: 'poll'
        }).when("/pollcreater", {
            templateUrl: 'views/poll-creater.html',
            isAuth: true
        }).when("/privacy", {
            templateUrl: 'views/privacy.html',
            isAuth: false
        }).when("/terms", {
            templateUrl: 'views/terms.html',
            isAuth: false
        }).when("/about", {
            templateUrl: 'views/about.html',
            isAuth: false
        }).when("/users/:id/:name", {
            templateUrl: 'views/user-home.html',
            isAuth: true,
            controller: 'userCtrl',
            controllerAs: 'User'
        }).otherwise({
            redirectTo: '/'
        });

        $locationProvider.html5Mode(true);
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        /**
         * The workhorse; converts an object to x-www-form-urlencoded serialization.
         * @param {Object} obj
         * @return {String}
         */
        var param = function (obj) {
            var query = '',
                name,
                value,
                fullSubName,
                subName,
                subValue,
                innerObj,
                i;

            for (name in obj) {
                value = obj[name];

                if (value instanceof Array) {
                    for (i = 0; i < value.length; ++i) {
                        subValue = value[i];
                        fullSubName = name + '[' + i + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                } else if (value instanceof Object) {
                    for (subName in value) {
                        subValue = value[subName];
                        fullSubName = name + '[' + subName + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                } else if (value !== undefined && value !== null)
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
            }

            return query.length ? query.substr(0, query.length - 1) : query;
        };

        // Override $http service's default transformRequest
        $httpProvider.defaults.transformRequest = [function (data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
    }

    function runMethod($rootScope, $window, sAuth, gAuth) {
        $rootScope.user = {};
        $window.fbAsyncInit = function () {
            FB.init({

                /*
                 The app id of the web app;
                 To register a new app visit Facebook App Dashboard
                 ( https://developers.facebook.com/apps/ )
                */

                appId: '1356379634397148',

                /*
                 Adding a Channel File improves the performance
                 of the javascript SDK, by addressing issues
                 with cross-domain communication in certain browsers.
                */

                channelUrl: 'app/channel.html',

                /*
                 Set if you want to check the authentication status
                 at the start up of the app
                */

                status: true,

                /*
                 Enable cookies to allow the server to access
                 the session
                */

                cookie: true,

                /* Parse XFBML */

                xfbml: true,

                /* Graph API Version */

                version: 'v2.4'
            });
            sAuth.watchLoginChange();
        };

        (function (d) {
            // load the Facebook javascript SDK

            var js,
                id = 'facebook-jssdk',
                ref = d.getElementsByTagName('script')[0];

            if (d.getElementById(id)) {
                return;
            }

            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//connect.facebook.net/en_US/sdk.js";

            ref.parentNode.insertBefore(js, ref);

        }(document));

        $window.gapiAsyncInit = function () {
            gapi.load('auth2', function () {
                gapi.auth2.init({
                    client_id: '418125885627-j2a16gbm8m1i62qqe820fspdkvb7fqop.apps.googleusercontent.com'
                });
            })
            gapi.signin2.render('google-signin', {
                scope: 'profile email',
                width: 236,
                height: 40,
                longtitle: true,
                theme: 'light',
                'onsuccess': gAuth.signinSuccess,
                'onfailure': gAuth.signinFailure
            });
        };
        (function (d) {
            var js,
                id = 'google-platform',
                ref = d.getElementsByTagName('script')[0];

            if (d.getElementById(id)) {
                return;
            }

            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//apis.google.com/js/platform.js?onload=gapiAsyncInit";

            ref.parentNode.insertBefore(js, ref);

        }(document));
        $rootScope.$on('$routeChangeStart', function () {
            angular.element('.cat-list-banner').find('a.active').removeClass('active');
            angular.element('.cat-nav-bar').find('a.active').removeClass('active');
        })
    }

})();
