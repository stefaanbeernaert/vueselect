<template>
    <v-select
        :options="paginated"
        label="name"
        :filterable="false"
        :as="['label', 'name', 'id']"
        @open="onOpen"
        @close="onClose"
        @search="(search, loading) => {
      loading(true)
      fetchOptions(search).then(() => loading(false))
    }"

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


    export default {
        name: 'InfiniteScroll',
        data: () => ({
            observer: null,
            limit: 10,
            search: '',
            users: [],
            total: 0,
            page:0,
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


            getUsers(){

              /*  let page = (this.paginated.length/this.limit)+1;*/
                this.page++;
                this.$emit('search', this.search, this.toggleLoading);
                axios
                .get('test2',{
                    params: {
                        search: this.search,
                        page: this.page,
                    }
                })

                .then((response) => {
                   //console.log(response);
                    this.users = this.users.concat(response.data.data);
                  //  console.log(this.users);
                    this.total = response.data.total;
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
            async infiniteScroll([{ isIntersecting, target }]) {
                if (isIntersecting) {
                    const ul = target.offsetParent
                    const scrollTop = target.offsetParent.scrollTop
                    this.limit += 10
                    this.getUsers();
                    await this.$nextTick()
                    ul.scrollTop = scrollTop
                }
            },
            fetchOptions(search,loading){
              this.search = search;
              this.getUsers();

            }
        },

    }

</script>
<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
