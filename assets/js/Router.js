/**
 * Backbone router to determine what should be rendered
 */
define(['backbone'], function(Backbone){
  'use strict';

  return Backbone.Router.extend({
    /**
     * All possible routes should be listed here.
     *
     * The keys of this dictionary are the URI values,
     * the values are the view it should trigger
     */
    routes: {
      '': 'gameday',
      'gameday': 'gameday',
      'gameday/:week': 'gameday',
      'make-picks': 'makepicks',
      'make-picks/:week': 'makepicks'
    }
  });
});