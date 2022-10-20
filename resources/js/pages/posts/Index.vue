<template>
    <div>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Post</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" @click="openModal()" class="btn btn-sm btn-outline-primary">Create New Posts</button>
            </div> 
        </div> 

        <table class="table">
            <thead>
                <tr>
                <th scope="col">Post Made by</th>
                <th scope="col">Title</th>
                <th scope="col">Company</th>
                <th scope="col">Department</th>
                <th scope="col">Date Posted</th>
                <th scope="col"> Total Post:</th>
                <th>{{ posts.total }}</th>  
                </tr>
            </thead>
            <tbody>
                <!-- ;every post setting; -->
                <tr v-if="loading === false && posts" v-for="(post,i) in posts.data" :key="i">
                    <!-- <td><a :href="'/storage/' + post.attachment.path" target="_blank">{{ post.name }}</a></td> -->
                   <td> {{ post.name }}
                    <br></br>
                    <br></br>
                    <a v-if="post.attachment" :href="'/storage/' + post.attachment.path" target="_blank">View Attachment</a>
                    <br></br>
                    <br></br>
                    View Comment:{{ post.comment ? post.comment.comment : ''  }}
                    </td>
                    <td>{{ post.title }}
                    </td>
                    <td>{{ post.company_info ? post.company_info.name : ''  }}
                    <br></br>
                    <br></br>
                     <p><u><b scope="col">Status:</b></u></p>  
                    {{ post.detail }}
                    </td>
                     <td>{{ post.department_info ? post.department_info.name : ''}}
                    </td>
                     <td>{{ post.created_at }}
                    </td>
                    <td>
                    <p><b scope="col">Option:</b></p> 
                    </td>
                    <td>
                    <button @click="modifyPost(post)" class="btn btn-primary">Edit</button>
                    <button @click="deletePost(i, post.id)" class="btn btn-danger">Delete</button>
                    <!-- <button @click="openModal_comment()" class="btn btn-primary">Comment</button> -->
                    <!-- Button trigger modal -->
                    <button @click="selectPost(post)" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                     Comment
                    </button>

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
                                Please wait...
                            </p>
                        </div>
                    </td>
                </tr>
                    <!-- <br></br>
                    <br></br> -->
                
                   <div class="row mb-3">
                        <!-- next and prev btn  -->
                <div class="col-6">
                    <button :disabled="posts.current_page == 1" @click="prevPage()" class="btn btn-primary btn-sm btn-fill"> Previous </button>
                        <span class="text-dark">Page {{ posts.current_page  }} of {{ posts.last_page }}</span>
                    <!-- <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill"> Next </button> -->
                    <button  @click="nextPage()" :disabled="posts.current_page == posts.last_page" class="btn btn-primary btn-sm btn-fill"> Next </button>
                
                </div>
                <div class="col-6 text-right">
                    
                </div>
            </div>

            <!-- modal component -->
            <modal-post 
                :post="post"
                :isForEdit="isForEdit"
                @resetEdit="isForEdit = $event"
                @returnUpdate="updatePost($event)"
                @return="pushNewPost($event)"
                @close="show = $event"
                :show="show">
            </modal-post> ;

            </tbody>
        </table> 
     
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea v-model="comment"  rows="4" cols="50">
A          
        </textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button @click="storecomment" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
   </div>
</template>

<script>
 import ModalPost from '../../components/ModalPost.vue';

       
       
export default{
    
        data() {
            return {
                comment: '',
                show: false,
                show_comment: false,
                loading: false,
                posts: [],
                // comments:[],
                search: "",
                current_page: 1,
                last_page_url: "",
                next_page_url: "",
                prev_page_url: "",
                isForEdit: false,
                post:{},
                // comment:{};
            }
        },

        components:{
            ModalPost,
     
        },
        
        mounted() {
            console.log('Component mounted.')
            this.fetchPosts();
        },

        methods: {

            fetchPosts(){
                                this.loading = true;
                                axios.get('/api/posts')
                                .then(response =>{
                                        console.log('response:', response.data)
                                        this.posts = response.data
                                        this.loading=false;
                                })
                        },
            selectPost(post){
                this.post = post;

            },
            storecomment(){
                axios.post('api/comments', {
                    comment: this.comment,
                  
                })
             
            },
            update(id){
                axios.patch(`api/comments/${id}`, {
                    name: this.comment.name,
                })
                .then(response => {
                    if(response.status === 200 || response.status === 201) {
                        this.close();
                        this.$emit('returnUpdate', response.data)
                        this.$emit('resetEdit', false)
                        // this.$toastr('success','Created Successfully');
                    }
                })
                .catch(error => {
                    // this.$toastr('warning', `Error found`)
                    if(error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                })  
            },
            openModal() {
                this.show = !this.show;
            },
            openModal_comment() {
                this.show_comment = !this.show_comment;
            },
            pushNewPost(event) {
                this.posts.data.unshift(event);
            },
            deletePost(index,id){
                                axios.delete(`/api/posts/${id}`)
                                .then(response => {
                               if(response.status === 200 || response.status === 201) {
                                 this.posts.data.splice(index,1);
                                                }
                                })
            },

            modifyPost(post) {
                this.show = !this.show;
                this.post = post;
                this.isForEdit = true;
            },

            addComment(comment) {
                this.show_comment = !this.show_comment;
                this.comment = comment;
                this.isForComment = true;
            },
            updatePost(event) {

                let findPost = this.posts.data.findIndex(x => x.id === event.id);
                this.posts.data[findPost] = event;
                
            },
            prevPage() {
                this.loading = true
                axios.get(`${this.posts.prev_page_url}`)
                .then(response => {
                    this.posts = response.data;
                    this.loading = false;
                });
            },

            nextPage() {
                this.loading = true
                axios.get(`${this.posts.next_page_url}`)
                .then(response => {
                    console.log('check response ter: ', response)
                    this.posts = response.data
                    this.loading = false;
                });
            },
        } 
    }




        
</script>