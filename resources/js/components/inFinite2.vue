<template>
<div id="app">
  <header class="hacker-news-header">
    <a target="_blank" href="http://www.ycombinator.com/">
      <img src="https://news.ycombinator.com/y18.gif">
    </a>
    <span>Hacker News</span>
  </header>

  <div
    class="hacker-news-item"
    v-for="(item, $index) in list"
    :key="$index"
    :data-num="$index + 1">
    <a target="_blank" :href="item.url" v-text="item.title"></a>
    <p>
      <span v-text="item.points"></span>
      points by
      <a
        target="_blank"
        :href="`https://news.ycombinator.com/user?id=${item.author}`"
        v-text="item.author"></a>
      |
      <a
        target="_blank"
        :href="`https://news.ycombinator.com/item?id=${item.objectID}`"
        v-text="`${item.num_comments} comments`"></a>
    </p>
  </div>

  <infinite-loading @infinite="infiniteHandler"></infinite-loading>
</div>

</template>
<script>
import axios from 'axios';

const api = '//hn.algolia.com/api/v1/search_by_date?tags=story';

const cors = 'https://cors-anywhere.herokuapp.com/';
export default {
  data() {
    return {
      page: 1,
      list: [],
    };
  },
  methods: {
    infiniteHandler($state) {
      axios.get(`${cors}${api}`, {
        params: {
          page: this.page,
        },
      }).then(({ data }) => {
        if (data.hits.length) {
          this.page += 1;
          this.list.push(...data.hits);
          console.log(this.list);
          $state.loaded();
        } else {
          $state.complete();
        }
      });
    },
  },
};
</script>
