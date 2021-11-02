import Resource from '@/api/resource';
import request from '@/utils/request';
class FilesResource extends Resource {
  constructor() {
    super('files');
  }
}
export function downloadFile(file_name) {
  console.log(file_name);
  return request({
    url: '/downloadFile/' + file_name,
    method: 'get',
    responseType: 'blob',

  });
}
export { FilesResource as default };
