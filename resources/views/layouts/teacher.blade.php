<?
use \App\Http\Controllers\Teacher\AccountController;
use \App\Http\Controllers\Teacher\RSController;
$rss = AccountController::getRS();
$teachers = AccountController::getTeachers();
$groups = AccountController::getGroups();
$students = AccountController::getStudents();
$id_from = Auth::user()->id;
$hasclass = AccountController::hasClassroom($id_from);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/new--style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <link rel="shortcut icon" href="{{ asset('img/monetka.ico') }}" type="image/x-icon">



    @yield('style')

  </head>

  <body>
    <div class="save-show">
      <i class="fas fa-save"></i>
    </div>
    <div class="send-show">
      <i class="fas fa-envelope"></i>
    </div>
@if(strpos(Request::url(), "journal") !== false || strpos(Request::url(), "classroom") !== false)
    <div id="p-pre" class="preloader">
      <div class="loader">
        <div class="pre_one"></div>
        <div class="pre_two"></div>
        <div class="pre_three"></div>
      </div>
    </div>
@endif

    <div id="app">
    <nav class="navbar navbar-dark fixed-top bg-dark p-0 shadow d-flex">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ route('teacher') }}">Br-system</a>

      <ul class="nav">




  <li class="nav-item">
    <a class="nav-link" href="{{ route('faq') }}"><i class="fas fa-question-circle"></i> Как тут работать :)</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" id="show-modal-reminder"><i style="color:goldenrod;" class="fas fa-envelope"></i> Отправить уведомление</a>
  </li>

  @if($hasclass == 1)
  <li class="nav-item">
    <a class="nav-link" href="{{ route('classroom') }}"><i style="color:#33a06e;font-size: 18px;margin-right: 7px;" class="fas fa-users"></i> Мой класс</a>
  </li>
  @endif


  <li class="nav-item">
    <a class="nav-link" href="{{ route('teacher') }}"><i class="fas fa-home"></i> В кабинет</a>
  </li>

          <a  class="nav-link" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>
              Выйти
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>


</ul>
    </nav>
    <div class="mobile-menu">
      <a class="mobile-menu-button">
        <span class="mobile-lines"></span>
      </a>
      <nav class="mobile-menu-nav">
        <a class="mobile-menu-link" href="{{ route('teacher') }}">
            <span data-feather="file"></span>
            <i class="fas fa-home"></i> Кабинет
        </a>
        <a class="mobile-menu-link" href="{{ route('classroom') }}">
          <i style="color:#33a06e;font-size: 18px;margin-right: 7px;" class="fas fa-users"></i> Мой класс
        </a>
        <div class="mobile-menu-links">

        @foreach($rss as $r)

          <b-btn v-b-toggle.accordion{{$r->id}} style="padding: 10px 30px;display: block;"><i class="fas fa-graduation-cap"></i> <span class="left-icon">{{$r->name}}, {{RSController::getGroupNameShort($r->id_group)}}</span>  <i class="fas fa-angle-down"></i></b-btn>

          <b-collapse id="accordion{{$r->id}}" style="display:none;" accordion="my-accordion" role="tabpanel">
            <div class="links-rs">

              @if($r->type_rs == 1)
              <a href="{!! route('journal.five', ['id' => $r->id]) !!}"><i style="background: transparent;padding: 7px 0px;" class="fas fa-th"></i> <span class="left-icon">Журнал</span></a>
              @else
              <a href="{!! route('journal', ['id' => $r->id]) !!}"><i style="background: transparent;padding: 7px 0px;" class="fas fa-th"></i> <span class="left-icon">Журнал</span></a>
              @endif

              <a href="{!! route('task.option', ['id' => $r->id]) !!}"><i style="color: #ffc107;background: transparent;padding: 7px 0px;max-width: 38px;" class="fas fa-cog"></i> <span class="left-icon">Параметры работ</span></a>

              <a href="{!! route('top', ['id' => $r->id]) !!}"><i style="color: rgb(236, 107, 79);background: transparent;padding: 7px 0px;max-width: 38px;" class="fas fa-trophy"></i> <span class="left-icon">ТОПы</span></a>

            </div>

          </b-collapse>


        @endforeach
        </div>


        <a class="mobile-menu-link" href="{{ route('update.userdata') }}"><i style="color:#ffdb70;font-size: 18px;margin-right: 7px;" class="fas fa-cog"></i> Личные данные
      </a>

      </nav>

      <div class="mobile-menu-overlay">

      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-dark  sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="edit-user-data" href="{{ route('update.userdata') }}"><i class="fas fa-cog"></i></a>
                <a class="nav-link text-light astronaut" style="padding-bottom: 10px;" href="{{ route('teacher') }}">
              @if(Auth::user()->img == NULL)
              <i class="fas fa-user-astronaut"></i>
              @else
              <div class="back-ava">
                <div class="avatar-menu">
              <img class="avatar" src="/public/img/{{Auth::user()->img}}"/>
              </div>
            </div>
              @endif
              <span class="date-day">
                <?php
                date_default_timezone_set('Europe/Moscow');
                $days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' ); ?>

                <span class="day-style">{{date("d")}}.{{date("m")}}</span>
                <br/>
                <span class="day-name">{{$days[date( 'N' )]}}</span>


              </span>
            </a>

          </li>
              <li class="nav-item">
                <a class="nav-link text-light" href="{{ route('teacher') }}">
                  <span data-feather="file"></span>
                  <i class="fas fa-home"></i> Кабинет
                </a>
              </li>






              <li class="nav-item ">
                <a class="nav-link text-light" href="{{ route('rs.create') }}" >
                  <span data-feather="file"></span>
                  <i class="fas fa-plus-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="Заполнение параметров для учета успеваемости по 100-балльной системе"></i> Создание БРС
                </a>
              </li>

              <li class="nav-item ">
                <a class="nav-link text-light" href="{{ route('rs.create.five') }}" >
                  <span data-feather="file"></span>
                  <i class="fas fa-plus-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="Заполнение параметров для учета успеваемости по 5-балльной системе"></i> Создание ПС
                </a>
              </li>



            </ul>

            @foreach($rss as $r)




              <p><b-btn v-b-toggle.accordion{{$r->id}} style="padding: 0.5rem 1rem;display: block;"><i class="fas fa-graduation-cap"></i> {{$r->name}}, {{RSController::getGroupNameShort($r->id_group)}}  <i class="fas fa-angle-down"></i></b-btn></p>

              <b-collapse id="accordion{{$r->id}}" style="display:none;" accordion="my-accordion" role="tabpanel">
                <div class="links-rs">




                  @if($r->type_rs == 1)
                  <p><a href="{!! route('journal.five', ['id' => $r->id]) !!}"><i class="fas fa-th"></i> <span class="left-icon">Журнал</span></a></p>
                  @else
                  <p><a href="{!! route('journal', ['id' => $r->id]) !!}"><i class="fas fa-th"></i> <span class="left-icon">Журнал</span></a></p>
                  @endif

                  <p><a href="{!! route('task.option', ['id' => $r->id]) !!}"><i style="color: #ffc107;background: transparent;padding: 7px 0px;max-width: 38px;" class="fas fa-cog"></i> <span class="left-icon">Параметры работ</span></a></p>

                  <p><a href="{!! route('top', ['id' => $r->id]) !!}"><i style="color: rgb(236, 107, 79);background: transparent;padding: 7px 0px;max-width: 38px;" class="fas fa-trophy"></i> <span class="left-icon">ТОПы</span></a></p>

                  @if($r->type_rs == 1)
                  <p><a href="{!! route('rs.editfive', ['id' => $r->id]) !!}"><i class="fas fa-edit"></i> Редактирование ПС</span></a></p>
                  @else
                  <p><a href="{!! route('rs.edit', ['id' => $r->id]) !!}"><i class="fas fa-edit"></i> <span class="left-icon">Редактирование БРС</span></a></p>
                  @endif







                </div>

              </b-collapse>


            @endforeach
            <hr />

            @if(Auth::user()->status == 99)
            <div class="admin-btn">

            <p><a href="{{ route('inst-param') }}"><i class="fas fa-plus-circle"></i> <span class="left-icon">Добавление дисциплин</span></a></p>

            <p><a href="{{ route('gen.pass') }}"><i class="fas fa-plus-circle"></i> <span class="left-icon">Логины, пароли</span></a></p>

            <p><a href="{{ route('import_user') }}"><i class="fas fa-plus-circle"></i> <span class="left-icon">Импорт студентов</span></a></p>

            </div>

            @endif


          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          @yield('content')
        </main>

      </div>
    </div>
    </div>

    <div id="reminder" class="reminder-modal">
      <div class="back-reminder">
        <p class="head-rem">
          Отправить уведомление
        </p>

      <p><input style="color:#fff;" id="theme" type="text" name="theme" required placeholder="Тема"></p>

      <p><input style="color:#fff;" placeholder="Дата начала события" type='text' class="datepicker-here" data-position="right top" name="date" id="date_rem_start"/></p>

      <p><input style="color:#fff;" placeholder="Дата конца события" type='text' class="datepicker-here" data-position="right top" name="date" id="date_rem_end"/></p>

      <div class="grid-rem">
        <textarea id="info" type="text" name="info" placeholder="Краткая информация"></textarea>

      <div id="full_info" name="full_info" class="summernote">Подробное описание</div>
      </div>

      <select multiple="multiple" id="whom" name="whom[]" required placeholder="Получатели">

        <optgroup label='Преподаватели'>
          @foreach($teachers as $teacher)
          <option value='{{$teacher->id}}'>{{$teacher->surname." ".mb_substr($teacher->name, 0, 1).".".mb_substr($teacher->patronymic, 0, 1)."."}}</option>
          @endforeach

        </optgroup>



        @foreach($groups as $group)
        <optgroup label='{{RSController::getGroupNameShort($group->id)}}'>

          @foreach($students as $student)
          @if($student->id_group == $group->id)

          <option value='{{$student->id}}'>{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</option>

          @endif
          @endforeach


        </optgroup>
        @endforeach

      </select>
     <p><button id="add_reminder" class="save" type="submit">Отправить</button></p>

    </div>
</div>


    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/jquery.multi-select.js') }}" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js" defer></script>
    <script src="{{ asset('js/lang-summernote.js') }}" defer></script>
    <script src="{{ asset('js/slick.min.js') }}" defer></script>
    <script>



    function preloader(){
     $(() => {
       let p = $('#p-pre');

       p.css('opacity', 0);

       setInterval(
         () => p.remove(),
         parseInt(p.css('--duration')) * 1000

       );
     });
    }

    preloader();


    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    $(function () {
      $('[data-toggle="popover"]').popover()
    })

      $(document).ready(function () {

        function mobileMenu(selector) {
          let menu = $(selector);
          let button = menu.find('.mobile-menu-button');
          let links = menu.find('.mobile-menu-link');
          let overlay = menu.find('.mobile-menu-overlay');

          button.on('click', (e) => {
            e.preventDefault();
            toggleMenu();
          });

          links.on('click', () => toggleMenu());
          overlay.on('click', () => toggleMenu());

          function toggleMenu() {
            menu.toggleClass('mobile-menu-active');

            if(menu.hasClass('mobile-menu-active'))
            {
              $('body').css('overflow', 'hidden');
            }
            else {
              $('body').css('overflow', 'visible');
            }
          }
        }
        mobileMenu('.mobile-menu');



          $(document).on('click', '#add_reminder', function(){

          var whom = $('#whom').val();
          var str_whom = whom.join();
          var full_info = $('#full_info').summernote('code');

          axios({
            method: 'post',
            url: '/api/add-reminder',
            data: {
              whoms: str_whom,
              from:<? echo $id_from; ?>,
              theme: $('#theme').val(),
              info: $('#info').val(),
              date_end: $('#date_rem_end').val(),
              date_start: $('#date_rem_start').val(),
              full_info: full_info

            }
          })
          .then(function (response) {
            $('#reminder').removeClass('reminder-show');

            $('.save-show').addClass('showw');
            setTimeout(function () {
                $('.save-show').removeClass('showw');
            }, 1000);

          });

        });



        $('#full_info').summernote({
          tabsize: 2,
          height: 100,
          lang: 'ru-RU',
          toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['color']],
    ['para', ['ul', 'ol']]
  ]
        });



        $(document).on('click', '#show-modal-reminder', function(){

          if(!$('#reminder').hasClass('reminder-show'))
          {
            $('#reminder').addClass('reminder-show');
          }
          else
          {
            $('#reminder').removeClass('reminder-show');
          }


        });


        /*
        $(document).mouseup(function (e){ // событие клика по веб-документу
          var modal = $('.back-reminder');

          if (!modal.is(e.target) && modal.has(e.target).length === 0 && !$('.datepicker-panel').is(e.target))
          {
            $('#reminder').removeClass('reminder-show');
          }
        });
        */




        $('#whom').multiSelect({ selectableOptgroup: true });


        $('#date_rem_start').datepicker({
        format: 'dd.mm.yyyy',
        language: 'ru-RU'
        });

        $('#date_rem_end').datepicker({
        format: 'dd.mm.yyyy',
        language: 'ru-RU'
        });



        $(document).on('click', '.faq', function(){

          if(!$(this).hasClass('zoom'))
          {
            $(this).addClass('zoom');
          }
          else
          {
            $(this).removeClass('zoom');
          }

        });


        $(document).on('click', '#info_work img', function(){

          if(!$(this).hasClass('zoom'))
          {
            $(this).addClass('zoom');
          }
          else
          {
            $(this).removeClass('zoom');
          }

        });

        $(document).on('click', '.mem', function(){

          if(!$(this).hasClass('zoom'))
          {
            $(this).addClass('zoom');
          }
          else
          {
            $(this).removeClass('zoom');
          }

        });

        $(document).on('click', '.body-task img', function(){

          if(!$(this).hasClass('zoom'))
          {
            $(this).addClass('zoom');
          }
          else
          {
            $(this).removeClass('zoom');
          }

        });

        $(document).on('click', '.ava-top', function(){

          if(!$(this).hasClass('zoom-top'))
          {
            $(this).addClass('zoom-top');
          }
          else
          {
            $(this).removeClass('zoom-top');
          }

        });


        function hide(){
          popup.css({
          top: -300,
          left: -300
          });
        }

        $('[data-animate="animated"]').addClass('animated bounceIn');
        $('[data-animate="animated-r"]').addClass('animated fadeIn');

      });
      </script>




    @yield('js')
    @include('inc.messages')

  </body>
</html>
