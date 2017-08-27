(function () {
  angular.module('app')
  .directive('authUserOnly', authUserOnly)
  .directive('fbShare', fbShare)
  .directive("fileSelect", fileSelect)
  .directive('pollList', pollList);

  authUserOnly.$inject = ['$rootScope', '$location', '$timeout'];

  function authUserOnly($rootScope, $location, $timeout) {
    var DDO = {
      restrict: 'AC',
      scope:{
        redirect:'='
      },
      link: function (scope, elem, attr) {
        elem[0].onclick = function () {
          if (!$rootScope.user.name) {
            if(scope.redirect){
              $rootScope.redirectUrl = scope.redirect;
            } else {
              $rootScope.redirectUrl = $location.path();
            }
            $timeout(function(){
              $location.path('login');
            },0);
          }
        };
      }
    };
    return DDO;
  }

  pollList.$inject = [];

  function pollList() {
    var DDO = {
      restrict: 'E',
      templateUrl: 'views/partials/poll-list.html',
      controller: 'polllist',
      controllerAs: 'plst'
    };
    return DDO;
  }

  fbShare.$inject = [];

  function fbShare() {
    return {
      restrict: 'A',
      link: function (scope, element, attr) {
        var attrs = attr;
        element.on('click', function () {
          FB.ui({
            method: 'share',
            href: 'https://inkpoll.com/' + attrs.href
          }, function (response) {
            console.log(response);
          });
        });
      }
    };
  }

  fileSelect.$inject = [];

  function fileSelect() {
    return {
      scope: {
        fileread: "="
      },
      link: function (scope, el) {
        el.bind("change", function (e) {
          var file = (e.srcElement || e.target).files[0];
          var reader = new FileReader();
          reader.onload = function (event) {
            scope.$apply(function(){
              scope.fileread = event.target.result;
            })
          }
          reader.readAsDataURL(file);
        });
      }
    }
  }
})();
