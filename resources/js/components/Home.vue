<template>
    <div>
        <search-infinite
            :search-params="{userId: selectedUser,addressId: selectedAddress}"
            :selected-user="selectedUser"
            :selected-address="selectedAddress"
            class="my-2"
            :url="'users'"
            :label="'name'"
            value-to-return="id"
            v-model="selectedUser"

        >
        </search-infinite>

        <search-infinite
            :search-params="{userId: selectedUser}"
            :selected-address="selectedAddress"
            :selected-user="selectedUser"
            :url="'address'"
            :label="'address'"
            v-model="selectedAddress"
            :value-id="valueId"
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
        valueId: 0,

    }),
    watch:{
        selectedAddress: function (){

                this.valueId = this.selectedAddress.user_id


          //  console.log(this.valueId)

        }
    },
    components: {
        SearchInfinite
    },
    methods: {
    },
}
</script>
