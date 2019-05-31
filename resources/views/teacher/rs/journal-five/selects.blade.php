
<div class="form-row">
<div class="form-group col-md-4">
  <label class="width-auto-label">Количество отображаемых столбцов:</label>
  <select v-model="selected" @change="cookies" class="form-control width-auto">
    <option v-for="(text, key) in options" >
      @{{ text }}
    </option>
  </select>
</div>
</div>
<div class="clearfix"></div>
