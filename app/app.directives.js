(function () {
    angular.module('app')
        .directive('authUserOnly', authUserOnly);

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

})();