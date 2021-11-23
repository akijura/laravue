<template>
  <div class="app-container">
    <el-form v-if="user" :model="user">
      <el-row :gutter="20">
        <el-col :span="14">
          <user-activity :user="user" />
        </el-col>
        <el-col :span="10">
          <user-card :user="user" />
          <user-bio />
        </el-col>

      </el-row>
    </el-form>
  </div>
</template>

<script>
import UserBio from './components/UserBio';
import UserCard from './components/UserCard';
import UserActivity from './components/UserActivity';
import { getProjectAuth } from '@/api/project';

export default {
  name: 'SelfProfile1',
  components: { UserBio, UserCard, UserActivity },
  data() {
    return {
      user: {},
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    this.getAuthor();
    console.log(this.$root);
  },
  methods: {
    async getAuthor() {
      const projectId = localStorage.getItem('projectId');
      await getProjectAuth(projectId).then(response => {
        this.user = response.data.author_name;
        console.log(this.$route.params.projectId);
      });
    },
  },
};
</script>
