//Vue component - <todo-form></todo-form>
//This controls all events
let todoForm = Vue.component('todo-form', {
    data: function() {
        return {
            title: '', //List title
            status: '', //List status (private||public)
            editTitle: 0, //Are we editing the title?

            newTodoText: '', //temporary for a new todo item
            nextTodoId: 1, //Next todo item ID
            todos: [], //Contains all todo items
        }
    },
    methods: {
        //On load / init
        init: function() {
            //Get list's settings
            axios.get(base_url + '/api/lists/' + update.dataset.listId)
                .then(response => {
                    //Update the values/data of the component
                    this.title = response.data.list.name;
                    this.status = response.data.list.status;
                })
                .catch(response => {
                    //Error
                    alert('Something went wrong');
                });

            //Get list's items
            axios.get(base_url + '/api/lists/' + update.dataset.listId + '/items')
                .then(response => {
                    let items = response.data.items;

                    //Only if there are items present
                    if (items.length) {

                        //Add the list's item to the component data
                        this.todos = items;

                        //update the next todo item ID
                        this.nextTodoId = items[items.length - 1].id + 1;
                    }

                })
                .catch(response => {
                    //Error
                    alert('Something went wrong');
                });
        },

        //Create new list item
        createItem: function(event) {

            //Make a call to API with the new item's name
            axios.post(base_url + '/api/lists/' + update.dataset.listId + '/items', {
                    name: inputItem.value
                })
                .then(response => {

                    //add item to todos array
                    this.todos.push({
                        id: response.data.item.id, // item ID
                        name: response.data.item.name, //name / title of item
                        status: response.data.item.status, //active or completed
                    });

                    //Update next todo ID
                    this.nextTodoId = response.data.item.id + 1;

                    //Set tmp todoText to empty string again
                    this.newTodoText = '';
                })
                .catch(response => {
                    //Error
                    alert('Something went wrong');
                });
        },

        //Remove a list item form ID
        removeItem: function(itemId) {

            //Loop todo items
            for (var i = 0; i < this.todos.length; i++) {

                //find the correct item
                if (this.todos[i].id == itemId) {

                    //Make api call (delete method)
                    axios.delete(base_url + '/api/lists/' + update.dataset.listId + '/items/' + this.todos[i].id)
                        .then(response => {
                            //When there's a success we can remove the element from the array
                            this.todos.splice(i, 1);
                        })
                        .catch(response => {
                            //Error
                            alert('Something went wrong');
                        });

                    //We have deleted the item
                    //Just finish now with break
                    break;
                }
            }
        },

        //Update a list item
        updateItem: function(itemId, newData) {

            //Loop todo items
            for (var i = 0; i < this.todos.length; i++) {

                //Find the correct item
                if (this.todos[i].id == itemId) {

                    //API call, with the new data
                    axios.post(base_url + '/api/lists/' + update.dataset.listId + '/items/' + this.todos[i].id, newData)
                        .then(response => {
                            //On success we can update the list name and status
                            this.todos[i].name = response.data.item.name;
                            this.todos[i].status = response.data.item.status;
                        })
                        .catch(response => {
                            //Error
                            alert('Something went wrong');
                        });

                    break;
                }
            }
        },

        //Delete the entire list
        removeList: function() {
            //API Call (method: delete)
            axios.delete(base_url + '/api/lists/' + update.dataset.listId)
                .then(response => {
                    //This page technically doesn't exist anymore now
                    //Redirect to homepage
                    window.location.href = base_url;
                })
                .catch(response => {
                    //Error
                    alert('Something went wrong');
                });
        },

        //Update the todo list settings
        updateList: function() {
            //Make sure we are not editing the title anymore
            this.editTitle = 0;

            //Make call to API, to update list with new data
            axios.post(base_url + '/api/lists/' + update.dataset.listId, {
                    name: this.title,
                    status: this.status,
                })
                .then(response => {
                    //Update the component's data to the new values
                    //To make sure everything is aligned with the DB
                    //Eg. an incorrect status would be fixed by this
                    this.title = response.data.list.name;
                    this.status = response.data.list.status;
                })
                .catch(response => {
                    //Error
                    alert('Something went wrong');
                });
        }
    },

    //Do this when the component is mounted (loaded)
    mounted: function() {
        //Call init
        this.init();
    },

    //Template
    template: `
    <div class="todo-list">

        <!-- Only show if the editTitle = false, and make sure to toggle on click -->
        <h1 class="header" v-on:click.self="editTitle = false" v-show="!editTitle"
            v-on:click.stop="editTitle = !editTitle">{{ title }}</h1>

        <!-- Only show if the editTitle = true, and toggle visibility on enter/blur -->
        <!-- Bind the variable title for updates -->
        <input
            v-show="editTitle"
            v-on:blur="updateList"
            v-on:keyup.enter="$event.target.blur()"
            v-model="title"
            class="input"
            autofocus/>

        <!-- Show items, of there are any -->
        <ul class="items" v-if="todos.length !== 0">

            <!-- Tell the <li> it is a <todo-item> -->
            <!-- Loop through all todo items -->
            <!-- bind properties to the child component -->
            <!-- Event Emitters -->
            <!-- Catches events from child, and executes removeItem/updateItem, -->
            <!-- with specified arguments from child -->
            <li
                is="todo-item"

                v-for="todo in todos"

                v-bind:id="todo.id"
                v-bind:name="todo.name"
                v-bind:status="todo.status"

                v-on:remove="removeItem"
                v-on:update="updateItem"
            ></li>
        </ul>

        <!-- Show notice when there are no items -->
        <p class="nothing" v-if="todos.length === 0">Der er ikke tilføjet nogle to-do's endnu.</p>

        <!-- Create new item, submit the form to Vue.js -->
        <form class="item-add" method="post" v-on:submit.prevent="createItem">
            <input id="inputItem" v-model="newTodoText" type="text" placeholder="Skriv en ny to-do her." class="input" autocomplete="off" required>
            <input id="submitItem" type="submit" value="Tilføj" class="submit">
        </form>

        <!-- List setting: Privacy -->
        <!-- Change to private or public -->
        <label for="listStatus" class="list-status">

            <!-- Bind "status" variable, and updateList() on change -->
            <!-- What values to get when the checkbox if checked and not checked -->
            <input id="listStatus" type="checkbox"

                v-model="status"
                v-on:change="updateList"

                true-value="private"
                false-value="public"/>
            Privat
        </label>

        <!-- List setting: Remove -->
        <!-- Delete the todo list -->
        <button id="listRemove" v-on:click="removeList">Slet "{{ title }}"</button>
    </div>`,
});



//Vue component - <todo-item></todo-item>
//This displays each todo item
let todoItem = Vue.component('todo-item', {
    //Properties inherited from parent
    props: ['name', 'id', 'status'],

    data: function() {
        return {
            editName: 0, //display/hide the input field for each item (used for editing "name")
            staticName: null //temporary name holder
        }
    },

    computed: {
        //computed name
        //This functions updates the previous inherited "name"
        newName: {
            //Getter
            get: function() {
                //Check if the name has been changed
                //If yes, then return the new name
                if (this.staticName != null) {
                    return this.staticName;
                }

                //Return default inherited name property
                return this.name;
            },

            //Setter
            set: function(val) {
                //Sets the static name, alongside with "newName"
                this.staticName = val;
            }
        },

        //class for the item name
        //Makes the strike-through styled title
        statusClass: function() {
            return {
                //Only add the class "done" if the status is completed
                done: this.status == 'completed',
            }
        },

        //Change status button text
        statusText: function() {
            return (this.status == 'active') ? 'Markér færdig' : 'Aktivér';
        }
    },
    methods: {
        //Remove the item from the list
        remove: function(event) {

            //We need to handle this in the parent component
            //Send a signal to the parent of what to do and the ID
            this.$emit('remove', this.id);
        },

        //Change the item status
        changeStatus: function(event) {

            //We also need to do the in the parent
            //This makes sure we update the status field to the opposite
            //And pass the name field with for "update"
            this.$emit('update', this.id, {
                name: this.name,
                status: (this.status == 'active') ? 'completed' : 'active'
            });
        },

        //Change the item name
        changeName: function(event) {
            //We aren't editing anymore now
            this.editName = false;

            //Same as before - pass it to parent
            //This makes sure we update the NAME field to the new name
            //And pass the status field with for "update"
            this.$emit('update', this.id, {
                name: this.newName,
                status: this.status
            });
        }
    },

    //Template for a <todo-item></todo-item>
    template: `
    <li class="item-wrap">
        <!-- Item name + input for update -->
        <div class="pull-left">

            <!-- bind the status class -->
            <!-- Only show if the editName = false, and make sure to toggle on click -->
            <span class="item" v-bind:class="[statusClass]" v-on:click.self="editName = false" v-show="!editName"
                v-on:click.stop="editName = !editName">{{ name }}</span>


            <!-- Only show if the editName = true, and make sure to call "changeName" on enter or blur -->
            <!-- Updates the model (newName) when input happens -->
            <input
                v-show="editName"
                v-on:blur="changeName"
                v-on:keyup.enter="$event.target.blur()"
                v-model="newName"
                class="input"
                autofocus/>
        </div>


        <!-- Status and delete buttons -->
        <div class="pull-right">
            <!-- ChangeStatus on click -->
            <button v-on:click="changeStatus" class="done-button mark-as-done-button">{{ statusText }}</button>

            <!-- Remove on click -->
            <button v-on:click="remove" class="done-button delete-button">Slet</button>
        </div>
    </li>
    `,
});


//Create Vue instance on the #app element
let VueApp = new Vue({
    el: '#app',
    data: {
        name: 'Todo List App',
        base_url: base_url //base url from layout template
    },

    //Register custom components
    components: {
        'todo-form': todoForm,
        'todo-item': todoItem
    },

    //custom functions
    methods: {

        //Create new Todo List
        create_list: function(event) {

            //Use axios.post - returns a Promise
            axios.post(base_url + '/api/lists')
                .then(response => {
                    //Check if we actually got a URL back
                    if (response.data.url) {
                        //Redirect to new list
                        window.location.href = base_url + '/' + response.data.url;
                    }
                })
                .catch(err => {
                    //We got an error
                    alert('Something went wrong');
                });;
        },
    }
});