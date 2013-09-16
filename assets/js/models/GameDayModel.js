define(['backbone', 'config'], function(Backbone, config){
  return Backbone.Model.extend({
    url: function() {
      return config.URLs.API + 'gameday/' + this.get('week');
    }
  })
});