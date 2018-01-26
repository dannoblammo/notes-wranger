<template>
  <div class="collapse" :class="{'show':visible}">
    <div class="row mt-1 justify-content-start">
      <div class="col-md-12">
        <form @submit.prevent="addEmail">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text">{{$t('notes_index_shared_with_label')}}</span>
            </div>
            <input type="email" name="email" v-model="newEmail" class="form-control" placeholder="name@example.com">
            <div class="input-group-append">
              <button class="btn">{{$t('notes_index_shared_with_add_button_label')}}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">

  export default {
    name: 'note-tool-controls',
    props: ['shares', 'visible'],
    data() {
      return {
        newEmail: '',
        emails: this.shares
      };
    },
    methods: {
      addEmail() {
        if (this.newEmail.trim() !== '') {
          if (this.emails.some(e => e.email === this.newEmail)) {
            return false;
          }
          this.emails.push({email: this.newEmail});
          this.newEmail = '';
          this.$emit('sharesUpdated', {shares: this.emails});
        }
      },
    },
    components: {}
  };

</script>

<style scoped>

</style>
