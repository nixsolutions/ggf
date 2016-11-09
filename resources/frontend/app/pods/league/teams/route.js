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
  }
});
