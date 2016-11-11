import DS from 'ember-data';
import config from '../config/environment';
import FormDataAdapterMixin from 'ember-cli-form-data/mixins/form-data-adapter';

export default DS.RESTAdapter.extend(FormDataAdapterMixin, {
  namespace: config.APP.namespace,
  host: config.APP.host,
  shouldReloadAll: function() {
    return true;
  },
  shouldBackgroundReloadAll: function() {
    return false;
  },
  shouldBackgroundReloadRecord: function() {
    return false;
  }
});
