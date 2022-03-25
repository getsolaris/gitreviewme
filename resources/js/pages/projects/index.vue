<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
      <card :title="$t('projects')">
        <div class="card-body">
          <div class="card" v-for="project in projects" :key="project.id" style="margin-bottom: 10px;">
            <div class="card-body">
              <div class="card-title">
                <router-link :to="{ name: 'projects.show', params: { id: project.id }}" class="h5">
                  {{ project.user.name }}/{{ project.title }}
                </router-link>
<!--                <a class="h5" :href="project.id">{{ project.user.name }}/{{ project.title }}</a>-->
              </div>
              <h6 class="card-subtitle mb-2 text-muted">{{ project.description }}</h6>
              <div>
                <span class="badge badge-dark" style="margin-right: 5px;">{{
                    project.github_repository.language
                  }}</span>
                <span style="float: right"><fa icon="star" fixed-width/> {{
                    project.github_repository.stargazers_count
                  }}</span>
                <hr/>
                <span class="badge badge-dark" style="margin-right: 5px;">{{
                    project.github_repository.license.name
                  }}</span>
              </div>
            </div>
          </div>
        </div>
      </card>
    </div>
  </div>
</template>

<style lang="scss">
.h-fixed-300 {
  height: 300px !important;
}

.h-fixed-310 {
  height: 310px !important;
}

.h-fixed-150 {
  height: 150px !important;
}

.badge-dark {
  color: #fff;
  background-color: #343a40;
}

.badge-danger {
  color: #fff;
  background-color: #e20808;
}

.word-remove {
  display: inline-block;
  width: 215px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

a:link {
  color: black;
  text-decoration: none;
}

a:visited {
  color: black;
  text-decoration: none;
}

a:hover {
  color: black;
  text-decoration: underline;
}

</style>

<script>
// import axios from 'axios'
import axios from "axios";

export default {
  // middleware: 'auth',
  data() {
    return {
      projects: [],
    }
  },
  methods: {
    async getProjects() {
      const {data: projects} = await axios.get('/api/projects');
      this.projects = projects.data;
      console.log(projects.data)

      return {
        projects
      }
    },
  },
  created() {
    this.projects = this.getProjects()
  },
  metaInfo() {
    return {title: this.$t('projects')}
  }
}
</script>
