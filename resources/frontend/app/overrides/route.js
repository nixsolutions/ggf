import Ember from 'ember';

export default Ember.Route.reopen({
  /**
   * @override
   */
  beforeModel: function(transition) {
    this._super(transition);

    if ('undefined' === typeof transition.pinged) {
      Ember.run.debounce(this, this.pingApi, 10000);
      transition.pinged = true;
    }
  },

  pingApi: function() {
    const self = this;
    const adapter = this.container.lookup('adapter:application');

    if (this.get('session').get('isAuthenticated')) {
      adapter.simpleFindAll('ping').then(function (response) {
        if (!response.isAuthenticated) {

          const flashMessages = Ember.get(self, 'flashMessages');
          flashMessages.danger('Unauthorized');

          Ember.run.debounce(self, function () {
            self.send('invalidateSession');
          }, 2000);
        }
      });
    }
  },

});
