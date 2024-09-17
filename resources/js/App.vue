<template>
    <div id="app">
      <h1>Comments</h1>
      <div class="comments">
        <Comment v-for="comment in comments" :key="comment.id" v-bind="comment" />
      </div>
    </div>
</template>

<script>
// import commentsData from './data/comments.js'; //ИМПОРТИРУЕМ КОММЕНТАРИИ(commentsData) из папки(data) и файла(comments.js)

import Comment from './components/Comment.vue';

  export default {
    name: 'App',
    components: {
      Comment,
    },
    data() {
      return {
        comments: [],
      };
    },
    mounted() {
      this.fetchComments();
    },

    methods: {
    async fetchComments() {
      try {
        const response = await fetch('https://spa-comments/api/comments'); // Используем полный URL
        if (!response.ok) {
          throw new Error(`Error fetching comments: ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        console.log(data); // Проверяем данные в консоли
        this.comments = this.transformComments(data); // Обрабатываем данные напрямую
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    },
    transformComments(data) {
      if (!Array.isArray(data)) {
        console.error('Data is not an array:', data);
        return [];
      }
      return data.map(comment => ({
        id: comment.id,
        author: comment.user_name,
        email: comment.email,
        homePage: comment.home_page,
        captcha: comment.captcha,
        body: comment.text,
        timestamp: comment.created_at, // Предполагаем, что у вас есть поле created_at
        replies: comment.replies ? this.transformComments(comment.replies) : [], // Рекурсивно обрабатываем вложенные комментарии, если они есть
      }));
    },
  }

  };
  </script>



    <style lang="css">
    html {
      box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
      box-sizing: inherit;
    }

    body {
      font-family: sans-serif;
    }
    </style>
