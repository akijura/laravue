<template>
  <el-card ref="UserBio" class="box-card user-bio">
    <div slot="header" class="clearfix">
      <span>{{ $t('projects.about') }}</span>
    </div>
    <div class="user-education user-bio-section">
      <div v-loading="loading" class="user-bio-section-header">
        <svg-icon icon-class="education" />
        <span> {{ $t('projects.deadline') }}</span>
      </div>
      <div class="user-bio-section-body">
        <div style="margin-top: 5px;margin-bottom: 5px; color: red;">
          {{ $t('projects.priority') }}: {{ this.projectDetails.level_name }}
        </div>
        <div class="text-muted">
          {{ $t('projects.from') }} {{ this.projectDetails.begin_date }} {{ $t('projects.to') }} {{ this.projectDetails.end_date }}
        </div>
        <div style="margin-top: 5px;">
          {{ $t('projects.dayspro') }}: {{ this.projectDetails.interval }}
        </div>
        <div style="margin-top: 5px; color: red;">
          {{ $t('projects.raminedays') }}: {{ this.projectDetails.remained }}
        </div>

      </div>
    </div>
    <div class="user-skills user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="skill" />
        <span>{{ $t('projects.proccess') }}</span>
      </div>
      <div class="user-bio-section-body">
        <div class="progress-item">
          <el-progress :percentage="Number(this.percent)" />
        </div>
      </div>
    </div>
    <div class="user-skills user-bio-section">
      <div class="user-bio-section-header">
        <svg-icon icon-class="edit" />
        <span>{{ $t('projects.change_status') }}</span>
      </div>
      <div class="user-bio-section-body">
        <div class="box-social">
          <el-table :data="listStatutes" :show-header="false" border>
            <el-table-column prop="status_name" label="status_name" />
            <el-table-column prop="basic_status_name" label="basic_status_name" />
            <el-table-column prop="created_at" label="created_at" />
            <el-table-column prop="author" label="author" />

            <el-table-column align="center" width="145" class="text-center">
              <template slot-scope="scope">

                <el-button :disabled="scope.row.isActive" size="small" :type="scope.row.status_confirm | statusFilter" @click="handleStatusConfirm(scope.row.project_id,scope.row.status_name,scope.row.id)">
                  {{ $t('projects.' + scope.row.text_confirm) }}
                </el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
import ProjectResource from '@/api/project';
import ProjectReportResource from '@/api/projectReport';
import UserActivity from './UserActivity';
import { confirmStatus } from '@/api/project';

const projectResource = new ProjectResource();
const projectReportResource = new ProjectReportResource();

export default {
  components: { UserActivity },
  filters: {
    statusFilter(status) {
      const statusMap = {
        0: 'danger',
        1: 'success',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      query: {
        id: '',
        projectDetail: 'OK',
      },
      projectDetails: [],
      loading: false,
      percent: '',
      listStatutes: [],
      queryStatus: {
        projectId: '',
      },
    };
  },
  mounted() {
    this.$root.$on('UserBio', () => {
      this.getProjectReport();
      this.getListProject();
    });
  },
  created() {
    this.getListProject();
    this.getProjectReport();
  },
  methods: {
    async getListProject(){
      const projectId = localStorage.getItem('projectId');
      this.projectDetails = [];
      this.query.id = projectId;
      this.query.projectDetail = 'OK';
      this.loading = true;
      const { data } = await projectResource.list(this.query);

      this.projectDetails = data[0];
      this.percent = Number(this.projectDetails.percent);
      this.loading = false;
    },
    handleStatusConfirm(project_id, name, status_id) {
      this.$confirm('This will permanently confirm status ' + name + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        confirmStatus(project_id, status_id).then(response => {
          console.log(response);
          this.$message({
            type: 'success',
            message: 'Confirmation completed',
          });
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Confirmation canceled',
        });
      }).finally(() => {
        this.$root.$emit('UserActivity');
        this.getListProject();
        this.getProjectReport();
      });
    },
    async getProjectReport(){
      this.loading = true;
      const projectId = localStorage.getItem('projectId');
      this.listStatutes = [];
      this.queryStatus.projectId = projectId;
      const { data } = await projectReportResource.list(this.queryStatus);
      console.log(data);
      data.forEach((status) => {
        if (status['status_confirm'] === 1) {
          if (status['user_role'] === 'admin' || status['user_role'] === 'moderator') {
            this.listStatutes.push({
              isActive: false,
              id: status['id'],
              status_id: status['status_id'],
              project_id: status['project_id'],
              status_name: status['status_name'],
              basic_status_name: status['basic_status_name'],
              status_confirm: status['status_confirm'],
              created_at: status['created_at'],
              author: status['author'],
              text_confirm: 'confirmed',
            });
          } else {
            this.listStatutes.push({
              isActive: true,
              id: status['id'],
              status_id: status['status_id'],
              project_id: status['project_id'],
              status_name: status['status_name'],
              basic_status_name: status['basic_status_name'],
              status_confirm: status['status_confirm'],
              created_at: status['created_at'],
              author: status['author'],
              text_confirm: 'confirmed',
            });
          }
        } else {
          if (status['user_role'] === 'admin' || status['user_role'] === 'moderator') {
            this.listStatutes.push({
              isActive: false,
              id: status['id'],
              status_id: status['status_id'],
              project_id: status['project_id'],
              status_name: status['status_name'],
              basic_status_name: status['basic_status_name'],
              status_confirm: status['status_confirm'],
              created_at: status['created_at'],
              author: status['author'],
              text_confirm: 'not_confirmed',
            });
          } else {
            this.listStatutes.push({
              isActive: true,
              id: status['id'],
              status_id: status['status_id'],
              project_id: status['project_id'],
              status_name: status['status_name'],
              basic_status_name: status['basic_status_name'],
              status_confirm: status['status_confirm'],
              created_at: status['created_at'],
              author: status['author'],
              text_confirm: 'not_confirmed',
            });
          }
        }
      });
      console.log(this.listStatutes);
      this.loading = false;
    },

  },
};
</script>

<style lang="scss" scoped>
.user-bio {
  margin-top: 20px;
  color: #606266;
  span {
    padding-left: 4px;
  }
  .user-bio-section {
    font-size: 14px;
    padding: 15px 0;
    .user-bio-section-header {
      border-bottom: 1px solid #dfe6ec;
      padding-bottom: 10px;
      margin-bottom: 10px;
      font-weight: bold;
    }
  }
}
</style>
