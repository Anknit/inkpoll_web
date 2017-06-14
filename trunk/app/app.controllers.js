(function () {
    angular.module('app')
        .controller('home', home)
        .controller('headerCtrl', headerCtrl)
        .controller('categoryHome', categoryHome)
        .controller('userCtrl', userCtrl)
        .controller('categoryPolls', categoryPolls)
        .controller('creator', creator)
        .controller('polllist', polllist);

    home.$inject = [];

    function home() {
        var scope = this;

    }

    userCtrl.$inject = ['$routeParams', 'pollReader','fbAuthService', 'googleAuthService'];

    function userCtrl($routeParams, pollReader, fbAuthService, googleAuthService) {
        var scope = this;
        this.current = {};
        this.current.id = $routeParams.id;
        this.currentPolls = {
            totalPolls: 0,
            order: 'newest',
            page: 1,
            totalPages: 1,
            list: []
        }
        this.currentVotes = {
            totalVotes: 0,
            order: 'newest',
            page: 1,
            totalPages: 1,
            list: []
        }
        this.logout = function () {
            fbAuthService.logout();
            googleAuthService.signout();
        };

        this.getCurrentPolls = function () {
            pollReader.getUserPolls(this.current.id, this.currentPolls.page, this.currentPolls.order).then(function (response) {
                if (response.status) {
                    scope.currentPolls.totalPolls = response.data.totalPolls;
                    scope.currentPolls.totalPages = response.data.totalPages;
                    scope.currentPolls.list = response.data.polllist;
                } else {
                    console.log(response.error);
                }
            });
        };
        this.getCurrentVotes = function () {
            pollReader.getUserVotePolls(this.current.id, this.currentVotes.page, this.currentVotes.order).then(function (response) {
                if (response.status) {
                    scope.currentVotes.totalVotes = response.data.totalVotes;
                    scope.currentVotes.totalPages = response.data.totalPages;
                    scope.currentVotes.list = response.data.polllist;
                } else {
                    console.log(response.error);
                }
            });
        };
        this.getCurrentVotes();
        this.getCurrentPolls();
    }

    headerCtrl.$inject = ['pollCategories'];

    function headerCtrl(pollCategories) {
        var scope = this;
        this.pollCatArr = [];
        this.showAuthModal = function () {
            jQuery('#auth-modal').modal('show');
        };
        pollCategories.getCategories().then(function (response) {
            if (response.status) {
                scope.pollCatArr = response.data;
            }
        });
    }

    categoryPolls.$inject = ['pollCaster', 'pollReader', '$routeParams'];

    function categoryPolls(pollCaster, pollReader, $routeParams) {
        var scope = this;
        this.list = [];
        this.category = $routeParams.category;

        pollReader.readPolls({
            category: this.category
        }).then(function (response) {
            if (response.status) {
                scope.list = response.data;
            }
        });
        this.showAuthModal = function () {
            jQuery('#auth-modal').modal('show');
        };

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
        pollCategories.getCategories().then(function (response) {
            if (response.status) {
                scope.catlist = response.data;
            }
        });
    }

    creator.$inject = ['pollEditor', 'pollCategories'];

    function creator(pollEditor, pollCategories) {
        var scope = this;
        this.category = {};
        resetPollEditor();
        pollCategories.getCategories().then(function (response) {
            if (response.status) {
                scope.pollCatArr = response.data;
                scope.category = scope.pollCatArr[0];
            }
        });
        this.createpoll = function () {
            scope.poll.category = scope.category.catId;
            if (scope.poll.question.trim() == '') {
                alert('Enter poll question');
                return false;
            }
            for (var i = 0; i < scope.poll.optionArr.length; i++) {
                if (scope.poll.optionArr[i].trim() == '') {
                    alert('You have one or more empty option. Please remove them if not needed');
                    return false;
                }
            }
            pollEditor.addNewPoll(scope.poll).then(function (response) {
                if (response.status) {
                    alert('Success');
                    resetPollEditor();
                }
            });
        };
        this.addoption = function () {
            scope.poll.optionArr.push('');
        };
        this.moveoption = function (index, direction) {
            var pos = index;
            if (direction == 'down') {
                pos++;
            } else {
                pos--;
            }
            scope.poll.optionArr.splice(pos, 0, scope.poll.optionArr.splice(index, 1)[0]);
        };
        this.removeoption = function (index) {
            scope.poll.optionArr.splice(index, 1);
        };

        function resetPollEditor() {
            scope.poll = {
                question: '',
                category: 0,
                optionArr: ['', '', ''],
                anonvote: false
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
        this.showAuthModal = function () {
            jQuery('#auth-modal').modal('show');
        };

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
