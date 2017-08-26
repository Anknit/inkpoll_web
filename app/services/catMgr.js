(function(){
  angular.module('app')
    .service('catMgrService',catMgrService);

  catMgrService.$inject = ['$http', 'APIBASE'];
  function catMgrService($http, APIBASE){
    function getCategories(type){
      if(!type){
        var type = 'active';
      }
      return $http.get(APIBASE + '?request=getCategories&type='+type).then(function(response){
        return response.data;
      }, function(error){
        console.error(error);
      });
    }
    return {
      getCategories:getCategories
    };
  }
})();
