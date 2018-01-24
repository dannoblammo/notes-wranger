<template>
  <div class="row">
    <div class="col-md-12">
      Notes list!
    </div>
  </div>

</template>

<script>

  export default {
    middleware: 'auth',
    metaInfo() {
      return {title: this.$t('notes_index_meta_title')};
    },
    data: () => ({
      notes: []
    }),
    methods: {
      async refresh() {

      }
    }
  };
</script>
