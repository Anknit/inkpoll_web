(function(){
  angular.module('authMgr')
  .controller('authCtrl', authCtrl);

  authCtrl.$inject = ['defaultView'];
  function authCtrl(defaultView) {
    var scope = this;
    this.authmode = defaultView;
  }
})();
