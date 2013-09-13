/**
 * Entry point used by require.js.
 *
 * This sets up all of our common base libraries.  Any specific Models/Views/Collections will be included by
 * the components that use them.
 */
(function() {
  'use strict';

  /**
   * Require.js config.  Assumes that our assets directory will always be found in {BASEDIR}/assets.
   */
  require.config({
    baseUrl: '/assets',
    paths: {
      'backbone': 'backbone/backbone-min',
      'bootstrap': 'bootstrap/js/bootstrap.min',
      'jquery': 'jQuery/jquery-2.0.3.min',
      'text': 'require/text',
      'transparency': 'transparency/transparency.min',
      'underscore': 'underscore/underscore-min',
      'config': 'js/config'
    },
    shim: {
      'backbone': {
        deps: ['underscore', 'jquery'],
        exports: 'Backbone'
      },
      'bootstrap': {
        deps: ['jquery'],
        exports: 'Bootstrap'
      },
      'transparency': {
        deps: ['jquery'],
        exports: 'transparency'
      },
      'underscore': {
        exports: '_'
      }
    }
  });

  /**
   * Require our app that initializes the components we need, and build it out
   */
  require(['js/NotSoExpert'], function(NotSoExpert){
    NotSoExpert.initialize();
  });
}());
