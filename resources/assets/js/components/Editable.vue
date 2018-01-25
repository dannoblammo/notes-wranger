<template>
  <div contenteditable="true" class="editable-text" @input="update" @blur="lostFocus"></div>
</template>

<script>
  export default {
    name: 'editable',
    props: ['content'],
    mounted: function() {
      this.$el.innerText = this.content;
    },
    methods: {
      update: function(event) {
        this.$emit('update', event.target.innerText.trim());
      },
      lostFocus: function(event) {
        this.$emit('lostFocus');
      },
    }
  };
</script>
<style scoped>
  .editable-text {
    cursor: text;
  }

  .editable-text:focus {
    display: block;
    box-sizing: border-box;
    color: blue;
    background: #fff;
  }

</style>
