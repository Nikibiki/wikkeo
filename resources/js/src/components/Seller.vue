<template>
    <h1 v-if="id">Edit seller {{id}}</h1>
    <h1 v-else>Create seller</h1>

    <div class="row">
        <div class="col-auto me-auto"></div>
        <div class="col-auto"><button type="button" class="btn btn-outline-dark" @click="$router.push('/')">Back</button></div>
    </div>
    <div class="d-flex justify-content-center" v-if="loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <form v-else>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="seller.name"
                    :class="{'is-invalid': v$.seller.name.$error}"
                >
                <div v-if="v$.seller.name.$error">Name field has an error:</div>
                <div class="invalid-feedback" v-for="error in v$.seller.name.$errors">
                    {{error.$message}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label  class="form-label">Username</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="seller.username"
                    :class="{'is-invalid': usernameErrors.length}"
                    @input="clearErrors('username')"
                >
                <div v-if="usernameErrors.length">Username field has an error:</div>
                <div class="invalid-feedback" v-for="error in usernameErrors">
                    {{error}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Email address</label>
                <input
                    type="email"
                    class="form-control"
                    v-model="seller.email"
                    :disabled="id"
                    :class="{'is-invalid': v$.seller.email.$error}"
                >
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                <div v-if="v$.seller.email.$error">Email field has an error:</div>
                <div class="invalid-feedback" v-for="error in v$.seller.email.$errors">
                    {{error.$message}}
                </div>
            </div>
        </div>

        <div v-if="id" class="row">
            <div class="col-1">
                <button type="submit" class="btn btn-success" @click.prevent="save" :disabled="disable">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="updating"></span>
                    <span v-else>Update</span>
                </button>
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-danger" @click.prevent="remove" :disabled="disable">Remove</button>
            </div>
        </div>
        <div v-else class="row">
            <div class="col-1">
                <button type="submit" class="btn btn-success" @click.prevent="save" :disabled="disable">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="updating"></span>
                    <span v-else>Create</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import Service from "../services/SellersService";
import useVuelidate from '@vuelidate/core'
import { required, minLength, email } from '@vuelidate/validators'

export default {
    name: "Seller",
    props: {
        id: {
            type: Number,
            required: false,
            default: 0
        }
    },
    setup () {
        return {
            v$: useVuelidate()
        }
    },
    data() {
        return {
            seller: {
                name: '',
                username: '',
                email: ''
            },
            service: new Service(),
            loading: true,
            deleting: false,
            updating: false,
            disable: false,
            errors: {},
        };
    },
    async created() {
        if (this.id) {
            console.log(123);
            this.seller = await this.service.getItem(this.id);
        }
        this.loading = false;
    },
    methods: {
        async remove() {
            this.toggleBtns();
            this.deleting = true;
            await this.service.destroy(this.seller.id);
            this.deleting = false;
            this.$router.push('/');
        },
        async save() {
            this.toggleBtns();
            this.updating = true;
            const valid = await this.v$.$validate();

            if (!valid || Object.keys(this.errors).length) {
                this.updating = false;
                this.toggleBtns();
                return;
            }

            this.service.save(this.seller, !this.id)
            .finally(() => {
                this.toggleBtns();
                this.updating = false;
            })
            .then(res => {
                if (!this.id) {
                    const id = res.data.id
                    this.seller.id = id;
                    this.$router.push(`/sellers/${id}/edit`);
                }
            })
            .catch(reg => {
                this.errors = reg.response.data.errors;
            });
        },
        toggleBtns() {
            this.disable = !this.disable;
        },
        clearErrors(errorKey) {
            delete this.errors[errorKey];
        }
    },
    computed: {
        usernameErrors() {
            const errors = this.errors['username'] ?? [];
            return [...errors, ...this.v$.seller.username.$errors.map(error => error.$message)];
        }
    },
    validations () {
        return {
            seller: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                username: {
                    required,
                    minLength: minLength(3)
                },
                email: {
                    required,
                    email
                }
            }
        }
    }
}
</script>

<style lang="scss" scoped>
.invalid-feedback {
    display: block;
}
</style>
