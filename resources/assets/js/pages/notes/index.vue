<template>
  <div class="row">
    <div class="col-md-12">

      <h1 class="display-5" v-if="pageIsReady && notes.length > 0">{{ $t('notes_index_heading') }}</h1>
      <p class="lead" v-if="pageIsReady && notes.length > 0">
        {{$t('notes_index_notes_exist_lead_message')}}
      </p>

      <div class="jumbotron" v-if="pageIsReady && notes.length === 0">
        <h1 class="display-4">{{$t('notes_index_no_notes_header')}} {{ user.name }}</h1>
        <p class="lead">{{$t('notes_index_no_notes_lead')}}</p>
        <hr class="my-4">
        <p>{{$t('notes_index_no_notes_paragraph')}}</p>
      </div>

      <div class="row mt-5">
        <div class="container">
          <div class="row justify-content-center align-items-center">
            <new-note @noteCreated="noteCreated"></new-note>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="container">
          <div class="row justify-content-start align-items-top">
            <div class="col-md-6 col-md-auto mt-2" v-for="note in notes" :key="note.id">
              <note :note="note" @noteDeleted="deleteNote($event.note)" @noteHidden="hideNote($event.note)"></note>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

  import axios from 'axios';
  import {mapGetters} from 'vuex';
  import swal from 'sweetalert2';
  import NewNote from '../../components/NewNote';
  import Note from '../../components/Note';

  const HIDDEN_NOTES_LS_KEY = 'hidden_notes';

  //todo move these to Vuex stores
  const addHiddenNote = function(note) {
    let hiddenNotes = JSON.parse(localStorage.getItem(HIDDEN_NOTES_LS_KEY)) || [];
    hiddenNotes.push(note.id);
    localStorage.setItem(HIDDEN_NOTES_LS_KEY, JSON.stringify(hiddenNotes));
  };

  const filterHiddenNotes = function(notes) {
    let hiddenNotes = JSON.parse(localStorage.getItem(HIDDEN_NOTES_LS_KEY)) || [];
    return notes.filter(n => hiddenNotes.indexOf(n.id) === -1);
  };

  export default {
    components: {Note, NewNote},
    middleware: 'auth',
    metaInfo() {
      return {title: this.$t('notes_index_meta_title')};
    },
    data: () => ({
      notes: [],
      sharedNotes: [],
      pageIsReady: false,
      blankNote: {
        title: '',
        contents: ''
      }
    }),
    computed: mapGetters({
      user: 'auth/user'
    }),
    mounted() {
      this.refresh();
    },
    methods: {
      hideNote(note) {
        swal({
          title: this.$t('notes_index_confirm_note_hide_title'),
          text: this.$t('notes_index_confirm_note_hide_message'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('yes_confirm'),
          focusCancel: true,
          reverseButtons: true
        }).then(async (result) => {
          if (result.value) {
            addHiddenNote(note);
            this.notes = filterHiddenNotes(this.notes);
          }
        });
      },

      async refresh() {
        try {
          const {data} = await axios.get('api/notes');
          this.notes = filterHiddenNotes(data);
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

      noteCreated() {
        this.refresh();
      },

      deleteNote(note) {
        swal({
          title: this.$t('notes_index_confirm_note_delete_title'),
          text: this.$t('notes_index_confirm_note_delete_message'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('yes_confirm'),
          focusCancel: true,
          reverseButtons: true
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
