import Ember from 'ember';

const {
  Component
} = Ember;

export default Component.extend({
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
