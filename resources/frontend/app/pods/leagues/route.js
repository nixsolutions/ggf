import Ember from 'ember';

export default Ember.Route.extend({
  model: function() {
    return this.store.findAll('league');
  },

  actions: {
    createLeague: function() {
      return this.transitionTo('leagues.new');
    }
  }
});
