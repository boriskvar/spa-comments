<template>
    <div>
      <ul>
        <li v-for="comment in comments" :key="comment.id">{{ comment.text }}</li>
      </ul>
    </div>
  </template>

  <script>
  export default {
    props: {
      comments: {
        type: Array,
        required: true
      }
    }
  };
  </script>

