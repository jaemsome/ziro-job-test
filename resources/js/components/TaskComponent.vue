<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Task Lists</h3>
                        <button @click="initAddTask()" class="btn btn-success" style="padding:5px">
                            New Task
                        </button>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="tasks.length > 0">
                           <tbody>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th width="20%">Name</th>
                                <th width="15%">Status</th>
                                <th width="15%">Archived</th>
                                <th width="15%">Owners</th>
                                <th width="15%">Created</th>
                                <th width="15%">Updated</th>
                                <th width="15%">Action</th>
                            </tr>
                            <tr v-for="(task, index) in tasks">
                                <!-- <td>{{ task.id }}</td> -->
                                <td>{{ task.name }}</td>
                                <td>{{ task.status }}</td>
                                <td>{{ task.archived }}</td>
                                <td>{{ task.owner.name }}</td>
                                <td>{{ task.created_at }}</td>
                                <td>{{ task.updated_at }}</td>
                                <td>
                                    <button @click="initUpdateTask(index)" class="btn btn-success btn-sm" style="padding:5px 12px"><span class="glyphicon glyphicon-edit"></span> Edit</button>
                                    <button @click="deleteTask(index)" class="btn btn-danger btn-sm" style="margin-top:5px; padding:5px;"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                </td>
                            </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create Task -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Task</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors[0]">{{ error[0] }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="names">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Task Name" class="form-control" v-model="task.name" ref="" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control" v-model="task.status" required>
                                <option value="" selected="true">Please select</option>
                                <option v-for="status in statuses" :value="status.id">{{ status.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Archived:</label>
                            <select id="status" name="status" class="form-control" v-model="task.archived" required>
                                <option value="" selected="true">Please select</option>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="createTask" class="btn btn-primary">Create Task</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- Edit Task -->
        <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Task</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors[0]">{{ error[0] }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="names">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Task Name" class="form-control" v-bind:value="this.selected_task.name" ref="editTaskName" :required="true">
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control" ref="editTaskStatus" :required="true">
                                <option value="">Please select</option>
                                <option v-for="status in statuses" :value="status.id" :selected="status.name === selected_task.status">{{ status.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Archived:</label>
                            <select id="archived" name="archived" class="form-control" ref="editTaskArchived" :required="true">
                                <option value="">Please select</option>
                                <option value="0" v-if="this.selected_task.archived === 'No'" selected>No</option>
                                <option value="0" v-else>No</option>
                                <option value="1" v-if="this.selected_task.archived === 'Yes'" selected>Yes</option>
                                <option value="1" v-else>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="updateTask" class="btn btn-primary">Update Task</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                task: {
                    id: '',
                    name: '',
                    status: '',
                    archived: '',
                    owner: '',
                    created_at: '',
                    updated_at: ''
                },
                status: {
                    id: '',
                    name: '',
                    created_at: '',
                    updated_at: ''
                },
                tasks: [],
                statuses: [],
                msg: '',
                errors: [],
                selected_index: [],
                selected_task: []
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getTasks();
        },
        computed: {
            
        },
        methods: {
            getTasks() {
                axios.get('/tasks').then(response => {
                    this.tasks = response.data.tasks;
                    // console.log( this.tasks );
                });
            },
            initAddTask() {
                this.statuses = [];
                this.errors = [];
                axios.get('/task/statuses').then(response => {
                    this.statuses = response.data.statuses;
                    // console.log( this.statuses );
                });
                $('#add_task_model').modal('show');
            },
            createTask() {
                this.errors = [];
                axios.post('/task', {
                   name: this.task.name,
                   status: this.task.status,
                   archived: this.task.archived,
                })
                .then(response => {
                    this.resetTask();
                    if(response.data.task) {
                        this.tasks.unshift(response.data.task);
                        // console.log(response.data.task);
                        this.msg = 'New task added.';
                    }
                    $("#add_task_model").modal("hide");
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        this.errors.push(error.response.data.errors);
                        // this.$set('errors', error.response.data.errors);
                    } 
                });
            },
            resetTask(){
                this.task.name = '';
                this.task.status = '';
                this.task.archived = '';
            },
            initUpdateTask(index) {
                this.statuses = [];
                this.errors = [];
                axios.get('/task/statuses').then(response => {
                    this.statuses = response.data.statuses;
                    // console.log( this.statuses );
                });
                $("#update_task_model").modal("show");
                this.selected_index = index;
                this.selected_task = this.tasks[index];
            },
            updateTask() {
                this.errors = [];
                this.selected_task.name = this.$refs.editTaskName.value;
                axios.patch('/task/'+this.selected_task.id, {
                    name: this.$refs.editTaskName.value,
                    status: this.$refs.editTaskStatus.value,
                    archived: this.$refs.editTaskArchived.value,
                })
                .then(response => {
                // .then(function(response, tasks, index) {
                    if(response.data.task) {
                        // console.log(response.data.task);
                        this.tasks[this.selected_index].name = response.data.task.name;
                        this.tasks[this.selected_index].status = response.data.task.status;
                        this.tasks[this.selected_index].archived = response.data.task.archived;
                        this.tasks[this.selected_index].updated_at = response.data.task.updated_at;
                    }
                    $("#update_task_model").modal("hide");
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        this.errors.push(error.response.data.errors);
                        // this.$set('errors', error.response.data.errors);
                    } 
                });
            },
            deleteTask(index) {
                let conf = confirm('Do you really want to delete Task: '+this.tasks[index].name+'?');
                if (conf === true) {
                    axios.delete('/task/'+this.tasks[index].id)
                    .then(response => {
                        this.tasks.splice(index, 1);
                        // console.log(response.data.deleted);
                    })
                    .catch(error => {
                    });
                }
            }
        }
    }
</script>
