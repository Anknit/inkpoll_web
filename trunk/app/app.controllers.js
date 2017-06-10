(function () {
    angular.module('app')
        .controller('home', home)
        .controller('categoryHome', categoryHome)
        .controller('creator', creator)
        .controller('polllist', polllist);

    home.$inject = [];

    function home() {
        var scope = this;

    }

    categoryHome.$inject = ['pollCategories'];

    function categoryHome(pollCategories) {
        var scope = this;
        this.catlist = [];
        pollCategories.getCategories().then(function(response){
            if(response.status) {
                scope.catlist = response.data;
            }
        });
    }

    creator.$inject = ['pollEditor', 'pollCategories'];

    function creator(pollEditor, pollCategories) {
        var scope = this;
        this.category = {};
        pollCategories.getCategories().then(function(response){
            if(response.status) {
                scope.pollCatArr = response.data;
                scope.category = scope.pollCatArr[0];
            }
        });
        this.poll = {
            question: '',
            optionArr: ['', '', '']
        };
        this.createpoll = function () {
            scope.poll.category = scope.category.catId;
            pollEditor.addNewPoll(scope.poll);
        }
    }

    polllist.$inject = ['pollCaster', 'pollReader'];

    function polllist(pollCaster, pollReader) {
        var scope = this;
        this.list = [];

        pollReader.readPolls({}).then(function (response) {
            if (response.status) {
                scope.list = response.data;
            }
        });
        this.submitPollVote = function (pollItem) {
            var voteData = {
                pollItemId: pollItem.id,
                voteOption: pollItem.userChoice
            }
            pollCaster.castVote(voteData).then(function (response) {
                if (response.status) {
                    alert('Success');
                } else {
                    alert(response.error);
                }
            });
        };
    }

})();
