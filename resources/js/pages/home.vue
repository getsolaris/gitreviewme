<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
      Main
    </div>
  </div>
</template>

<script>
// import axios from 'axios'
export default {
  // middleware: 'auth',

  // async asyncData () {
  //   const { data: projects } = await axios.get('/api/projects')

  //   return {
  //     projects
  //   }
  // },

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>
