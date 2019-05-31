

$(document).ready(function () {



  var id = '';
  var name = '';
  var info = '';
  var total_score = '';
  var date_end = '';
  var date_start = '';
  var pattern = '';
  var necessary = '';

  var open_bb = 0;
  var open_option = 0;
  var open_journal = 0;


  //-------------------------------------------------------------------






  //храню тут предыдущее значение в ячейке
  var lesson_old = 0;
  var popup_info = $('#form-comment-info');


  $('#update-progress___BV_tab_button__').html('<i class="fas fa-graduation-cap"></i> Успеваемость');
  $('#lesson-tab___BV_tab_button__').html('<i class="fas fa-walking"></i> Посещаемость');
  $('#test___BV_tab_button__').html('<i class="fas fa-tasks"></i> Тесты');
  $('#main_test___BV_tab_button__').html('<i class="fas fa-tasks itog"></i> Итоговые тесты');
  $('#bonuse___BV_tab_button__').html('<i class="fas fa-star"></i> Бонусные баллы');
  $('#ktp___BV_tab_button__').html('<i class="fas fa-calendar-check"></i> КТП');
  $('#jurnal___BV_tab_button__').html('<i class="fas fa-calendar-alt"></i> Журнал');
  $('#options___BV_tab_button__').html('<i class="fas fa-cogs"></i> Параметры работ');


  $('#jurnal___BV_tab_button__').on('click', function () {

    if(open_journal == 0)
    {
      $url_paper = $('#url-paper').val();

      axios.get($url_paper).then(function (response) {
       $("#paper-ajax").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();
     });
     open_journal++;
    }

  });



    $url = $('#url-lesson').val();

    axios.get($url).then(function (response) {
    $("#lesson-ajax").html(response.data);
    $('[data-toggle="tooltip"]').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip();
    });

    $url_test = $('#url-test').val();

    axios.get($url_test).then(function (response) {
    $("#test-ajax").html(response.data);
    $('[data-toggle="tooltip"]').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip();
    });

    $url_main_test = $('#url-main-test').val();

    axios.get($url_main_test).then(function (response) {
    $("#main-test-ajax").html(response.data);
    $('[data-toggle="tooltip"]').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip();
    });





  $('#bonuse___BV_tab_button__').on('click', function () {

    if(open_bb == 0)
    {
      $url_bonuses = $('#url-bonuses').val();

      axios.get($url_bonuses).then(function (response) {
       $("#bonuse-table").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();
     });

     open_bb++;
    }



  });


  $(document).on('click', '#task', function(){

    //обновление данных внутри полей, после выбор другой работы


      id = $("#task").val();
      console.log(id);
      name = $('#' + id).attr("data-name");
      info = $('#' + id).attr("data-info");
      total_score = $('#' + id).attr("data-total-score");
      date_end = $('#' + id).attr("data-date-end");
      date_start = $('#' + id).attr("data-date-start");
      pattern = $('#' + id).attr("data-pattern");
      necessary = $('#' + id).attr("data-necessary");


      $('#date_end').datepicker({
      format: 'dd.mm.yyyy',
      language: 'ru-RU'
      });

      $('#date_start').datepicker({
      format: 'dd.mm.yyyy',
      language: 'ru-RU'
      });


      $('#name').val(name);
      $('#total_score').val(total_score);
      $('#date_end').val(date_end);
      $('#date_start').val(date_start);


      if(necessary == 1){ $("#necessary").attr("checked","checked"); }
      else { $("#necessary").removeAttr("checked");}

      $('#pattern').summernote('destroy');
      $('#info').summernote('destroy');

      $('#info').html(info);
      $('#pattern').html(pattern);

        $('#pattern').summernote({
          tabsize: 2,
          height: 200,
          lang: 'ru-RU'
        });

      $('#info').summernote({
          tabsize: 2,
          height: 200,
          lang: 'ru-RU'
        });

    });


      $(document).on('blur', '[data-toggle="save-ktp"]', function(){

      var ktp_id = $(this).attr('id');
      var text_id = $(this).text();

      axios({
        method: 'post',
        url: '/api/edit-ktp',
        data: {
          id: ktp_id,
          name: text_id
        }
        })
        .then(function (response) {
          console.log('norm');
        });

    });


    //Сохранение изменений
    $(document).on('click', '#save_task', function(){

      var patternData = $('#pattern').summernote('code');
      var infoData = $('#info').summernote('code');
      id = $("#task").val();

      var task_date_start = $('#date_start').val();
      var task_date_end = $('#date_end').val();


      axios({
        method: 'post',
        url: '/api/save-task-info',
        data: {
          id: $("#task").val(),
          name: $('#name').val(),
          total_score: $('#total_score').val(),
          date_start: $('#date_start').val(),
          date_end: $('#date_end').val(),
          info: infoData,
          pattern: patternData,
          necessary: $("#necessary").prop("checked")

        }
      })
      .then(function (response) {

        var $task_info = $('#task' + id);
        $task_info.attr("data-name", $('#name').val());
        $task_info.attr("data-info", infoData);
        $task_info.attr("data-date-end", task_date_end);
        $task_info.attr("data-date-start", task_date_start);

        $('#' + id).attr("data-name", $('#name').val());
        $('#' + id).attr("data-info", infoData);
        $('#' + id).attr("data-total-score", $('#total_score').val());
        $('#' + id).attr("data-date-end", $('#date_end').val());
        $('#' + id).attr("data-date-start", $('#date_start').val());
        $('#' + id).attr("data-pattern", patternData);
        if($("#necessary").val() == "on") $('#' + id).attr("data-necessary", 1);

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

      });
    });





  $('#options___BV_tab_button__').on('click', function () {

    if(open_option == 0)
    {
      $url_task_options = $('#url-task-options').val();

      axios.get($url_task_options).then(function (response) {
       $("#task-option-ajax").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();


         $('[data-toggle="datepicker"]').datepicker({
         format: 'dd.mm.yyyy',
         language: 'ru-RU'
         });

         $('#date_end').datepicker({
         format: 'dd.mm.yyyy',
         language: 'ru-RU'
         });

         $('#date_start').datepicker({
         format: 'dd.mm.yyyy',
         language: 'ru-RU'
         });

        $("#town :first").attr("selected", "selected");

        var first = $('#task option:first');

        name = first.attr("data-name");
        info = first.attr("data-info");
        total_score = first.attr("data-total-score");
        date_end = first.attr("data-date-end");
        date_start = first.attr("data-date-start");
        pattern = first.attr("data-pattern");
        necessary = first.attr("data-necessary");


        $('#name').val(name);
        $('#total_score').val(total_score);
        $('#date_end').val(date_end);
        $('#date_start').val(date_start);

        if(necessary == 1){
          $("#necessary").attr("checked","checked");
        }

        $('#pattern').summernote('destroy');
        $('#info').summernote('destroy');

        $('#info').html(info);
        $('#pattern').html(pattern);

          $('#pattern').summernote({
            tabsize: 2,
            height: 200,
            lang: 'ru-RU'
          });

        $('#info').summernote({
            tabsize: 2,
            height: 200,
            lang: 'ru-RU'
          });


     });

     open_option++;
    }


  });


  //сохраняю значение после ухода с ячейки если оно изменилось
  $(document).on('blur', '[data-toggle="modal-work"]', function(){
    var value = $(this).text();
    value = value.replace(",",".");

    var id = $(this).attr('data-id');
    var id_student = $(this).attr('data-id-student');
    var id_type = $(this).attr('data-task-type');
    var type = $(this).attr('data-type');
    var id_work = 0;


    if(lesson_old != value)
    {
      axios({
        method: 'post',
        url: '/api/save-task',
        data: {
          id: id,
          value: value
        }
      })
      .then(function (response) {

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 500);


        if(type == 'task')
        {
          $('#total_task_score' + id_student  + id_type).html(response.data['score']);
          $('#mark' + id).html(response.data['mark']);
        }
        if(type == 'test')
        {
          $('#test' + id_student).html(response.data['score']);
          $('#test' + id).html(response.data['mark']);
        }
        if(type == 'main_test')
        {
          $('#maintest' + id_student).html(response.data['score']);
          $('#maintest' + id).html(response.data['mark']);
        }

        $url_progress = $('#url-progress').val();

        axios.get($url_progress).then(function (response) {
         $("#progress-ajax").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
        })


      });
    }
  });




  $(document).on('focus', '[data-toggle="modal-question"]', function(){
    question_old = $(this).text(); //сохраняю начальное значение
  });


  $(document).on('click', '[data-toggle="modal-question"]', function(){
    question_old = $(this).text(); //сохраняю начальное значение
  });




  $(document).on('blur', '[data-toggle="modal-question"]', function(){
    var value = $(this).text();
    var id = $(this).attr('data-id');

    if(question_old != value)
    {
      axios({
        method: 'post',
        url: '/api/save-total-question',
        data: {
          id: id,
          value: value
        }
      })
      .then(function (response) {
        console.log('vrode norm');
      });
    }
  });






    $(document).on('click', '#save-color-work', function(){


      axios({
        method: 'post',
        url: '/api/save-color-work',
        data: {
          id: id_work,
          color: $('#bg-info-work').val()
        }
      })
      .then(function (response) {
        $('#task' + id_work).attr('data-color', $('#bg-info-work').val());
        $('#task' + id_work).css("background", ($('#bg-info-work').val() + "66"));
        $(".task_s" + id_work).css("background", ($('#bg-info-work').val() + "66"));

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

      });

  });



    $(document).on('click', '#reset-color-work', function(){

    $('#bg-info-work').val("#ffffff00");
    var val_color = $('#bg-info-work').val();


      axios({
        method: 'post',
        url: '/api/save-color-work',
        data: {
          id: id_work,
          color: val_color,
          type: 'reset'
        }
      })
      .then(function (response) {
        $('#task' + id_work).attr('data-color', $('#bg-info-work').val());
        $('#task' + id_work).css("background", ($('#bg-info-work').val() + "00"));
        $(".task_s" + id_work).css("background", ($('#bg-info-work').val() + "00"));

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

      });

  });




    $(document).on('click', '[data-toggle="modal-work-info"]', function(){
    id_db = $(this).attr('data-id'); //беру id для сохранения в БД
    id_work = $(this).attr('data-id');

    var name_work = $(this).attr('data-name'); //беру коментик, чтобы отобразить в модальном окне
    var info_work = $(this).attr('data-info'); //беру коментик, чтобы отобразить в модальном окне
    var color_work = $(this).attr('data-color'); //беру коментик, чтобы отобразить в модальном окне

    var date_start = $(this).attr('data-date-start'); //беру коментик, чтобы отобразить в модальном окне
    var date_end = $(this).attr('data-date-end'); //беру коментик, чтобы отобразить в модальном окне

    if(color_work != "")
    { $('#bg-info-work').val(color_work); }
    else {
      $('#bg-info-work').val("#ffffff00");
    }
    $('#name_work').html(name_work);
    $('#info_work').html(info_work);

    if(date_start == "") { $('#date_start').html("Не задано"); }
    if(date_end == "") { $('#date_end').html("Не задано"); }
    if(date_start != "") { $('#date_start').html(date_start); }
    if(date_end != "") { $('#date_end').html(date_end); }

    var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
    coordinates.top += $(this).height() + 1; // На высоте середины кнопки
    coordinates.left += $(this).width() + 1; //Отодвигаем от кнопки на 15 пикселей

    popup_info.offset(coordinates);

  });


    var popup = $('#form-comment-work');
    var id_db = -1;
    var text = '';

    //Показываем модальное окно

    $(document).on('contextmenu', '[data-modal="modal-work-comment"]', function(e){
      e.preventDefault();


      $('[data-modal="modal-work-comment"]').not(this).css('border','1px solid transparent');

      lesson_old = $(this).text(); //сохраняю начальное значение
      $(this).css('border','1px solid #3490dc'); //выделяю текущею ячейку
      id_db = $(this).attr('data-id'); //беру id для сохранения в БД

      var pusto = "";

      $('#comment').val(pusto);

      var comment = $(this).attr('data-comment'); //беру коментик, чтобы отобразить в модальном окне
      var pattern = $(this).attr('data-pattern'); //беру коментик, чтобы отобразить в модальном окне


      $('#comment').val(comment); //устанавливаю в окошке
      $('#pattern-work').html(pattern);


      var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
      coordinates.top += $(this).height() + 7; // На высоте середины кнопки
      coordinates.left += $(this).width() + 27; //Отодвигаем от кнопки на 15 пикселей

      popup.offset(coordinates);
    });


    //отправляем коментик


      $(document).on('click', '#but-work', function(){
      axios({
        method: 'post',
        url: '/api/add-comment-task',
        data: {
          id: id_db,
          comment: $('#comment').val()
        }
      })
      .then(function (response) {
        $('#work'+ id_db).attr('data-comment', $('#comment').val());

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);
      });
    });

    //убираем окошко

    $(document).on('click', '#close-comment', function(){
      popup.css('top','-2300px');
      popup.css('left','-2300px');
      $('[data-modal="modal-work-comment"]').css('border','1px solid transparent');
    });

    //убираем окошко
    $('#close-info').on('click', function () {
      popup_info.css('top','-2300px');
      popup_info.css('left','-2300px');
    });


    $(document).mouseup(function (e){ // событие клика по веб-документу

      if (!popup.is(e.target) && popup.has(e.target).length === 0)
      {
        popup.css('top','-2300px');
        popup.css('left','-2300px');
        $('[data-modal="modal-work-comment"]').css('border','1px solid transparent');
      }
    });

    $(document).mouseup(function (e){ // событие клика по веб-документу

      if (!popup_info.is(e.target) && popup_info.has(e.target).length === 0)
      {
        popup_info.css('top','-2300px');
        popup_info.css('left','-2300px');
      }
    });



      /*
      $(document).on('focus', '[data-modal="modal-work-comment"]', function(){

      $('[data-modal="modal-work-comment"]').not(this).css('border','1px solid transparent');

      lesson_old = $(this).text(); //сохраняю начальное значение
      $(this).css('border','1px solid #3490dc'); //выделяю текущею ячейку
      var id_db = $(this).attr('data-id'); //беру id для сохранения в БД
      var pusto = "";

      $('#comment').val(pusto);


      var comment = $(this).attr('data-comment'); //беру коментик, чтобы отобразить в модальном окне
      var pattern = $(this).attr('data-pattern'); //беру коментик, чтобы отобразить в модальном окне

      $('#comment').val(comment);
      $('#pattern-work').html(pattern);


      var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
      coordinates.top += $(this).height() + 15; // На высоте середины кнопки
      coordinates.left += $(this).width() + 25; //Отодвигаем от кнопки на 15 пикселей

      popup.offset(coordinates);
    });
    */



    $(document).on('focus', '[data-toggle="modal-work"]', function(){
      lesson_old = $(this).text(); //сохраняю начальное значение
    });

    $(document).on('click', '[data-toggle="modal-work"]', function(){
      lesson_old = $(this).text(); //сохраняю начальное значение
    });





})
