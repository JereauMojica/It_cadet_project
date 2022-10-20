
<template>

<div class="modal fade" id="modalComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comment</h5>
        <button type="button" class="close" @click="close()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <!-- prod name -->
        <div class="form-group" :class="{ ' has-danger' : errors.comment }">
            <label class="col-form-label">Add Comment:</label>
            <textarea class="form-control" v-model="post.comment" :class="{ 'is-invalid' : errors.comment }" id="message-text"></textarea>
            <div v-if="errors.comment" class="invalid-feedback">{{ errors.comment[0] }}</div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
               <button type="button" class="btn btn-secondary" @click="close()" data-dismiss="modal">Close</button>
        <button type="button" v-if="!isForEdit " class="btn btn-primary" @click="store()">Submit</button> 
        <button type="button" v-if="isForEdit " class="btn btn-primary" @click="update(comment.id)">Update</button>
      </div>
    </div>
  </div>
</div> 
    
</template>

<script>


export default{

        props:{
            show:{
                type:Boolean,
                default: false
                
            },
            comment:{
              type:Object,
          
            },
            isForEdit:{
                type:Boolean,
                default: false
                
            }
        },

        data(){
            return{
                errors: [],
                
            }
        },

        watch:{
            show(){
                if(this.show === true){
                    $('#modalComment')
                    .modal({
                            backdrop: "static",
                            keyboard: false
                    });
                }   else{
                      $('#modalComment')
                      .modal("hide");
                }
            }
        },

        methods:{
            close(){
                this.$emit('close', false);
                $('#modalComment').modal("hide");
                this.downloading = false
            },
        
            store(){
                console.log('check if store is triggered')
                
// form data
// let formData = new FormData();
// let attachment = this.$refs['attachment']['files'][0];
// formData.append('attachment', attachment);
// if (this.product.name) formData.append('name', this.product.name);
// if (this.product.detail) formData.append('detail', this.product.detail);
// if (this.product.type) formData.append('type', this.product.type);

// axios.post(`api/products`,
// formData, {
// headers: {
// 'Content-Type': 'multipart/form-data'
// }
// })




                axios.post('api/comments', {
                    name: this.comment.name,
                  
                })
                .then(response =>{
                    if(response.status === 200 ||response.status === 201){
                        this.close();
                        this.$emit('return', response.data)
                    }

                })
                .catch(error =>{

                    if(error.response.status === 422){
                        this.errors = error.response.data.errors;
                    }
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
       
        }



}


</script>
