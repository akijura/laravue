<template>
  <div class="app-container">
    <div v-for="(user, index) in list" :key="index">
      <el-card class="box-card mt-4">
        <div slot="header" class="clearfix">
          <span icon>{{ user.name }}</span>
          <el-button
            style="float: right"
            type="primary"
            icon="el-icon-plus"
            @click="handleCreateForm(user.id, user.name)"
          >Add new credential</el-button>
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

            <el-table-column align="center" label="Channel name">
              <template slot-scope="scope">
                <span>{{ scope.row.channel.name }}</span>
              </template>
            </el-table-column>

            <el-table-column align="center" label="Identifier">
              <template slot-scope="scope">
                <span>{{ scope.row.identifier }}</span>
              </template>
            </el-table-column>
            <el-table-column align="center" label="Actions" width="350">
              <template slot-scope="scope">
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
            <el-form-item label="Channel">
              <el-select v-model="chosenChannel" placeholder="Select">
                <el-option
                  v-for="channel in channels"
                  :key="channel.id"
                  :label="channel.name"
                  :value="channel.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label="Identifier" prop="description">
              <el-input v-model="currentUserCredential.id" type="input" />
            </el-form-item>
          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click="userCredentialFormVisible = false">
              Cancel
            </el-button>
            <el-button type="primary" @click="handleSubmit()">
              Confirm
            </el-button>
          </div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const channelResource = new Resource('channels');
const userCredentialResource = new Resource('user-credentials');

export default {
  name: 'UserCredentials',
  data() {
    return {
      list: [],
      channels: [],
      currentUser: null,
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
      console.log(this.list);
    },

    async getChannels() {
      const { data } = await channelResource.list({});
      this.channels = data;
    },

    handleSubmit() {},
    handleCreateForm(currenUserId, currentUserName) {
      this.currentUser = currenUserId;
      this.userCredentialFormTitle =
        'New user credential for ' + currentUserName;
      this.userCredentialFormVisible = true;
      this.currentUserCredential = {
        identifier: '',
      };
    },
  },
};
</script>
<style scoped>
.mt-4 {
  margin-top: 1rem;
}
</style>
