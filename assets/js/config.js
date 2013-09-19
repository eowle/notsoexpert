/**
 * Configuration variables.
 */
define([], function(){
  if(window.location.href.indexOf('localhost') !== -1)
  {
    return {
      'URLs': {
        'Base':'http://localhost/',
        'API': 'http://localhost/api/'
      }
    }
  }

  return {
    'URLs': {
      'Base': 'http://notsoexpert-dev.elasticbeanstalk.com/',
      'API':  'http://notsoexpert-dev.elasticbeanstalk.com/api/'
    }
  }
});