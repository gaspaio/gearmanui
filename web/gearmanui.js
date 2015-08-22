/*
 * Configure services
 */

var gearmanui = angular.module('gearmanui', ['ngResource', 'ngRoute'])

    // Configure routes
    .config(['$routeProvider', function ($routeProvider) {
        $routeProvider
            .when('/status', {templateUrl:'status'})
            .when('/workers', {templateUrl:'workers'})
            .when('/servers', {templateUrl:'servers'})
            .otherwise({redirectTo:'/status'});
    }])

    // Service : Gearman info callback
    .factory('GearmanInfo', function ($resource) {
        return $resource('info', {});
    })

    // Service : Handle server errors
    .factory('GearmanErrorHandler', function () {

        var wrapper = {};

        wrapper.get = function (data) {
            var errors = [];

            data.forEach(function (element, index, array) {
                if ('error' in element) {
                    errors.push(element.error);
                }
            });

            // Make sure that a list containing the same errors is always shown in the same order.
            return errors.sort();
        };

        return wrapper;
    })

    // Service : Transfort server incomming data info model tables.
    .factory('GearmanInfoHandler', function () {

        var wrapper = {};

        // Servers Table
        wrapper.servers = function(data) {
            var serversTable = [];
            data.forEach(function (element, index, array) {
                serversTable[index] = {
                    name: element.name,
                    addr: element.addr,
                    version: element.version,
                    up: element.up ? "Running" : "Unreachable"
                };

                serversTable[index].nb_workers = null;
                if (element.up && 'workers' in element) {
                    serversTable[index].nb_workers = element.workers.length;
                }

                serversTable[index].rowClass = element.up ? '' : 'error';
            });

            return serversTable.sort(function (s1, s2) {
                return s1.name.localeCompare(s2);
            });
        };

        // Workers Table
        wrapper.workers = function(data) {
            var counter = 0;
            var workersTable = [];

            data.forEach(function (element, index, array) {
                if (element.up && 'workers' in element) {
                    for (var i=0 ; i < element.workers.length ; i++) {
                        workersTable[counter++] = {
                            id: element.workers[i].id,
                            fd: element.workers[i].fd,
                            ip: element.workers[i].ip,
                            abilities: element.workers[i].abilities.join(', '),
                            server: element.name
                        };
                    }
                }
            });

            // Default order: file descriptor
            return workersTable.sort(function(w1, w2) {
                return w1.fd - w2.fd;
            });
        };

        // Status table
        wrapper.status = function(data) {
            var f;
            var counter = 0;
            var statusTable = [];

            data.forEach(function (element, index, array) {
                for (f in element.status) {
                    statusTable[counter++] = {
                        function: f,
                        queued: parseInt(element.status[f].in_queue, 10),
                        running: parseInt(element.status[f].jobs_running, 10),
                        workers: parseInt(element.status[f].capable_workers, 10),
                        server: element.name
                    };
                }
            });

            // Default order: function name, then server name.
            return statusTable.sort(function(s1, s2) {
                if (s1.function == s2.function) {
                    return s1.server.localeCompare(s2.server);
                }
                else {
                    return s1.function.localeCompare(s2.function);
                }
            });
        };

        return wrapper;
    });

/*
 * Controllers
 */
gearmanui.controller('NavigationCtrl', function($scope, $location) {
    $scope.getClass = function (path) {
        if ($location.path().substr(0, path.length) == path) {
            return "active";
        } else {
            return "";
        }
    };
});

gearmanui.controller('InfoCtrl', function($scope, GearmanSettings, GearmanInfo, GearmanInfoHandler, GearmanErrorHandler) {
    /*
     * TODO Handle communication errors.
     */
    function setInfo() {
        GearmanInfo.query(function (data) {
            // Update model with the massaged data.
            $scope.errors = GearmanErrorHandler.get(data);
            $scope.status = GearmanInfoHandler.status(data);
            $scope.workers = GearmanInfoHandler.workers(data);
            $scope.servers = GearmanInfoHandler.servers(data);
        }, function (err) {
            // Handle server errors
            $scope.errors = GearmanErrorHandler.get(
                [{'error': "Server Error while accessing URL '/info': "+ err.status + " - " + err.statusText}]
            );
        });
    }

    window.setInterval(setInfo, GearmanSettings.refreshInterval * 1000);
    setInfo();
});
