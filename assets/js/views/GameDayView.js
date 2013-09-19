define(['backbone',
        'moment',
        'js/models/GameDayModel',
        'text!templates/GameDayTemplate.html',
        'text!templates/MemberRowTemplate.html'],
       function(Backbone, moment, GameDayModel, GameDayTemplate, MemberRowTemplate) {
  return Backbone.View.extend({
    model: null,

    initialize: function() {
      this.model = new GameDayModel({'week': this.options.week});
      this.model.on('sync', this.render, this);
      this.model.fetch();
    },
    renderMembers: function(members) {
      var members_array = [],
          directives = {
            'member_avatar': {
              'src': function() {
                return 'http://www.notsoexpert.com/Assets/images/user_images/' + this.image;
              },
              'data-original-title': function() {
                return '<img src="http://www.notsoexpert.com/Assets/images/user_images/' + this.image + '"/>';
              }
            }
          }

      $.each(members, function(k,v){
        members_array.push(v);
      });

      var rendered_members = $(MemberRowTemplate).render(members_array, directives);
      return rendered_members;
    },

    render: function() {
      var member_template = this.renderMembers(this.model.attributes.members);
      var rendered_template = $(GameDayTemplate).render(this.model.attributes);
      rendered_template.find('.member-row').append(member_template);
      this.$el.append(rendered_template).find('[data-toggle="tooltip"]').tooltip();
    }
  });
});