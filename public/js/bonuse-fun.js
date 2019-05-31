$(document).ready(function () {
  var selected_list = 'def';
  var selected_stud = 0;
  var bb_old = 0;
  var id_rs = $('#id_rs').attr('data-rs');
  var popup_comment_bb = $('#form-comment-bb');
  var popup_info_bb = $('#form-info-bb');
  var id_bb = 0;
  var id_bb_info = 0;
  var id_stud = 0;



  $(document).on('click', '.del-val', function(){
    if(confirm("Вы действительно хотите удалить это значение?"))
    {
      var id = $(this).attr('data-id');

      axios({
        method: 'post',
        url: '/api/del-value',
        data: {
          id: id
        }
      })
      .then(function (response) {

        $url = $('#url-values').val();

        axios.get($url).then(function (response) {
        $("#values-ajax").html(response.data);

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        });
    });
  }
  });

  $(document).on('click', '.del-theme', function(){
    if(confirm("Вы действительно хотите удалить это значение?"))
    {
      var id = $(this).attr('data-id');

      axios({
        method: 'post',
        url: '/api/del-value',
        data: {
          id: id
        }
      })
      .then(function (response) {

        $url = $('#url-themes').val();

        axios.get($url).then(function (response) {
        $("#themes-ajax").html(response.data);

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        });
    });
  }
  });



  $(document).on('click', '.del-col-bb', function(){

    if(confirm("Вы действительно хотите удалить этот столбец?"))
    {
        var id = $(this).attr('data-id');

        axios({
          method: 'post',
          url: '/api/del-column-bb',
          data: {
            id: id
          }
        })
        .then(function (response) {

          $url_bonuses = $('#url-bonuses').val();

          axios.get($url_bonuses).then(function (response) {
           $("#bonuse-table").html(response.data);
           $('[data-toggle="tooltip"]').tooltip('dispose');
           $('[data-toggle="tooltip"]').tooltip();
            $("#bonuse-table .right-table").scrollLeft(9999);
          })

          $url_progress = $('#url-progress').val();

          axios.get($url_progress).then(function (response) {
           $("#progress-ajax").html(response.data);
           $('[data-toggle="tooltip"]').tooltip('dispose');
           $('[data-toggle="tooltip"]').tooltip();
          })

          $('.save-show').addClass('showw');
          setTimeout(function () {
              $('.save-show').removeClass('showw');
          }, 1000);

        });
  }

  });

    /*
    $(document).on('contextmenu', '[data-toggle="bb-edit"]', function(){
    bb_old = $(this).text(); //сохраняю начальное значение
    bb_old = bb_old.replace(",",".");
    id_stud = $(this).attr("data-student");
   });
   */

  $(document).on('focus', '[data-toggle="bb-edit"]', function(){
    bb_old = $(this).text(); //сохраняю начальное значение
    bb_old = bb_old.replace(",",".");
    id_stud = $(this).attr("data-student");
  });

  $(document).on('click', '#add-column-bb', function(){

    var name_column = $('#name-column-bb').val(); //сохраняю начальное значение

    axios({
      method: 'post',
      url: '/api/add-column-bb',
      data: {
        id_rs: id_rs,
        name: name_column
      }
    })
    .then(function (response) {

      $url_bonuses = $('#url-bonuses').val();

      axios.get($url_bonuses).then(function (response) {
       $("#bonuse-table").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();
       $("#bonuse-table .right-table").scrollLeft(9999);
      })

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);

    });

   });



  //изменение ББ в таблице
  $(document).on('blur', '[data-toggle="bb-edit"]', function(){
    var value = $(this).text();
    value = value.replace(",",".");
    var id = $(this).attr('data-id');
    var id_student = $(this).attr('data-student');

    if(bb_old != value)
    {
      axios({
      method: 'post',
      url: '/api/update-bb',
      data: {
        id_rs: id_rs,
        id: id,
        value: value,
        id_student: id_student
      }
    })
    .then(function (response) {

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 500);
      
      $('#name_student').addClass("save_rand");
      $('#sumbb' + id_student).text(response.data);

      $url_progress = $('#url-progress').val();

      axios.get($url_progress).then(function (response) {
       $("#progress-ajax").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();
      })


    });
  }

  });


  //сохраняет значение для дальньешего использования (в форме проставления ББ)
  $('#save_value').on('click', function() {
    var value = $('#value_bonuse').val();

    axios({
      method: 'post',
      url: '/api/save-value',
      data: {
        id_rs: id_rs,
        value: value,
        type:' value'
      }
    })
    .then(function (response) {
      $url = $('#url-values').val();

      axios.get($url)
      .then(function (response) {
    $("#values-ajax").html(response.data);



  })

  $('.save-show').addClass('showw');
  setTimeout(function () {
      $('.save-show').removeClass('showw');
  }, 1000);



    });

  });


  $('.btn-val').on('click', function() {
    $('#value_bonuse').val($(this).attr('data-value'));
    $('.btn-val').removeClass('active-value');
    $(this).addClass('active-value');
  });

  $('.btn-theme').on('click', function() {
    $('#theme_bonuse').val($(this).attr('data-theme'));


  });

  /////////////////////////////////////////////////////////////////////всплывающее окно для комента в ББ

  $(document).on('contextmenu', '[data-toggle="bb-edit"]', function(e){
  e.preventDefault();
  bb_old = $(this).text(); //сохраняю начальное значение
  bb_old = bb_old.replace(",",".");
  id_stud = $(this).attr("data-student");

  $('[data-toggle="bb-edit"]').not(this).css('border','1px solid transparent');
  $(this).css('border','1px solid #3490dc'); //выделяю текущею ячейку


  $('#operation').val('null');
  $('#plus-pp').val('');


  id_bb = $(this).attr('data-id'); //беру id для сохранения в БД



  var comment = $(this).attr('data-comment'); //беру коментик, чтобы отобразить в модальном окне

  $('#comment-bb').val(comment);

  var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
  coordinates.top += $(this).height() + 8; // На высоте середины кнопки
  coordinates.left += $(this).width() + 26; //Отодвигаем от кнопки на 15 пикселей

  popup_comment_bb.offset(coordinates);

});

    $(document).on('click', '[data-toggle="bb-info"]', function(){


    $('#bg-info-bb').val('#ffffff00');
    $('#name-info-bb').val('');
    $('#comment-info-bb').val('');


    id_bb_info = $(this).attr('data-id'); //беру id для сохранения в БД

    $('#comment-info-bb').val($(this).attr('data-comment'));
    $('#name-info-bb').val($(this).attr('data-name'));
    if($(this).attr('data-color') === '')
    {

    }
    else{$('#bg-info-bb').val($(this).attr('data-color'));}



    var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
    coordinates.top += $(this).height() + 27; // На высоте середины кнопки
    coordinates.left += $(this).width() + 27; //Отодвигаем от кнопки на 15 пикселей

    popup_info_bb.offset(coordinates);

    });
//////////////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '#but-info-bb', function(){



    var bg_info_bb = $('#bg-info-bb').val();
    var name_info_bb = $('#name-info-bb').val();
    var comment_info_bb = $('#comment-info-bb').val();

    axios({
      method: 'post',
      url: '/api/add-info-bb',
      data: {
        id: id_bb_info,
        color: bg_info_bb,
        name: name_info_bb,
        comment: comment_info_bb
      }
    })
    .then(function (response) {

      $(this).attr('data-comment', comment_info_bb);
      $(this).attr('data-name', name_info_bb);
      $('#bb-col' + id_bb_info).html(name_info_bb);
      $('#bb-col' + id_bb_info).css('background', (bg_info_bb+'66'));
      $('.bb' + id_bb_info).css('background', (bg_info_bb+'66'));

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);


    });

    });

    $(document).on('click', '#reset-bg-bb', function(){
      $('#bg-info-bb').val("#ffffff00");

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);

    });

    $(document).on('click', '#but-comment-bb', function(){

    var plus = $('#plus-pp').val();
    plus = plus.replace(",",".");


    var comment_bb = $('#comment-bb').val();
    var operation = $('#operation').val();

    axios({
      method: 'post',
      url: '/api/add-comment-bb',
      data: {
        id: id_bb,
        value: plus,
        comment: comment_bb,
        operation: operation
      }
    })
    .then(function (response) {
      $('#bbs' + id_bb).html(response.data['new_val']);
      $('#sumbb' + id_stud).html(response.data['summ']);

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);



    });

    });

    //убираем окошко
    $('#close-comment-bb').on('click', function () {
      popup_comment_bb.css('top','-2300px');
      popup_comment_bb.css('left','-2300px');
      $('[data-toggle="bb-edit"]').css('border','1px solid transparent');

    });

    $(document).mouseup(function (e){ // событие клика по веб-документу

      if (!popup_comment_bb.is(e.target) && popup_comment_bb.has(e.target).length === 0)
      {
        popup_comment_bb.css('top','-2300px');
        popup_comment_bb.css('left','-2300px');
        $('[data-toggle="bb-edit"]').css('border','1px solid transparent');
      }
    });

    //убираем окошко
    $('#close-info-bb').on('click', function () {
      popup_info_bb.css('top','-2300px');
      popup_info_bb.css('left','-2300px');

    });

    $(document).mouseup(function (e){ // событие клика по веб-документу

      if (!popup_info_bb.is(e.target) && popup_info_bb.has(e.target).length === 0)
      {
        popup_info_bb.css('top','-2300px');
        popup_info_bb.css('left','-2300px');
      }
    });




  $('#save_theme').on('click', function() {
    var theme = $('#theme_bonuse').val();

    axios({
      method: 'post',
      url: '/api/save-theme',
      data: {
        id_rs: id_rs,
        value: theme,
        type:'theme'
      }
    })
    .then(function (response) {

      $url = $('#url-themes').val();

      axios.get($url)
      .then(function (response) {
    $("#themes-ajax").html(response.data);
  })

  $('.save-show').addClass('showw');
  setTimeout(function () {
      $('.save-show').removeClass('showw');
  }, 1000);


    });

  });

  $('#getRand').on('click', function() {

    id_rs = $('#id_rs').attr('data-rs');
    var theme = $('#theme_bonuse').val();

    $('#name_student').removeClass("save_rand");

    axios({
      method: 'post',
      url: '/api/get-rand',
      data: {
        param: selected_list,
        id_rs: id_rs,
        theme: theme
      }
    })
    .then(function (response) {

      selected_stud = response.data['student'];
      var was = response.data['was'];
      var was = was.split(',');
      $('.stud').css('text-decoration', 'none');
      $('.stud').removeClass("selected_stud");

      $('#round').text(response.data['round']);

      $('#name_student').text(response.data['fio']);
      $('#message').text(response.data['message']);


      was.forEach(function(element) {
        $('.stud'+ element ).css('text-decoration', 'line-through');
      });

      $('.stud'+ response.data['student']).css('text-decoration', 'none');
      $('.stud'+ response.data['student']).addClass("selected_stud");

      if(response.data['fresh'] > 0)
      {
        $url_bonuses = $('#url-bonuses').val();

        axios.get($url_bonuses).then(function (response) {
         $("#bonuse-table").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
         $("#bonuse-table .right-table").scrollLeft(9999);
        })
      }


    });

  });


  $('#wrong').on('click', function() {

    var type_rs = $('#type_rs').val();

    if($('#value_bonuse').val() != "")
    {
      var value = $('#value_bonuse').val();
      value = value.replace(",",".");
      var theme = $('#theme_bonuse').val();


      console.log(value);
      console.log(theme);

      console.log(selected_stud);
      axios({
        method: 'post',
        url: '/api/right',
        data: {
          id_rs: id_rs,
          id_student: selected_stud,
          value: -value,
          theme: theme
        }
      })
      .then(function (response) {
        $('#name_student').addClass("save_rand");


        $url_bonuses = $('#url-bonuses').val();

        axios.get($url_bonuses).then(function (response) {
         $("#bonuse-table").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
         $("#bonuse-table .right-table").scrollLeft(9999);
        })

        $url_progress = $('#url-progress').val();

        axios.get($url_progress).then(function (response) {
         $("#progress-ajax").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
        })

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);


      });
    }
    else {
      $('#error-value').css('display','block');
    }


  });

  $('#right').on('click', function() {

    var type_rs = $('#type_rs').val();

    if($('#value_bonuse').val() != "")
    {
      var value = $('#value_bonuse').val();
      value = value.replace(",",".");
      var theme = $('#theme_bonuse').val();


      console.log(value);
      console.log(theme);

      console.log(selected_stud);
      axios({
        method: 'post',
        url: '/api/right',
        data: {
          id_rs: id_rs,
          id_student: selected_stud,
          value: value,
          theme: theme
        }
      })
      .then(function (response) {
        $('#name_student').addClass("save_rand");


        $url_bonuses = $('#url-bonuses').val();

        axios.get($url_bonuses).then(function (response) {
         $("#bonuse-table").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
         $("#bonuse-table .right-table").scrollLeft(9999);
        })

        $url_progress = $('#url-progress').val();

        axios.get($url_progress).then(function (response) {
         $("#progress-ajax").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
        })

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);


      });
    }
    else {
      $('#error-value').css('display','block');
    }


  });

  $('#default-rad').on('click', function() {
    $('.list').css('display', 'none');
    $('.selest-stud').css('display', 'none');
    $('.stud').css('text-decoration', 'none');
    $('.stud').removeClass("selected_stud");

    $('#default').css('display', 'block');
    selected_list = 'def';

  });

  $('#sub-rad').on('click', function() {
    $('.list').css('display', 'none');
    $('.selest-stud').css('display', 'none');
    $('.stud').css('text-decoration', 'none');
    $('.stud').removeClass("selected_stud");

    $('#sub-select').css('display', 'block');

    $( "#sub-select" ).change(function() {
      var sub = $("#sub-select").val();
      $('.list').css('display', 'none');
      $('#subgroup' + sub).css('display', 'block');
      selected_list = 'sub' + sub;
      });

  });


  $('#att-rad').on('click', function() {
    $('.list').css('display', 'none');
    $('.selest-stud').css('display', 'none');
    $('.stud').css('text-decoration', 'none');
    $('.stud').removeClass("selected_stud");

    $('#att-select').css('display', 'block');

    $( "#att-select" ).change(function() {
      var att = $("#att-select").val();
      $('.list').css('display', 'none');
      $('#att' + att).css('display', 'block');
      selected_list = 'att' + att;
    });

  });

  $('#list-rad').on('click', function() {
    $('.list').css('display', 'none');
    $('.selest-stud').css('display', 'none');
    $('.stud').css('text-decoration', 'none');
    $('.stud').removeClass("selected_stud");

    $('#list-select').css('display', 'block');

    $( "#list-select" ).change(function() {
      selected_list = $('#list-select').val();
    });


  });

  });
