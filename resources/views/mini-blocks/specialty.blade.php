
<div class="table-responsive-md">
<table class="table">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Номер</th>
      <th scope="col">Название</th>
    </tr>
  </thead>
  <tbody>
    @foreach($specialties as $specialty)
    <tr>
      <th scope="row">{{$specialty->id}}</th>
      <td>{{$specialty->number}}</td>
      <td>{{$specialty->name}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
