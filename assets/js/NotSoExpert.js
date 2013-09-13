/**
 * This is the driving functionality behind the app.  This sets up the router, and starts configuring/rendering
 * the views for the requested route
 */
define(['backbone',
        'bootstrap',
        'js/Router',
        'js/models/MeModel',
        'js/views/NavbarView'],
        function(Backbone, bootstrap, Router, MeModel, NavbarView) {
  return {
    /**
     * Instance of MeModel
     */
    me: null,

    /**
     * Set up our router, MeModel, and kick off to the requested page
     *
     * @returns void
     */
    initialize: function() {
      this.me = new MeModel();
      this.me.on('sync', this.startRouting, this);
      this.me.on('sync', this.buildNav, this);
      this.me.fetch();
    },

    /**
     * Create our routes and begin creating the page
     *
     * @return void
     */
    startRouting: function() {
      this.router = new Router();
      this.router.on('route:gameday', this.displayGameday);
      Backbone.history.start({pushState: true});
    },

    /**
     * Build out our nav bar
     *
     * @return void
     */
    buildNav: function() {
      new NavbarView({'logged_in': this.me.isLoggedIn(), 'el': $('.nfl-nav-bar')});
    },

    /**
     * Show the GameDay view for the given week
     *
     * @param week
     * @returns void
     */
    displayGameday: function(week) {

    }
  };
});