
<div class="table-responsive-md">
<table class="table">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Специальность</th>
      <th scope="col">Год поступления</th>
    </tr>
  </thead>
  <tbody>
    @foreach($groups as $group)
    <tr>
      <th scope="row">{{$group->id}}</th>
      <td>{{$group->specialty->name}}</td>
      <td>{{$group->year_adms}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
