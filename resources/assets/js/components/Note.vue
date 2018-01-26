<template>
  <div class="card note-card" :class="{'border-light':note.is_shared}">
    <div class="card-body">
      <h5 class="card-title position-relative">
        <editable :content="note.title"
                  @update="note.title = $event; noteModified(note);"
                  :placeholder-text="$t('notes_create_title_placeholder')"
                  @lostFocus="saveNote(note)"></editable>
        <i class="fa fa-cog fa-spin fa-fw position-absolute loading-indicator" v-show="isUpdating"></i>
      </h5>
      <p class="card-text">
        <editable :content="note.contents"
                  @update="note.contents = $event; noteModified(note);"
                  :placeholder-text="$t('notes_create_contents_placeholder')"
                  @lostFocus="saveNote(note)"></editable>
      </p>
      <note-share-list v-if="!note.is_shared"
                       :shares="note.shares"
                       @sharesUpdated="sharesUpdated(note, $event.shares)"></note-share-list>
      <div class="card-footer text-right" v-if="!note.is_shared">
        <note-tool-controls :shares="note.shares" :visible="toolsVisible"></note-tool-controls>

        <button class="btn btn-sm" :class="{'dropdown-toggle':toolsVisible}" @click="toggleTools">
          <!-- {{$t('notes_index_tools_button_label')}} -->
          <i class="fa fa-user-plus"></i>
        </button>
        <button class="btn btn-danger btn-sm" @click="deleteNote" v-if="!note.is_shared">
          <i class="fa fa-trash-o"></i>
          {{$t('notes_index_delete_button_label')}}
        </button>
      </div>
      <div class="card-footer text-muted text-right" v-if="note.is_shared">
        <small class="text-muted">{{$t('notes_index_shared_by_label')}}
          <span :title="note.user.email">{{note.user.name}}</span>
        </small>
        <button class="btn btn-warning btn-sm" @click="hideNote">
          <i class="fa fa-trash-o"></i>
          {{$t('notes_index_hide_button_label')}}
        </button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
  import axios from 'axios';
  import Editable from './Editable';
  import NoteShareList from './NoteShareList';
  import NoteToolControls from './NoteToolControls';
  import swal from 'sweetalert2';

  export default {
    name: 'note',
    components: {Editable, NoteShareList, NoteToolControls},
    props: ['note'],
    data() {
      return {
        isHidden: false,
        isModified: false,
        isUpdating: false,
        toolsVisible: false
      };
    },
    methods: {
      noteModified(note) {
        this.isModified = true;
      },

      toggleTools() {
        this.toolsVisible = !this.toolsVisible;
      },

      sharesUpdated(note, shares) {
        note.shares = shares;
        this.isModified = true;
        this.saveNote(note);
      },

      hideNote() {
        this.$emit('noteHidden', {note: this.note});
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
          if (this.isModified) {
            this.isUpdating = true;
            const {data} = await axios.put(`api/notes/${note.id}`, note);
            this.isUpdating = false;
            this.isModified = false;
          }
        } catch (error) {
          this.isUpdating = false;
          showError();
        }
      },

      deleteNote() {
        this.$emit('noteDeleted', {note: this.note});
      }
    },
  };

</script>

<style scoped>
  .loading-indicator {
    top: -10px;
    right: -10px;
  }
  .note-card {
    box-shadow: -1px 2px 4px #666;
  }
</style>
