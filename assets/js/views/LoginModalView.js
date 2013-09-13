define(['backbone', 'config', 'text!templates/LoginModal.html'], function(Backbone, config, LoginModalTemplate) {
  return Backbone.View.extend({
    events: function() {
      return {
        'click .login-button': this.validateLogin
      }
    },

    initialize: function() {
      this.render();
    },

    render: function() {
      var rendered_template = $(LoginModalTemplate).render({});
      $('body').append(rendered_template);
      this.$el = rendered_template;
    },

    validateLogin: function() {
      var username = this.$el.find('#username').val(),
          password = this.$el.find('#password').val();

      if(username.length === 0 || password.length === 0)
      {
        this.displayErrorMessage('Looks like you forgot something here...')
        return;
      }

      this.tryLogin(username, password);
    },

    tryLogin: function(username, password) {
      var _this = this;

      $.ajax({
        url: config.URLs.API + 'login',
        type: 'POST',
        data: {'username': username, 'password': password},
        success: function(response) {
          if(response.success === false)
          {
            _this.displayErrorMessage('Whoa there, care to fix that?');
          }
          else if(response.success === true)
          {
            window.location.reload();
          }
        },
        error: function() {
          _this.displayErrorMessage('Hrmph, something is really wrong here.');
        }
      })
    },

    displayErrorMessage: function(message) {
      this.$el.find('.error-message').text(message);
    }
  });
});