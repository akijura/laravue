<template>
  <div>
    <el-button class="item-btn" size="small" type="success" style="margin-bottom:10px;" @click="handleCreate">
      {{ $t('table.add') }}
    </el-button>
    <el-table :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        v-loading="loading"
        align="center"
        :label="$t('main_status.id')"
        width="80"
        element-loading-text="Pleas be patient！"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column mwidth="110px" align="center" :label="$t('main_status.statusName')">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="300px" align="center" :label="$t('main_status.statusDesc')">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column class-name="status-col" :label="$t('main_status.status')" width="110">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">
            {{ scope.row.status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column width="250px" align="center" :label="$t('main_status.actions')">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEditStatus(scope.row.id);">
            Edit
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.name);">
            Delete
          </el-button>
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
    <el-dialog :title="$t('main_status.text_dialog_status_edit') + '  ' + this.editForm.editName" :visible.sync="dialogEditFormVisible">
      <div v-loading="statusEdit" class="form-container">
        <el-form ref="statusEditForm" :model="editForm" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('main_status.statusName')" prop="statusEditName">
            <el-input v-model="editForm.editName" />
          </el-form-item>
          <el-form-item :label="$t('main_status.statusDesc')" type="textarea" prop="statusEditDescription">
            <el-input v-model="editForm.editDescription" />
          </el-form-item>
          <el-form-item :label="$t('main_status.status')">
            <el-radio-group v-model="editForm.editActive" style="padding: 10px;">
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
          <el-button @click="dialogEditFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>

          <el-button type="primary" @click="updateStatus()">
            {{ $t('main_status.update') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import StatusResource from '@/api/status';
const StatusResourceConst = new StatusResource();

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    type: {
      type: Number,
      default: 1,
    },
  },
  data() {
    return {
      dialogFormVisible: false,
      dialogEditFormVisible: false,
      statusEdit: false,
      statusCreating: false,
      createForm: {
        statusName: '',
        statusDescription: '',
        statusActive: 1,
        mainStatusId: this.type,
      },
      editForm: {
        editId: '',
        editName: '',
        editDescription: '',
        editActive: '',
        editMainStatusId: this.type,
      },
      rules: {
        statusName: [{ required: true, message: 'Status name is required', trigger: 'blur' }],
        statusDescription: [{ required: true, message: 'Status description is required', trigger: 'blur' }],
      },
      listItems: [],
      list: null,
      loading: false,
    };
  },
  created() {
    this.getList();
    this.createForm.mainStatusId = this.type;
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await StatusResourceConst.list(this.createForm.mainStatusId);
      this.list = data;
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
        this.dialogFormVisible = false;
        this.getList();
      });
    },
    handleEditStatus(id) {
      this.dialogEditFormVisible = true;
      this.statusEdit = true;
      const found = this.list.find(list => list.id === id);
      this.editForm = {
        editId: found.id,
        editName: found.name,
        editDescription: found.description,
        editActive: found.status,
        editMainStatusId: this.type,
      };
      this.$nextTick(() => {
        this.$refs['statusEditForm'].clearValidate();
      });
      this.statusEdit = false;
      console.log(this.editForm);
    },
    updateStatus() {
      this.$refs['statusEditForm'].validate((valid) => {
        if (valid) {
          this.statusEdit = true;

          StatusResourceConst
            .update(this.editForm.editId, this.editForm)
            .then(response => {
              console.log(response);
              this.$message({
                message: 'Status ' + this.editForm.editName + ' has been updated successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetEditStatus();
              this.dialogEditFormVisible = false;
              // this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.statusEdit = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
        this.dialogEditFormVisible = false;
        this.getList();
      });
    },
    handleDelete(id, name) {
      this.$confirm('This will permanently delete status ' + name + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        StatusResourceConst.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Delete completed',
          });
          this.getList();
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Delete canceled',
        });
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
    resetEditStatus() {
      this.editForm = {
        editName: '',
        editDescription: '',
        editActive: '',
        editMainStatusId: '',
      };
    },
  },
};
</script>

