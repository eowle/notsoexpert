define(['backbone'], function(Backbone){
  'use strict';

  return Backbone.Router.extend({
    routes: {
      '': 'gameday',
      'gameday': 'gameday',
      'gameday/:week': 'gameday'
    }
  });
});