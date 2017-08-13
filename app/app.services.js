(function () {
    angular.module('app')
        .service('pollCaster', pollCaster)
        .service('pollReader', pollReader)
        .service('pollMetaData', pollMetaData)
        .service('cookieService', cookieService)
        .service('fbAuthService', fbAuthService)
        .service('googleAuthService', googleAuthService)
        .service('emailAuthService', emailAuthService)
        .service('pollCategories', pollCategories)
        .service('UserProfileService', UserProfileService)
        .service('pollEditor', pollEditor);

    pollEditor.$inject = ['$http', 'APIBASE'];

    function pollEditor($http, APIBASE) {
        var pollEditor = this;
        this.addNewPoll = function (pollData) {
            var fd = new FormData();
            if (pollData.image && typeof (pollData.image) != "undefined") {
                if (pollData.pollType == 'upload') {
                    var imgBlob = dataURItoBlob(pollData.image);
                    fd.append('imagefile', imgBlob);
                } else {
                    fd.append('imagefile', pollData.image)
                }
                delete pollData.image;
            }
            fd.append('data', angular.toJson(pollData));
            return $http.post(APIBASE + '?request=newpoll', fd, {
                transformRequest: angular.identity,
                headers: {
                    'Content-Type': undefined
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };

        function dataURItoBlob(dataURI) {
            var binary = atob(dataURI.split(',')[1]);
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            var array = [];
            for (var i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            return new Blob([new Uint8Array(array)], {
                type: mimeString
            });
        }
    }

    cookieService.$inject = [];

    function cookieService() {
        this.setCookie = setCookie;
        this.getCookie = getCookie;

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
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
        this.getUserPolls = function (userid, page, order) {
            return $http.post(APIBASE + '?request=userPolls', {
                data: {
                    userid: userid,
                    page: page,
                    order: order
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.getUserVotePolls = function (userid, page, order) {
            return $http.post(APIBASE + '?request=userVotePolls', {
                data: {
                    userid: userid,
                    page: page,
                    order: order
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.getUserFavPolls = function (userid, page, order) {
            return $http.post(APIBASE + '?request=userFavPolls', {
                data: {
                    userid: userid,
                    page: page,
                    order: order
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.getUserLikedPolls = function (userid, page, order) {
            return $http.post(APIBASE + '?request=userLikedPolls', {
                data: {
                    userid: userid,
                    page: page,
                    order: order
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.getUserDislikedPolls = function (userid, page, order) {
            return $http.post(APIBASE + '?request=userDislikedPolls', {
                data: {
                    userid: userid,
                    page: page,
                    order: order
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.deletePoll = function (pollData) {
            return $http.post(APIBASE + '?request=deletePoll', {
                data: pollData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollMetaData.$inject = ['$http', 'APIBASE'];

    function pollMetaData($http, APIBASE) {
        var pollMetaData = this;
        this.getPollComments = function (pollid, count) {
            return $http.post(APIBASE + '?request=readPollComments', {
                data: {
                    id: pollid,
                    count: count
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.addPollComment = function (pollid, comment, parent) {
            return $http.post(APIBASE + '?request=addPollComment', {
                data: {
                    id: pollid,
                    text: comment,
                    parentid: parent
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.changeuserlike = function (pollid, likeaction) {
            return $http.post(APIBASE + '?request=changePollLikeStatus', {
                data: {
                    id: pollid,
                    action: likeaction
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.changeuserfav = function (pollid, favaction) {
            return $http.post(APIBASE + '?request=changePollFavStatus', {
                data: {
                    id: pollid,
                    action: favaction
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.deleteComment = function (commmentData) {
            return $http.post(APIBASE + '?request=deleteComment', {
                data: commmentData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    fbAuthService.$inject = ['$rootScope', '$http', 'APIBASE', 'cookieService'];

    function fbAuthService($rootScope, $http, APIBASE, cookieService) {
        this.watchLoginChange = function () {

            var _self = this;

            FB.Event.subscribe('auth.statusChange', function (res) {
                if (res.status === 'connected') {
                    cookieService.setCookie("sessionStatus", "active", 365);
                    cookieService.setCookie("authvendor", "FACEBOOK", 365);
                    $http.post(APIBASE + '?request=userdata', {
                        data: res.authResponse
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            $rootScope.user = {
                                email: response.data.userEmail,
                                id: response.data.userId,
                                type: response.data.userType,
                                name: response.data.userName
                            };
                        } else {
                            cookieService.setCookie("sessionStatus", "inactive", -1);
                        }
                    }, function (error) {
                        console.log(error);
                    });

                } else {
                    _self.logout();
                }

            });
            FB.Event.subscribe('auth.login', function (res) {
                if (res.status === 'connected') {
                    _self.getUserInfo();
                    $http.post(APIBASE + '?request=login', {
                        data: {
                            vendor: 'FACEBOOK',
                            authData: res.authResponse
                        }
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            cookieService.setCookie("sessionStatus", "active", 365);
                            cookieService.setCookie("authvendor", "FACEBOOK", 365);
                            $rootScope.user = {
                                email: response.data.userEmail,
                                id: response.data.userId,
                                type: response.data.userType,
                                name: response.data.userName
                            };
                            $rootScope.$broadcast('userloggedin');
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
            if (cookieService.getCookie("authvendor") == "FACEBOOK") {
                FB.logout(function (res) {
                    $http.post(APIBASE + '?request=logout', {
                        data: {}
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            cookieService.setCookie("sessionStatus", "inactive", -1);
                            $rootScope.user = this.user = {};
                            location.href = './';
                        } else {
                            alert(response.error);
                        }
                    }, function (error) {
                        console.log(error);
                    });
                });
            }

        };
    }

    googleAuthService.$inject = ['$rootScope', '$http', 'APIBASE', 'cookieService'];

    function googleAuthService($rootScope, $http, APIBASE, cookieService) {
        this.user = {};
        this.signinSuccess = function (response) {
            var id_token = response.getAuthResponse().id_token;
            if (cookieService.getCookie("sessionStatus") != "active") {
                $http.post(APIBASE + '?request=login', {
                    data: {
                        vendor: 'GOOGLE',
                        token: id_token
                    }
                }).then(function (response) {
                    response = response.data;
                    if (response.status) {
                        cookieService.setCookie("sessionStatus", "active", 365);
                        cookieService.setCookie("authvendor", "GOOGLE", 365);
                        $rootScope.user = {
                            email: response.data.userEmail,
                            id: response.data.userId,
                            type: response.data.userType,
                            name: response.data.userName
                        };
                        $rootScope.$broadcast('userloggedin');
                    } else {
                        console.log(response.error);
                    }
                }, function (error) {
                    console.log(error);
                });
            } else {
                $http.post(APIBASE + '?request=userdata', {
                    data: {}
                }).then(function (response) {
                    response = response.data;
                    if (response.status) {
                        $rootScope.user = {
                            email: response.data.userEmail,
                            id: response.data.userId,
                            type: response.data.userType,
                            name: response.data.userName
                        };
                    } else {
                        cookieService.setCookie("sessionStatus", "inactive", -1);
                        auth2.signOut().then(function(){});
                    }
                }, function (error) {
                    console.log(error);
                });
            }
        };
        this.signinFailure = function (response) {
            console.log(response);
        };
        this.signout = function () {
            if (cookieService.getCookie("authvendor") == "GOOGLE") {
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    $http.post(APIBASE + '?request=logout', {
                        data: {}
                    }).then(function (response) {
                        response = response.data;
                        if (response.status) {
                            cookieService.setCookie("sessionStatus", "inactive", -1);
                            $rootScope.user = this.user = {};
                            location.href = './';
                        } else {
                            alert(response.error);
                        }
                    }, function (error) {
                        console.log(error);
                    });
                });
            }
        };
    }

    emailAuthService.$inject = ['$rootScope', '$http', 'APIBASE', 'cookieService'];

    function emailAuthService($rootScope, $http, APIBASE, cookieService) {
        this.login = function (email, password, remember) {
            return $http.post(APIBASE + '?request=login', {
                data: {
                    vendor: 'EMAIL',
                    authData: {
                        email: email,
                        password: password,
                        remember: remember
                    }
                }
            }).then(function (response) {
                response = response.data;
                if (response.status) {
                    cookieService.setCookie("sessionStatus", "active", 365);
                    cookieService.setCookie("authvendor", "EMAIL", 365);
                }
                return response;
            }, function (error) {
                console.log(error);
            });
        };
        this.logout = function () {
            if (cookieService.getCookie("authvendor") == "EMAIL") {
                $http.post(APIBASE + '?request=logout', {
                    data: {}
                }).then(function (response) {
                    response = response.data;
                    if (response.status) {
                        cookieService.setCookie("sessionStatus", "inactive", -1);
                        $rootScope.user = this.user = {};
                        location.href = './';
                    } else {
                        alert(response.error);
                    }
                }, function (error) {
                    console.log(error);
                });
            }
        };
        this.forgotpswd = function (email) {
            return $http.post(APIBASE + '?request=forgotpswd', {
                data: {
                    authData: {
                        email: email,
                    }
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
        this.signup = function (email) {
            return $http.post(APIBASE + '?request=signup', {
                data: {
                    authData: {
                        email: email,
                    }
                }
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }
    
    UserProfileService.$inject = ['$rootScope', '$http', 'APIBASE'];
    
    function UserProfileService($rootScope, $http, APIBASE) {
        var _self = this;
        this.getProfileData = function () {
            return $http.get(APIBASE + '?request=getUserProfileData', '').then(function(response){
                return response.data;
            },function(error){
                console.log(error);
            });
        };
        this.updateProfileData = function (data) {
            return $http.post(APIBASE + '?request=updateUserProfileData', {data:data}, '').then(function(response){
                return response.data;
            },function(error){
                console.log(error);
            });
        };
    }
})();
