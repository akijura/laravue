<template>
  <div class="tab-container">
    <span v-if="checkRole(['admin'])" class="pan-btn light-blue-btn" @click="handleCreate">
      {{ $t('table.add') }}
    </span>
    <el-tabs v-model="activeName" v-loading="loading" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in listMainStatus" :key="item.id" :label="item.name" :name="numToStr(item.id)">
        <keep-alive>
          <tab-pane v-if="activeName==numToStr(item.id)" :type="numToStr(item.id)" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
    <el-dialog :title="$t('main_status.text_dialog')" :visible.sync="dialogFormVisible">
      <div v-loading="statusCreating" class="form-container">
        <el-form ref="mainStatusForm" :model="createMainForm" label-position="left" label-width="150px" style="max-width: 500px;" :rules="rules">
          <el-form-item :label="$t('main_status.statusName')" prop="mainStatusName">
            <el-input v-model="createMainForm.mainStatusName" />
          </el-form-item>
          <el-form-item :label="$t('main_status.statusDesc')" type="textarea" prop="mainStatusDescription">
            <el-input v-model="createMainForm.mainStatusDescription" />
          </el-form-item>
          <el-form-item :label="$t('main_status.status')">
            <el-radio-group v-model="createMainForm.mainStatusActive" style="padding: 10px;">
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

          <el-button type="primary" @click="createMainStatus()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import TabPane from './components/TabPane';
import MainStatusResource from '@/api/main_status';
import checkRole from '@/utils/role'; // Role checking
const mainStatusResource = new MainStatusResource();

export default {
  name: 'Tab',
  components: { TabPane },
  data() {
    return {
      activeName: '',
      dialogFormVisible: false,
      userCreating: false,
      active: 1,
      createMainForm: {
        mainStatusName: '',
        mainStatusDescription: '',
        mainStatusActive: 1,
      },
      loading: false,
      listMainStatus: [],
      statusCreating: false,
      rules: {
        mainStatusName: [{ required: true, message: 'Status name is required', trigger: 'blur' }],
        mainStatusDescription: [{ required: true, message: 'Status description is required', trigger: 'blur' }],
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    checkRole,
    async getList() {
      this.loading = true;
      const { data } = await mainStatusResource.list();

      this.listMainStatus = data.main_statuses;
      //       this.listMainStatus.forEach((status, index) => {
      //   status['index'] = index + 1;
      //   role['description'] = this.$t('roles.description.' + role.name);
      // });
      this.activeName = data.main_statuses[0].id;
      this.activeName = this.activeName.toString();
      this.loading = false;
    },
    numToStr(num) {
      num = num.toString();
      return num;
    },
    handleCreate() {
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['mainStatusForm'].clearValidate();
      });
    },
    createMainStatus() {
      this.$refs['mainStatusForm'].validate((valid) => {
        if (valid) {
          this.statusCreating = true;

          mainStatusResource
            .store(this.createMainForm)
            .then(response => {
              this.$message({
                message: 'New status ' + this.createMainForm.mainStatusName + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewMainStatus();
              this.dialogFormVisible = false;
              // this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.statusCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
        this.getList();
      });
    },
    resetNewMainStatus() {
      this.createMainForm = {
        mainStatusName: '',
        mainStatusDescription: '',
        mainStatusActive: 1,
      };
    },
  },
};
</script>

<style scoped>
  .tab-container {
    margin: 30px;
  }
</style>
