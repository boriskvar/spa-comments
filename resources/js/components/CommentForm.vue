<template>
    <div v-if="isFormVisible">
      <form id="commentForm" @submit.prevent="submitComment">
        <div>
          <label for="user_name">User Name:</label>
          <input
            type="text"
            id="user_name"
            v-model="user_name"
            required
          />
        </div>

        <div>
            <label for="avatar">Avatar (optional):</label>
            <input
              type="file"
              id="avatar"
              @change="handleFileChange"
              accept="image/*"
            />
        </div>

        <div>
          <label for="email">E-mail:</label>
          <input
            type="email"
            id="email"
            v-model="email"
            required
          />
        </div>

        <div>
          <label for="home_page">Home page (optional):</label>
          <input
            type="url"
            id="home_page"
            v-model="home_page"
            placeholder="https://example.com"
          />
        </div>

        <div>
          <label for="text">Text:</label>
          <textarea
            id="text"
            v-model="text"
            required
          >
          </textarea>
        </div>

        <div>
          <label for="fileInput">Attach file (optional):</label>
          <input
            type="file"
            id="fileInput"
            @change="handleFileChange"
            accept=".jpg, .jpeg, .png, .gif, .txt"
          />
        </div>

        <div class="captcha">
          <input
            v-model="captchaInput"
            placeholder="Enter captcha"
            required
          />
        </div>

        <button type="submit">Submit</button>
      </form>
    </div>
  </template>

  <script>
  import { ref } from 'vue';

  export default {
    name: 'CommentForm',
    props: {
      comments: {
        type: Array,
        required: true,
      },
      isFormVisible: {
        type: Boolean,
        default: true,
      },
    },
    setup(props, { emit }) {
      const user_name = ref("");
      const avatar = ref("null");
      const email = ref("");
      const text = ref("");
      const home_page = ref("");
      const captchaInput = ref("");
      const fileInput = ref(null);

      const handleFileChange = (event) => {
      const file = event.target.files[0];
      if (event.target.id === 'avatar') {
        avatar.value = file;
      } else {
        fileInput.value = file;
      }
    };

    const submitComment = async () => {
      const formData = new FormData();
      formData.append('user_name', user_name.value);
      formData.append('email', email.value);
      formData.append('home_page', home_page.value);
      formData.append('text', text.value);
      formData.append('captchaInput', captchaInput.value);
      if (avatar.value) {
        formData.append('avatar', avatar.value);
      }
      if (fileInput.value) {
        formData.append('fileInput', fileInput.value);
      }

      try {
          const response = await fetch('https://spa-comments/api/comments', {
            method: 'POST',
            body: formData,
          });

          if (!response.ok) {
            throw new Error(`Error saving comment: ${response.status} ${response.statusText}`);
          }

          const savedComment = await response.json();
          emit('submitComment', savedComment);

          // Очистка полей формы после успешной отправки
          user_name.value = "";
          avatar.value = "";
          email.value = "";
          text.value = "";
          home_page.value = "";
          captchaInput.value = "";
          fileInput.value = null;
        } catch (error) {
          console.error('Error saving comment:', error);
        }
      };

      return {
        user_name,
        avatar,
        email,
        text,
        home_page,
        captchaInput,
        fileInput,
        handleFileChange,
        submitComment,
      };
    },
  };
  </script>



  <style scoped>
  .comment-tree {
    margin-left: 2em;
    border-left: 2px solid #1e90ff;
    padding-left: 1em;
  }

  .comment-item {
    margin-bottom: 1em;
  }

  .comment-header {
    display: flex;
    align-items: center;
  }

  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 1em;
  }

  .author {
    font-weight: bold;
  }

  .date-time {
    font-size: 0.85em;
    color: #888;
  }

  .reply-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.5em 1em;
    cursor: pointer;
  }

  .reply-button:hover {
    background-color: #0056b3;
  }

  .reply-form {
    margin-top: 0.5em;
  }

  .reply-form input {
    width: 100%;
    padding: 0.5em;
    border: 1px solid #ccc;
  }

  .reply-form button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.5em 1em;
    cursor: pointer;
  }

  .reply-form button:hover {
    background-color: #0056b3;
  }
  </style>
