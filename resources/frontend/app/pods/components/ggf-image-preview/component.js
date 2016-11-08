import Ember from 'ember';

export default Ember.Component.extend({
    attributeBindings: ['src', 'width', 'height'],
    width: 100,
    height: 100,
    tagName: 'img',
    didInsertElement() {
        this.logoChanged();
    },
    fileDidChange: Ember.observer('logo', function () {
        this.logoChanged();
    }),
    logoChanged() {
        const self = this;
        const file = this.get('logo');
        const reader = new FileReader();

        reader.onload = function(e) {
            self.set("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});
