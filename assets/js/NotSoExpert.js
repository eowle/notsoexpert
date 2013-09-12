define(['backbone', 'bootstrap', 'js/Router'], function(Backbone, bootstrap, Router) {
  return {
    initialize: _.once(function() {
      this.router = new Router();
      this.router.on('route:gameday', this.displayGameday);
      Backbone.history.start({pushState: true});
    }),

    displayGameday: function(week) {
      
    }
  };
});