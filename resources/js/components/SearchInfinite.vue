<template>
    <v-select

        :options="list"

        :label="label"
        :filterable="false"
        @open="onOpen"
        @close="onClose"
        @search="inputSearch"
        @option="selected"
        class="form-control"
        :loading="loading"
    >

        <template #list-footer>
            <li v-show="hasNextPage" ref="load" class="loader">
                Loading more options...
            </li>
        </template>
    </v-select>
</template>
<script>
import 'vue-select/dist/vue-select.css';
import _ from "lodash";
export default {

    name: 'Search-Infinite',
    props:{
        url: String,
        label: String,
       selected:Number,

    },
    data: () => ({
        observer: null,
        limit: 10,
        search: '',
        list: [],
        total: 0,
        page: 0,
        loading: false,

    }),
    computed: {

        hasNextPage() {

            return this.list.length < this.total

        },
    },
    mounted() {
      this.observer = new IntersectionObserver(this.infiniteScroll)
    },
    created() {

        this.getUsers();
    },
    methods: {
        getUsers(search) {

            this.page++;
            axios
                .get(this.url, {

                    params: {
                        search: search,
                        page: this.page,
                    }
                })
                .then((response) => {

                   this.list = this.list.concat(response.data.data);
                    this.total = response.data.total;

                })
                .catch()
                .then(() => {
                    this.loading = false;
                })
        },
        async onOpen() {

               await this.$nextTick()
                this.observer.observe(this.$refs.load)

        },
        onClose() {
          this.observer.disconnect()
        },
        async infiniteScroll([{isIntersecting, target}]) {
            if (isIntersecting) {

                const ul = target.offsetParent
                const scrollTop = target.offsetParent.scrollTop
                this.limit += 10
                this.getUsers();
                await this.$nextTick()
               ul.scrollTop = scrollTop
            }
        },
        inputSearch: _.debounce(   async function (search, loading) {
            if (search.length) {
                this.list = []
                this.loading = true
                this.page = 0
                this.limit += 10
                this.getUsers(search, loading)
                await this.$nextTick()

            }
        }, 500),
    },
    selected(list){
       //this.$emit('input',3)
        this.$emit("option:selected", selectedOption);
    }
}
</script>
<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
