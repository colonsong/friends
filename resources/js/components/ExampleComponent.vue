<template>





<div class="row row-cols-1 row-cols-md-4">

  <div class="col mb-4" v-for="item in list">
    <div class="card">
      <img src="https://cdn.unwire.hk/wp-content/uploads/2014/11/0157.jpg" class="card-img-top" alt="">
      <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
      </div>
    </div>
  </div>
  <!-- distance:这是滚动的临界值。如果到滚动父元素的底部距离小于这个值，那么infiniteHandler回调函数就会-->
  <infinite-loading @distance="1" @infinite="infiniteHandler"></infinite-loading>
</div>


</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
              list: [1,2,3],
              page: 1,
            };
          },
          methods: {
            infiniteHandler($state) {
                let vm = this;

                this.$http.get('/profiles/get/?page='+this.page)
                    .then(response => {
                        return response.json();
                    }).then(data => {
                        $.each(data.data, function(key, value) {
                                vm.list.push(value);
                        });
                        $state.loaded();
                    });

                this.page = this.page + 1;
            },
          },
    }
</script>
