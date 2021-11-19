<template>
  <div class="app-container">
    <div v-for="(user, index) in list" :key="index">
      <el-card class="mt-6">
        <div slot="header" class="flex">
          <span style="font-weight: bold">
            <i class="el-icon-user-solid" /> {{ user.name }}</span
          >
          <el-button
            v-permission="['manage notification channels']"
            style="float: right"
            size="small"
            type="primary"
            icon="el-icon-plus"
            @click="handleCreateForm(user.id, user.name)"
            >{{ $t('notification.addNewCredential') }}</el-button
          >
        </div>
        <div>
          <el-table
            :data="user.notification_credentials"
            border
            fit
            highlight-current-row
          >
            <el-table-column align="center" label="ID" width="80">
              <template slot-scope="scope">
                <span>{{ scope.row.id }}</span>
              </template>
            </el-table-column>

            <el-table-column
              align="center"
              :label="$t('notification.channelName')"
            >
              <template slot-scope="scope">
                <span>{{ scope.row.channel.name }}</span>
              </template>
            </el-table-column>

            <el-table-column
              align="center"
              :label="$t('notification.identifier')"
            >
              <template slot-scope="scope">
                <span>{{ scope.row.identifier }}</span>
              </template>
            </el-table-column>
            <el-table-column
              align="center"
              :label="$t('table.actions')"
              width="350"
            >
              <template slot-scope="scope">
                <el-button
                  v-permission="['manage notification channels']"
                  type="primary"
                  size="mini"
                  icon="el-icon-edit"
                  @click="handleEditForm(scope.row.id, user.id)"
                >
                  {{ $t('table.edit') }}
                </el-button>
                <el-button
                  v-permission="['manage notification channels']"
                  type="danger"
                  size="mini"
                  icon="el-icon-delete"
                  @click="handleDelete(scope.row.id, scope.row.name)"
                >
                  {{ $t('table.delete') }}
                </el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </el-card>

      <el-dialog
        :title="userCredentialFormTitle"
        :visible.sync="userCredentialFormVisible"
      >
        <div class="form-container">
          <el-form
            ref="userCredentialForm"
            :model="currentUserCredential"
            label-position="left"
            label-width="150px"
            style="max-width: 400px"
          >
            <el-form-item :label="$t('notification.channelName')">
              <el-select
                v-model="currentUserCredential.channel_id"
                placeholder="Select"
              >
                <el-option
                  v-for="channel in channels"
                  :key="channel.id"
                  :label="channel.name"
                  :value="channel.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item
              :label="$t('notification.identifier')"
              prop="description"
            >
              <el-input
                v-model="currentUserCredential.identifier"
                type="input"
              />
            </el-form-item>
          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click="userCredentialFormVisible = false">
              {{ $t('table.cancel') }}
            </el-button>
            <el-button type="primary" @click="handleSubmit()">
              {{ $t('table.confirm') }}
            </el-button>
          </div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import permission from '@/directive/permission';
const channelResource = new Resource('channels');
const userCredentialResource = new Resource('user-credentials');

export default {
  name: 'UserCredentials',
  directives: { permission },
  data() {
    return {
      list: [],
      channels: [],
      currentUserId: null,
      currentUserName: '',
      chosenChannel: 1,
      userCredentialFormTitle: '',
      userCredentialFormVisible: false,
      currentUserCredential: {},
    };
  },
  created() {
    this.getList();
    this.getChannels();
  },
  methods: {
    async getList() {
      const { data } = await userCredentialResource.list({});
      this.list = data;
    },

    async getChannels() {
      const { data } = await channelResource.list({});
      this.channels = data;
    },

    handleSubmit() {
      if (this.currentUserCredential.id !== undefined) {
        userCredentialResource
          .update(this.currentUserCredential.id, this.currentUserCredential)
          .then((response) => {
            this.$message({
              type: 'success',
              message: this.$t('notification.credentialUpdated'),
              duration: 5 * 1000,
            });
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            this.userCredentialFormVisible = false;
          });
      } else {
        userCredentialResource
          .store(this.currentUserCredential)
          .then((response) => {
            this.$message({
              message: this.$t('notification.credentialAdded', {
                userName: this.currentUserName,
              }),
              type: 'success',
              duration: 5 * 1000,
            });

            this.currentUserCredential = {
              user_id: null,
              channel_id: null,
              identifier: '',
            };

            this.userCredentialFormVisible = false;
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleEditForm(id, user_id) {
      const user = this.list.find((user) => user.id === user_id);
      const credential = user.notification_credentials.find(
        (credential) => credential.id === id
      );
      this.currentUserCredential = {
        id: id,
        channel_id: credential.channel_id,
        identifier: credential.identifier,
        user_id: user_id,
      };
      this.userCredentialFormTitle = this.$t('notification.editCredential');
      this.userCredentialFormVisible = true;
    },
    handleDelete(id, name) {
      this.$confirm(
        this.$t('notification.credentialDeleteWarning'),
        this.$t('table.warning'),
        {
          confirmButtonText: 'OK',
          cancelButtonText: this.$t('table.cancel'),
          type: 'warning',
        }
      )
        .then(() => {
          userCredentialResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Deletion completed',
              });
              this.getList();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Deletion canceled',
          });
        });
    },
    handleCreateForm(currentUserId, currentUserName) {
      this.currentUserId = currentUserId;
      this.userCredentialFormTitle = this.$t('notification.newCredential', {
        userName: currentUserName,
      });
      this.currentUserName = currentUserName;
      this.userCredentialFormVisible = true;
      this.currentUserCredential = {
        user_id: this.currentUserId,
        channel_id: '',
        identifier: '',
      };
    },
  },
};
</script>
<style scoped>
.mt-6 {
  margin-top: 1.5rem;
}
.flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
</style>
