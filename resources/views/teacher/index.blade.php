<?
use \App\Http\Controllers\Teacher\RSController;
use \App\User;
use \App\Http\Controllers\Student\CalculateController;
date_default_timezone_set('Europe/Moscow');
$mem_winer = array();
$mem_count = array();
$mem_yest = array();
$mem_can = array();
$rs_array = array();
$top_hooky = array();
?>
@extends('layouts.teacher')
@section('title')
    <title>Кабинет</title>
  @endsection
@section('content')

<div class="cab-teacher">
<h3>Кабинет</h3>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
@foreach($rs as $n_rs => $r)
<?
$mem_winer += [$r->id => CalculateController::getMem($r->id)];
$mem_count += [$r->id => CalculateController::countUploadMem($r->id)];
$mem_yest +=[$r->id => CalculateController::countUploadMemYest($r->id)];
$mem_can += [$r->id => CalculateController::getCan($r->id, Auth::user()->id)];

?>
  <li class="nav-item">
    @if($n_rs == 0)
    <a class="nav-link active animated" id="link-rs{{ $r->id }}" data-toggle="pill" href="#rs{{ $r->id }}" role="tab" aria-controls="rs{{ $r->id }}" aria-selected="true"><span style="color:#20c997">{{RSController::getGroupName($r->id_group)}}</span>, {{ $r->name }}</a>
    @else
    <a class="nav-link animated" id="link-rs{{ $r->id }}" data-toggle="pill" href="#rs{{ $r->id }}" role="tab" aria-controls="rs{{ $r->id }}" aria-selected="false"><span style="color:#20c997">{{RSController::getGroupName($r->id_group)}}</span>, {{ $r->name }}</a>
    @endif
  </li>
@endforeach
</ul>
<div class="tab-content" id="pills-tabContent">
@foreach($rs as $n_rs => $r)
@if($n_rs == 0)
  <div class="tab-pane fade show active" id="rs{{ $r->id }}" role="tabpanel" aria-labelledby="link-rs{{ $r->id }}">
    @else
    <div class="tab-pane fade" id="rs{{ $r->id }}" role="tabpanel" aria-labelledby="link-rs{{ $r->id }}">
    @endif

    <div class="flex-div">
      <div class="flex-row">
        <div class="wid-small flex-style mr-rg">
          <div class="blur-block"></div>
          <div class="flex-title">{{$r->name}}, <span style="color:#20c997">{{RSController::getGroupName($r->id_group)}}</span></div>
          <div class="flex-content ">
            <? $done_lesson = CalculateController::doneLessonsTeacher($r->id);?>


            <div class="progress-rs" >

            <p><i class="fas fa-walking"></i> Пар осталось: {{$r->total_lesson + ($r->lesson_subgroup * 2) - $done_lesson}}</p>
            <div class="progress">
              <div class="progress-bar bg-white prog-cnt green" role="progressbar" data-toggle="tooltip" data-html="true" title="{{round(CalculateController::scoreOneLesson($r->id), 1)}} баллов за одну пару" style="width: {{CalculateController::getPercentDoneLessonTEACH($r->id)}}%; border-radius:10px;" aria-valuenow="{{$done_lesson}}" aria-valuemin="0" aria-valuemax="100">@if($done_lesson > 0) {{$done_lesson}} @endif</div>
              <span class="progress-total" >{{$r->total_lesson + ($r->lesson_subgroup * 2)}}</span>
          </div>



          @if(count($r->rstasks) > 0)

          @foreach($r->rstasks as $task)


          <p><i class="fas fa-paste"></i> {{$task->name_task}}, осталось: {{CalculateController::getStillTaskType($r->id, $task->id)}} из {{$task->total_task}} </p>


        <div class="progress">
            <? $one_task_percent = 100 /$task->total_task;  ?>

            @foreach($r->infotasks as $itask)
            @if($itask->type == "task" && $itask->id_info_task == $task->id && $itask->date_end != NULL &&  $itask->date_start != NULL)

            <?$date_end = substr($itask->date_end, -4).substr($itask->date_end, 3, 2).substr($itask->date_end, 0, 2);
              $date_start = substr($itask->date_start, -4).substr($itask->date_start, 3, 2).substr($itask->date_start, 0, 2);
              $today = date("Ymd");?>

            <div class="progress-bar bg-white prog-cnt
            @if($date_end >= $today && $date_start <= $today)
            yellow
            @endif
            @if($date_end <= $today)
            green
            @endif
            " role="progressbar" style="width:{{$one_task_percent}}%;" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-html="true" title="{{CalculateController::scoreOneTask($r->id, $itask->id)}} баллов">{{$itask->number}}</div>

            @endif
            @endforeach
          </div>


          @endforeach
          @endif


          @if($r->total_test > 0)
          <p><i class="fas fa-tasks itog"></i> Тестов осталось: {{CalculateController::getStillTest($r->id)}} из {{$r->total_test}} </p>
          <div class="progress">
            <? $one_test_percent = 100 /$r->total_test;  ?>

            @foreach($r->infotasks as $test)
            @if($test->type == "test" && $test->date_end != NULL && $test->date_start != NULL )

            <?$date_end = substr($test->date_end, -4).substr($test->date_end, 3, 2).substr($test->date_end, 0, 2);
              $date_start = substr($test->date_start, -4).substr($test->date_start, 3, 2).substr($test->date_start, 0, 2);
              $today = date("Ymd");?>


            @if($test->date_start != NULL && CalculateController::moreTodayDate($test->date_start))
            <div class="progress-bar bg-white prog-cnt
            @if($date_end >= $today && $date_start <= $today)
            yellow
            @endif
            @if($date_end <= $today)
            green
            @endif
            " role="progressbar" style="width:{{$one_test_percent}}%;" aria-valuemin="0" data-toggle="tooltip" data-html="true" title="{{CalculateController::scoreOneTest($r->id, $test->id)}} баллов" aria-valuemax="100">{{$test->number}}</div>
            @endif
            @endif
            @endforeach
          </div>

          @endif

          @if($r->total_main_test > 0)
          <p><i class="fas fa-tasks itog"></i>  Итоговых тестов осталось: {{CalculateController::getStillMainTest($r->id)}} из {{$r->total_main_test}} </p>
          <div class="progress">
            <? $one_test_percent = 100 /$r->total_main_test;  ?>

            @foreach($r->infotasks as $mtest)
            @if($mtest->type == "main_test" && $mtest->date_end != NULL && $mtest->date_start != NULL )

            <?$date_end = substr($mtest->date_end, -4).substr($mtest->date_end, 3, 2).substr($mtest->date_end, 0, 2);
              $date_start = substr($mtest->date_start, -4).substr($mtest->date_start, 3, 2).substr($mtest->date_start, 0, 2);
              $today = date("Ymd");?>


            @if($mtest->date_start != NULL && CalculateController::moreTodayDate($mtest->date_start))
            <div class="progress-bar bg-white prog-cnt
            @if($date_end >= $today && $date_start <= $today)
            yellow
            @endif
            @if($date_end <= $today)
            green
            @endif
            " role="progressbar" style="width:{{$one_test_percent}}%;" aria-valuemin="0" data-toggle="tooltip" data-html="true" title="{{CalculateController::scoreOneTest($r->id, $mtest->id)}}" aria-valuemax="100">{{$mtest->number}}</div>
            @endif
            @endif
            @endforeach
          </div>

          @endif










            </div>


            @if($mem_can[$r->id] == 1)
            <div class="mem-done{{$r->id}}">
            <b-btn v-b-toggle.collapsemem class="m-1 mem-up-sp mem-done{{$r->id}}">😃 Загрузить мем</b-btn>
            <b-collapse id="collapsemem" style="display:none;">
              <div class="memday">
                <p>


                <input class="mem_score" id="mem_score{{$r->id}}" type="number" data-toggle="tooltip" data-html="true" title="Max = 15" max="15" placeholder="Балл"/>
                <span class="file-input btn btn-mem btn-file" data-toggle="tooltip" data-id-rs="{{$r->id}}" data-html="true" title="Выбрать мем"><i class="fas fa-image"></i>
                  <input id="mem{{$r->id}}" class="mem-input" type="file" accept="image/x-png,image/gif,image/jpeg">
                </span>
                <input id="mem_rs" type="hidden" value="{{$r->id}}"/>
                <input id="mem_user" type="hidden" value="{{Auth::user()->id}}"/>
                <a data-toggle="tooltip" data-html="true" title="Загрузить" class="save_mem" data-id-rs="{{$r->id}}"><i style="color:#fff;" class="fas fa-save"></i></a></p>


                <b-btn v-b-toggle.collapseinfo class="m-1" style="background:transparent; border:none;padding: 0px !important;border-bottom: 1px dashed;"><i class="fas fa-question" style=""></i> Подробнее, в чем прикол</b-btn>
                <b-collapse id="collapseinfo" style="display:none;">
                  Загруженные вами мемасы выбираются рандомно. Если выпадает ваш мем вы можете получить +10 или больше, максимум 30 баллов.
                  <ul>
                    <li style="color:#ffc83d;">Мемы можно заливать только один раз в день!</li>

                    <li>Поле "Балл" можно оставить пустым и если выпадет ваш мем, вы получите стандартно +10 баллов</li>
                    <li>Если вы поставили балл, например 10, и ваш мем не выигрывает у вас -10 баллов</li>
                    <li>Если вы поставили балл и выпал ваш мем, то удвоите поставленные баллы +20 баллов</li>
                    <li>Поставить вы можете MAX = 15, т.е. максимум вы можете получить 30 баллов (15 * 2)</li>
                    <li>Баллы ставятся только, если участвуют более 5 человек</li>
                    <li>Сегодня загружаете, завтра выпадает мем. Рандом выбирает только из вчерашних загрузок, поэтому нужно/можно грузить мемчики каждый день :) </li>
                    <li style="color:#ffc83d;">Грузить мемесы могут только, те кто аттестован хотя бы на 3</li>
                  </ul>
                </b-collapse>

              </div>


            </b-collapse>
          </div>
            @endif



          </div>

        </div>


        <div class="wid-but flex-style">
          <div class="blur-block"></div>
          <div class="flex-title">Опции</div>
          <div class="flex-content">
            @if($r->type_rs == 1)
            <p><a href="{!! route('journal.five', ['id' => $r->id]) !!}"><i class="fas fa-th"></i> <span class="left-icon">Журнал</span></a></p>
            @else
            <p><a href="{!! route('journal', ['id' => $r->id]) !!}"><i class="fas fa-th"></i> <span class="left-icon">Журнал</span></a></p>
            @endif

            @if(count($r->rstasks) > 0)
            <p><a href="{!! route('task.option', ['id' => $r->id]) !!}"><i style="color: #ffc107;background: rgba(255, 255, 255, 0.2);padding: 13px;max-width: 38px;border-radius: 5px 0 0 5px;" class="fas fa-cog"></i> <span class="left-icon">Параметры работ</span></a></p>
            @endif


            @if($r->type_rs == 1)
            <p><a href="{!! route('rs.editfive', ['id' => $r->id]) !!}"><i class="fas fa-edit"></i> <span class="left-icon">Редактирование ПС</span></a></p>
            @else
            <p><a href="{!! route('top', ['id' => $r->id]) !!}"><i style="color: rgb(236, 107, 79);background: rgba(255, 255, 255, 0.2);padding: 13px;max-width: 38px;border-radius: 5px 0 0 5px;" class="fas fa-trophy"></i> <span class="left-icon">ТОПы</span></a></p>

            <p><a href="{!! route('rs.edit', ['id' => $r->id]) !!}"><i class="fas fa-edit"></i> <span class="left-icon">Редактирование БРС</span></a></p>
            @endif


            <p><a href="{!! route('import_ktp') !!}"><i class="fas fa-download"></i> <span class="left-icon">Загрузить КТП</span></a></p>

            @if($r->ktp->count() > 0)
            <p><a href="javascript:;" data-rs="{{$r->id}}" class="delKTP" data-rs-del="{{$r->id}}"><i class="fas fa-trash"></i> <span class="left-icon">Удалить КТП</span></a></p>
            @endif


            <p><a href="javascript:;" class="delRS" data-rs="{{$r->id}}" ><i class="fas fa-trash"></i>  <span class="left-icon">Удалить БРС</span></a></p>

          </div>
        </div>

        <?
        $reminders = CalculateController::getReminders(Auth::user()->id);

        ?>

        <div class="wid-but flex-style mem-flex">
          <div class="blur-block"></div>
          <div class="flex-title">Мемас дня из {{$mem_yest[$r->id]}} шт.</div>
          <div class="flex-content">
            @if(isset($mem_winer[$r->id]['path']))
            <img class="mem" src="/public/img/{{$mem_winer[$r->id]['path']}}">
            <p class="winmem"><span class="name-mem-win"> {{ CalculateController::getFIO($mem_winer[$r->id]['id_user']) }}</span>
            <span class="score-mem-win">  @if($mem_winer[$r->id]['score'] > 0)
              +{{$mem_winer[$r->id]['score']}} <span style="color:#c5a809">ББ</span>
              @endif
            </span>
          </p>

          <p>Претендентов на мем дня было: {{$mem_yest[$r->id]}} шт.
            @if($mem_yest[$r->id] == 0)
            😡
          @endif
          @if($mem_yest[$r->id] == 1)
           😢
        @endif
        @if($mem_yest[$r->id] == 2)
         😔
      @endif
      @if($mem_yest[$r->id] == 3)
       😒
    @endif
    @if($mem_yest[$r->id] == 4)
      😐
  @endif
  @if($mem_yest[$r->id] == 5)
     😏
@endif
@if($mem_yest[$r->id] > 5)
   😊
@endif
      </p>

      <p style="border-top: 1px dashed rgba(255,255,255,0.3);padding-top: 12px;">Сегодня мемов загружено: {{$mem_count[$r->id]}} шт.
      @if($mem_count[$r->id] < 5)
      <br /><span  data-toggle="tooltip" data-html="true" title="Чтобы студент, чей мем выпадет, получил баллы.">Нужно еще {{5 - $mem_count[$r->id] }} шт.</span>
    @endif</p>

            @endif


          </div>
        </div>






    </div>







    </div>



  </div>

@endforeach

</div>

<? $my_reminders = CalculateController::getMyReminders(Auth::user()->id); $counter = 1;?>
    @if(count($my_reminders) > 0)
<div class="flex-style mr-rg myreminders" >
  <div class="blur-block"></div>
  <div class="flex-title">Напоминания</div>
  <div class="flex-content">
    <b-tabs class="rem-cat" pills>
    <b-tab title="Мне">
      @if(gettype($reminders) != 'integer')
      <b-tabs end pills>
      @foreach($reminders as $i_r => $reminder)
      <b-tab title="{{$i_r+1}}">
      <div class="grid-reminder" style="border: 1px dashed rgba(32, 201, 151,0.4);">
        <div class="deadline">от: {{$reminder->date_start}} по: {{$reminder->date_end}}</div>
        <div class="from">
          <div class="name">от: {{CalculateController::getFIO($reminder->id_from)}}</div>
          <div class="seen"><i data-atr="seen" data-chan="seen{{$reminder->id}}" data-data="{{$reminder->seen}}" data-id="{{$reminder->id}}"
            @if($reminder->seen == 1)
            style="color: #20c997;"
            @endif
          class="fas fa-eye"></i></div>

          <div class="done-rem"><i data-atr="done-rem" data-chan="done{{$reminder->id}}" data-data="{{$reminder->done}}" data-id="{{$reminder->id}}"
            @if($reminder->done == 1)
            style="color: #20c997;"
            @endif
          class="fas fa-check-circle"></i></div>

          </div>

        <div class="thene">{{$reminder->theme}}</div>
        <div class="info">{{$reminder->short_info}}</div>
        @if($reminder->full_info != "Подробное описание")
        <div class="info">{{$reminder->full_info}}</div>
        @endif
      </div>
      </b-tab>
      @endforeach
      </b-tabs>
      @endif
    </b-tab>


    <b-tab title="От меня">
      <b-tabs end pills>
    @foreach($my_reminders as $mi_r => $mreminder)
    <b-tab title="{{$counter++}}">
    <div class="grid-reminder" style="border: 1px dashed rgba(255,255,255,0.55);">
      <? $created = $mreminder->created_at;
         $created = substr($created, 8, 2).'.'.substr($created, 5, 2).'.'.substr($created, 0, 4);
      ?>
      <div class="deadline" style="border-bottom: 1px dashed rgba(255,255,255,0.2);padding-bottom: 5px;margin-bottom: 10px;">Создано: {{$created}}</div>
      <div class="deadline">от: {{$mreminder->date_start}} по: {{$mreminder->date_end}}</div>
      <div class="from">
        <div class="name">кому:
          @if(User::getIdGroup($mreminder->id_whom) != NULL)
           {{RSController::getGroupName(User::getIdGroup($mreminder->id_whom))}}
           @else
           {{CalculateController::getFIO($mreminder->id_whom)}}
          @endif
        </div>
      </div>

      <div class="thene">{{$mreminder->theme}}</div>
      <div class="info">{{$mreminder->short_info}}</div>
      @if($mreminder->full_info != "Подробное описание")
      <div class="info">{{$mreminder->full_info}}</div>
      @endif
    </div>
    </b-tab>
    @endforeach
    </b-tabs>
    </b-tab>
    </b-tabs>



  </div>
</div>



@endif

</div>
@endsection
@section('js')
<script>


  $(document).ready(function () {
    var id_rs_b;



    $('.btn-mem').on('click', function () {
       id_rs_b = $(this).attr('data-id-rs');

       $('#mem' + id_rs_b).on('input', function() {
         $('.btn-mem').css('background', '#0063cc');

   });

    });




    $('.save_mem').on('click', function () {

      var id_rs = $(this).attr('data-id-rs');
      var formData = new FormData();
      const fileInput = document.querySelector( '#mem' + id_rs );
      formData.append("mem",  fileInput.files[0]);
      formData.append( 'id_user', $('#mem_user').val());
      formData.append( 'id_rs', id_rs);
      formData.append( 'score', $('#mem_score'+ id_rs).val());
      console.log(fileInput.files[0]);

      let config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      }


      axios({
        method: 'post',
        url: '/api/save-mem',
        config: config,
        data: formData
      }).then(function (response){
        $('.mem-done'+ id_rs).css('display', 'none');

        $('.save-show').addClass('showw');
        $('.btn-mem').css('background', 'rgba(0,0,0,0.5)');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);
      });

    });


    $('.delRS').on('click', function () {

      if(confirm("Вы действительно хотите удалить эту БРС?"))
      {
      var id = $(this).attr("data-rs");
      axios({
        method: 'post',
        url: '/api/del-rs',
        data: {
          id_rs: id,
        }
      })
      .then(function (response) {
        location.reload();
      });
    }


    });

    $('[data-atr="seen"]').on('click', function () {
      var id_rem = $(this).attr('data-id');
      var status = 0;
      if($(this).attr('data-data') != 1)
      {status = 1}

      axios({
        method: 'post',
        url: '/api/seen-rem',
        data: {
          id: id_rem,
          status: status
        }
      })
      .then(function (response) {
        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        if(status == 1)
        {
          $('[data-chan="seen'+id_rem+'"]').css('color','#20c997');
          $('[data-chan="seen'+id_rem+'"]').attr('data-data', 1);
        }
        else
        {
          $('[data-chan="seen'+id_rem+'"]').css('color','#fff');
          $('[data-chan="seen'+id_rem+'"]').attr('data-data', 0);
        }

      });

    });

    $('[data-atr="done-rem"]').on('click', function () {
      var id_rem = $(this).attr('data-id');
      var status = 0;
      if($(this).attr('data-data') != 1)
      {status = 1}

      axios({
        method: 'post',
        url: '/api/done-rem',
        data: {
          id: id_rem,
          status: status
        }
      })
      .then(function (response) {
        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        if(status == 1)
        {
          $('[data-chan="done'+id_rem+'"]').css('color','#20c997');
          $('[data-chan="done'+id_rem+'"]').attr('data-data', '1');

        }
        else
        {
          $('[data-chan="done'+id_rem+'"]').css('color','#fff');
          $('[data-chan="done'+id_rem+'"]').attr('data-data', '0');
        }



      });

    });



    $('.delKTP').on('click', function () {

      if(confirm("Вы действительно хотите удалить КТП для данной БРС?"))
      {
      var id = $(this).attr("data-rs");
      axios({
        method: 'post',
        url: '/api/del-ktp',
        data: {
          id_rs: id,
        }
      })
      .then(function (response) {
        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        $('[data-rs-del="'+id+'"]').css('display','none');
      });
    }


    });

  });



</script>

@endsection
