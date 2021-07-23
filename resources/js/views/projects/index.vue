<template>
  <div class="mixin-components-container">
    <el-row>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>{{ $t('route.projects') }}</span>
          <el-input :placeholder="$t('table.keyword')" style="margin-left: 400px;width: 200px;" class="filter-item" />
          <el-button v-waves class="filter-item" type="primary" icon="el-icon-search">
            {{ $t('table.search') }}
          </el-button>
          <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
            {{ $t('table.add') }}
          </el-button>
        </div>
      </el-card>
    </el-row>
    <el-row :gutter="20" style="margin-top:50px;">

      <el-col :span="6">
        <div class="board-column-header-todo">
          {{ $t('projects.todo') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
        </div>

      </el-col>
      <el-col :span="6">
        <div class="board-column-header-working">
          {{ $t('projects.working') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
        </div>

      </el-col>
      <el-col :span="6">
        <div class="board-column-header-done">
          {{ $t('projects.done') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
        </div>

      </el-col>
      <el-col :span="6">
        <div class="board-column-header-cancelled">
          {{ $t('projects.cancelled') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
          <div class="component-item-project">
            Test
          </div>
        </div>

      </el-col>
    </el-row>
    <el-dialog :title="$t('projects.text_dialog')" :visible.sync="dialogFormVisible">
      <div v-loading="loadingCreateProject" class="form-container">
        <el-form ref="createProjectForm" :model="createProjectForm" label-position="left" label-width="150px" style="max-width: 800px;" :rules="rules">
          <el-form-item :label="$t('projects.projectName')" prop="projectName">
            <el-input v-model="createProjectForm.projectName" />
          </el-form-item>
          <el-form-item :label="$t('projects.projectDescription')" type="textarea" prop="projectDescription">
            <el-input v-model="createProjectForm.projectDescription" />
          </el-form-item>
          <el-form-item :label="$t('projects.period')" type="textarea" prop="projectBeginDate">
            <el-col :span="11">
              <el-date-picker v-model="createProjectForm.projectBeginDate" type="date" :placeholder="$t('projects.projectBeginDate')" style="width: 100%;" />
            </el-col>
            <el-col :span="2" class="line">
              -
            </el-col>
            <el-col :span="11">
              <el-date-picker v-model="createProjectForm.projectEndDate" type="date" :placeholder="$t('projects.projectEndDate')" style="width: 100%;" format="yyyy-MM-dd" />
            </el-col>
          </el-form-item>
          <el-form-item :label="$t('projects.projectExecutors')" type="textarea" prop="projectExecutors">
            <el-select
              v-model="createProjectForm.projectExecutors"
              style="width: 100%;"
              multiple
              filterable
              class="filter-item"
              placeholder="Please select servers"
              required
            >
              <el-option v-for="user in userList" :key="user.id" :value="user.id" :label="user.name" class="filter-item" />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('main_status.statusName')" type="textarea" prop="projectTypeStatus">
            <el-select v-model="createProjectForm.projectTypeStatus" placeholder="please select" style="width: 100%;">
              <el-option v-for="status in listMainStatus" :key="status.id" :value="status.id" :label="status.name" class="filter-item" />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('projects.projectComment')">
            <el-input v-model="createProjectForm.projectComment" type="textarea" />
          </el-form-item>
          <el-form-item :label="$t('main_status.status')">
            <el-radio-group v-model="createProjectForm.projectStatusActive" style="padding: 10px;">
              <el-radio :label="1">
                {{ $t('main_status.active') }}
              </el-radio>
              <el-radio :label="0">
                {{ $t('main_status.nonActive') }}
              </el-radio>
            </el-radio-group>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>

          <el-button type="primary" @click="createProject()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>

</template>

<script>
import waves from '@/directive/waves/index.js'; // v-wave directive
import UserResource from '@/api/user';
import MainStatusResource from '@/api/main_status';
import ProjectResource from '@/api/project';

const mainStatusResource = new MainStatusResource();
const userResource = new UserResource();
const projectResource = new ProjectResource();
export default {
  name: 'ComponentMixinDemo',
  directives: {
    waves,
  },
  data() {
    return {
      dialogFormVisible: false,
      loadingCreateProject: false,
      userList: [],
      listMainStatus: '',
      createProjectForm: {
        projectName: '',
        projectDescription: '',
        projectBeginDate: '',
        projectEndDate: '',
        projectStatusActive: 1,
        projectTypeStatus: '',
        projectExecutors: '',
        projectComment: '',

      },
      rules: {
        projectName: [{ required: true, message: 'Project name is required', trigger: 'blur' }],
        projectDescription: [{ required: true, message: 'Project description is required', trigger: 'blur' }],
        projectBeginDate: [{ required: true, message: 'Project pariod is required', trigger: 'blur' }],
        projectExecutors: [{ required: true, message: 'Project executors is required', trigger: 'blur' }],
        projectTypeStatus: [{ required: true, message: 'Status is required', trigger: 'blur' }],
      },
      options: {
        group: 'mission',
      },
      list1: [
        { name: 'Mission', id: 1 },
        { name: 'Mission', id: 2 },
        { name: 'Mission', id: 3 },
        { name: 'Mission', id: 4 },
      ],
      list2: [
        { name: 'Mission', id: 5 },
        { name: 'Mission', id: 6 },
        { name: 'Mission', id: 7 },
      ],
      list3: [
        { name: 'Mission', id: 8 },
        { name: 'Mission', id: 9 },
        { name: 'Mission', id: 10 },
      ],
    };
  },
  methods: {
    handleCreate() {
      this.dialogFormVisible = true;
      this.loadingCreateProject = true;
      this.getListUsers();
      this.getListStatus();
      this.$nextTick(() => {
        this.$refs['createProjectForm'].clearValidate();
      });
      this.loadingCreateProject = false;
    },
    async getListUsers() {
      const { data } = await userResource.list();
      this.userList = data;
      console.log(this.userList);
    },
    async getListStatus() {
      const { data } = await mainStatusResource.list();
      this.listMainStatus = data.main_statuses;
    },
    createProject() {
      this.$refs['createProjectForm'].validate((valid) => {
        if (valid) {
          this.loadingCreateProject = true;
          projectResource
            .store(this.createProjectForm)
            .then(response => {
              console.log(response);
              this.$message({
                message: 'New project ' + this.createProjectForm.projectName + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.loadingCreateProject = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  },
};
</script>
<style scoped>
.mixin-components-container {
  background-color: #f0f2f5;
  padding: 30px;

}
.component-item{
      width: 100%;
      margin: 5px 0;
      background-color: #304156;
      text-align: left;
      line-height: 54px;
      padding: 5px 10px;
      box-sizing: border-box;
      box-shadow: 0px 1px 3px 0 rgba(0, 0, 0, 0.2);
}
.component-item-project{
      cursor: pointer;
      width: 100%;
      height: 64px;
      margin: 5px 0;
      background-color: rgb(255, 255, 255);
      text-align: left;
      line-height: 54px;
      padding: 5px 10px;
      box-sizing: border-box;
      box-shadow: 0px 1px 3px 0 rgba(0, 0, 0, 0.2);
}

    .board-item {
      cursor: pointer;
      width: 100%;
      height: 64px;
      margin: 5px 0;
      background-color: rgb(255, 255, 255);
      text-align: left;
      line-height: 54px;
      padding: 5px 10px;
      box-sizing: border-box;
      box-shadow: 0px 1px 3px 0 rgba(0, 0, 0, 0.2);
    }
    .board-column-header-todo {
    height: 50px;
    line-height: 50px;
    overflow: hidden;
    padding: 0 20px;
    text-align: center;
    background: #4A9FF9;
    color: #fff;
    border-radius: 3px 3px 0 0;
  }
      .board-column-header-working {
    height: 50px;
    line-height: 50px;
    overflow: hidden;
    padding: 0 20px;
    text-align: center;
    background: #F9944A;
    color: #fff;
    border-radius: 3px 3px 0 0;
  }
      .board-column-header-done {
    height: 50px;
    line-height: 50px;
    overflow: hidden;
    padding: 0 20px;
    text-align: center;
    background: #2AC06D;
    color: #fff;
    border-radius: 3px 3px 0 0;
  }
      .board-column-header-cancelled {
    height: 50px;
    line-height: 50px;
    overflow: hidden;
    padding: 0 20px;
    text-align: center;
    background: #EB4585;
    color: #fff;
    border-radius: 3px 3px 0 0;
  }
  .line{
  text-align: center;
}
</style>
