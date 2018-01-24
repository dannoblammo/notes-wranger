<template>
  <div class="row">
    <div class="col-md-12">
      <card :title="$t('notes_create_card_title')">
        <form @submit.prevent="save" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="$t('notes_create_note_created_message')"/>

          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">
              {{$t('notes_create_new_note_title_input_label')}}
            </label>
            <div class="col-md-7">
              <input type="text" v-model="form.title" name="title" class="form-control" required maxlength="255"
                     :data-msg-required="$t('notes_create_errors_title_required_message')"
                     :class="{ 'is-invalid': form.errors.has('title') }">
              <has-error :form="form" field="title"/>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">
              {{ $t('notes_create_new_note_input_label') }}
            </label>
            <div class="col-md-7">
              <textarea v-model="form.contents" name="contents" class="form-control" rows="5" required
                        :data-msg-required="$t('notes_create_errors_contents_required_message')"
                        :class="{ 'is-invalid': form.errors.has('contents') }">
              </textarea>
              <has-error :form="form" field="contents"/>
            </div>
          </div>


          <div class="form-group row">
            <div class="col-md-9 ml-md-auto">
              <v-button type="success" :loading="form.busy">{{ $t('notes_create_save_button_label') }}</v-button>
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>

</template>

<script>
  import Form from 'vform';
  import swal from 'sweetalert2';

  export default {
    middleware: 'auth',
    metaInfo() {
      return {title: this.$t('notes_create_meta_title')};
    },
    data: () => ({
      form: new Form({
        contents: '',
        title: ''
      })
    }),

    methods: {
      async save() {
        try {
          await this.form.post('/api/notes/create');
          this.form.reset();
          this.$router.push({name: 'notes_index'});
        } catch (error) {
          swal({
            type: 'error',
            title: this.$t('error_alert_title'),
            text: this.$t('notes_create_save_error_message'),
            reverseButtons: true,
            confirmButtonText: this.$t('ok'),
            cancelButtonText: this.$t('cancel')
          });
        }
      }
    }
  };
</script>
