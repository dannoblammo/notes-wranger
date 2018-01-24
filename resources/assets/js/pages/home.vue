<template>

  <div class="jumbotron">
    <h1 class="display-4">{{$t('home_header')}}</h1>
    <p class="lead">{{$t('home_lead_paragraph')}}</p>
    <hr class="my-4">
    <p>{{$t('home_secondary_paragraph')}}</p>
    <p class="lead">
      <router-link :to="{ name: 'notes_create' }" class="btn btn-primary btn-lg" href="#" role="button">
        {{$t('home_create_new_button_label')}}
      </router-link>
    </p>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home_meta_title') }
  }
}
</script>
