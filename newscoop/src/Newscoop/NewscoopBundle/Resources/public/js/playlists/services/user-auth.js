(function () {
    'use strict';

    /**
    * Constructor function for the login modal controller
    *
    * @class ModalLoginCtrl
    */
    function ModalLoginCtrl($modalInstance, $window) {
        // On successful login, Newscoop login form redirects user to some
        // redirect URL and that URL contains the new authentication token.
        // Upon redirect, the iframe in modal body is reloaded and its
        // Javascript code extracts the token from the URL. On session
        // storage change login modal will be closed.
        angular.element($window).on('storage', function() {
            if ($window.sessionStorage.getItem('newscoop.token')) {
                $modalInstance.close();
            }
        });
    }

    ModalLoginCtrl.$inject = ['$modalInstance', '$window'];

    /**
    * A service for managing user authentication.
    *
    * @class userAuth
    */
    angular.module('playlistsApp').service('userAuth', [
        '$http',
        '$modal',
        '$q',
        '$window',
        function ($http, $modal, $q, $window) {
            var self = this;

            /**
            * Returns the current oAuth token in sessionStorage.
            *
            * @method token
            * @return {String} the token itself or null if does not exist
            */
            self.token = function () {
                return $window.sessionStorage.getItem('newscoop.token');
            };

            /**
            * Returns true if the current user is authenticated (=has a token),
            * otherwise false.
            *
            * @method isAuthenticated
            * @return {Boolean}
            */
            self.isAuthenticated = function () {
                return !!$window.sessionStorage.getItem('newscoop.token');
            };

            /**
            * Opens a modal with Newscoop login form. On successful login it
            * stores the new authentication token into session storage and
            * resolves given promise with it.
            *
            * @method newTokenByLoginModal
            * @return {Object} promise object
            */
            self.newTokenByLoginModal = function () {
                var deferred = $q.defer(),
                    dialog;

                dialog = $modal.open({
                    templateUrl: '/bundles/newscoopnewscoop/views/modal-login.html',
                    controller: ModalLoginCtrl,
                    controllerAs: 'ctrl',
                    windowClass: 'modalLogin',
                    backdrop: 'static'
                });

                dialog.result.then(function () {
                    flashMessage(Translator.trans('Successfully refreshed authentication token.', {}, 'messages'));
                    deferred.resolve();
                })
                .catch(function (reason) {
                    flashMessage(Translator.trans('Failed to refresh authentication token.', {}, 'messages'), 'error');
                    deferred.reject(reason);
                });

                return deferred.promise;
            };
        }
    ]);
}());
