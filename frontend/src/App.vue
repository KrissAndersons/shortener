<template>
  <div class="app">
    <h1>URL Shortener</h1>
    <form @submit.prevent="shortenUrl">
      <input v-model="longUrl" placeholder="https://example.com" />
      <button type="submit">Shorten</button>
    </form>

    <p v-if="shortUrl" class="success">
      Short URL: <a :href="shortUrl" target="_blank">{{ shortUrl }}</a>
    </p>
    <p v-if="error" class="error">{{ error }}</p>

    <div v-if="links.length" class="links-list">
      <h2>Created links</h2>
      <ul>
        <li v-for="link in links" :key="link.id">
          <div class="link-row">
            <a :href="link.short_url || `${apiUrl}/${link.short_code}`" target="_blank">{{ link.short_url }}</a>
            <span>{{ link.click_count }} clicks</span>
          </div>
          <small>{{ link.original_url }}</small>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const apiUrl = import.meta.env.VITE_API_BASE_URL;

const longUrl = ref('');
const shortUrl = ref('');
const error = ref('');
const links = ref([]);

async function loadLinks() {
  try {
    const response = await axios.get(`${apiUrl}/api/links`);
    links.value = response.data.map((link) => ({
      ...link,
      short_url: `${apiUrl}/${link.short_code}`,
    }));
  } catch (err) {
    console.error('Unable to load links', err);
  }
}

async function shortenUrl() {
  error.value = '';
  shortUrl.value = '';

  if (!longUrl.value.trim()) {
    error.value = 'Please enter a valid URL.';
    return;
  }

  try {
    const response = await axios.post(`${apiUrl}/api/shorten`, { url: longUrl.value });
    shortUrl.value = response.data.short_url;
    await loadLinks();
    longUrl.value = '';
  } catch (err) {
    const message = err.response?.data?.message || err.response?.data?.error || 'Unable to shorten URL';
    error.value = message;
  }
}

onMounted(() => {
  loadLinks();
});
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
.success {
  color: green;
}
.links-list {
  margin-top: 24px;
}
.links-list ul {
  list-style: none;
  padding: 0;
}
.links-list li {
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 10px 12px;
  margin-bottom: 8px;
}
.link-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}
small {
  display: block;
  color: #666;
  margin-top: 4px;
}
</style>
