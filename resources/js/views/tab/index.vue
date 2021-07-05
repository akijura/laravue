<template>
  <div class="tab-container">
    <span class="pan-btn light-blue-btn" @click="handleCreate">
      {{ $t('table.add') }}
    </span>
    <el-alert :closable="false" style="width:200px;display:inline-block;vertical-align: middle;margin-left:30px;" title="Tab with keep-alive" type="success" />
    <el-tabs v-model="activeName" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in tabMapOptions" :key="item.key" :label="item.label" :name="item.key">
        <keep-alive>
          <tab-pane v-if="activeName==item.key" :type="item.key" @create="showCreatedTimes" />
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
const mainStatusResource = new MainStatusResource();

export default {
  name: 'Tab',
  components: { TabPane },
  data() {
    return {
      tabMapOptions: [
        { label: 'USA', key: 'US' },
        { label: 'Vietnam', key: 'VI' },
        { label: 'China', key: 'CN' },
        { label: 'Eurozone', key: 'EU' },
      ],
      activeName: 'VI',
      createdTimes: 0,
      dialogFormVisible: false,
      userCreating: false,
      active: 1,
      createMainForm: {
        mainStatusName: '',
        mainStatusDescription: '',
        mainStatusActive: 1,
      },
      statusCreating: false,
      rules: {
        mainStatusName: [{ required: true, message: 'Status name is required', trigger: 'blur' }],
        mainStatusDescription: [{ required: true, message: 'Status description is required', trigger: 'blur' }],
      },
    };
  },
  methods: {
    showCreatedTimes() {
      this.createdTimes = this.createdTimes + 1;
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
              console.log(response);
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
