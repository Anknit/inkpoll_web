(function(){
  angular.module('authMgr')
  .service('authService', authService);

  authService.$inject = ['$rootScope', '$http', 'APIBASE', 'cookieService'];
  function authService($rootScope, $http, APIBASE, cookieService) {
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
})();
