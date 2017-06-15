(function () {
    angular.module('app')
        .directive('authUserOnly', authUserOnly)
        .directive('pollList', pollList);

    authUserOnly.$inject = ['$rootScope'];

    function authUserOnly($rootScope) {
        var DDO = {
            restrict: 'AC',
            link: function(scope, elem, attr) {
                elem[0].onclick = function () {
                    if(!$rootScope.user.name) {
                        jQuery('#auth-modal').modal('show');
                        event.preventDefault();
                        event.stopPropagation();
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

})();