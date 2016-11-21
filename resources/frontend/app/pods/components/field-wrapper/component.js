import Ember from 'ember';

const {
  Component
} = Ember;

export default Component.extend({
  classNames: 'form-group',

  classNameBindings: ['showErrors:has-error', 'required'],

  property: Ember.computed.alias('for'),

  forceRequired: null,

  setupErrors: function() {
    if (!this.get('property')) {
      return;
    }
    let propertyName = 'content.errors.' + this.get('property');

    let errors = Ember.computed(propertyName + '.length', function() {
      let errors = this.get(propertyName);
      if (Ember.isEmpty(errors)) {
        return [];
      }

      return errors;
    });

    Ember.defineProperty(this, 'errors', errors);
  }.on('init'),

  bindLabels: function() {
    this.$('label').prop('for', this.$('input, select, textarea').prop('id'));
  },

  didInsertElement: function() {
    this._super();
    const self = this;

    Ember.run.scheduleOnce('afterRender', this, function() {
      if (this.$('input, textarea, select')) {
        this.bindLabels();

        this.$('input, textarea, select').on('blur', function() {
          self.send('showErrors');
        }).on('focus', function() {
          self.send('hideErrors');
        });
      }

    });
  },

  click: function(e) {
    if (!Ember.$(e.target).is('.error-span')) {
      return;
    }
    this.set('canShowErrors', false);
    this.$('input').focus();
  },

  required: function() {
    let params = this.get('content.validations');
    if (!params) {
      return false;
    }
    params = params[this.get('property')];
    if (!params) {
      return false;
    }
    if (!Ember.isEmpty(this.get('forceRequired'))) {
      return this.get('forceRequired');
    }
    return !!params.presence;
  }.property('forceRequired'),

  showErrors: Ember.computed.and('canShowErrors', 'errors.length'),
  canShowErrors: Ember.computed.oneWay('content.showErrors'),

  actions: {
    showErrors: function() {
      return Ember.run.next(this, function() {
        if (!this.isDestroyed) {
          this.set('canShowErrors', true);
        }
      });
    },
    hideErrors: function() {
      return Ember.run.next(this, this.set, 'canShowErrors', false);
    }
  }
});
