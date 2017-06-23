(function () {
    angular.module('app')
        .controller('home', home)
        .controller('headerCtrl', headerCtrl)
        .controller('categoryHome', categoryHome)
        .controller('userCtrl', userCtrl)
        .controller('pollPage', pollPage)
        .controller('categoryPolls', categoryPolls)
        .controller('creator', creator)
        .controller('polllist', polllist);

    home.$inject = [];

    function home() {
        var scope = this;

    }

    pollPage.$inject = ['$routeParams'];

    function pollPage($routeParams) {
        var scope = this;
        this.pollId = $routeParams.id;
        this.pollName = $routeParams.name;
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
        this.currentFavs = {
            totalFavs: 0,
            order: 'newest',
            page: 1,
            totalPages: 1,
            list: []
        }
        this.currentLiked = {
            totalLiked: 0,
            order: 'newest',
            page: 1,
            totalPages: 1,
            list: []
        }
        this.currentDisliked = {
            totalDisliked: 0,
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
        this.getCurrentFavs = function () {
            pollReader.getUserFavPolls(this.current.id, this.currentFavs.page, this.currentFavs.order).then(function (response) {
                if (response.status) {
                    scope.currentFavs.totalFavs = response.data.totalFavs;
                    scope.currentFavs.totalPages = response.data.totalPages;
                    scope.currentFavs.list = response.data.polllist;
                } else {
                    console.log(response.error);
                }
            });
        };
        this.getCurrentLiked = function () {
            pollReader.getUserLikedPolls(this.current.id, this.currentLiked.page, this.currentLiked.order).then(function (response) {
                if (response.status) {
                    scope.currentLiked.totalLiked = response.data.totalLiked;
                    scope.currentLiked.totalPages = response.data.totalPages;
                    scope.currentLiked.list = response.data.polllist;
                } else {
                    console.log(response.error);
                }
            });
        };
        this.getCurrentDisliked = function () {
            pollReader.getUserDislikedPolls(this.current.id, this.currentDisliked.page, this.currentDisliked.order).then(function (response) {
                if (response.status) {
                    scope.currentDisliked.totalDisliked = response.data.totalDisliked;
                    scope.currentDisliked.totalPages = response.data.totalPages;
                    scope.currentDisliked.list = response.data.polllist;
                } else {
                    console.log(response.error);
                }
            });
        };
        this.getCurrentPolls();
        this.getCurrentVotes();
        this.getCurrentFavs();
        this.getCurrentLiked();
        this.getCurrentDisliked();
    }

    headerCtrl.$inject = ['pollCategories', 'fbAuthService', 'googleAuthService'];

    function headerCtrl(pollCategories, fbAuthService, googleAuthService) {
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
        this.logout = function () {
            fbAuthService.logout();
            googleAuthService.signout();
        };

    }

    categoryPolls.$inject = ['$routeParams'];

    function categoryPolls($routeParams) {
        var scope = this;
        this.category = $routeParams.category;
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

    polllist.$inject = ['$scope', '$attrs', 'pollCaster', 'pollReader', 'pollMetaData'];

    function polllist($scope, $attrs, pollCaster, pollReader, pollMetaData) {
        var scope = this, configObj = {};
        this.list = [];
        this.pIndex=1;
        this.category = '';
        this.pollId = 0;
        this.listCompleted = false;
        if($attrs.type == 'category-polls') {
            this.category = $scope.catPoll.category;
        } else if($attrs.type == 'single-poll') {
            this.pollId = $scope.poll.pollId;
        }
        this.loadPolls = function() {
            if(this.category != '') {
                configObj['category'] = this.category;
            } else if(this.pollId != 0) {
                configObj['pollid'] = this.pollId;
            }
            configObj['index'] = this.pIndex;
            pollReader.readPolls(configObj).then(function (response) {
                if (response.status) {
                    scope.list = scope.list.concat(response.data);
                    if(response.data.length == 0) {
                        scope.listCompleted = true;
                    }
                }
            });
        };
        this.loadmore = function() {
            this.pIndex++;
            this.loadPolls();
        };
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
        this.loadPolls();
        this.likepoll = function (pollItem) {
            var poll = pollItem;
            if(pollItem.userlikescore == 1) {
                pollMetaData.changeuserlike(pollItem.id, 'unlike').then(function(response){
                    if(response.status) {
                        poll.likecount -= 1;
                        pollItem.userlikescore -= 1;
                    } else {
                        console.log(response.error);
                    }
                });
            } else {
                pollMetaData.changeuserlike(pollItem.id, 'like').then(function(response){
                    if(response.status) {
                        poll.likecount += 1;
                        if(pollItem.userlikescore == -1) {
                            poll.dislikecount -= 1;
                            pollItem.userlikescore += 1;
                        }
                        pollItem.userlikescore += 1;
                    } else {
                        console.log(response.error);
                    }
                });
            }
        };
        this.dislikepoll = function (pollItem) {
            var poll = pollItem;
            if(pollItem.userlikescore == -1) {
                pollMetaData.changeuserlike(pollItem.id, 'unlike').then(function(response){
                    if(response.status) {
                        poll.dislikecount -= 1;
                        pollItem.userlikescore += 1;
                    } else {
                        console.log(response.error);
                    }
                });
            } else {
                pollMetaData.changeuserlike(pollItem.id, 'dislike').then(function(response){
                    if(response.status) {
                        poll.dislikecount += 1;
                        if(pollItem.userlikescore == 1) {
                            poll.likecount -= 1;
                            pollItem.userlikescore -= 1;
                        }
                        pollItem.userlikescore -= 1;
                    } else {
                        console.log(response.error);
                    }
                });
            }
        };
        this.favpoll = function (pollItem) {
            var poll = pollItem;
            if(pollItem.userfavscore == 1) {
                pollMetaData.changeuserfav(pollItem.id, 'unfavorite').then(function(response){
                    if(response.status) {
                        pollItem.userfavscore -= 1;
                    } else {
                        console.log(response.error);
                    }
                });
            } else {
                pollMetaData.changeuserfav(pollItem.id, 'favorite').then(function(response){
                    if(response.status) {
                        pollItem.userfavscore += 1;
                    } else {
                        console.log(response.error);
                    }
                });
            }
        };
        this.getComments = function(item){
            var pollitem = item;
            pollMetaData.getPollComments(item.id).then(function(response){
                if(response.status) {
                    pollitem.comments = response.data;
                } else {
                    console.log(response.error);
                }
            });
        }
    }

})();
