<template>
  <div class="mixin-components-container">
    <el-row>
      <el-card class="box-card">
        <div :class="{'show':show}" class="header-search">
          <span>{{ $t('route.projects') }}</span>
          <!-- <el-input :placeholder="$t('table.keyword')" style="margin-left: 400px;width: 200px;" class="filter-item" /> -->
          <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
          <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" style="margin-left: 10px;" @click="handleFilter">
            {{ $t('table.search') }}
          </el-button>
          <el-button v-role="['admin','moderator']" class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
            {{ $t('table.add') }}
          </el-button>
        </div>
      </el-card>
    </el-row>
    <el-row v-loading="loading" :gutter="20" style="margin-top:50px;">

      <el-col :span="6">
        <div class="board-column-header-todo">
          {{ $t('projects.todo') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div v-for="todo in todoList" :key="todo.id" class="component-item-project" @click="getProjectId(todo.id)">
            {{ todo.name }}
            <div class="pull-right">
              <el-tag class="tag-title" :type="todo.level | statusFilter">
                {{ todo.level_name }}
              </el-tag>
            </div>

          </div>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="board-column-header-working">
          {{ $t('projects.working') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div v-for="working in workingList" :key="working.id" class="component-item-project" @click="getProjectId(working.id)">
            {{ working.name }}
            <div class="pull-right">
              <el-tag class="tag-title" :type="working.level | statusFilter">
                {{ working.level_name }}
              </el-tag>
            </div>
          </div>
        </div>

      </el-col>
      <el-col :span="6">
        <div class="board-column-header-done">
          {{ $t('projects.done') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div v-for="done in doneList" :key="done.id" class="component-item-project" @click="getProjectId(done.id)">
            {{ done.name }}
            <div class="pull-right">
              <el-tag class="tag-title" :type="done.level | statusFilter">
                {{ done.level_name }}
              </el-tag>
            </div>
          </div>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="board-column-header-cancelled">
          {{ $t('projects.cancelled') }}
        </div>
        <div class="component-item" style="min-height: calc(100vh - 84px);">
          <div v-for="cancel in cancelledList" :key="cancel.id" class="component-item-project" @click="getProjectId(cancel.id)">
            {{ cancel.name }}
            <div class="pull-right">
              <el-tag class="tag-title" :type="cancel.level | statusFilter">
                {{ cancel.level_name }}
              </el-tag>
            </div>
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
          <el-form-item :label="$t('projects.project_level')" type="textarea" prop="project_level">
            <el-select v-model="createProjectForm.projectLevel" placeholder="please select" style="width: 100%;">
              <el-option v-for="level in projectLevels" :key="level.id" :value="level.id" :label="level.name" class="filter-item" />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('projects.projectComment')" prop="projectComment">
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
    <el-tooltip placement="top" :content="$t('projects.up')">
      <back-to-top :custom-style="myBackToTopStyle" :visibility-height="30" :back-position="50" transition-name="fade" />
    </el-tooltip>
  </div>

</template>

<script>
import waves from '@/directive/waves/index.js'; // v-wave directive
import UserResource from '@/api/user';
import MainStatusResource from '@/api/main_status';
import ProjectResource from '@/api/project';
import Fuse from 'fuse.js';
import { fetchProjectLevelList } from '@/api/project';
import BackToTop from '@/components/BackToTop';
import permission from '@/directive/permission'; // Permission directive
import role from '@/directive/role'; // Permission directive (v-role)
import path from 'path';

const mainStatusResource = new MainStatusResource();
const userResource = new UserResource();
const projectResource = new ProjectResource();
export default {
  name: 'ComponentMixinDemo',
  name: 'BackToTopDemo',
  components: { BackToTop },
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'warning',
        2: 'primary',
        3: 'danger',
      };
      return statusMap[status];
    },
  },
  directives: {
    waves,
    permission,
    role,
  },
  data() {
    return {
      query: {
        keyword: '',
      },
      myBackToTopStyle: {
        right: '50px',
        bottom: '50px',
        width: '40px',
        height: '40px',
        'border-radius': '4px',
        'line-height': '45px', // Please keep consistent with height to center vertically
        background: '#e7eaf1', // The background color of the button
      },
      projectLevels: [],
      search: '',
      options: [],
      searchPool: [],
      show: false,
      fuse: undefined,
      dialogFormVisible: false,
      loadingCreateProject: false,
      userList: [],
      projectList: [],
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
        projectLevel: '',
      },
      rules: {
        projectName: [{ required: true, message: 'Project name is required', trigger: 'blur' }],
        projectDescription: [{ required: true, message: 'Project description is required', trigger: 'blur' }],
        projectBeginDate: [{ required: true, message: 'Project pariod is required', trigger: 'blur' }],
        projectExecutors: [{ required: true, message: 'Project executors is required', trigger: 'blur' }],
        projectTypeStatus: [{ required: true, message: 'Status is required', trigger: 'blur' }],
        projectComment: [{ required: true, message: 'Project comment is required', trigger: 'blur' }],
      },
      options: {
        group: 'mission',
      },
      todoList: [],
      workingList: [],
      doneList: [],
      cancelledList: [],
    };
  },
  watch: {
    lang() {
      this.searchPool = this.generateRoutes(this.routes);
    },
    routes() {
      this.searchPool = this.generateRoutes(this.routes);
    },
    searchPool(list) {
      this.initFuse(list);
    },
    show(value) {
      if (value) {
        document.body.addEventListener('click', this.close);
      } else {
        document.body.removeEventListener('click', this.close);
      }
    },
  },
  created() {
    this.getListProject();
  },
  methods: {
    initFuse(list) {
      this.fuse = new Fuse(list, {
        shouldSort: true,
        threshold: 0.4,
        location: 0,
        distance: 100,
        maxPatternLength: 32,
        minMatchCharLength: 1,
        keys: [{
          name: 'title',
          weight: 0.7,
        }, {
          name: 'path',
          weight: 0.3,
        }],
      });
    },
    click() {
      this.show = !this.show;
      if (this.show) {
        this.$refs.headerSearchSelect && this.$refs.headerSearchSelect.focus();
      }
    },
    close() {
      this.$refs.headerSearchSelect && this.$refs.headerSearchSelect.blur();
      this.options = [];
      this.show = false;
    },
    querySearch(query) {
      if (query !== '') {
        this.options = this.fuse.search(query);
      } else {
        this.options = [];
      }
    },
    change(val) {
      this.$router.push(val.path);
      this.search = '';
      this.options = [];
      this.$nextTick(() => {
        this.show = false;
      });
    },
    getProjectId(id) {
      localStorage.setItem('projectId', id);
      const projectId = id;
      this.$router.push({ name: 'SelfProfile1', params: { projectId }});
    },
    handleFilter() {
      this.todoList = [],
      this.workingList = [],
      this.doneList = [],
      this.cancelledList = [],
      this.getListProject();
    },
    async getListProject(){
      this.todoList = [];
      this.workingList = [];
      this.doneList = [];
      this.cancelledList = [];
      this.loading = true;
      const { data } = await projectResource.list(this.query);
      console.log(data);
      this.projectList = data;
      if (this.projectList.length === 0) {
        this.todoList = [];
        this.workingList = [];
        this.doneList = [];
        this.cancelledList = [];
      } else {
        this.projectList.forEach((project) => {
          if (project['basic_status'] === 1) {
            this.todoList.push({
              basic_status: project['basic_status'],
              begin_date: project['begin_date'],
              description: project['description'],
              end_date: project['end_date'],
              id: project['id'],
              main_status_id: project['main_status_id'],
              name: project['name'],
              status: project['status'],
              level: project['level'],
              level_name: project['level_name'],
            });
          } else if (project['basic_status'] === 2) {
            this.workingList.push({
              basic_status: project['basic_status'],
              begin_date: project['begin_date'],
              description: project['description'],
              end_date: project['end_date'],
              id: project['id'],
              main_status_id: project['main_status_id'],
              name: project['name'],
              status: project['status'],
              level: project['level'],
              level_name: project['level_name'],
            });
          } else if (project['basic_status'] === 3) {
            this.doneList.push({
              basic_status: project['basic_status'],
              begin_date: project['begin_date'],
              description: project['description'],
              end_date: project['end_date'],
              id: project['id'],
              main_status_id: project['main_status_id'],
              name: project['name'],
              status: project['status'],
              level: project['level'],
              level_name: project['level_name'],
            });
          } else {
            this.cancelledList.push({
              basic_status: project['basic_status'],
              begin_date: project['begin_date'],
              description: project['description'],
              end_date: project['end_date'],
              id: project['id'],
              main_status_id: project['main_status_id'],
              name: project['name'],
              status: project['status'],
              level: project['level'],
              level_name: project['level_name'],
            });
          }
        });
      }

      this.loading = false;
    },

    handleCreate() {
      this.dialogFormVisible = true;
      this.loadingCreateProject = true;
      this.getListUsers();
      this.getListStatus();
      this.getProjectLevelList();
      // this.$nextTick(() => {
      //   this.$refs['createProjectForm'].clearValidate();
      // });
      this.loadingCreateProject = false;
    },
    async getProjectLevelList() {
      const { data } = await fetchProjectLevelList();
      this.projectLevels = data;
    },
    async getListUsers() {
      const { data } = await userResource.list();
      this.userList = data;
    },
    async getListStatus() {
      const { data } = await mainStatusResource.list();
      this.listMainStatus = data.main_statuses;
    },
    resetNewProject() {
      this.createProjectForm = {
        projectName: '',
        projectDescription: '',
        projectBeginDate: '',
        projectEndDate: '',
        projectStatusActive: 1,
        projectTypeStatus: '',
        projectExecutors: '',
        projectComment: '',
        projectLevel: '',
      };
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
              this.resetNewProject();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.loadingCreateProject = false;
              this.dialogFormVisible = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
      this.getListProject();
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
<style lang="scss" scoped>
.header-search {
  font-size: 0 !important;

  .search-icon {
    cursor: pointer;
    font-size: 18px;
    vertical-align: middle;
  }

  .header-search-select {
    font-size: 18px;
    transition: width 0.2s;
    width: 0;
    overflow: hidden;
    background: transparent;
    border-radius: 0;
    display: inline-block;
    vertical-align: middle;

    /deep/ .el-input__inner {
      border-radius: 0;
      border: 0;
      padding-left: 0;
      padding-right: 0;
      box-shadow: none !important;
      border-bottom: 1px solid #d9d9d9;
      vertical-align: middle;
    }
  }

  &.show {
    .header-search-select {
      width: 210px;
      margin-left: 10px;
    }
  }
}
</style>
