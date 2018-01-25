<template>
  <div class="row">
    <div class="col-md-12">

      <h1 class="display-5">{{ $t('notes_index_heading') }}</h1>
      <p class="lead" v-if="pageIsReady && notes.length > 0">
        {{$t('notes_index_notes_exist_lead_message')}}
      </p>

      <div class="jumbotron" v-if="pageIsReady && notes.length === 0">
        <h1 class="display-4">{{$t('notes_index_no_notes_header')}} {{ user.name }}</h1>
        <p class="lead">{{$t('notes_index_no_notes_lead')}}</p>
        <hr class="my-4">
        <p>{{$t('notes_index_no_notes_paragraph')}}</p>
        <p class="text-center">
          <router-link :to="{ name: 'notes_create' }" class="btn btn-primary btn-lg" href="#" role="button">
            {{$t('home_create_new_button_label')}}
          </router-link>
        </p>
      </div>

      <div class="row">
        <div class="container">
          <div class="row justify-content-start align-items-center">
            <div class="col-md-6 mt-2" v-for="note in notes" :key="note.id">
              <note :note="note" @noteDeleted="deleteNote($event.note)"></note>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3" v-if="pageIsReady && notes.length > 0">
        <div class="col-md-12 text-right">
          <router-link :to="{ name: 'notes_create'}" class="btn btn-primary">New note</router-link>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

  import axios from 'axios';
  import { mapGetters } from 'vuex'
  import swal from 'sweetalert2';
  import Note from '../../components/Note';

  export default {
    components: {Note},
    middleware: 'auth',
    metaInfo() {
      return {title: this.$t('notes_index_meta_title')};
    },
    data: () => ({
      notes: [],
      pageIsReady: false,
    }),
    computed: mapGetters({
      user: 'auth/user'
    }),
    mounted() {
      this.refresh();
    },
    methods: {
      async refresh() {
        try {
          this.pageIsReady = false;
          const {data} = await axios.get('api/notes');
          this.notes = data.map(note => {
            note.updating = false;
            note.modified = false;

            return note;
          });
        } catch (error) {
          swal({
            type: 'error',
            title: this.$t('error_alert_title'),
            text: this.$t('notes_index_load_error_message'),
            confirmButtonText: this.$t('ok')
          });
        }
        this.pageIsReady = true;
      },

      deleteNote(note) {
        swal({
          title: this.$t('notes_index_confirm_note_delete_title'),
          text: this.$t('notes_index_confirm_note_delete_message'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('yes_confirm')
        }).then(async (result) => {
          if (result.value) {
            try {
              const {data} = await axios.delete(`api/notes/${note.id}`);
              if (data.success) {
                this.notes = this.notes.filter((n) => n.id !== note.id);
              }
            } catch (error) {
              swal({
                type: 'error',
                title: this.$t('error_alert_title'),
                text: this.$t('notes_index_delete_error_message'),
                confirmButtonText: this.$t('ok')
              });
            }
          }
        });
      }
    }

  };
</script>
