import Resource from '@/api/resource';
import request from '@/utils/request';
class ProjectResource extends Resource {
  constructor() {
    super('project');
  }
}
export function fetchProjectLevelList() {
  return request({
    url: '/projectLevelList/',
    method: 'get',
  });
}
export function getProjectAuth(project_id) {
  return request({
    url: '/getProjectAuth/' + project_id,
    method: 'get',
  });
}
export function getProjectMembers(project_id) {
  return request({
    url: '/getProMembers/' + project_id,
    method: 'get',
  });
}
export function confirmStatus(project_id, status_id) {
  return request({
    url: '/confirmStatus/' + project_id + '/' + status_id,
    method: 'get',
  });
}
export function addProjectMember(data) {
  return request({
    url: '/project/addMember',
    method: 'post',
    data,
  });
}
export { ProjectResource as default };
