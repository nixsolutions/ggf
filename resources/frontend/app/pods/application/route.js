import Ember from 'ember';
import ApplicationRouteMixin from 'simple-auth/mixins/application-route-mixin';
import ErrorHandlerMixin from './../../mixins/error-handler-mixin';

export default Ember.Route.extend(ApplicationRouteMixin, ErrorHandlerMixin, {
  actions: {
    error (error) {
      this.handleError(error);
    },
    authenticate: function(provider) {
      const flashMessages = Ember.get(this, 'flashMessages');

      this.get('session').authenticate('authenticator:torii-oauth2', {
        torii: this.get('torii'),
        provider: provider
      }).then(function() {
        flashMessages.success('Welcome aboard!');
      }).catch(function() {
        flashMessages.danger('Unable to authenticate');
      });
    }
  }
});
