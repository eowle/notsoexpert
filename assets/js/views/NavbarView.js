/**
 * The nav bar will change dependent on whether or not the user is logged in,
 * however this view handles both cases
 */
define(['backbone', 'transparency', 'text!templates/Navbar.html'], function(Backbone, transparency, NavbarTemplate){
  return Backbone.View.extend({
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
        this.$template = $(NavbarTemplate).find('.logged-out-nav');
      }

      this.render();
    },

    /**
     * Render our nav bar, and append it to our main dom
     */
    render: function() {
      var renderedTemplate = this.$template.render({});
      this.$el.append(renderedTemplate);
    },

    /**
     * If the user is logged out, we'll also append the login modal to the app
     */
    appendLoginModal: function() {

    }
  });
});