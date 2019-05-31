<template>
    <div style="width: 100% !important;">
    <button style="margin-left: 15px;margin-bottom: 40px;" class="btn btn-primary" @click.prevent="addTask" >Добавить тип работ</button>
    <div class="clearfix"></div>


    <div style="display: inline-flex; width: 100%;">
    <div class="card mb-4 shadow-sm" v-for="(task, i) in tasks">
      <div class="card-header bg-secondary text-white">
        <h5 class="my-0 font-weight-normal text-center">Параметры типа работ <button @click="deltask(i)" type="button" class="btn task-del">x</button></h5>

      </div>
      <div class="card-body">
      <div class="mb-3">
        <label for="name_task">Наименование типа работ</label>
      <input type="text" id="name_task" name="name_task" class="form-control" placeholder="Например: Практические работы, Лабораторные работы" v-model="tasks[i].name"/>
      </div>

        <div class="mb-3">
          <label for="total_task">Количество работ этого типа</label>
        <input type="number" id="total_task" name="total_task" class="form-control" v-model="tasks[i].total"/>
        </div>

        <div class="mb-3">
          <label for="total_task_score">Количество баллов за все работы</label>
        <input type="number" id="total_task_score" name="total_task_score" class="form-control" v-model.number="tasks[i].total_score"/>
        </div>
      </div>
    </div>
      </div>
      <input  style="display:none;" type="text" name="name_task" v-model="names" class="form-control"/>
      <input  style="display:none;" type="text" name="total_task" v-model="totals" class="form-control"/>
      <input  style="display:none;" type="text" name="total_task_score" v-model="total_scores" class="form-control"/>
      <p type="hidden" style="display:none;">{{ getNames }} {{ getTotal }} {{ getTotalScore }} {{ sumScore }}</p>
    </div>

</template>

<script>
  export default {
     data: function() {
       return {
        tasks: [],
        defNameTask: '',
        defTotalTask: 0,
        defTotalTaskScore: 0,
        names: '',
        totals: '',
        total_scores: '',
        total_score_tasks: 0
        }
      },
      computed: {
      sumScore(){
      var sum = 0;
      var taskmass = this.tasks;

      taskmass.forEach(function(item, i, taskmass) {
         sum += taskmass[i].total_score;
      });
      sum += this.$root.total_score;
      this.total_score_tasks = sum;

      return this.total_score_tasks;
      },
      getNames(){
        var name_mass = [];
        var taskmass = this.tasks;

        taskmass.forEach(function(item, i, taskmass) {
          name_mass.push(taskmass[i].name);
        });

        this.names = name_mass.join();
        return this.names;
      },
      getTotal(){
        var total_mass = [];
        var taskmass = this.tasks;

        taskmass.forEach(function(item, i, taskmass) {
          total_mass.push(taskmass[i].total);
        });

        this.totals = total_mass.join();
        return this.totals;
      },
      getTotalScore(){
        var t_score_mass = [];
        var taskmass = this.tasks;

        taskmass.forEach(function(item, i, taskmass) {
          t_score_mass.push(taskmass[i].total_score);
        });

        this.total_scores = t_score_mass.join();
        return this.total_scores;
      }

      },
      methods: {
        addTask(){
          this.tasks.push({
            name: '',
            total: 0,
            total_score: 0
          });
        },
        deltask(i){
          this.tasks.splice(i, 1);
        },
      }
  }
</script>
