<template>
    <v-select
        :options="list"
        :label="label"
        :filterable="false"
        @open="onOpen"
        @close="onClose"
        @search="inputSearch"
        class="form-control"
        :loading="loading"
        @input="selected"
        :value="this.item"
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
    emits: ['input'],
    props: {
        url: String,
        label: String,
        valueToReturn: String,
        searchParams: {},
        value: null,
    },
    data: () => ({
        observer: null,
        limit: 10,
        search: '',
        list: [],
        total: 0,
        page: 0,
        loading: false,
        item: {},

    }),
    watch: {
        searchParams: function () {
            this.list = []
            this.loading = true
            this.page = 0
            this.item = null
            this.getData()
        },
        value: function (e, old) {
            if (this.value > 0 && e !== old) {
                this.getItem()
            }
            else{
                this.item = e;
            }
        },


    }
    ,
    computed: {
        hasNextPage() {
            return this.list.length < this.total
        }
        ,
    }
    ,
    mounted() {
        this.observer = new IntersectionObserver(this.infiniteScroll)
    }
    ,
    created() {

        this.selected();
    }
    ,
    methods: {

        getItem() {
            axios
                .get('/getItem', {
                    params: {
                        id: this.value,

                    }
                })
                .then((response) => {

                    this.item = {
                        name : response.data.name,
                        id : response.data.id
                    }

                })
                .catch()

        }
        ,
        getData(search) {
            this.page++;

            axios
                .get(this.url, {
                    params: {
                        search: search,
                        page: this.page,
                        ...this.searchParams,
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
        }
        ,
        async onOpen() {
            this.getData();
            await this.$nextTick()
            this.observer.observe(this.$refs.load)
        }
        ,
        onClose() {
            this.observer.disconnect()
        }
        ,
        async infiniteScroll([{isIntersecting, target}]) {
            if (isIntersecting) {
                const ul = target.offsetParent
                const scrollTop = target.offsetParent.scrollTop
                this.limit += 10
                this.getData();
                await this.$nextTick()
                ul.scrollTop = scrollTop
            }
        }
        ,
        inputSearch: _.debounce(async function (search, loading) {
            if (search.length) {

                this.list = []
                this.loading = true
                this.page = 0
                this.limit += 10
                this.getData(search, loading)
                await this.$nextTick()
            }
        }, 500),
        selected(value) {
            if (value && this.valueToReturn) {
                value = value[this.valueToReturn];
            }
            this.$emit('input', value);
        }
        ,
    }
    ,

}
</script>
<style scoped>
.loader {
    text-align: center;
    color: #bbbbbb;
}
</style>
