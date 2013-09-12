(function() {
  'use strict';

  require.config({
    baseUrl: '/assets',
    paths: {
      'backbone': 'backbone/backbone-min',
      'bootstrap': 'bootstrap/js/bootstrap.min',
      'jquery': 'jQuery/jquery-2.0.3.min',
      'text': 'require/text',
      'transparency': 'transparency/transparency.min',
      'underscore': 'underscore/underscore-min'
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

  require(['js/NotSoExpert'], function(NotSoExpert){
    NotSoExpert.initialize();
  });
}());
