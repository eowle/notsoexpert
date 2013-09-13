/**
 * The nav bar will change dependent on whether or not the user is logged in,
 * however this view handles both cases
 */
define(['backbone', 'transparency', 'config', 'js/views/LoginModalView', 'text!templates/Navbar.html'],
       function(Backbone, transparency, config, LoginModalView, NavbarTemplate){
  return Backbone.View.extend({
    /**
     * Render options
     */
    directives: {
      'make-picks-link': {
        'href': function() {
          return config.URLs.Base + 'makepicks/' + this.week;
        }
      }
    },

    /**
     * The template loaded in from NavbarTemplate
     *
     * @var jQuery Object
     */
    $template: null,

    /**
     * On construction, get whether or not we want to use the logged in or logged out template,
     * then render it to our index
     *
     * @return void
     */
    initialize: function() {
      if(this.options.logged_in === true)
      {
        this.$template = $(NavbarTemplate).find('.logged-in-nav');
      }
      else
      {
        this.appendLoginModal();
        this.$template = $(NavbarTemplate).find('.logged-out-nav');
      }

      this.render();
    },

    /**
     * Render our nav bar, and append it to our main dom
     */
    render: function() {
      var render_params = {'week': this.options.week},
          rendered_template = this.$template.render(render_params, this.directives);
      this.$el.append(rendered_template);
    },

    /**
     * If the user is logged out, we'll also append the login modal to the app
     */
    appendLoginModal: function() {
      new LoginModalView();
    }
  });
});