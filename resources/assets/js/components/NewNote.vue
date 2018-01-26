<template>
  <div class="new-card-note-container" :class="{'col-md-8':isBeingEdited, 'col-md-6':!isBeingEdited}">
    <div class="card new-note-card" @lostFocus="saveNote(note)">
      <div class="card-body">
        <h5 class="card-title note-title" :class="{'edit-toggle-on': modified}">
          <editable :content="title"
                    :key="titleComponentKey"
                    :placeholder-text="$t('notes_create_title_placeholder')"
                    @update="noteTitleModified($event);"></editable>
        </h5>
        <p class="card-text">
          <editable :content="contents"
                    :key="contentsComponentKey"
                    :placeholder-text="$t('notes_create_contents_placeholder')"
                    @update="noteContentsModified($event);"></editable>
        </p>
        <note-share-list
          :shares="shares" @sharesUpdated="sharesUpdated($event.shares)"
          :class="{'edit-toggle-on': modified, 'edit-toggle-off':!modified}"></note-share-list>
        <div class="card-footer text-right" v-show="modified">
          <i class="fa fa-cog fa-spin fa-fw" v-show="updating"></i>
          <button class="btn btn-primary" @click="saveNote" :disabled="title ==='' && contents===''">
            <i class="fa fa-plus"></i>
            {{$t('notes_create_save_button_label')}}
          </button>
        </div>
      </div>
    </div>

    <div class="text-center">
      <small class="text-muted">{{$t('notes_create_get_started_new_note_text')}}</small>
    </div>

  </div>
</template>

<script type="text/javascript">
  import axios from 'axios';
  import NoteShareList from './NoteShareList';
  import Editable from './Editable';
  import swal from 'sweetalert2';

  export default {
    name: 'new-note',
    components: {Editable, NoteShareList},
    data() {
      return {
        //these keys are to be able to remount the child content-editable components after an update
        titleComponentKey: Math.round(Math.random() * 1000),
        contentsComponentKey: Math.round(Math.random() * 1000),
        isBeingEdited: false,
        title: '',
        contents: '',
        modified: false,
        updating: false,
        shares: []
      };
    },
    methods: {
      noteTitleModified(txt) {
        this.title = txt;
        this.isBeingEdited = true;
        this.modified = true;
      },
      sharesUpdated(shares) {
        this.shares = shares;
        this.modified = true;
      },
      noteContentsModified(txt) {
        this.contents = txt;
        this.isBeingEdited = true;
        this.modified = true;
      },
      resetNote() {
        this.title = '';
        this.contents = '';
        this.modified = false;
        this.isBeingEdited = false;
        this.titleComponentKey++;
        this.contentsComponentKey++;
      },
      async saveNote() {
        const showError = () => {
          swal({
            type: 'error',
            title: this.$t('error_alert_title'),
            text: this.$t('notes_index_save_error_message'),
            confirmButtonText: this.$t('ok'),
          });
        };

        try {
          if (this.modified) {
            this.updating = true;
            const {data} = await axios.post(`api/notes`, {
              title: this.title,
              contents: this.contents,
              shares: this.shares
            });
            this.updating = false;
            this.$emit('noteCreated');
            this.resetNote();
          }
        } catch (error) {
          showError();
        }
      }
    }
  };

</script>

<style scoped>
  .new-card-note-container {
    transition: all 0.5s ease-in-out;
  }

  .card-title.note-title {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.25s ease-in-out;
  }

  .edit-toggle-off {
    max-height: 0;
    transition: max-height 0.5s ease-in-out;
    overflow: hidden;
  }

  .edit-toggle-on, .card-title.note-title.edit-toggle-on {
    max-height: 100%;
    transition: max-height 0.5s ease-in-out;
  }

  .new-note-card {
    box-shadow: -1px 2px 4px #666;
  }
</style>
