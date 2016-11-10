import Ember from 'ember';
import ApplicationRouteMixin from 'simple-auth/mixins/application-route-mixin';

const {
  Route
} = Ember;

export default Route.extend(ApplicationRouteMixin, {
  model() {
      return this.store.query('league-team', {leagueId: this.paramsFor('league').id});
  },
  setupController(controller, model) {
    controller.set('model', model);
  },
  actions: {
    remove(team) {
      const flashMessages = Ember.get(this, 'flashMessages');

      return team.destroyRecord().then(() => {
        flashMessages.success(`${team.get('name')} has been removed from the league`);
      }).catch(() => {
        team.rollback();

        flashMessages.danger('Unable to remove team from the league');
      });
    }
  }
});
