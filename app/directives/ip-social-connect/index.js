(function(){
  angular.module('app')
  .directive('ipSocialConnect',ipSocialConnect);

  ipSocialConnect.$inject = [];
  function ipSocialConnect(){
    return {
      restrict: 'EA',
      replace:true,
      templateUrl:'app/directives/ip-social-connect/layout.html',
      link: function(scope, elem, attr){

      }
    }
  }
})();
