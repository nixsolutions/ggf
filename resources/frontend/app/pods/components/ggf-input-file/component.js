import Ember from 'ember';

export default Ember.Component.extend({
  attributeBindings: ['type'],
  tagName: 'input',
  type: 'file',
  logo: null,
  change: function(event) {
    const flashMessages = Ember.get(this, 'flashMessages');
    const availableTypes = [
      'image/png',
      'image/jpg',
      'image/jpeg',
      'image/bmp',
      'image/git'
    ];

    const file = event.target.files[0];

    if (file.type && availableTypes.indexOf(file.type) + 1) {
      return this.set('logo', file);
    }

    flashMessages.danger('The image must be a file of type: jpeg, bmp, png, jpg.');
  }
});
