<template>
    <div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Companies</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" @click="openModal()" class="btn btn-sm btn-outline-primary">Add New Company</button>
            </div> 
        </div> 
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Company Name </th>
                <th scope="col">Company ID</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="loading === false && companies" v-for="(company,i) in companies.data" :key="i">
                    <td>{{ company.name }}</td>
                    <td>{{ company.id }}</td>
                    <td>
                        <button @click="modifyCompany(company)" class="btn btn-primary">Edit</button>
                        <button @click="deleteCompany(i, company.id)" class="btn btn-danger">Delete</button>
                    </td>
                </tr>

                <tr v-if="loading === true">
                    <td colspan="2">
                        <div class="spiner-example">
                            <!-- <div class="sk-spinner sk-spinner-double-bounce">
                                <div class="sk-double-bounce1"></div>
                                <div class="sk-double-bounce2"></div>
                            </div> -->
                            <p class="text-center">
                                Please wait...[]
                            </p>
                        </div>
                    </td>
                </tr>
                 <div class="row mb-3">
                <div class="col-6">
                    <button :disabled="companies.current_page == 1" @click="prevPage()" class="btn btn-primary btn-sm btn-fill"> Previous </button>
                        <span class="text-dark">Page {{ companies.current_page  }} of {{ companies.last_page }}</span>
                    <!-- <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill"> Next </button> -->
                    <button  @click="nextPage()" :disabled="companies.current_page == companies.last_page" class="btn btn-primary btn-sm btn-fill"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span>{{ companies.total }} Total</span>
                </div>
            </div>

            <!-- modal component -->
            <modal-company
                :company="company"
                :isForEdit="isForEdit"
                @resetEdit="isForEdit = $event"
                @returnUpdate="updateCompany($event)"
                @return="pushNewCompany($event)"
                @close="show = $event"
                :show="show">
            </modal-company> ;
            </tbody>
        </table> 
    </div>
</template>

<script>
import ModalCompany from '../../components/ModalCompany.vue';
        export default{
                data(){
                return{
                        show:false,
                        loading:false,
                        companies:[],
                        search:"",
                        current_page:1,
                        last_page_url: "",
                        next_page_url: "",
                        prev_page_url: "",
                        isForEdit: false,
                        company:{},
                      }
                },

                //array []= set of values
                //object {}= can have multiple properties

                components:{
                       ModalCompany,
                },

                mounted(){
                        console.log('Component mounted.')
                        this.fetchCompanies();
                },
                
                methods:{
                        openModal(){
                                this.show = !this.show;
                        },
                        pushNewCompany(event){
                                this.companies.data.unshift(event);
                        },
                        fetchCompanies(){
                                this.loading = true;
                                axios.get('/api/companies')
                                .then(response =>{
                                        console.log('response:', response.data)
                                        this.companies = response.data
                                        this.loading=false;
                                })
                        },
                        deleteCompany(index,id){
                                axios.delete(`/api/companies/${id}`)
                                .then(response => {
                               if(response.status === 200 || response.status === 201) {
                                 this.companies.data.splice(index,1);
                                                }
                                })
                        },

                        modifyCompany(company ){
                                this.show = !this.show;
                                this.company = company;
                                this.isForEdit =true;

                        },

                        updateCompany(event){
                                        let findCompany = this.companies.data.findIndex( x => x.id === event.id);
                                        this.companies.data[findCompany] = event
                        },
                         prevPage() {
                                this.loading = true
                                axios.get(`${this.companies.prev_page_url}`)
                                .then(response => {
                                this.companies = response.data;
                                this.loading = false;
                });
            },

                          nextPage() {
                                this.loading = true
                                 axios.get(`${this.companies.next_page_url}`)
                                .then(response => {
                                console.log('check response ter: ', response)
                                this.companies = response.data
                                this.loading = false;
                });
            },

                }

        }
</script>