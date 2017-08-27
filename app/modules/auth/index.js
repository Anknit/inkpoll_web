(function(){
  angular.module('authMgr',[])
  .config(configObj)

  function configObj($routeProvider) {
    $routeProvider.when('/login',{
      templateUrl: 'app/modules/auth/layout.html',
      controller: 'authCtrl',
      controllerAs: 'Auth',
      resolve: {
        defaultView: function (){ return 'login';}
      }
    }).when('/signup', {
      templateUrl: 'app/modules/auth/layout.html',
      controller: 'authCtrl',
      controllerAs: 'Auth',
      resolve: {
        defaultView: function (){ return 'signup';}
      }
    }).otherwise({redirectTo:'/'});
  }
})();
