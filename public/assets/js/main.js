let todoForm = Vue.component('todo-form', {
    data: function() {
        return {
            title: '',
            status: 'test',
            editTitle: 0,

            newTodoText: '',
            nextTodoId: 1,
            todos: [],
        }
    },
    methods: {
        init: function() {
            axios.get('/api/lists/' + update.dataset.listId)
                .then(response => {
                    this.title = response.data.list.name;
                    this.status = response.data.list.status;
                })
                .catch(response => {
                    alert('Something went wrong');
                });

            axios.get('/api/lists/' + update.dataset.listId + '/items')
                .then(response => {
                    let items = response.data.items;

                    if (items.length) {
                        this.todos = items.map(obj => {
                            obj.show = true;
                            return obj;
                        });
                        this.nextTodoId = items[items.length - 1].id + 1;
                    }

                })
                .catch(response => {
                    alert('Something went wrong');
                });
        },
        create: function(event) {
            axios.post('/api/lists/' + update.dataset.listId + '/items', {
                    name: inputItem.value
                })
                .then(response => {
                    this.todos.push({
                        id: response.data.item.id,
                        name: response.data.item.name,
                        status: response.data.item.status,
                        show: true,
                    });

                    this.nextTodoId = response.data.item.id + 1;
                    this.newTodoText = '';
                })
                .catch(response => {
                    alert('Something went wrong');
                });
        },
        removeItem: function(itemId) {
            for (var i = 0; i < this.todos.length; i++) {
                if (this.todos[i].id == itemId) {
                    axios.delete('/api/lists/' + update.dataset.listId + '/items/' + this.todos[i].id)
                        .then(response => {
                            this.todos[i].show = false;

                            this.todos.splice(i, 1);
                        })
                        .catch(response => {
                            alert('Something went wrong');
                        });

                    break;
                }
            }
        },
        updateItem: function(itemId, newData) {
            for (var i = 0; i < this.todos.length; i++) {
                if (this.todos[i].id == itemId) {
                    axios.post('/api/lists/' + update.dataset.listId + '/items/' + this.todos[i].id, {
                            name: newData.name,
                            status: newData.status,
                        })
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
        removeList: function() {
            axios.delete('/api/lists/' + update.dataset.listId)
                .then(response => {
                    window.location.href = base_url;
                })
                .catch(response => {
                    alert('Something went wrong');
                });
        },
        updateList: function() {
            this.editTitle = 0;

            axios.post('/api/lists/' + update.dataset.listId, {
                    name: this.title,
                    status: this.status,
                })
                .then(response => {
                    console.log(this.status);

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
    <ul class="items" v-if="todos.length !== 0">
        <li
          is="todo-item"
          v-for="todo in todos"
          v-bind:id="todo.id"
          v-bind:name="todo.name"
          v-bind:status="todo.status"

          v-if="todo.show"

          v-on:remove="removeItem"
          v-on:update="updateItem"
        ></li>
    </ul>
    <p class="nothing" v-if="todos.length === 0">Der er ikke tilføjet nogle to-do's endnu.</p>
    <form class="item-add" method="post" v-on:submit.prevent="create">
        <input id="inputItem" v-model="newTodoText" type="text" placeholder="Skriv en ny to-do her." class="input" autocomplete="off" required>
        <input id="submitItem" type="submit" value="Tilføj" class="submit">
    </form>
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
                if (this.staticName != null) {
                    return this.staticName;
                }

                return this.name;
            },
            set: function(val) {
                this.staticName = val;
            }
        },
        statusClass: function() {
            return {
                done: this.status == 'completed',
            }
        },
        statusText: function() {
            return (this.status == 'active') ? 'Markér færdig' : 'Aktivér';
        }
    },
    methods: {
        remove: function(event) {
            this.$emit('remove', this.id);
        },
        changeStatus: function(event) {
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
    <li :id="'item-' + id" class="item-wrap">
        <div class="pull-left">
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
            <button v-on:click="changeStatus" :id="'completed-'+ id" class="done-button mark-as-done-button">{{ statusText }}</button>
            <button v-on:click="remove" id="'delete-'+ id" class="done-button delete-button">Slet</button>
        </div>
    </li>
    `,
});

let VueApp = new Vue({
    el: '#app',
    data: {
        name: 'Todo List App',
        base_url: base_url
    },
    components: {
        'todo-form': todoForm,
        'todo-item': todoItem
    },
    methods: {
        create_list: function(event) {
            axios.post('/api/lists')
                .then(response => {
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