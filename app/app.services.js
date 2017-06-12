(function () {
    angular.module('app')
        .service('pollCaster', pollCaster)
        .service('pollReader', pollReader)
        .service('fbAuthService', fbAuthService)
        .service('googleAuthService', googleAuthService)
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

            FB.Event.subscribe('auth.statusChange', function (res) {
                if (res.status === 'connected') {

                    /*
                     The user is already logged,
                     is possible retrieve his personal info
                    */
                    /*
                                        _self.getUserInfo();
                    */

                    /*
                     This is also the point where you should create a
                     session for the current user.
                     For this purpose you can use the data inside the
                     res.authResponse object.
                    */
                    $http.post(APIBASE + '?request=userdata', {
                        data: res.authResponse
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            $rootScope.user = {
                                email: response.data.userEmail,
                                name: response.data.userName
                            };
                        } else {
                            //    alert(response.error);
                        }
                    }, function (error) {
                        console.log(error);
                    });

                } else {
                    _self.logout();

                    /*
                     The user is not logged to the app, or into Facebook:
                     destroy the session on the server.
                    */
                }

            });
            FB.Event.subscribe('auth.login', function (res) {
                if (res.status === 'connected') {

                    /*
                     The user is already logged,
                     is possible retrieve his personal info
                    */
                    _self.getUserInfo();

                    /*
                     This is also the point where you should create a
                     session for the current user.
                     For this purpose you can use the data inside the
                     res.authResponse object.
                    */
                    $http.post(APIBASE + '?request=login', {
                        data: {
                            vendor: 'FACEBOOK',
                            authData: res.authResponse
                        }
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            location.reload();
                        } else {
                            alert(response.error);
                        }
                    }, function (error) {
                        console.log(error);
                    });
                }
            });

        };
        this.getUserInfo = function () {

            var _self = this;

            FB.api('/me', {
                locale: 'en_US',
                fields: 'name, email'
            }, function (res) {
                $rootScope.$apply(function () {
                    $rootScope.user = _self.user = res;
                });
            });

        };

        this.logout = function () {

            var _self = this;
            FB.logout(function (res) {
                $http.post(APIBASE + '?request=logout', {
                    data: {}
                }).then(function (response) {
                    response = response.data;
                    if (response.status) {
                        $rootScope.user = this.user = {};
                        location.reload();
                    } else {
                        alert(response.error);
                    }
                }, function (error) {
                    console.log(error);
                });
            });
        };
    }

    googleAuthService.$inject = ['$rootScope', '$http', 'APIBASE'];

    function googleAuthService($rootScope, $http, APIBASE) {
        this.signinSuccess = function (response) {
            var id_token = response.getAuthResponse().id_token;
            $http.post(APIBASE + '?request=login', {
                data: {
                    vendor: 'GOOGLE',
                    token: id_token
                }
            }).then(function(response){
                response = response.data;
                if(response.status) {
                    location.reload();
                } else {
                    console.log(response.error);
                }
            }, function(error){
                console.log(error);
            });
            console.log(id_token);
        };
        this.signinFailure = function (response) {
            console.log(response);
        };
        this.signout = function () {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                console.log('User signed out.');
            });
        };
    }
})();
