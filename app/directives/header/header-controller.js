(function(){
  angular.module('app')
  .controller('headerCtrl', headerCtrl)

  headerCtrl.$inject = ['pollCategories'];
  function headerCtrl(pollCategories){
    var scope = this;
    this.pollCatArr = [];
    pollCategories.getCategories().then(function (response) {
      if (response.status) {
        scope.pollCatArr = response.data;
      }
    });
  }
})();
