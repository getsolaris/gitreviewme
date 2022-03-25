<template>
  <div>
    <section class="py-5 text-center container">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">{{ project.title }} <br/></h1>
          <!--        <span class="lead text-muted" style="font-size: 1rem;">-->
          <!--              <span>{{ profile.oauth_provider.github_information.followers }} followers</span>-->
          <!--              <span>&nbsp;&nbsp;·&nbsp;&nbsp;</span>-->
          <!--              <span>{{ profile.oauth_provider.github_information.following }} following</span>-->
          <!--            </span>-->
          <!--        <hr/>-->
          <!--        <p class="lead text-muted">{{ profile.oauth_provider.github_information.bio }}</p>-->
        </div>
      </div>
    </section>
    <div class="row">
      <div v-html="markdownToHtml"></div>
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

</style>

<script>
// import axios from 'axios'
import axios from "axios";
import marked from 'marked';
import {component as VueCodeHighlight} from 'vue-code-highlight';

export default {
  components: {
    VueCodeHighlight,
  },
  // middleware: 'auth',
  data() {
    return {
      id: null,
      project: [],
      markdown: null,
    }
  },
  methods: {
    async getProject() {
      const {data: project} = await axios.get('/api/projects/' + this.id);
      this.project = project.data;
      this.markdown = this.project.content;

      return {
        project
      }
    },
  },
  created() {
    this.id = this.$route.params.id
    this.project = this.getProject()
  },
  computed: {
    markdownToHtml() {
      return marked(this.markdown);
    }
  },
  metaInfo() {
    return {title: this.$t('projects')}
  }
}
</script>
