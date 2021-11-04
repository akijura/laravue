<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreateForm"
      >
        {{ $t('table.add') }}
      </el-button>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      highlight-current-row
      width="100%"
    >
      <el-table-column align="center" label="ID" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="API Link">
        <template slot-scope="scope">
          <span>{{ scope.row.api_link }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="350">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="small"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id, scope.row.name)"
          >
            Edit
          </el-button>
          <el-button
            type="danger"
            size="small"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.name)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="formTitle" :visible.sync="channelFormVisible">
      <div class="form-container">
        <el-form
          ref="channelForm"
          :model="currentChannel"
          label-position="left"
          label-width="150px"
          style="max-width: 500px"
        >
          <el-form-item label="Name" prop="name">
            <el-input v-model="currentChannel.name" />
          </el-form-item>
          <el-form-item label="API Link" prop="description">
            <el-input v-model="currentChannel.api_link" type="text" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="channelFormVisible = false"> Cancel </el-button>
          <el-button type="primary" @click="handleSubmit()">
            Confirm
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const channelResource = new Resource('channels');

export default {
  name: 'ChannelList',
  data() {
    return {
      list: [],
      formTitle: '',
      loading: true,
      channelFormVisible: false,
      currentChannel: {},
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      const { data } = await channelResource.list({});
      this.list = data;
      this.loading = false;
    },
    handleSubmit() {
      if (this.currentChannel.id !== undefined) {
        channelResource
          .update(this.currentChannel.id, this.currentChannel)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Channel info has been updated successfully',
              duration: 5 * 1000,
            });

            this.getList();
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            this.channelFormVisible = false;
          });
      } else {
        channelResource.store(this.currentChannel).then((response) => {
          this.$message({
            message: 'New channel has been created successfully',
            type: 'success',
            duration: 5 * 1000,
          });

          this.currentChannel = {
            name: '',
            api_link: '',
          };

          this.channelFormVisible = false;
          this.getList();
        });
      }
    },
    handleCreateForm() {
      (this.formTitle = 'Create a new channel'),
        (this.channelFormVisible = true);
      this.currentChannel = {
        name: '',
        api_link: '',
      };
    },

    handleDelete(id, name) {
      this.$confirm(
        'This will permanently delete channel ' + name + '. Continue?',
        'Warning',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      ).then(() => {
        channelResource
          .destroy(id)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Delete completed',
            });
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: 'Delete canceled',
            });
          });
      });
    },

    handleEditForm(id) {
      this.formTitle = 'Edit channel';
      this.currentChannel = this.list.find((channel) => channel.id === id);
      this.channelFormVisible = true;
    },
  },
};
</script> 