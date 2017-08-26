(function(){
  angular.module('app')
  .directive('ipInlineFooter',ipInlineFooter);

  ipInlineFooter.$inject = [];
  function ipInlineFooter(){
    return {
      restrict: 'EA',
      replace:true,
      templateUrl:'app/directives/ip-inline-footer/layout.html',
      link: function(scope, elem, attr){

      }
    }
  }
})();
