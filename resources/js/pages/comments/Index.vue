<template>
    <div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Comments</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" @click="openModal()" class="btn btn-sm btn-outline-primary">Add New Comment</button>
            </div> 
        </div> 
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Comment </th>
                <th scope="col">Comment ID</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="loading === false && comments" v-for="(comment,i) in comments.data" :key="i">
                    <td>{{ comment.comment }}</td>
                    <td>{{ comment.id }}</td>
                    <td>
                        <button @click="modifyComment(comment)" class="btn btn-primary">Edit</button>
                        <button @click="deleteComment(i, comment.id)" class="btn btn-danger">Delete</button>
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
                    <button :disabled="comments.current_page == 1" @click="prevPage()" class="btn btn-primary btn-sm btn-fill"> Previous </button>
                        <span class="text-dark">Page {{ comments.current_page  }} of {{ comments.last_page }}</span>
                    <!-- <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill"> Next </button> -->
                    <button  @click="nextPage()" :disabled="comments.current_page == comments.last_page" class="btn btn-primary btn-sm btn-fill"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span>{{ comments.total }} Total</span>
                </div>
            </div>

            <!-- modal component -->
            <modal-comment
                :comment="comment"
                :isForEdit="isForEdit"
                @resetEdit="isForEdit = $event"
                @returnUpdate="updateComment($event)"
                @return="pushNewComment($event)"
                @close="show = $event"
                :show="show">
            </modal-comment> ;
            </tbody>
        </table> 
    </div>
</template>

<script>
import ModalComment from '../../components/ModalComment.vue';
        export default{
                data(){
                return{
                        show:false,
                        loading:false,
                        comments:[],
                        search:"",
                        current_page:1,
                        last_page_url: "",
                        next_page_url: "",
                        prev_page_url: "",
                        isForEdit: false,
                        comment:{},
                      }
                },

                //array []= set of values
                //object {}= can have multiple properties

                components:{
                       ModalComment,
                },

                mounted(){
                        console.log('Component mounted.')
                        this.fetchComments();
                },
                
                methods:{
                        openModal(){
                                this.show = !this.show;
                        },
                        pushNewComment(event){
                                this.comments.data.unshift(event);
                        },
                        fetchComments(){
                                this.loading = true;
                                axios.get('/api/comments')
                                .then(response =>{
                                        console.log('response:', response.data)
                                        this.comments = response.data
                                        this.loading=false;
                                })
                        },
                        deleteComment(index,id){
                                axios.delete(`/api/comments/${id}`)
                                .then(response => {
                               if(response.status === 200 || response.status === 201) {
                                 this.comments.data.splice(index,1);
                                                }
                                })
                        },

                        modiftComment(comment ){
                                this.show = !this.show;
                                this.comment = comment;
                                this.isForEdit =true;

                        },

                        updateComment(event){
                                        let findComment = this.comments.data.findIndex( x => x.id === event.id);
                                        this.comments.data[findComment] = event
                        },
                         prevPage() {
                                this.loading = true
                                axios.get(`${this.comments.prev_page_url}`)
                                .then(response => {
                                this.comments = response.data;
                                this.loading = false;
                });
            },

                          nextPage() {
                                this.loading = true
                                 axios.get(`${this.comments.next_page_url}`)
                                .then(response => {
                                console.log('check response ter: ', response)
                                this.comments = response.data
                                this.loading = false;
                });
            },

                }

        }
</script>