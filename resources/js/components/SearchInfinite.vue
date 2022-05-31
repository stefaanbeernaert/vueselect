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
    emits:['input'],
    props:{
        url: String,
        label: String,
        valueToReturn: String,
        selectedUser: null,
        selectedAddress:null,
        searchParams:[],

    },
    data: () => ({
        userId : 0,
        addressId: 0,
        observer: null,
        limit: 10,
        search: '',
        list: [],
        total: 0,
        page: 0,
        loading: false,
    }),
    watch:{

        selectedUser: function(value){
            if (value !== 0){
                this.userId = value
                this.list = []
                this.loading = true
                this.page = 0
                this.limit += 10
                this.getData()
            }

        },
        selectedAddress: function (value){
           // console.log(value)
            if (value !== 0){
                this.addressId = value

            }

        }
    },
    computed: {

        hasNextPage() {

            return this.list.length < this.total

        },
    },
    mounted() {
      this.observer = new IntersectionObserver(this.infiniteScroll)

    },
    created() {
        this.selected();
       // this.getData();
    },
    methods: {
        getData(search) {

            this.page++;
            axios
                .get(this.url, {

                    params: {
                        search: search,
                        page: this.page,
                      //  userId: 1612,
                        userId: this.userId,
                        addressId: this.addressId,
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
                this.getData();
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
                this.getData(search, loading)
                await this.$nextTick()

            }
        }, 500),
        selected(value){
            if( value && this.valueToReturn ){
                value = value[this.valueToReturn];

            }
            this.$emit('input', value);



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
