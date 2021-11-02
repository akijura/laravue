import Resource from '@/api/resource';
import request from '@/utils/request';
class ProjectReportResource extends Resource {
  constructor() {
    super('projectReport');
  }
}
export function updateStatusConfirm(id) {
  return request({
    url: '/statusConfirm/' + id,
    method: 'get',
  });
}
export { ProjectReportResource as default };
