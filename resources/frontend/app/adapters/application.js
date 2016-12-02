import DS from 'ember-data';
import config from '../config/environment';

export default DS.RESTAdapter.extend({
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
  },
  simpleFindAll(uri, data) {
    if (!data) {
      data = {};
    }
    return this.ajax(this.buildURL4SimpleFindAll(uri), 'GET', {data: data});
  },
  buildURL4SimpleFindAll(uri) {
    let url = [];
    url.push(uri);
    let prefix = this.urlPrefix();

    if (prefix) {
      url.unshift(prefix);
    }
    url = url.join('/');

    if (!this.get('host') && url) {
      url = '/' + url;
    }

    return url;
  }
});
