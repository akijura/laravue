<template>
  <div>
    <el-button class="item-btn" size="small" type="success" style="margin-bottom:10px;" @click="handleCreate">
      {{ $t('table.add') }}
    </el-button>
    <el-table :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        v-loading="loading"
        align="center"
        label="ID"
        width="65"
        element-loading-text="Pleas be patient！"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Date">
        <template slot-scope="scope">
          <span>{{ scope.row.timestamp | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="300px" label="Title">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
          <el-tag>{{ scope.row.type }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column width="110px" align="center" label="Author">
        <template slot-scope="scope">
          <span>{{ scope.row.author }}</span>
        </template>
      </el-table-column>

      <el-table-column width="120px" label="Importance">
        <template slot-scope="scope">
          <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Readings" width="95">
        <template slot-scope="scope">
          <span>{{ scope.row.pageviews }}</span>
        </template>
      </el-table-column>

      <el-table-column class-name="status-col" label="Status" width="110">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">
            {{ scope.row.status }}
          </el-tag>
        </template>
      </el-table-column>

    </el-table>
    <el-dialog :title="$t('main_status.text_dialog_status')" :visible.sync="dialogFormVisible">
      <div v-loading="statusCreating" class="form-container">
        <el-form ref="statusForm" :model="createForm" label-position="left" label-width="150px" style="max-width: 500px;" :rules="rules">
          <el-form-item :label="$t('main_status.statusName')" prop="statusName">
            <el-input v-model="createForm.statusName" />
          </el-form-item>
          <el-form-item :label="$t('main_status.statusDesc')" type="textarea" prop="statusDescription">
            <el-input v-model="createForm.statusDescription" />
          </el-form-item>
          <el-form-item :label="$t('main_status.status')">
            <el-radio-group v-model="createForm.statusActive" style="padding: 10px;">
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
import { fetchList } from '@/api/article';
import StatusResource from '@/api/status';
const StatusResourceConst = new StatusResource();

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    type: {
      type: Number,
      default: 5,
    },
  },
  data() {
    return {
      dialogFormVisible: false,
      statusCreating: false,
      active: 1,
      createForm: {
        statusName: '',
        statusDescription: '',
        statusActive: 1,
        mainStatusId: this.type,
      },
      rules: {
        statusName: [{ required: true, message: 'Status name is required', trigger: 'blur' }],
        statusDescription: [{ required: true, message: 'Status description is required', trigger: 'blur' }],
      },
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type,
        sort: '+id',
      },
      loading: false,
    };
  },
  created() {
    this.getList();
    this.createForm.mainStatusId = this.type;
    console.log(this.createForm.mainStatusId);
  },
  methods: {
    async getList() {
      this.loading = true;
      this.$emit('create'); // for test
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.loading = false;
    },
    handleCreate() {
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['statusForm'].clearValidate();
      });
    },
    createMainStatus() {
      this.$refs['statusForm'].validate((valid) => {
        if (valid) {
          this.statusCreating = true;

          StatusResourceConst
            .store(this.createForm)
            .then(response => {
              console.log(response);
              this.$message({
                message: 'New status ' + this.createForm.statusName + ' has been created successfully.',
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
        // this.getList();
      });
    },
    resetNewStatus() {
      this.createForm = {
        statusName: '',
        statusDescription: '',
        statusActive: 1,
        mainStatusId: this.type,
      };
    },
  },
};
</script>

