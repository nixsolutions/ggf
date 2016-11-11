import Ember from 'ember';

export default Ember.Mixin.create({
  handleError: function(error) {
    if (console && console.warn) {
      Ember.Logger.warn('Ember.onerror', error);

      if (error.stack) {
        Ember.Logger.warn(error.stack);
      }
    }

    switch (this._getStatusCode(error)) {
      case 401:
        this.errorNotifier('Unauthorized');
        this.send('logout');
        break;
      case 403:
        this.errorNotifier('Forbidden');
        Ember.Logger.log('forbidden');
        break;
      case 404:
        this.transitionTo('/not-found');
        break;
      default :
          Ember.run.once(this, this.errorNotifier, 200000);
    }
  },
  _getStatusCode: function(error) {
    let status = error.status;

    if (!status && error.errors && error.errors[0].status) {
      status = parseInt(error.errors[0].status);
    }

    return status;
  },
  errorNotifier(message = 'Internal Error, please contact customer support') {
    const flashMessages = Ember.get(this, 'flashMessages');

    flashMessages.danger(message);
  },
});
