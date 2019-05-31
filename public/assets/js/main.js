let todoForm = Vue.component('todo-form', {
    data: function() {
        return {
            title: '',
            status: '',
            editTitle: false,

            newTodoText: '',
            nextTodoId: 1,
            todos: [],
        }
    },

    methods: {
        // On load / init
        init: function() {

            // Get list's settings
            axios.get(base_url + '/api/lists/' + update.dataset.slug)
                .then(response => {
                    this.title = response.data.list.name;
                    this.status = response.data.list.status;
                })
                .catch(response => {
                    alert('Something went wrong');
                });

            // Get list's items
            axios.get(base_url + '/api/lists/' + update.dataset.slug + '/items')
                .then(response => {
                    let items = response.data.items;

                    if (items.length) {
                        this.todos = items;

                        // Update the next todo item's ID
                        this.nextTodoId = items[items.length - 1].id + 1;
                    }

                })
                .catch(response => {
                    alert('Something went wrong');
                });
        },

        // Create new list item
        createItem: function(event) {

            // Make a call to API with the new item's name
            axios.post(base_url + '/api/lists/' + update.dataset.slug + '/items', {
                name: inputItem.value
            })
            .then(response => {

                // Add new item to todos
                this.todos.push({
                    id: response.data.item.id,
                    name: response.data.item.name,
                    status: response.data.item.status
                });

                // Update next todo ID
                this.nextTodoId = response.data.item.id + 1;

                this.newTodoText = '';
            })
            .catch(response => {
                alert('Something went wrong');
            });
        },

        // Remove a list item form ID
        removeItem: function(itemId) {

            for (var i = 0; i < this.todos.length; i++) {

                // Find the correct item
                if (this.todos[i].id == itemId) {

                    // Make API call (delete method)
                    axios.delete(base_url + '/api/lists/' + update.dataset.slug + '/items/' + this.todos[i].id)
                        .then(response => {
                            this.todos.splice(i, 1);
                        })
                        .catch(response => {
                            alert('Something went wrong');
                        });

                    break;
                }
            }
        },

        // Update a list item
        updateItem: function(itemId, newData) {

            for (var i = 0; i < this.todos.length; i++) {

                // Find the correct item
                if (this.todos[i].id == itemId) {

                    // Make API call with new data
                    axios.post(base_url + '/api/lists/' + update.dataset.slug + '/items/' + this.todos[i].id, newData)
                        .then(response => {
                            this.todos[i].name = response.data.item.name;
                            this.todos[i].status = response.data.item.status;
                        })
                        .catch(response => {
                            alert('Something went wrong');
                        });

                    break;
                }
            }
        },

        // Delete the entire list
        removeList: function() {
            // Make API call
            axios.delete(base_url + '/api/lists/' + update.dataset.slug)
                .then(response => {
                    // This page technically doesn't exist anymore now
                    window.location.href = base_url;
                })
                .catch(response => {
                    alert('Something went wrong');
                });
        },

        // Update the todo list settings
        updateList: function() {
            this.editTitle = false;

            // Make call to API, to update list with new data
            axios.post(base_url + '/api/lists/' + update.dataset.slug, {
                name: this.title,
                status: this.status,
            })
            .then(response => {
                this.title = response.data.list.name;
                this.status = response.data.list.status;
            })
            .catch(response => {
                alert('Something went wrong');
            });
        }
    },

    mounted: function() {
        this.init();
    },

    template: `
    <div class="todo-list">
        <h1 class="header" v-on:click.self="editTitle = false" v-show="!editTitle"
            v-on:click.stop="editTitle = !editTitle">{{ title }}</h1>

        <input
            v-show="editTitle"
            v-on:blur="updateList"
            v-on:keyup.enter="$event.target.blur()"
            v-model="title"
            class="input"
            autofocus/>

        <!-- Show items, if there are any -->
        <ul class="items" v-if="todos.length !== 0">

            <li
                is="todo-item"

                v-for="todo in todos"

                v-bind:key="todo.id"
                v-bind:id="todo.id"
                v-bind:name="todo.name"
                v-bind:status="todo.status"

                v-on:remove="removeItem"
                v-on:update="updateItem"
            ></li>
        </ul>

        <!-- Show notice when there are no items -->
        <p class="nothing" v-if="todos.length === 0">Der er ikke tilføjet nogle to-do's endnu.</p>

        <!-- Create new item -->
        <form class="item-add" method="post" v-on:submit.prevent="createItem">
            <input id="inputItem" v-model="newTodoText" type="text" placeholder="Skriv en ny to-do her." class="input" autocomplete="off" required>
            <input id="submitItem" type="submit" value="Tilføj" class="submit">
        </form>

        <!-- Change to private or public -->
        <label for="listStatus" class="list-status">

            <input id="listStatus" type="checkbox"

                v-model="status"
                v-on:change="updateList"

                true-value="private"
                false-value="public"/>
            Privat
        </label>

        <button id="listRemove" v-on:click="removeList">Slet "{{ title }}"</button>
    </div>`,
});

let todoItem = Vue.component('todo-item', {
    props: ['name', 'id', 'status'],

    data: function() {
        return {
            editName: 0,
            staticName: null
        }
    },

    computed: {
        newName: {
            get: function() {
                // Check if the name has been changed
                // If yes, then return the new name
                if (this.staticName != null) {
                    return this.staticName;
                }

                // Return default inherited name property
                return this.name;
            },

            set: function(val) {
                // Sets the static name, alongside with "newName"
                this.staticName = val;
            }
        },

        statusClass: function() {
            return {
                done: this.status == 'completed'
            }
        },

        statusText: function() {
            return (this.status == 'active') ? 'Markér færdig' : 'Aktivér';
        }
    },
    methods: {
        remove: function(event) {
            // Send a signal to the parent of what to do next
            this.$emit('remove', this.id);
        },

        changeStatus: function(event) {
            // Emit update to parent
            this.$emit('update', this.id, {
                name: this.name,
                status: (this.status == 'active') ? 'completed' : 'active'
            });
        },

        changeName: function(event) {
            this.editName = false;

            this.$emit('update', this.id, {
                name: this.newName,
                status: this.status
            });
        }
    },

    template: `
    <li class="item-wrap">
        <div class="pull-left">
            <!-- Only show if the editName = false, and make sure to toggle on click -->
            <span class="item" v-bind:class="[statusClass]" v-on:click.self="editName = false" v-show="!editName"
                v-on:click.stop="editName = !editName">{{ name }}</span>

            <input
                v-show="editName"
                v-on:blur="changeName"
                v-on:keyup.enter="$event.target.blur()"
                v-model="newName"
                class="input"
                autofocus/>
        </div>

        <div class="pull-right">
            <button v-on:click="changeStatus" class="done-button mark-as-done-button">{{ statusText }}</button>
            <button v-on:click="remove" class="done-button delete-button">Slet</button>
        </div>
    </li>
    `,
});

let VueApp = new Vue({
    el: '#app',
    data: {
        name: 'Todo List App',
        base_url: base_url // app constant
    },

    components: {
        'todo-form': todoForm,
        'todo-item': todoItem
    },

    methods: {
        createList: function(event) {
            axios.post(base_url + '/api/lists')
                .then(response => {
                    // Redirect to new list
                    if (response.data.url) {
                        window.location.href = base_url + '/' + response.data.url;
                    }
                })
                .catch(err => {
                    alert('Something went wrong');
                });;
        },
    }
});
