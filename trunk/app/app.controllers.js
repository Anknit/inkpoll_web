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

    categoryHome.$inject = [];

    function categoryHome() {
        var scope = this;
        this.catlist = [
            {
                catName: 'Entertainment'
            },
            {
                catName: 'Sports'
            },
            {
                catName: 'Politics'
            },
            {
                catName: 'Education'
            },
            {
                catName: 'Business'
            }
        ];
    }

    creator.$inject = ['pollEditor'];

    function creator(pollEditor) {
        var scope = this;
        scope.poll = {
            question: '',
            optionArr: ['', '', '']
        };
        scope.createpoll = function () {
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
