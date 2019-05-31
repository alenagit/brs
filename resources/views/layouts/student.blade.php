<?
use \App\Http\Controllers\Teacher\AccountController;
use \App\Http\Controllers\Teacher\RSController;
$rss = AccountController::getStudentRS();

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
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <link rel="shortcut icon" href="{{ asset('img/monetka.ico') }}" type="image/x-icon">



    @yield('style')

  </head>

  <body>
@if(strpos(Request::url(), "journal") !== false)
    <div id="p-pre" class="preloader">
      <div class="loader">
        <div class="pre_one"></div>
        <div class="pre_two"></div>
        <div class="pre_three"></div>
      </div>
    </div>
@endif

<div class="save-show">
  <i class="fas fa-save"></i>
</div>
<div class="send-show">
  <i class="fas fa-envelope"></i>
</div>



    <div id="app">
    <nav class="navbar navbar-dark fixed-top bg-dark p-0 shadow d-flex">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ route('student') }}">Br-system</a>



      <ul class="nav">
  <li class="nav-item">
    <a class="nav-link" href="{{ route('student') }}"><i class="fas fa-home"></i> В кабинет</a>
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
        <a class="mobile-menu-link" href="{{ route('student') }}"><span data-feather="file"></span><i class="fas fa-home"></i> Кабинет</a>
        <div class="mobile-menu-links">
          @foreach($rss as $rs)

          <a class="mobile-menu-link" href="{!! route('discipline', ['id' => $rs->id]) !!}"><span data-feather="file"></span><i class="fas fa-graduation-cap"></i> {{$rs->name}}</a>

          @endforeach

        </div>
        <a class="mobile-menu-link" href="{{ route('update.userdata.stud') }}"><i style="color:#ffdb70;font-size: 18px;margin-right: 7px;" class="fas fa-cog"></i> Личные данные</a>

      </nav>

      <div class="mobile-menu-overlay">

      </div>
    </div>


    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-dark  sidebar">
          <div class="sidebar-sticky ">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="edit-user-data" href="{{ route('update.userdata.stud') }}"><i class="fas fa-cog"></i></a>
                <a class="nav-link text-light astronaut" href="{{ route('student') }}">
                  @if(Auth::user()->img == NULL)
                  <i class="fas fa-user-astronaut"></i>
                  @else
                  <div class="back-ava">
                    <div class="avatar-menu" >
                  <img class="avatar" src="/public/img/{{Auth::user()->img}}"/>
                  </div>
                </div>
                  @endif
            </a>
          </li>
              <li class="nav-item">
                <a class="nav-link text-light" href="{{ route('student') }}">
                  <span data-feather="file"></span>
                  <i class="fas fa-home"></i> Кабинет
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          @yield('content')
        </main>

      </div>
    </div>
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
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
            $('.up-mem-stud').css('display', 'none');
            $('.save-show').addClass('showw');
            setTimeout(function () {
                $('.save-show').removeClass('showw');
            }, 1000);
          });

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

        $(document).on('click', '.dis-student img', function(){

          if(!$(this).hasClass('zoom'))
          {
            $(this).addClass('zoom');
          }
          else
          {
            $(this).removeClass('zoom');
          }

        });


        function hide(){
          popup.css({
          top: -300,
          left: -300
          });
        }
      });
      </script>




    @yield('js')
    @include('inc.messages')

  </body>
</html>
