<template>
  <div>
    <v-toolbar flat color="white">
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <v-btn slot="activator" color="primary" dark class="mb-2" @click.native="new_question">New Question</v-btn>
        <v-form v-model="valid" ref="form">
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container grid-list-md>
                <v-list>
                  <v-text-field v-model="editedItem.question" label="Question" required :rules="[() => editedItem.question.length > 0 || 'Required field']"></v-text-field>
                    <v-radio-group v-model="editedItem.selected">
                        
                        <v-list-tile v-for="video in editedItem" :key="video" @click="editedItem.selected = video" class="mt-2">
                          
                          <v-list-tile-action>
                            <v-radio name="video" v-bind:value="video" @click.prevent=""/></v-radio>
                          </v-list-tile-action>
                          
                          <v-list-tile-content>
                            <v-text-field v-model="editedItem.answers[video-1]['answer']" label="Answer" required :rules="[() => editedItem.answers[video-1]['answer'].length > 0 || 'Required field']"></v-text-field>
                          </v-list-tile-content>

                          <v-list-tile-content>
                            <v-btn v-if="video == 1" small color="primary" flat-right @click="add">Add</v-btn>
                            <v-btn v-if="video != 1" small color="primary" flat-right @click="remove(video-1)">Close</v-btn>
                          </v-list-tile-content>
                        
                        </v-list-tile>
                        
                      </v-radio-group>
                    </v-list>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="orange" dark flat @click.native="close">Cancel<v-icon dark left>remove_circle</v-icon></v-btn>
            <v-btn color="orange"  flat @click.native="save_qustions">Save<v-icon dark right>check_circle</v-icon></v-btn>
          </v-card-actions>
        </v-card>
        </v-form>
      </v-dialog>
      
    </v-toolbar>
    <v-data-table :headers="headers" :items="desserts" hide-actions class="elevation-1">
      <template slot="items" slot-scope="props">
        <td>{{ props.item.question }}</td>
        <td class="text-xs-center">{{ props.item.correct_answer }}</td>
        <td class="text-xs-center">{{ props.item.count }}</td>
        <!-- <td class="text-xs-center">{{ props.item.created_at }}</td> -->
        <td class="justify-center layout px-0">
          <v-icon small class="mr-2" @click="editItem(props.item)">
            edit
          </v-icon>
          <v-icon small @click="deleteItem(props.item)">
            delete
          </v-icon>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
var test = [];
  import * as actions from '../../store/action-types'
  import withSnackbar from '../mixins/withSnackbar'
  export default {
    mixins: [withSnackbar],
    data: () => ({
      selected: 1,
      question:'',
      dialog: false,
      count: 1,
      answer:[],
      loginLoading: false,
      valid: false,
      headers: [
        { text: 'Questions',align: 'center',sortable: false, value: 'question'},
        { text: 'Correct', value: 'correct', align: 'center', },
        { text: 'Answer Count', value: 'count',align: 'center'},
        // { text: 'Create Date', value: 'created_at',align: 'center' },
        { text: 'Actions', value: 'name', sortable: false, align: 'center' }
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        question: '',
        count: 0,
        answers: [],
        selected: 0,
        _id: '',
        correct_answer:''
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Question' : 'Edit Question'
      }
    },
    watch: {
      dialog (val) {
        val || this.close()
      }
    },
    created () {
      this.init()
    },
    methods: {
      new_question: function()
      {
         this.editedItem.question = '';
         this.editedItem.count = 1;
         this.editedItem.answers = [];
         this.editedItem.selected = 1;
         this.editedItem._id = '';
         var answers = {'answer': '', 'valid' : 0};
         this.editedItem.answers.push(answers);
         this.editedIndex = -1;
      },
      remove: function(order){
        this.editedItem.count = this.editedItem.count - 1;
        this.editedItem.answers.splice(order, 1);
        this.editedItem.selected = 1;
        this.reset_selectitem();
      },
      
      add: function(){
        this.editedItem.count = this.editedItem.count + 1;
        var answers = {
           'answer': '',
           'valid' : 0
        };
         this.editedItem.answers.push(answers);
      },
      save_qustions: function(){
        this.$refs.form.validate();
        if(this.valid == false){
          return;
        }
        if(this.editedIndex > -1)
        {
            this.editedItem.correct_answer = this.editedItem.answers[this.editedItem.selected - 1].answer;
            axios.post('/api/admin/question-management/update',{data: JSON.stringify(this.editedItem)}, {
            headers:{
              'Content-Type':'applicaton/json',
            }
          }).then(function(response){
            Object.assign(this.desserts[this.editedIndex], this.editedItem);
            this.showMessage(`Successfully Updated`);

          }.bind(this)).catch(function (error){
            console.log(error);
          }.bind(this));
        }
        else{
          axios.post('/api/admin/question-management/create', {data: JSON.stringify(this.editedItem)}, {
            headers:{
              'Content-Type':'applicaton/json',
            }
          }).then(function(response){
            this.desserts = response.data.questions;
            this.showMessage(`Successfully Saved`);
          }.bind(this)).catch(function (error){
            console.log(error.response);
          }.bind(this));
        }
        this.dialog = false
      },
      init () {
        this.loading = true
        axios.get('/api/admin/question-management/show', {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then( function (response) {
          this.loading = false
          this.desserts = response.data.questions
        }.bind(this))
        .catch(function (error) {
          this.loading = false
        }.bind(this))
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item);
        console.log('item+++',item);
        console.log('index+++', this.editedIndex);
        let itemQuestion = item.question;
        axios.get('/api/admin/question-management/edit', 
                  {params:{
                    itemQuestion : itemQuestion
                  }},
                  {headers: {'Content-Type':'application/json'}})
              .then(function (response){
                console.log('response+++', response);
              })
              .catch(function (error){
                console.log('error---', error);
              });
        // this.dialog = true
      //   this.editedItem.selected = item['selected'];
      //   this.editedItem.question = item['question'];
      //   this.editedItem.count = item['count'];
      //   this.editedItem.correct_answer = item['correct_answer'];
      //   this.editedItem._id = item['_id'];
      //   this.editedItem.answers = [];
      //   for (var i=0;i<item['answers'].length;i++)
      //   {
      //        this.editedItem.answers.push({'answer':item['answers'][i]['answer'], 'valid':item['answers'][i]['valid']})
      //   }
      //   this.dialog = true
      },

      deleteItem (item) {
        const index = this.desserts.indexOf(item);
        let delete_item = Object.assign({}, item);
        if(confirm('Are you sure you want to delete this item?'))
        {
          axios.post('/api/admin/question-management/delete', {data:delete_item['_id']}, {headers: {'Content-Type': 'application/json'}})
          .then( function (response) {
            this.loading = false
            this.desserts.splice(index, 1);
            this.showMessage(`Successfully Deleted`);
          }.bind(this))
          .catch(function (error) {
            this.loading = false
          }.bind(this));

        }
      },
      close () {
        this.dialog = false
      },
      reset_selectitem: function(){
        var self = this;
        setTimeout(function(){
                self.editedItem.selected = 1;
            }, 30);
      },
    }
  }
</script>