(function () {
    angular.module('app')
        .service('pollCaster', pollCaster)
        .service('pollReader', pollReader)
        .service('pollEditor', pollEditor);

    pollEditor.$inject = ['$http', 'APIBASE'];

    function pollEditor($http, APIBASE) {
        var pollEditor = this;
        this.addNewPoll = function (pollData) {
            return $http.post(APIBASE + '?request=newpoll', {
                data: pollData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollCaster.$inject = ['$http', 'APIBASE'];

    function pollCaster($http, APIBASE) {
        var pollCaster = this;
        this.castVote = function (pollData) {
            return $http.post(APIBASE + '?request=castvote', {
                data: pollData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

    pollReader.$inject = ['$http', 'APIBASE'];

    function pollReader($http, APIBASE) {
        var pollReader = this;
        this.readPolls = function (readData) {
            return $http.post(APIBASE + '?request=readPolls', {
                data: readData
            }).then(function (response) {
                return response.data;
            }, function (error) {
                console.log(error);
            });
        };
    }

})();
