(function(){
  angular.module('app')
  .directive('ipFbVideo',ipFbVideo);

  ipFbVideo.$inject = [];
  function ipFbVideo(){
    return {
      restrict: 'EA',
      replace:true,
      scope:{
        url: '='
      },
      templateUrl:'app/directives/ip-fb-video/layout.html',
      link: function(scope, elem, attr){

      }
    }
  }
})();
