@extends('layouts.teacher')

@section('content')
<h3>Основные параметры</h3>


      <div class="card-deck mb-3 ">
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-secondary text-white">
            <h4 class="my-0 font-weight-normal text-center ">Специальности</h4>
          </div>
          <div class="bg-secondary text-white p-3">
          @include('forms.create-specialties-form')
          </div>
          <div class="card-body custom-padd">
            @include('mini-blocks.specialty',['specialties' => $specialties])
          </div>
        </div>
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-secondary text-white">
            <h4 class="my-0 font-weight-normal text-center">Группы</h4>
          </div>
          <div class="bg-secondary text-white p-3">
            @include('forms.create-groups-form')
            </div>
          <div class="card-body">
            @include('mini-blocks.group',['groups' => $groups])
          </div>
        </div>
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-secondary text-white">
            <h4 class="my-0 font-weight-normal text-center">Дисциплины</h4>
          </div>
          <div class="bg-secondary text-white p-3">
          @include('forms.create-disciplines-form')
          </div>
          <div class="card-body">
            @include('mini-blocks.disciplines',['disciplines' => $disciplines])
          </div>
        </div>
</div>


@endsection
@section('js')
<script src="{{ asset('js/jquery.js') }}" defer></script>
<script src="{{ asset('js/axios-form.js') }}" defer></script>
<script>
function createGroup(id_spec, year)
{

axios({
  method: 'post',
  url: '/api/axios-group-create',
  data: {
    id_specialty: id_spec,
    year_adms: year
  }
})
.then(function (response) {
  location.reload();
});

}

function createSpecialty(number, name)
{
  axios({
    method: 'post',
    url: '/api/axios-specialty-create',
    data: {
      number: number,
      name: name
    }
  })
  .then(function (response) {
    location.reload();
  });
}

function createDiscipline(number, name,mdk)
{

axios({
  method: 'post',
  url: '/api/axios-discipline-create',
  data: {
    number: number,
    name: name,
    mdk: mdk
  }
})
.then(function (response) {
  location.reload();
});

}
</script>
@endsection
