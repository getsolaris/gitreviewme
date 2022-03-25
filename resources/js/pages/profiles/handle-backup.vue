<template>
  <div>

    <Modal @close="closeModal" v-if="modal" v-bind:style="{ display : modal === true ? 'inherit' : 'none'}">
      <div class="card-header">
        <div style="float: left">
          <h4 v-bind:style="{ marginBottom: project.description === null ? '2px' : '' }">{{ project.full_name }}</h4>
          <h6 v-bind:style="{ marginBottom: project.description !== '' ? '2px' : '-6px' }">{{
              project.description
            }}</h6>
        </div>
      </div>
      <div class="card-body">
        <p style="color: red">{{ $t('add_project_body') }}</p>
      </div>
      <footer class="card-footer">
        <button class="btn btn-sm btn-secondary" @click="closeModal">Close</button>
        <button class="btn btn-sm btn-secondary" style="float: right" @click="submitProject">Submit</button>
      </footer>
    </Modal>

    <div class="row">
      <div class="col-lg-4 ml-auto" style="margin-top: 15px;">
        <div class="card h-fixed-310" style="margin-bottom: 35px;">
          <div class="card-header">
            <div style="float: left"
                 v-if="! user || (user && user.data.oauth_provider.provider_user_handle_id !== handle)">{{ handle }}'s
              Info
            </div>
            <div style="float: left" v-if="user && user.data.oauth_provider.provider_user_handle_id === handle">
              {{ $t('your_info') }}
            </div>
          </div>
          <div class="card-body">
            <h4 class="card-title">
              @{{ profile.oauth_provider.provider_user_handle_id }}
              <span style="float: right; font-size: 13px; margin-top: 15px;">{{
                  profile.oauth_provider.github_information.followers
                }} followers</span>
              <span style="float: right; font-size: 13px; margin-top: 15px;">&nbsp;&nbsp;·&nbsp;&nbsp;</span>
              <span style="float: right; font-size: 13px; margin-top: 15px;">{{
                  profile.oauth_provider.github_information.following
                }} following</span>
            </h4>
            <hr/>
            <h6>bio: {{ profile.oauth_provider.github_information.bio }}</h6>
            <hr/>
            <h6>company: {{ profile.oauth_provider.github_information.company }}</h6>
            <hr/>
            <h6>email: {{ profile.oauth_provider.github_information.email }}</h6>
            <hr/>
            <h6>register date: {{ profile.oauth_provider.github_information.user_registered_at }}</h6>
          </div>
        </div>

      <!--        <card :title="$t('your_skills')" class="h-fixed-150" style="margin-top: 15px;">-->
      <!--          <span v-for="skill in profile.skills" :key="skill.id" class="btn btn-sm btn-dark"-->
      <!--                style="margin-right: 5px;">{{ skill.name }}</span>-->
      <!--        </card>-->

      <div class="card" style="margin-top: 15px; margin-bottom: 35px;">
        <div class="card-header">
          <div style="float: left"
               v-if="! user || (user && user.data.oauth_provider.provider_user_handle_id !== handle)">{{ handle }}'s Projects
          </div>
          <div style="float: left" v-if="user && user.data.oauth_provider.provider_user_handle_id === handle">
            {{ $t('your_projects') }}
          </div>
        </div>
        <div class="card-body">
          <div class="card" v-for="project in profile.projects" :key="project.id" style="margin-bottom: 10px;">
            <div class="card-body">
              <div class="card-title">
                <span class="h5"><a style="color: black" :href="project.url">{{ project.title }}</a></span>
                <span v-if=" user && user.data.oauth_provider.user_id === project.user_id"> <!-- TODO: oauth_provider.provider_user_id -->
                    <span style="float: right; margin-right: 5px; cursor: pointer" class="badge badge-dark"><fa
                      icon="pencil-alt" fixed-width/></span>
                    <span style="float: right; margin-right: 5px; cursor: pointer"
                          class="badge badge-danger"><fa icon="trash" fixed-width/></span>
                  </span>
              </div>
              <h6 class="card-subtitle mb-2 text-muted">{{ project.description }}</h6>
              <div style="float: right">
                  <span v-for="skill in project.skills" :key="skill.id" class="badge badge-dark"
                        style="margin-right: 5px;">{{ skill.name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--        <div class="card h-fixed-310" style="margin-top: 15px;">-->
      <!--          <div class="card-header">-->
      <!--            <div style="float: left">{{ $t('all_activity') }}</div>-->
      <!--          </div>-->
      <!--          <div class="card-body">-->
      <!--            <p>ALL ACTIVITY SECTION</p>-->
      <!--          </div>-->
      <!--        </div>-->
    </div>

    <div class="col-lg-8 ms-auto" style="margin-top: 15px; margin-bottom: 35px;">
      <div class="card">
        <div class="card-header">
          <div style="float: left"
               v-if="! user || (user && user.data.oauth_provider.provider_user_handle_id !== handle)">{{ handle }}'s
            Repositories (Order by Pushed)
          </div>
          <div style="float: left" v-if="user && user.data.oauth_provider.provider_user_handle_id === handle">
            {{ $t('your_repos') }} (Order by Pushed)
          </div>
        </div>
        <div class="card-body">
          <div class="card" v-if="repository.owner && ! repository.hide"
               v-for="repository in profile.oauth_provider.github_repositories"
               :key="repository.id"
               style="margin-bottom: 10px;"
               v-bind:style="{ opacity : repository.is_visibility === 'private' ? '0.5' : '', border: repository.has_project === true ? '1px solid #00000040' : ''}">
            <div class="card-body">
              <div class="card-title">
                  <span class="h5"><a style="color: black" :href="repository.url">{{ repository.full_name }}</a>
                  </span>
                <!--                  <span style="float: right;" class="badge badge-dark">{{ $t('refresh') }}</span>-->
                <span style="float: right; margin-right: 5px; cursor: pointer" class="badge badge-dark"
                      v-if="repository.is_visibility === 'public' && repository.has_project === false && (user && user.data.oauth_provider.id === repository.oauth_provider_id)"
                      @click="openModal(repository)"><fa icon="plus" fixed-width/></span>
              </div>
              <h6 class="card-subtitle mb-2 text-muted">{{ repository.description }}</h6>
              <p style="font-size: 11px; color: gray; margin-bottom: 5px;"
                 v-if="repository.is_visibility === 'private'">(Only public repository can register for the
                project)</p>
              <div style="float: left">
                <span class="badge badge-dark">{{ repository.language }}</span>
              </div>
              <div style="float: right">
                <span><fa icon="star" fixed-width/> {{ repository.stargazers_count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
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
import axios from 'axios'
import Modal from '~/components/Modal'
import Form from "vform";
import {mapGetters} from "vuex";

export default {
  // middleware: 'auth',
  components: {Modal},

  computed: mapGetters({
    user: 'auth/user'
  }),

  data() {
    return {
      handle: null,
      modal: false,
      project: null, // modal
      profile: null,
    }
  },
  methods: {
    async getProfile() {
      const {data: profiles} = await axios.get('/api/profiles/' + this.handle)
      this.profile = profiles.data;

      return {
        profiles
      }
    },
    async submitProject() {
      const {data} = await axios.post('/api/projects', {
        "user_id": this.profile.id,
        "repository_id": this.project.id,
        "oauth_provider_id": this.project.oauth_provider_id
      });

      this.profile.projects.push(data);
    },
    openModal(repository) {
      this.modal = true
      this.project = repository
    },
    closeModal() {
      this.modal = false
      this.project = null
    },
  },
  created() {
    this.handle = this.$route.params.handle
    this.profile = this.getProfile()
  },

  metaInfo() {
    return {title: this.$t('profile')}
  }
}
</script>
