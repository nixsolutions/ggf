import Ember from 'ember';

export default Ember.Component.extend({
  league: null,

  name: Ember.computed.alias('league.name'),
  logo: Ember.computed.alias('league.logo'),
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
