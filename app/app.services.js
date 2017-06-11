(function () {
    angular.module('app')
        .service('pollCaster', pollCaster)
        .service('pollReader', pollReader)
        .service('fbAuthService', fbAuthService)
        .service('pollCategories', pollCategories)
        .service('pollEditor', pollEditor);

    pollEditor.$inject = ['$http', 'APIBASE'];

    function pollEditor($http, APIBASE) {
        var pollEditor = this;
        this.addNewPoll = function (pollData) {
            return $http.post(APIBASE + '?request=newpoll', {
                data: pollData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollCaster.$inject = ['$http', 'APIBASE'];

    function pollCaster($http, APIBASE) {
        var pollCaster = this;
        this.castVote = function (pollData) {
            return $http.post(APIBASE + '?request=castvote', {
                data: pollData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollCategories.$inject = ['$http', 'APIBASE'];

    function pollCategories($http, APIBASE) {
        var pollCategories = this;
        this.getCategories = function () {
            return $http.get(APIBASE + '?request=getCategories').then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollReader.$inject = ['$http', 'APIBASE'];

    function pollReader($http, APIBASE) {
        var pollReader = this;
        this.readPolls = function (readData) {
            return $http.post(APIBASE + '?request=readPolls', {
                data: readData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    fbAuthService.$inject = ['$rootScope', '$http', 'APIBASE'];
    function fbAuthService($rootScope, $http, APIBASE) {
        this.watchLoginChange = function () {

            var _self = this;

            FB.Event.subscribe('auth.authResponseChange', function (res) {
                if (res.status === 'connected') {

                    /*
                     The user is already logged,
                     is possible retrieve his personal info
                    */
                    _self.getUserInfo();
                    
                    $http.post(APIBASE + '?request=login', {data: res.authResponse}).then(function(response){
                        console.log(response);
                    }, function(error){
                        console.log(error);
                    })
                    /*
                     This is also the point where you should create a
                     session for the current user.
                     For this purpose you can use the data inside the
                     res.authResponse object.
                    */

                } else {

                    /*
                     The user is not logged to the app, or into Facebook:
                     destroy the session on the server.
                    */

                }

            });

        };
        this.getUserInfo = function () {

            var _self = this;

            FB.api('/me', { locale: 'en_US', fields: 'name, email' }, function (res) {
                $rootScope.$apply(function () {
                    $rootScope.user = _self.user = res;
                });
            });

        };

        this.logout = function () {

            var _self = this;

            FB.logout(function (response) {
                $rootScope.$apply(function () {
                    $rootScope.user = _self.user = {};
                });
            });

        }
    }
})();
