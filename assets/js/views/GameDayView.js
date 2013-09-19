define(['backbone',
        'moment',
        'js/models/GameDayModel',
        'text!templates/GameDayTemplate.html',
        'text!templates/MemberRowTemplate.html',
        'text!templates/MemberPicksTemplate.html'],
       function(Backbone, moment, GameDayModel, GameDayTemplate, MemberRowTemplate, MemberPicksTemplate) {
  return Backbone.View.extend({
    model: null,

    initialize: function() {
      this.model = new GameDayModel({'week': this.options.week});
      this.model.on('sync', this.render, this);
      this.model.fetch();
    },

    renderPicks: function(schedule, members)
    {
      var tmpObj = {},
          cur_pick = '',
          rendered_template,
          toRender = [],
          directives = {
            'game_time': {
              text: function() {
                return moment(this.game_time).format('dddd h:mm');
              }
            }
          };

      $(schedule).each(function(k, game){
        tmpObj = game;
        tmpObj.member_picks = [];
        $.each(members, function(member_id, member_data) {
          if(member_data.picks && member_data.picks.hasOwnProperty(game.game_id)) {
            if(member_data.picks[game.game_id].pick === true) {
              cur_pick = 'MADE'
            }
            else {
              cur_pick = member_data.picks[game.game_id].pick;
            }
          }
          else {
            cur_pick = "NOT";
          }

          tmpObj.member_picks.push({'member_pick': cur_pick});
        });

        toRender.push(tmpObj);
      });

      rendered_template = $(MemberPicksTemplate).render(toRender, directives);
      return rendered_template;
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
      member_template.prepend('<div class="schedule-box" style="visibility: hidden; margin-top: 24px;"></div>');
      var picks_template = this.renderPicks(this.model.attributes.schedule, this.model.attributes.members);
      var rendered_template = $(GameDayTemplate).render(this.model.attributes);

      rendered_template.find('.member-row').append(member_template).end()
                       .find('.member-picks-row').append(picks_template);
      this.$el.append(rendered_template).find('[data-toggle="tooltip"]').tooltip();
    }
  });
});