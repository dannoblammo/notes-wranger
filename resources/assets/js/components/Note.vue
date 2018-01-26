<template>
  <div class="card note-card">
    <div class="card-body">
      <h5 class="card-title">
        <editable :content="note.title"
                  @update="note.title = $event; noteModified(note);"
                  :placeholder-text="$t('notes_create_title_placeholder')"
                  @lostFocus="saveNote(note)"></editable>
      </h5>
      <p class="card-text">
        <editable :content="note.contents"
                  @update="note.contents = $event; noteModified(note);"
                  :placeholder-text="$t('notes_create_contents_placeholder')"
                  @lostFocus="saveNote(note)"></editable>
      </p>
      <div class="card-footer text-right">
        <i class="fa fa-cog fa-spin fa-fw" v-show="note.updating"></i>
        <button class="btn btn-danger btn-sm" @click="deleteNote">
          <i class="fa fa-trash-o"></i>
          {{$t('notes_index_delete_button_label')}}
        </button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
  import axios from 'axios';
  import Editable from './Editable';
  import swal from 'sweetalert2';

  export default {
    name: 'note',
    components: {Editable},
    props: ['note'],
    data() {
      return {};
    },
    methods: {
      noteModified(note) {
        note.modified = true;
      },
      async saveNote(note) {
        const showError = () => {
          swal({
            type: 'error',
            title: this.$t('error_alert_title'),
            text: this.$t('notes_index_save_error_message'),
            confirmButtonText: this.$t('ok'),
          });
        };

        try {
          if (note.modified) {
            note.updating = true;
            const {data} = await axios.put(`api/notes/${note.id}`, note);
            note.updating = false;
            if (data.success) {
              note.modified = false;
            } else {
              showError();
            }
          }
        } catch (error) {
          showError();
        }
        this.isLoading = false;
      },
      deleteNote() {
        this.$emit('noteDeleted', {note: this.note});
      }
    },
  };

</script>

<style scoped>
  .note-card {
    box-shadow: -1px 2px 4px #666;
  }
</style>
