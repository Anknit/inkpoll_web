(function(){
  angular.module('app')
  .directive('siteHeader',siteHeader);

  siteHeader.$inject = [];
  function siteHeader(){
    return {
      restrict: 'EA',
      replace:true,
      scope:{
        showCategories: '='
      },
      templateUrl:'app/directives/header/layout-root.html',
      controller:'headerCtrl',
      controllerAs:'head',
      link: function(scope, elem, attr){

      }
    }
  }
})();
