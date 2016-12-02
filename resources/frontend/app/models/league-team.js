import DS from 'ember-data';
import EmberValidations from 'ember-validations';

const { Model, attr } = DS;

export default Model.extend(EmberValidations.Mixin, {
  name:     attr('string'),
  logoPath: attr('string'),
  logo:     attr('file'),
  leagueId: attr('number'),

  validations: {
    name: {
      presence: true,
      length: {
        minimum: 3
      }
    },
    logo: {
      presence: {
        message: 'you must upload an image'
      },
    }
  }
});
