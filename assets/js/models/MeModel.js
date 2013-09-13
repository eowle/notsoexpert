/**
 * MeModel handles logged in user status, able to retrieve the logged in status of a user, and their respective user id
 *
 * For a logged out user, user_id will be 0 and logged_in will be false
 */
define(['backbone', 'config'], function(Backbone, config) {
  return Backbone.Model.extend({
    // Default values in case of a sync failure
    defaults: {
      logged_in: false,
       user_id: 0
    },

    /**
     * URL Endpoint to hit to get our data
     *
     * @returns {string}
     */
    url: function() {
      return config.URLs.API + 'login';
    },

    /**
     * After a sync, get the user_id
     *
     * @returns int
     */
    getUserId: function() {
      return this.get('user_id');
    },

    /**
     * After a sync, get whether or not the user is logged in
     *
     * @returns bool
     */
    isLoggedIn: function() {
      return this.get('logged_in');
    }
  });
});