import request from '@/utils/request';
import Resource from '@/api/resource';

class NotificationChannelResource extends Resource {
  constructor() {
    super('channels');
  }
}
export function fetchEditUser(id) {
  return request({
    url: '/channels/' + id,
    method: 'get',
  });
}

export function fetchChannels() {
  return request({
    url: '/channels',
    method: 'post',
  });
}

export function UpdateUser(data) {
  return request({
    url: '/channels/update',
    method: 'post',
    data,
  });
}
export function getRoles() {
  return request({
    url: '/userroles',
    method: 'get',

  });
}
export { NotificationChannelResource as default };
