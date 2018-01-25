<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </template>
    </div>


    <div class="jumbotron">
      <h1 class="display-4">{{$t('home_header')}}</h1>
      <p class="lead">{{$t('home_lead_paragraph')}}</p>
      <hr class="my-4">
      <p>{{$t('home_secondary_paragraph')}}</p>
      <p class="text-center">
        <router-link :to="{ name: 'notes_create' }" class="btn btn-primary btn-lg" href="#" role="button">
          {{$t('home_create_new_button_label')}}
        </router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'basic',

  metaInfo () {
    return { title: this.$t('home') }
  },

  computed: mapGetters({
    authenticated: 'auth/check'
  }),

  data: () => ({
    title: window.config.appName
  })
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
