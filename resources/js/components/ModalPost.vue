<template>
    <div class="modal fade" id="modalPost" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Post</h5>
        <button type="button" class="close" @click="close()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form>
          <!-- post name -->
          <div class="form-group" :class="{ ' has-danger' : errors.name }">
            <label class="col-form-label">Posted by:</label>
            <input type="text" :class="{ 'is-invalid' : errors.name }" class="form-control acc_format" id="name"  v-model="post.name" placeholder="Enter Name">
            <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
          </div>
          <!-- post  title -->
          <div class="form-group" :class="{ ' has-danger' : errors.title }">
            <label class="col-form-label">Title:</label>
            <input type="text" :class="{ 'is-invalid' : errors.title }" class="form-control acc_format" id="title"  v-model="post.title" placeholder="TItle">
            <div v-if="errors.title" class="invalid-feedback">{{ errors.title[0] }}</div>
          </div>
          <!-- post status -->
          <div class="form-group" :class="{ ' has-danger' : errors.detail }">
            <label class="col-form-label">Status:</label>
            <textarea class="form-control" v-model="post.detail" :class="{ 'is-invalid' : errors.detail }" id="message-text"></textarea>
            <div v-if="errors.detail" class="invalid-feedback">{{ errors.detail[0] }}</div>
          </div>
          <!-- post comp -->
          <!-- <div class="form-group" :class="{ ' has-danger' : errors.company }">
            <label class="col-form-label">Company:</label>
            <textarea class="form-control" v-model="post.company" :class="{ 'is-invalid' : errors.company }" id="message-text" placeholder="Choose Company"></textarea>
            <div v-if="errors.company" class="invalid-feedback">{{ errors.company[0] }}</div>
          </div> -->

          <div class="form-group" :class="{ ' has-danger' : errors.company }">
            <label class="col-form-label">Company:</label>
            <select  class="form-control" v-model="post.company_id"  >
              <option disabled value="" >Please Select A company</option> 
              <option v-for="(company,i) in companies" :key="i" :value="company.id">{{company.name}}</option> 
            <!-- <option value="id">LFUGOC</option>
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option> -->
            </select>
            <div v-if="errors.company" class="invalid-feedback">{{ errors.company[0] }}</div>
          </div>

          <!-- post dep -->
          <div class="form-group" >
            <label class="col-form-label">Department:</label>
            <select  class="form-control" v-model="post.department_id"  >
              <option disabled value="" >Please Select A Department</option> 
              <option v-for="(department,i) in departments" :key="i" :value="department.id">{{department.name}}</option> 
            </select>
            <div v-if="errors.department" class="invalid-feedback">{{ errors.department[0] }}</div>
          </div>

          <!-- post attach -->
          <div class="form-group">
          <label>Attachment:</label>
                  <div></div>
          <div class="custom-file">
              <input type="file" ref="attachment" @change="handleFileAttachment()" accept="image/*,application/pdf" :class="{ 'is-invalid' : errors.attachment }">
             <div v-if="errors.attachment" class="invalid-feedback">{{ errors.attachment[0] }}</div>
              </div>
              </div>
            
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="close()" data-dismiss="modal">Close</button>
        <button type="button" v-if="!isForEdit " class="btn btn-primary" @click="store()">Submit</button> 
        <button type="button" v-if="isForEdit " class="btn btn-primary" @click="update(post.id)">Update</button>
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
            post:{
              type:Object,
              default: false
            },
            isForEdit:{
                type:Boolean,
                default: false
                
            }
        },

        data(){
            return{
                errors: [],
                companies: [],
                departments: [],
            }
        },

        watch:{
            show(){
                if(this.show === true){
                    $('#modalPost')
                    .modal({
                            backdrop: "static",
                            keyboard: false
                    });
                }   else{
                      $('#modalPost')
                      .modal("hide");
                }
            }
        },
        created() {
          this.fetchCompanies();
          this.fetchDepartments();
        },

        methods:{
          fetchCompanies(){
                
                                axios.get('/api/company_data')
                                .then(response =>{
                                  console.log('response:', response.data)
                                this.companies = response.data
                                    
                                })
                        },
          fetchDepartments(){
                
                                axios.get('/api/department_data')
                                .then(response =>{
                                  console.log('response:', response.data)
                                this.departments = response.data
                                    
                                })
                        },
            close(){
                this.$emit('close', false);
                $('#modalPost').modal("hide");
                this.downloading = false
            },

            store(){
                console.log('check if store is triggered')
              // form data
                let formData = new FormData();
                let attachment = this.$refs['attachment']['files'][0];
                formData.append('attachment', attachment);
                if (this.post.name) formData.append('name', this.post.name);
                if (this.post.title) formData.append('title', this.post.title);
                if (this.post.detail) formData.append('detail', this.post.detail);
                if (this.post.company_id) formData.append('company_id', this.post.company_id);
                if (this.post.department_id) formData.append('department_id', this.post.department_id);
                axios.post(`api/posts`,
                formData, {
                headers: {
                'Content-Company': 'multipart/form-data',
                'Content-Department': 'multipart/form-data',
                }
                })

                // axios.post('api/posts', {
                //     name: this.post.name,
                //     title: this.post.title,
                //     detail: this.post.detail
                // })
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
                      axios.patch(`api/posts/${id}`, {
                      name: this.post.name,
                    title: this.post.title,
                    detail: this.post.detail,

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
        update(id){
                      axios.patch(`api/posts/${id}`, {
                    name: this.post.name,
                    title: this.post.title,
                    detail: this.post.detail,
                    

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
         handleFileAttachment(){
              this.attachment = this.$refs['attachment']['files'][0];
                },

        }
        }
    
</script>