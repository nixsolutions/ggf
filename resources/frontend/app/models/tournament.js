import Ember from 'ember';
import DS from 'ember-data';
import EmberValidations from 'ember-validations';

const { Model, attr, hasMany } = DS;

const { computed } = Ember;

export default Model.extend(EmberValidations.Mixin, {
  name:         attr('string'),
  description:  attr('string'),
  type:         attr('string'),
  status:       attr('string'),
  membersType:  attr('string'),
  teams:        hasMany('teams', {async: false}),
  matches:      hasMany('matches', {async: false}),
  tablescore:   hasMany('tablescores', {async: false}),
  standing:     hasMany('standings', {async: false}),

  isDraft: computed('status', function () {
    return this.get('status') === 'draft';
  }),

  isStarted: computed('status', function () {
    return this.get('status') === 'started';
  }),

  title: computed('name', 'type', function() {
    let name = this.get('name');
    let type;

    switch (this.get('type')) {
      case 'knock_out':
        type = 'K';
        break;
      case 'league':
        type = 'L';
        break;
      default:
        type = 'L';
        break;
    }

    return `${name} (${type})`;
  }),
  validations: {
    name: {
      presence: true,
      length: {
        minimum: 3
      }
    },
    description: {
      length: {
        minimum: 3
      }
    }

  }
});
