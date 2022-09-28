<template>
    <div>
        <button @click="createModal" class="btn btn-primary btn-block">Add new task</button>

        <table class="table">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Name</th>
      <th scope="col">Body</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(task, index) in tasks" :key='task.index'>
      <th scope="row">{{ index + 1 }}</th>
      <td>{{ task.name }}</td>
      <td>{{ task.body }}</td>
      <td><button @click="updateModal(index)" class="btn btn-info">Edit</button></td>
      <td><button class="btn btn-danger">Delete</button></td>
    </tr>

  </tbody>
</table>

<!--Create modal-->
<div class="modal" id="create-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" v-if="errors.length > 0">
            <ul>
                <li v-for="(error,index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input v-model="task.name" type="text" id="name" class="form-control" />
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input v-model="task.body" type="text" id="description" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <button @click="createTask" type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--End modal-->


<!--Update modal-->

<div class="modal" id="update-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" v-if="errors.length > 0">
            <ul>
                <li v-for="(error,index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input v-model="new_update_task.name" type="text" id="name" class="form-control" />
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input v-model="new_update_task.body" type="text" id="description" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <button @click="updateTask" type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--End modal-->

    </div>
</template>

<script>
    export default {

        data(){
            return{
                task: {
                    name: '',
                    body: ''
                },
                tasks: [],
                uri: 'http://127.0.0.1:8002/tasks',
                errors: [],
                new_update_task: []
            }
        },

        methods: {

            createModal(){
               $("#create-modal").modal("show");
            },

            updateModal(index){
                this.errors = [];
                $('#update-modal').modal('show');
                this.new_update_task = this.tasks[index];
                //this.new_update_task = {...this.tasks[index]};
            },

            createTask(){
                axios.post(this.uri,
                {name: this.task.name, body: this.task.body}).
                then(response=>{
                   this.tasks.push(response.data.task);
                    $('#create-modal').modal('hide');
               }).catch(error=>{
                this.errors = [];

                   if(error.response.data.errors.name){
                    this.errors.push(error.response.data.errors.name[0])
                   }

                   if(error.response.data.errors.body){
                    this.errors.push(error.response.data.errors.body[0])
                   }
                });
            },

            updateTask(){
                //console.log(this.new_update_task.name);

                axios.patch('http://127.0.0.1:8002/tasks/' + this.new_update_task.id, {

            name: this.new_update_task.name,
            body: this.new_update_task.body,

            }).then(response =>{

            $("#update-modal").modal("hide");
           // toastr.success(response.data.message);

            }).catch(error=>{

            if(error.response.data.errors.name){
            this.errors.push(error.response.data.errors.name[0]);

            }

            if(error.response.data.errors.body){
            this.errors.push(error.response.data.errors.body[0]);

            }

});

            },

            loadTasks(){
                axios.get(this.uri).then(response=>{
                    this.tasks = response.data.tasks
                });
            }

        },

        mounted() {
            //console.log('Component mounted.')
            this.loadTasks()
        }
    }
</script>
