<template>
    <v-select
        :options="users"
        label="name"
        :filterable="false"
        @open="onOpen"
        @close="onClose"
        @search="inputSearch"
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

    name: 'InfiniteScroll',
    data: () => ({
        observer: null,
        limit: 10,
        search: '',
        users: [],
        total: 0,
        page: 0,
        loading: false,
    }),
    computed: {

      /*  filtered() {
            return this.users.filter((user) => user.name.includes(this.search))
        },*/
   /*     paginated() {
            return this.filtered.slice(0, this.limit)
        },*/

        hasNextPage() {

            return this.users.length < this.total
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
                .get('test2', {
                    params: {
                        search: search,
                        page: this.page,
                    }
                })

                .then((response) => {

                    this.users = this.users.concat(response.data.data);
                    this.total = response.data.total;

                })
                .catch()
                .then(() => {
                    this.loading = false;
                })
        },
        async onOpen() {
            if (this.hasNextPage) {
                await this.$nextTick()
                this.observer.observe(this.$refs.load)
            }
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
        inputSearch: _.debounce(function (search, loading) {
            if (search.length) {
                this.loading = true
                this.page = 0
                this.users = []
                this.getUsers(search, loading)

            }
        }, 500),
    },
}
</script>
<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
