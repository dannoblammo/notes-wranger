<template>
  <div contenteditable="true"
       class="editable-text"
       @input="update"
       :placeholder="placeholderText"
       @blur="lostFocus"></div>
</template>

<script>
  export default {
    name: 'editable',
    props: ['content', 'placeholderText'],
    mounted: function() {
      this.$el.innerText = this.content;
    },
    methods: {
      update: function(event) {
        this.$emit('update', event.target.innerText);
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

  .editable-text:empty:before {
    content: attr(placeholder);
    display: block;
    color: #ccc;
  }

  .editable-text:focus {
    display: block;
    box-sizing: border-box;
    color: blue;
    background: #fff;
  }

</style>
