'use strict';

(function(){
  angular.module('categoryManagement',['ngRoute'])
  .config(function($routeProvider){
    $routeProvider.when("/admin/categories", {
      templateUrl: 'app/modules/admin/category-management/layout-root.html',
      controller: 'catMgrCtrl',
      controllerAs: 'CatMgr'
    }).otherwise({
      redirectTo: '/'
    });
  })
  .controller('catMgrCtrl',catMgrCtrl);

  catMgrCtrl.$inject = ['$rootScope','catMgrService'];
  function catMgrCtrl(catMgrService, $rootScope) {
    var scope = this;
    catMgrService.getCategories('all').then(function(response){
      if(response.status){
        scope.categoryList = response.data;
      } else {
        console.log(response.error);
      }
    })
  }
})();
