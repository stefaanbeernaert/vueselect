<template>
    <div>
        <search-infinite
            class="my-2"
            :url="'users'"
            :label="'name'"
            value-to-return="id"
            v-model="selectedUser"
        >
        </search-infinite>

        <search-infinite
            :search-params="{userId: selectedUser}"
            :url="'address'"
            :label="'address'"
            v-model="selectedAddress"
        >
        </search-infinite>
    </div>
</template>

<script>
import SearchInfinite from "./SearchInfinite";
export default {
    name: "Home",
    props: {

    },
    data: () => ({
        url: String,
        label: String,
        selectedUser: null,
        selectedAddress: null,

    }),
    watch:{
        selectedAddress: function (){
            if( this.selectedAddress ){
                this.selectedUser = this.selectedAddress.user_id
            }
        }
    },
    components: {
        SearchInfinite
    },
    methods: {
    },
}
</script>
