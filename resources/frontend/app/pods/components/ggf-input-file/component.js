import Ember from 'ember';

export default Ember.Component.extend({
    attributeBindings: ['type'],
    tagName: 'input',
    type: 'file',
    logo: null,
    change: function(event) {
        const file = event.target.files;
        this.set('logo', file[0]);
    }
});
