(function(){
  angular.module('authMgr',[])
  .config(configObj)

  function configObj($routeProvider) {
    $routeProvider.when('/login',{
      templateUrl: 'app/modules/auth/layout-root.html',
      controller: 'authCtrl',
      controllerAs: 'Auth',
      data: {
        defaultView: 'login'
      }
    }).when('/signup', {
      templateUrl: 'app/modules/auth/layout-root.html',
      controller: 'authCtrl',
      controllerAs: 'Auth',
      data: {
        defaultView: 'signup'
      }
    }).otherwise({redirectTo:'/'});
  }
})();
