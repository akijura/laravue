import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
  get(id) {
    console.log(id);
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource, exdata) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
      exdata: exdata,
    });
  }

  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
  destroy(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'delete',
    });
  }
}

export { Resource as default };
