<template>
  <ais-instant-search :search-client="searchClient" index-name="threads">
    <div class="row">
      <div class="col-md-8">
        <ais-hits>
          <div slot="item" slot-scope="{ item }">
              {{item.title}}
          </div>
        </ais-hits>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Search
          </div>
          <div class="card-body">
          <ais-search-box :value="search" autofocus>
            
          </ais-search-box>
          </div>
        </div>
        

            <div class="card" v-if="trending">
                <div class="card-header">
                    Trending Threads
                </div>
                <div class="card-body">
                    <ul class="list-group">
                    
                        <li class="list-group-item" v-for="thread in trending">
                            <a href="" v-text="thread.title">
                                
                            </a>
                        </li>
                   
                    </ul>
            </div>
          </div>
      </div>
    </div>
  </ais-instant-search>
</template>

<script>
import algoliasearch from 'algoliasearch';

export default {
  props: ['query','trending'],
  data() {
    return {
      search: this.query,
      searchClient: algoliasearch(
        'E4SGXXVT9B',
        'de4d3829ac26f12b24538c34186e074d'
      ),
    };
  },
  mounted(){
    // console.log(this.query);
  }
};
</script>

<style>
body {
  font-family: sans-serif;
  padding: 1em;
}
</style>