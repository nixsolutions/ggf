import DS from 'ember-data';
import EmberValidations from 'ember-validations';

const { Model, attr, hasMany } = DS;

export default Model.extend(EmberValidations.Mixin, {
  name:     attr('string'),
  logoPath: attr('string'),
  logo:     attr('file'),
  teams:    hasMany('league-team', {async: false}),

  validations: {
    name: {
      presence: true,
      length: {
        minimum: 3
      }
    },
    logo: {
      presence: {
        message: 'image must be uploaded'
      },
    }
  }
});
