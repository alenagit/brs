<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
<line-chart :chart-data="data" :height="100" options="{responsive: true, maintainAspectRation: true}"></line-chart>
    <button style="margin-left: 15px;margin-bottom: 40px;" class="btn btn-primary" @click.prevent="update" >Обновить</button>
    <p>{{ data }}</p>
            </div>
        </div>
    </div>
</template>

<script>
  import LineChart from './LineChart.js'
  export default{
      components: {
       LineChart
     },
     data: function() {
       return {
        data: [],
        label: [],
        scores: []
        }
      },
      mounted() {

        this.update()
      },
      methods: {

      update: function() {

      if(this.$root.total_lesson_score > 0){
      this.label.push('Лекции');
      this.scores.push(this.$root.total_lesson_score);
      }

      if(this.$root.total_test_score > 0){
      this.label.push('Тесты');
      this.scores.push(this.$root.total_test_score);
      }

      if(this.$root.total_main_test_score > 0){
      this.label.push('Итоговые тесты');
      this.scores.push(this.$root.total_main_test_score);
      }

      if(this.$root.total_score_tasks > 0){
      this.label.push('Все самостоятельные работы');
      this.scores.push(this.$root.total_score_tasks);
      }




      this.data = [{
      labels: this.label,
      datasets: {
      data: this.scores,
      backgroundColor: ['#ffc0cb','#42aaff','#50c878']
      }
}]


      }
      }
  }
</script>
