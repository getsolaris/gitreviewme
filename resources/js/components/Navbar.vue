<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <router-link :to="{ name: 'home' }" class="navbar-brand">
        {{ appName }}
      </router-link>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
        <span class="navbar-toggler-icon" />
      </button>

      <div id="navbar" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <locale-dropdown />
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li> -->
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link :to="{ name: 'reviewers.index' }" class="nav-link" active-class="active">
              {{ $t('reviewers') }}
            </router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'projects.index' }" class="nav-link" active-class="active">
              {{ $t('projects') }}
            </router-link>
          </li>
          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark"
               href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            >
              <img :src="user.data.oauth_provider.provider_user_avatar_url" class="rounded-circle profile-photo me-1">
              {{ user.data.oauth_provider.provider_user_handle_id }}
            </a>
            <div class="dropdown-menu">
              <router-link :to="{ name: 'profile.handle', params: { handle: user.data.oauth_provider.provider_user_handle_id }}" class="dropdown-item ps-3">
                <fa icon="user" fixed-width />
                {{ $t('your_profile') }}
              </router-link>

              <div class="dropdown-divider" />
              <a href="#" class="dropdown-item ps-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width />
                {{ $t('logout') }}
              </a>
            </div>
          </li>
          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'

export default {
  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: window.config.appName
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}

.container {
  max-width: 1100px;
}
</style>
