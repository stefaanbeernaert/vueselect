<template>
    <v-select
        :options="paginated"

        label="name"
        :filterable="false"
        :as="['label', 'name', 'id']"
        @open="onOpen"
        @close="onClose"
        @search="inputSearch"


        class="form-control"
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
import debounce from 'lodash.debounce';

export default {
    name: 'InfiniteScroll',
    data: () => ({
        observer: null,
        limit: 10,
        search: '',
        users: [],
        total: 0,
        page: 0,
    }),

    computed: {

        filtered() {

            return this.users.filter((user) => user.name.includes(this.search))
        },
        paginated() {
            return this.filtered.slice(0, this.limit)
        },
        hasNextPage() {
            return this.paginated.length < this.total
        },
    },
    mounted() {
        this.observer = new IntersectionObserver(this.infiniteScroll)
    },
    created() {
        this.getUsers();
    },
    methods: {


        getUsers(loading, search) {

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
                    loading(false);
                })
                .catch()
                .then(() => {

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
        inputSearch(search, loading) {
            if (search.length) {
                loading(true);
                this.page = 0
                this.users = []
                this.getUsers(loading, search)
            }
        },


    },

}

</script>
<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
