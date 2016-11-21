import Ember from 'ember';

export default Ember.Component.extend({
  tagName: 'form',

  submit: function(e) {
    e.preventDefault();
      this.set('for.showErrors', true);
  }
});
