<template>
    <div>
        <search-infinite

            class="my-2"
            :url="'users'"
            :label="'name'"
            v-model="selectedUser"
        >
        </search-infinite>

        <search-infinite
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
    props:{

    },
    data: () => ({
        selected: 0,
        url: String,
        label: String,
        selectedUser:0,
        selectedAddress:Number,
    }),
    components:{
        SearchInfinite
    },
    methods:{

    }
}
</script>
