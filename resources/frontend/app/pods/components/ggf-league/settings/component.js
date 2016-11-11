import Ember from 'ember';

export default Ember.Component.extend({
  tournament: null,

  name: Ember.computed.oneWay('league.name'),
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
