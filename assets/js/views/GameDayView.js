define(['backbone', 'js/models/GameDayModel', 'text!templates/GameDayTemplate.html'],
       function(Backbone, GameDayModel, GameDayTemplate) {
  return Backbone.View.extend({
    model: null,
    initialize: function() {
      this.model = new GameDayModel({'week': this.options.week});
      this.model.on('sync', this.render, this);
      this.model.fetch();
    },
    render: function() {
      var rendered_template = $(GameDayTemplate).render(this.model.attributes);
      this.$el.append(rendered_template);
    }
  });
});