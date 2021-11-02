<template>
  <el-card v-if="this.projectDetails.author">
    <div class="user-profile">
      <!-- <div class="user-avatar box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />
      </div> -->
      <div class="box-center">
        <div class="user-name text-center">
          {{ this.projectDetails.author }}
        </div>
        <div class="user-role text-center text-muted">
          {{ $t('projects.creator') }}
        </div>
      </div>
      <div class="box-social">
        <el-table :data="members" :show-header="false">
          <el-table-column prop="name" label="Name" />
           <el-table-column prop="email" label="Email" />
        </el-table>
      </div>
      <div class="user-follow">
        <el-button type="success" v-role="['admin','moderator']" style="width: 100%;" @click="handleAdd">
          {{ $t('table.add') }}
        </el-button>
      </div>
    </div>
  <el-dialog :title="$t('projects.add_member')" :visible.sync="dialogFormVisible">
      <div v-loading="loading" class="form-container">
        <el-form ref="addProjectMember" :model="addProjectMember" label-position="left" label-width="150px" style="max-width: 900px;">
          <el-form-item :label="$t('projects.projectExecutors')" type="textarea" prop="projectExecutors" > 
            <el-select
              v-model="addProjectMember.projectExecutors"
              style="width: 100%;"
              multiple
              filterable
              class="filter-item"
              required
            >
              <el-option v-for="user in userList" :key="user.id" :value="user.id" :label="user.name" class="filter-item" />
            </el-select>
          </el-form-item>
         
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>

          <el-button type="primary" @click="addProMember">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </el-card>
  
</template>

<script>
import PanThumb from '@/components/PanThumb';
import { getProjectMembers,addProjectMember } from '@/api/project';
import UserResource from '@/api/user';
import ProjectResource from '@/api/project';
import role from '@/directive/role'; // Permission directive (v-role)

const userResource = new UserResource();
const projectResource = new ProjectResource();

export default {
  components: { PanThumb },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
        };
      },
    },
  },
  directives: {
    role
  },
  data() {
    return {
      query: {
        id: '',
        projectDetail: 'OK',
      },
      members: [],
      projectDetails: [],
      dialogFormVisible: false,
      loading: false,
      addProjectMember: {
        projectExecutors: [],
        projectId: '',
      },
      userList: [],
    };
  },
  created() {
    this.getProMembers();
    this.getListProject();
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

      this.loading = false;
    },
    getRole() {
      const roles = this.user.roles.map(value => this.$options.filters.uppercaseFirst(value));
      return roles.join(' | ');
    },
    async getProMembers() {
      const projectId = localStorage.getItem('projectId');
      await getProjectMembers(projectId).then(response => {    
        this.members = response.data;
            });
    },
    async getListUsers() {
      const { data } = await userResource.list();
      this.userList = data;
    },
    handleAdd() {
       const projectId = localStorage.getItem('projectId');
      this.addProjectMember.projectExecutors = [];
      this.dialogFormVisible = true;
      this.loading = true;
      this.members.forEach((element) => {
      this.addProjectMember.projectExecutors.push(element['id']);
        });
      this.addProjectMember.projectId = projectId;
      this.getListUsers();
      this.loading = false;
    },
      async addProMember(){
      await addProjectMember(this.addProjectMember).then((response) => {
        this.dialogFormVisible = false;
        this.$notify({
          title: 'Success',
          message: 'Added successfully',
          type: 'success',
          duration: 2000,
        });
      });

      this.getProMembers();
    },
  },
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
