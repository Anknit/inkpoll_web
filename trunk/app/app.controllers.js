(function () {
    angular.module('app')
        .controller('home', home)
        .controller('categoryHome', categoryHome)
        .controller('categoryPolls', categoryPolls)
        .controller('creator', creator)
        .controller('polllist', polllist);

    home.$inject = [];

    function home() {
        var scope = this;

    }

    categoryPolls.$inject = ['pollCaster', 'pollReader', '$routeParams'];

    function categoryPolls(pollCaster, pollReader, $routeParams) {
        var scope = this;
        this.list = [];
        this.category = $routeParams.category;

        pollReader.readPolls({category: this.category}).then(function (response) {
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
        resetPollEditor();
        pollCategories.getCategories().then(function(response){
            if(response.status) {
                scope.pollCatArr = response.data;
                scope.category = scope.pollCatArr[0];
            }
        });
        this.createpoll = function () {
            scope.poll.category = scope.category.catId;
            if(scope.poll.question.trim() == '') {
                alert('Enter poll question');
                return false;
            }
            for(var i=0; i< scope.poll.optionArr.length; i++) {
                if(scope.poll.optionArr[i].trim() == '') {
                    alert('You have one or more empty option. Please remove them if not needed');
                    return false;
                }
            }
            pollEditor.addNewPoll(scope.poll).then(function(response){
                if(response.status) {
                    alert('Success');
                    resetPollEditor();
                }
            });
        };
        this.addoption = function () {
            scope.poll.optionArr.push('');
        };
        this.moveoption = function(index, direction) {
            var pos = index;
            if(direction == 'down') {
                pos++;
            } else {
                pos--;
            }
            scope.poll.optionArr.splice(pos, 0 , scope.poll.optionArr.splice(index, 1)[0]);
        };
        this.removeoption = function(index) {
            scope.poll.optionArr.splice(index, 1);
        };
        function resetPollEditor() {
            scope.poll = {
                question: '',
                category: 0,
                optionArr: ['', '', '']
            };
        };
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
