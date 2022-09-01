<template>
    <h1>Sellers page</h1>
    <div class="row">
        <div class="col-auto me-auto"></div>
        <div class="col-auto"><button type="button" class="btn btn-secondary" @click="generate">Generate</button></div>
        <div class="col-auto"><button type="button" class="btn btn-primary" @click="$router.push('/create')">Create</button></div>
    </div>
    <div class="d-flex justify-content-center" v-if="loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <table class="table" v-else>
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(seller, key) in sellers" :key="key">
            <th scope="row">{{ seller.id }}</th>
            <td>{{ seller.name }}</td>
            <td>{{ seller.username }}</td>
            <td>{{ seller.email }}</td>
            <td><router-link :to="'/sellers/' + seller.id + '/edit'">Edit</router-link></td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import Service from "../../services/SellersService";
import Seller from "../Seller";

export default {
    name: "Home",
    components: {
        Seller
    },
    data() {
        return {
            sellers: [],
            service: new Service(),
            loading: true,
        }
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        generate() {
            this.loading = true;
            axios.get('/api/v1/generate').then(() => {
                this.fetchData();
            });
        },
        async fetchData() {
            this.sellers = await this.service.getAll();
            this.loading = false;
        }
    }
}
</script>

<style scoped>

</style>
