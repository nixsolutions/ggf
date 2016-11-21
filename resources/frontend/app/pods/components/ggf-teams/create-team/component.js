import Ember from 'ember';

const {
  Component
} = Ember;

export default Component.extend({
  leagueTeam: null,

  name: Ember.computed.alias('leagueTeam.name'),

  actions: {
    create() {
      const params = this.getProperties('name', 'logo');

      this.sendAction('submit', params);
    },

    save() {
      const params = this.getProperties('name', 'logo');

      this.sendAction('submit', params);
    }
  }
});
