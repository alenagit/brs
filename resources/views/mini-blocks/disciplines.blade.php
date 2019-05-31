
<div class="table-responsive-md">
<table class="table">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Номер</th>
      <th scope="col">Название</th>
      <th scope="col">МДК</th>
    </tr>
  </thead>
  <tbody>
    @foreach($disciplines as $discipline)
    <tr>
      <th scope="row">{{$discipline->id}}</th>
      <td>{{$discipline->number}}</td>
      <td>{{$discipline->name}}</td>
      <td>{{$discipline->mdk}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
