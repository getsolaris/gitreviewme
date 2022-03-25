<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
      <card :title="$t('login')">
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <div class="mb-3 row">
            <div class="d-grid gap-2">
              <!-- GitHub Login Button -->
              <login-with-github />
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'
import LoginWithGithub from '~/components/LoginWithGithub'
import Swal from 'sweetalert2/dist/sweetalert2.js'

export default {
  components: {
    LoginWithGithub
  },

  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/auth/login')

      if (data.error || data.errors) {
        return Swal.fire({
          icon: 'warning',
          title: 'ERROR TITLE',
          text: 'ERROR TEXT',
          reverseButtons: true,
        });
      }

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        // token: data.token,
        token: data.access_token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.redirect()
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
