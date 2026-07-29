<template>
  <div class="app">
    <h1>URL Shortener</h1>
    <form @submit.prevent="shortenUrl">
      <input v-model="longUrl" placeholder="https://example.com" />
      <button type="submit">Shorten</button>
    </form>
    <p v-if="shortUrl">Short URL: <a :href="shortUrl" target="_blank">{{ shortUrl }}</a></p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const longUrl = ref('');
const shortUrl = ref('');
const error = ref('');

async function shortenUrl() {
  error.value = '';
  shortUrl.value = '';
  try {
    const response = await axios.post('http://localhost:8080/api/shorten', { url: longUrl.value });
    shortUrl.value = response.data.short_url;
  } catch (err) {
    error.value = 'Unable to shorten URL';
  }
}
</script>

<style scoped>
.app {
  max-width: 700px;
  margin: 80px auto;
  font-family: Arial, sans-serif;
}
input {
  width: 70%;
  padding: 10px;
  margin-right: 8px;
}
button {
  padding: 10px 14px;
}
.error {
  color: red;
}
</style>
