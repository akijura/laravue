<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button
        v-permission="['manage notification channel']"
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

      <el-table-column align="center" :label="$t('notification.channelName')">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('notification.channelApiLink')"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.api_link }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.actions')" width="350">
        <template slot-scope="scope">
          <el-button
            v-permission="['manage notification channels']"
            type="primary"
            size="small"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id, scope.row.name)"
          >
            {{ $t('table.edit') }}
          </el-button>
          <el-button
            v-permission="['manage notification channels']"
            type="danger"
            size="small"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.name)"
          >
            {{ $t('table.delete') }}
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
        >
          <el-form-item :label="$t('notification.channelName')" prop="name">
            <el-input v-model="currentChannel.name" />
          </el-form-item>
          <el-form-item
            :label="$t('notification.channelApiLink')"
            prop="description"
          >
            <el-input v-model="currentChannel.api_link" type="text" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="channelFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="handleSubmit()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import permission from '@/directive/permission';
const channelResource = new Resource('channels');

export default {
  name: 'ChannelList',
  directives: { permission },
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
              message: this.$t('notification.channelUpdated'),
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
            message: this.$t('notification.channelCreated'),
            type: 'success',
            duration: 5 * 1000
          });

          this.currentChannel = {
            name: '',
            api_link: ''
          };
          this.channelFormVisible = false;
          this.getList();
        });
      }
    },
    handleCreateForm() {
      (this.formTitle = this.$t('notification.createNewChannel')),
        (this.channelFormVisible = true);
      this.currentChannel = {
        name: '',
        api_link: '',
      };
    },

    handleDelete(id, name) {
      this.$confirm(
        this.$t('notification.channelDeleteWarning', { channelName: name }),
        this.$t('table.warning'),
        {
          confirmButtonText: 'OK',
          cancelButtonText: this.$t('table.cancel'),
          type: 'warning',
        }
      ).then(() => {
        channelResource
          .destroy(id)
          .then((response) => {
            this.$message({
              type: 'success',
              message: this.$t('notification.channelDeleted'),
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
      this.formTitle = this.$t('notification.editChannel');
      this.currentChannel = this.list.find((channel) => channel.id === id);
      this.channelFormVisible = true;
    },
  },
};
</script>
