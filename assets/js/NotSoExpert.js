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
    week: 1,
    /**
     * Set up our router, MeModel, and kick off to the requested page
     *
     * @returns void
     */
    initialize: function() {
      this.me = new MeModel();
      this.me.on('sync', this.startRouting, this);
      this.me.fetch();
    },

    /**
     * Create our routes and begin creating the page
     *
     * @return void
     */
    startRouting: function() {
      var _this = this;
      this.router = new Router();

      this.router.on('route:gameday', function(week) {
        _this.setWeekOrDefault(week);
        _this.displayGameday();
      });

      Backbone.history.start({pushState: true});
    },

    /**
     * Build out our nav bar
     *
     * @param week
     * @return void
     */
    buildNav: function(week) {
      new NavbarView({'logged_in': this.me.isLoggedIn(), 'el': $('.nfl-nav-bar'), 'week': week});
    },

    /**
     * Show the GameDay view for the given week
     *
     * @param week
     * @returns void
     */
    displayGameday: function() {
      this.buildNav(this.week);
      var $el = $('.content'),
          _this = this;
      require(['js/views/GameDayView'], function(GameDayView){
        new GameDayView({'week': _this.week, 'el': $el});
      });
    },

    /**
     * Set the week to given week, or go to the server and get the default week
     *
     * @param week
     * @param callback
     * @return void
     */
    setWeekOrDefault: function(week) {
      if(!week)
      {
        var now = new Date().getTime(),
            start_time = new Date(2013, 8, 3, 11, 0, 0, 0).getTime(),
            time_diff = (now - start_time) / 1000;
        week = Math.ceil(time_diff/(7*86400));
      }

      this.week = week;
    }
  };
});