<template>
  <el-card v-if="user.name" v-loading="loading">
    <div class="user-activity">
      <div class="post">
        <div class="user-block" style=" margin-bottom: 15px; ">

          <el-row>
            <span class="username project-name">

              <span class="pull-right btn-box-tool">
                <i class="fa fa-times" />
              </span>
              <el-button class="item-btn pull-right" size="small" type="warning" style="margin-left: 5px">
                {{ this.projectDetails.main_status_name }}
              </el-button>
              <el-button class="item-btn pull-right" size="small" type="info">
                {{ this.projectDetails.status_name }}
              </el-button>
              <el-button class="item-btn pull-right" size="small" type="danger">
                {{ $t('projects.' + this.projectDetails.basic_status_name) }}
              </el-button>
              <el-button class="item-btn pull-right" size="small" type="primary" style="margin-left: 5px" @click="handleUpdateStatus">
                {{ $t('projects.changeTypeStatus') }}
              </el-button>
            </span>
          </el-row>

          <el-row style="margin-top: 10px;">
            <el-card class="box-card">
              <span style="color: red; font-size: 25px;"> {{ $t('projects.projectName') }} : {{ this.projectDetails.name }}</span>
              <span class="description " style="font-size: 20px">{{ $t('projects.projectDescription') }} : {{ this.projectDetails.description }}</span>
            </el-card>
          </el-row>

          <!-- <div  class="pull-right">{{$t('projects.createdAt') + ' ' + this.projectDetails.created_at }}</div> -->
        </div>
        <div v-for="comment in comments" :key="comment.id">
          <div class="post">
            <el-row>
              <el-card class="box-card">
                <div slot="header" class="clearfix" style=" margin-bottom: 15px; ">
                  <span> {{ $t('projects.comment') }} : {{ comment.comment }}</span>
                </div>
                <div slot="header" class="clearfix">
                  <span> {{ $t('projects.files') }}</span>
                  <el-button type="info" size="small" class="pull-right" style=" border-radius: 5px 5px 5px 5px;">
                    {{ comment.status_name }}
                  </el-button>
                </div>
                <div v-for="file in comment.files" :key="file.id" class="item-btn pull-right" size="small" type="warning" style="margin-left: 5px; margin-right: 5px; margin-bottom: 15px; ">
                  <el-button type="primary" @click="downloadFiles(file.name,file.id)">
                    {{ file.name }}
                  </el-button>
                </div>
              </el-card>

            </el-row>
            <ul class="list-inline">
              <li>
                <span class="link-black text-sm">
                  <svg-icon icon-class="comment" /> {{ comment.user_name }}
                </span>
              </li>
              <li class="pull-right">
                <span class="link-black text-sm">
                  {{ $t('projects.createdAt') + ' ' + comment.created_at }}
                </span>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
    <el-dialog :title="$t('projects.changeTypeStatus')" :visible.sync="dialogFormVisible">
      <div v-loading="updateStatus" class="form-container">
        <el-form ref="updateStatus" :model="updateStatusForm" label-position="left" label-width="150px" style="max-width: 800px;" :rules="rules">
          <el-form-item :label="$t('main_status.statusName')" type="textarea" prop="basicStatus">
            <el-select v-model="updateStatusForm.status" placeholder="please select" style="width: 100%;">
              <el-option v-for="status in listStatus" :key="status.id" :value="status.id" :label="status.name" class="filter-item" />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('projects.projectComment')" type="textarea" prop="comment">
            <el-input v-model="updateStatusForm.comment" type="textarea" placeholder="Type a comment" />
          </el-form-item>

          <el-form-item :label="$t('projects.projectComment')" type="file" prop="mainStatusDescription">
            <input ref="files" :v-model="updateStatusForm.files" type="file" multiple class="form-control" placeholder="Upload ...." @change="imageChange">
          </el-form-item>

        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>

          <el-button type="primary" @click="uploadImages">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
    <el-tooltip placement="top" :content="$t('projects.up')">
      <back-to-top :custom-style="myBackToTopStyle" :visibility-height="30" :back-position="50" transition-name="fade" />
    </el-tooltip>
  </el-card>

</template>

<script>
import Resource from '@/api/resource';
import ProjectResource from '@/api/project';
import CommentsResource from '@/api/comments';
import StatusResource from '@/api/status';
import Dropzone from '@/components/Dropzone';
import UserBio from './UserBio';
import FilesResource from '@/api/files';
import { downloadFile } from '@/api/files';
import BackToTop from '@/components/BackToTop';

const projectResource = new ProjectResource();
const commentsResource = new CommentsResource();
const StatusResourceConst = new StatusResource();
const filesResource = new FilesResource();

export default {
  name: 'DropzoneDemo',
  // name: 'BackToTopDemo',
  // name: 'EditorSlideUpload',
  components: { Dropzone,UserBio ,BackToTop},
    mounted() {
        this.$root.$on('UserActivity',() =>{
           this.getListProject();
            this.getComments();
          
        })
      },
  props: {
    color: {
      type: String,
      default: '#1890ff',
    },
  },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],

        };
      },
    },
  },

  data() {
    return {
      rules: {
        comment: [{ required: true, message: 'Comment is required', trigger: 'change' }],
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
      images: [],
      files: [],
      uploads: {
        formData: new FormData(),
        project_id: '',
        comment: '',
      },
      attachment: null,
      form: new FormData(),
      query: {
        id: '',
        projectDetail: 'OK',
      },
      projectDetails: [],
      comments: [],
      updateStatusForm: {
        status: '',
        comment: '',
        files: [],
        comment_text: '',
        project_id: '',
      },
      listStatus: [],
      listObj: {},
      fileList: [],
      updateStatus: false,
      dialogFormVisible: false,
      loading: false,
      activeActivity: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      updating: false,
    };
  },
  created() {
    this.getListProject();
    this.getComments();
  },
  methods: {
    imageChange() {
      for (let i = 0; i < this.$refs.files.files.length; i++) {
        this.images.push(this.$refs.files.files[i]);
      }
    },
    uploadImages() {
      this.$refs['updateStatus'].validate((valid) => {
        if (valid) {
          var self = this;
          const formData = new FormData();
          for (let i = 0; i < this.images.length; i++) {
            const file = self.images[i];
            formData.append('files[' + i + ']', file);
          }
          formData.append('id', this.projectDetails.id);
          formData.append('comment', this.updateStatusForm.comment);
          formData.append('status_id', this.updateStatusForm.status);
          filesResource
            .store(formData)
            .then(response => {
              console.log(response);
              this.$message({
                message: 'New comment has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.getComments();
              this.$root.$emit('UserBio');
              this.getListProject();
              this.updateStatusForm.files = [];
              this.updateStatusForm.comment = '';
              this.dialogFormVisible = false;
            });
        }
      });
    },
    async downloadFiles(name) {
      await downloadFile(name).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', name);
        document.body.appendChild(link);
        link.click();
        link.remove();
      });
    },

    async getListProject(){
      const projectId = localStorage.getItem('projectId');
      this.projectDetails = [];
      this.query.id = projectId;
      this.query.projectDetail = 'OK';
      this.loading = true;
      const { data } = await projectResource.list(this.query);

      this.projectDetails = data[0];

      this.loading = false;
    },
    async getComments(){
      const projectId = localStorage.getItem('projectId');
      this.comments = [];
      this.query.id = projectId;
      this.loading = true;
      const { data } = await commentsResource.list(this.query);
      this.comments = data;
      this.loading = false;
    },
    handleUpdateStatus() {
      this.dialogFormVisible = true;
      this.getListStatus();
    },
    async getListStatus() {
      this.updateStatus = true;
      this.listStatus = [];
      this.updateStatusForm.status = this.projectDetails.type_status;
      const { data } = await StatusResourceConst.list(this.projectDetails.main_status_id);
      data.forEach((status) => {
        if (status['current_role'] === 'admin' || status['current_role'] === 'moderator') {
          this.listStatus.push({
            id: status['id'],
            name: status['name'],
          });
        } else {
          if (status['queue'] >= this.projectDetails.queue) {
            this.listStatus.push({
              id: status['id'],
              name: status['name'],
            });
          }
        }
      });
      this.updateStatus = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.editor-slide-upload {
  margin-bottom: 20px;
  /deep/ .el-upload--picture-card {
    width: 100%;
  }
}
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
      font-weight: bold;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: bold;
      font-size: 20px;
    }
  }
  .project-name {
    font-weight: bold;
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
</style>
