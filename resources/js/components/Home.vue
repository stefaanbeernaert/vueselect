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
            :url="'address'"
            :searchRelations="searchRelations"
            :label="'address'"
            value-to-return="id"
            v-model="selectedAddress"
        >
        </search-infinite>

    </div>
</template>

<script>
import SearchInfinite from "./SearchInfinite";

export default {
    name: "Home",
    props:{

    },
    data: () => ({
        url: String,
        label: String,
        selectedUser: null,
        selectedAddress:null,
    }),
    components:{
        SearchInfinite
    },

    methods:{

    },

}
</script>
