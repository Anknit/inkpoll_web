(function(){
  angular.module('app')
  .directive('ipAuthMgr',ipAuthMgr);

  ipAuthMgr.$inject = ['authService', '$location', '$rootScope'];
  function ipAuthMgr(authService, $location, $rootScope){
    return {
      restrict: 'EA',
      replace:true,
      scope:{
        authmode: '='
      },
      templateUrl:'app/directives/ip-auth-mgr/layout-root.html',
      link: function(scope, elem, attr){
        scope.authvars = {
          login: {
            email: '',
            password: '',
            remember: true
          },
          signup: {
            email: ''
          },
          forgot: {
            email: ''
          }
        };
        scope.authaction = {
          login: function () {
            var email = scope.authvars.login.email.trim();
            var pswd = scope.authvars.login.password.trim();
            var remember = scope.authvars.login.remember;
            if (email != '' && pswd != '') {
              authService.login(email, pswd, remember).then(function (response) {
                if (response.status) {
                  if ($rootScope.redirectUrl) {
                    $location.path($rootScope.redirectUrl);
                    $rootScope.redirectUrl = '';
                  } else {
                    location.path('/');
                  }
                } else {
                  alert(response.error);
                }
              });
            } else {
              alert('Please enter valid login credentials');
            }
          },
          forgotpswd: function () {
            var email = scope.authvars.forgot.email.trim();
            if (email != '') {
              authService.forgotpswd(email).then(function (response) {
                if (response.status) {
                  alert('Check your email address for password reset instructions');
                } else {
                  alert(response.error);
                }
              });
            } else {
              alert('Please enter valid email address');
            }
          },
          signup: function () {
            var email = scope.authvars.signup.email.trim();
            if (email != '') {
              authService.signup(email).then(function (response) {
                if (response.status) {
                  alert('Check your email address for account activation link');
                } else {
                  alert(response.error);
                }
              });
            } else {
              alert('Please enter valid email address');
            }
          }
        };
        scope.logout = function () {
          authService.logout();
        };

      }
    }
  }
})();
