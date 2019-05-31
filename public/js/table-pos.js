$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})




$(document).ready(function () {

  function createCaretPlacer(atStart) { // для того чтобы курсор в конец поставить функция
      return function(el) {
          el.focus();
          if (typeof window.getSelection != "undefined"
                  && typeof document.createRange != "undefined") {
              var range = document.createRange();
              range.selectNodeContents(el);
              range.collapse(atStart);
              var sel = window.getSelection();
              sel.removeAllRanges();
              sel.addRange(range);
          } else if (typeof document.body.createTextRange != "undefined") {
              var textRange = document.body.createTextRange();
              textRange.moveToElementText(el);
              textRange.collapse(atStart);
              textRange.select();
          }
      };
  }

  var placeCaretAtStart = createCaretPlacer(true);
  var placeCaretAtEnd = createCaretPlacer(false);

  $(document).on('keyup', '[data-toggle="modal"]', function(e){

    if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40)//смотрю не стрелки ли это
    {
      var val = $(this).text();
      $.trim(val); //убираю лишние пробелы

      var text = val.indexOf(0); //ищу ноль
      var twos = val.indexOf('.'); //ищу точку
      if(text > 0 && twos < 0 && text < 17 && val.substr(text-1,1) == " ") //если это ноль, но не число дробь меньше 1 или я поставила число перед нулем, тогда ок
      {
        $(this).text(val.substring(text+1)); //ставим все кроме нуля
        placeCaretAtEnd(e.currentTarget); //курсор в конец
      }

    }
  });

  $(document).on('keyup', '[data-toggle="modal-work"]', function(e){

    if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40)//смотрю не стрелки ли это
    {
      var val = $(this).text();
      $.trim(val); //убираю лишние пробелы

      var text = val.indexOf(0); //ищу ноль
      var twos = val.indexOf('.'); //ищу точку
      if(text > 0 && twos < 0 && text < 22 && val.substr(text-1,1) == " ") //если это ноль, но не число дробь меньше 1 или я поставила число перед нулем, тогда ок
      {
        $(this).text(val.substring(text+1)); //ставим все кроме нуля
        placeCaretAtEnd(e.currentTarget); //курсор в конец
      }

    }
  });

  $(document).on('keyup', '[data-toggle="bb-edit"]', function(e){

    if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40)//смотрю не стрелки ли это
    {
      var val = $(this).text();
      $.trim(val); //убираю лишние пробелы

      var text = val.indexOf(0); //ищу ноль
      var twos = val.indexOf('.'); //ищу точку
      if(twos < 0 && text == 0) //если это ноль, но не число дробь меньше 1 или я поставила число перед нулем, тогда ок
      {
        $(this).text(val.substring(text+1)); //ставим все кроме нуля
        placeCaretAtEnd(e.currentTarget); //курсор в конец
      }

    }
  });



  $(document).on('click', '#red', function(){
    $('#bg-info-date').val('#800000');
    $('#bg-info-work').val('#800000');
    $('#bg-info-bb').val('#800000');
  });

  $(document).on('click', '#blue', function(){
    $('#bg-info-date').val('#000080');
    $('#bg-info-work').val('#000080');
    $('#bg-info-bb').val('#000080');
  });

  $(document).on('click', '#green', function(){
    $('#bg-info-date').val('#008040');
    $('#bg-info-work').val('#008040');
    $('#bg-info-bb').val('#008040');
  });

  $(document).on('click', '#yellow', function(){
    $('#bg-info-date').val('#ff8000');
    $('#bg-info-work').val('#ff8000');
    $('#bg-info-bb').val('#ff8000');
  });

  $(document).on('click', '#violet', function(){
    $('#bg-info-date').val('#400080');
    $('#bg-info-work').val('#400080');
    $('#bg-info-bb').val('#400080');
  });





    //храню тут предыдущее значение в ячейке
    var lesson_old = 0;
    var date_old = 0;
    var id_rs = $('#id_rs').attr('data-rs');
    var popup = $('#date-comment');

    $(document).on('click', 'li:first-child a', function(){
      console.log('lf');
      $url_progress = $('#url-progress').val();

      axios.get($url_progress).then(function (response) {
       $("#progress-ajax").html(response.data);
       $('[data-toggle="tooltip"]').tooltip('dispose');
       $('[data-toggle="tooltip"]').tooltip();
      })



    });


    //сохраняю значение после ухода с ячейки если оно изменилось
    $(document).on('blur', '[data-toggle="modal"]', function(){

      var text = $(this).text();
      text = text.replace(",",".");
      var calc = 0;


      if(text > 1)
      {
        calc = 1 - (text / 90);
        calc = calc.toFixed(2);
        $(this).text(calc);
      }
      else{calc = text;}

      if(text == "") {calc = 0; $(this).text('');}
      var id = $(this).attr('data-id');
      var stud_id = $(this).attr('data-stud-id');
      var date_has = $(this).attr('data-date-has');


      if(lesson_old != calc)
      {
        axios({
          method: 'post',
          url: '/api/save-lesson',
          data: {
            id: id,
            text: calc
          }
        })
        .then(function (response) {

          $('.save-show').addClass('showw');
          setTimeout(function () {
              $('.save-show').removeClass('showw');
          }, 500);

          if(text == "")
          {
            $('#les' + id).text('0');
          }
          if($('#type_rs').val() == 1 && text == "")
          {
            $('#les' + id).text('');
          }

          if(calc > 0){
            $('#les' + id).removeClass('progul');
          }




          $('#les-' + stud_id).text(response.data['score']);
          $('#les-' + stud_id).attr('title', ('Посещаемость: ' + response.data['percent'] + '%'));

          if($('#type_rs').val() == 1)
          {
            $('#prog-' + stud_id).text(response.data['percent']);
          }

          $('#les-' + stud_id).tooltip('dispose');
          $('#les-' + stud_id).tooltip();



          $url_there = $('#url-there-students').val();
          $url_select = $('#url-select-students').val();

          $url_sub_1 = $('#url-sub-1').val();
          $url_sub_2 = $('#url-sub-2').val();

          $url_att_5 = $('#url-att-5').val();
          $url_att_4 = $('#url-att-4').val();
          $url_att_3 = $('#url-att-3').val();
          $url_att_2 = $('#url-att-2').val();



          axios.get($url_there).then(function (response) {
           $("#there-students").html(response.data);
          })

          axios.get($url_select).then(function (response) {
           $("#select-students").html(response.data);
          })

          axios.get($url_sub_1).then(function (response) {
           $("#subgroup-1-ajax").html(response.data);
          })

          axios.get($url_sub_2).then(function (response) {
           $("#subgroup-2-ajax").html(response.data);
          })

          axios.get($url_att_5).then(function (response) {
           $("#att-5-ajax").html(response.data);
          })

          axios.get($url_att_4).then(function (response) {
           $("#att-4-ajax").html(response.data);
          })

          axios.get($url_att_3).then(function (response) {
           $("#att-3-ajax").html(response.data);
          })

          axios.get($url_att_2).then(function (response) {
           $("#att-2-ajax").html(response.data);
          })

          $url_progress = $('#url-progress').val();

          axios.get($url_progress).then(function (response) {
           $("#progress-ajax").html(response.data);
           $('[data-toggle="tooltip"]').tooltip('dispose');
           $('[data-toggle="tooltip"]').tooltip();
          })



      })
    }
    });

    //сохраняю старые значения даты
    $(document).on('focus', '[data-toggle="modal-date"]', function(){
      date_old = $(this).text(); //сохраняю начальное значение
    });



    $(document).on('click', '[data-toggle="modal-date"]', function(){
    date_old = $(this).text(); //сохраняю начальное значение

    $(this).css('color','#3490dc'); //выделяю текущею ячейку
    id_db = $(this).attr('data-id'); //беру id для сохранения в БД
    var subgroup = $(this).attr('data-subgroup');
    var type = $(this).attr('data-type');
    var id_ktp = $(this).attr('data-id-ktp');
    var pusto = "";


    if($(this).attr('data-color') != "")
    { $('#bg-info-date').val($(this).attr('data-color')); }
    else{ $('#bg-info-date').val("#ffffff00"); }





    $('#comment-date').val(pusto);
    $('#task-id').val(-1);
    $('#subgroup').val(0);
    if($(this).attr('data-optional') == 1)
    { $('#optional-date').prop('checked', true);}
    else {$('#optional-date').prop('checked', false); }




    var comment = $(this).attr('data-comment'); //беру коментик, чтобы отобразить в модальном окне
    var pattern = $(this).attr('data-pattern'); //беру коментик, чтобы отобразить в модальном окне
    var check = $(this).attr('data-optional'); //беру коментик, чтобы отобразить в модальном окне


    $('#comment-date').val(comment);
    $('#pattern-work').html(pattern);
    if(type != ""){$('#task-id').val(type);}
    if(subgroup != ""){$('#subgroup').val(subgroup);}
    if(id_ktp != ""){$('#ktp-select').val(id_ktp);} else{$('#ktp-select').val(-1);}




    var coordinates = $(this).offset(); //Получаем координаты кликнутой кнопки
    coordinates.top += $(this).height() + 26; // На высоте середины кнопки
    coordinates.left += $(this).width() + 26; //Отодвигаем от кнопки на 15 пикселей

    popup.offset(coordinates);

  });



    $(document).on('click', '#save-date', function(){

    var type = $('#task-id').val();
    var subgroup = $('#subgroup').val();
    var ktp = $('#ktp-select').val();
    var comment = $('#comment-date').val();
    var val = $('#date' + id_db).attr('data-date');
    var color = $('#bg-info-date').val();
    var check = $('#optional-date').prop('checked');
    var text = $('#date' + id_db).text();



      axios({
        method: 'post',
        url: '/api/save-date-info',
        data: {
          id: id_db,
          comment: comment,
          subgroup: subgroup,
          type: type,
          color: color,
          optional: check,
          ktp: ktp,
          text: text

        }
      })
      .then(function (response) {



        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

        var ost_hour = $('#' + ktp + 'option').attr('data-hour-ost') - 2;
        var opt_number = $('#' + ktp + 'option').attr('data-number');
        var opt_name = $('#' + ktp + 'option').attr('data-name');
        var opt_all_hour = $('#' + ktp + 'option').attr('data-all-hour');

        console.log(ost_hour);
        if(ost_hour > 0)
        {
          $('#' + ktp + 'option').text(opt_number + ". Осталось " + ost_hour + " из " + opt_all_hour + "." + opt_name);
          $('#' + ktp + 'option').attr('data-hour-ost', ost_hour);
        }
        else
        {
          $('#' + ktp + 'option').text(opt_number + ".  " + "✓ " + opt_name);
          $('#' + ktp + 'option').attr('data-hour-ost', ost_hour);
        }



        if(subgroup > 0)
        {
          $('#date' + id_db).text(val + ' (' + subgroup + ')');
        }

        $('#date' + id_db).attr('data-comment',comment);
        $('#date' + id_db).attr('data-subgroup',subgroup);
        $('#date' + id_db).attr('data-type', type);
        $('#date' + id_db).attr('data-id-ktp', ktp);

        if(check == true)
        $('#date' + id_db).attr('data-optional', 1);

        $('#date' + id_db).attr('data-color', color);
        $('#date' + id_db).css('background', (color + "66"));
        $('.date_s' + id_db).css('background', (color + "66"));

        var pos_left = $('#lesson-ajax-table').scrollLeft();

        if(subgroup > 0)
        {
          $url = $('#url-lesson').val();

          axios.get($url).then(function (response) {
          $("#lesson-ajax").html(response.data);
          $('#lesson-ajax-table').scrollLeft(pos_left);
          $('[data-toggle="tooltip"]').tooltip('dispose');
          $('[data-toggle="tooltip"]').tooltip();
          });
        }

        $url_progress = $('#url-progress').val();

        axios.get($url_progress).then(function (response) {
         $("#progress-ajax").html(response.data);
         $('[data-toggle="tooltip"]').tooltip('dispose');
         $('[data-toggle="tooltip"]').tooltip();
        })




      });

  });

  $(document).mouseup(function (e){ // событие клика по веб-документу

    if (!popup.is(e.target) && popup.has(e.target).length === 0)
    {
      popup.css('top','-2300px');
      $('[data-toggle="modal-date"]').css('color','#333');
    }
  });


  //Устанавливаю дату на сегодняшнюю
  $(document).on('contextmenu', '[data-toggle="modal-date"]', function(e){
      e.preventDefault();
      var pos_click = e.pageX - $(this).offset().left;
      console.log(pos_click);
      var now = new Date();
      var month = ('0' + (now.getMonth()+1)).slice(-2);
      var day = ('0' + now.getDate()).slice(-2);

      $(this).text(day + '.' + month); //сохраняю начальное значение
    });

    //сохраняю значение после ухода с ячейки если оно изменилось
    $(document).on('blur', '[data-toggle="modal-date"]', function(){
      var date = $(this).text();
      var id = $(this).attr('data-id');

      if(date_old != date)
      {
        axios({
          method: 'post',
          url: '/api/save-date',
          data: {
            id: id,
            date: date
          }
        })
        .then(function (response) {

          $('.save-show').addClass('showw');
          setTimeout(function () {
              $('.save-show').removeClass('showw');
          }, 500);

          $('.date_s' + id).addClass('progul');




          $url_there = $('#url-there-students').val();
          $url_select = $('#url-select-students').val();

          $url_sub_1 = $('#url-sub-1').val();
          $url_sub_2 = $('#url-sub-2').val();

          $url_att_5 = $('#url-att-5').val();
          $url_att_4 = $('#url-att-4').val();
          $url_att_3 = $('#url-att-3').val();
          $url_att_2 = $('#url-att-2').val();



          axios.get($url_there).then(function (response) {
           $("#there-students").html(response.data);
          })

          axios.get($url_select).then(function (response) {
           $("#select-students").html(response.data);
          })

          axios.get($url_sub_1).then(function (response) {
           $("#subgroup-1-ajax").html(response.data);
          })

          axios.get($url_sub_2).then(function (response) {
           $("#subgroup-2-ajax").html(response.data);
          })

          axios.get($url_att_5).then(function (response) {
           $("#att-5-ajax").html(response.data);
          })

          axios.get($url_att_4).then(function (response) {
           $("#att-4-ajax").html(response.data);
          })

          axios.get($url_att_3).then(function (response) {
           $("#att-3-ajax").html(response.data);
          })

          axios.get($url_att_2).then(function (response) {
           $("#att-2-ajax").html(response.data);
          })

        });
      }
    });

    //ставлю еденицу  в ячейку если чувак выбрал
    $(document).on('contextmenu', '[data-toggle="modal"]', function(e){
      e.preventDefault();
        $(this).text(1);


    });

    //убираем окошко
    $('#close').on('click', function () {
      popup.css('top','-2300px');
      popup.css('left','-2300px');
    });


    $(document).on('focus', '[data-toggle="modal"]', function(){
      lesson_old = $(this).text(); //сохраняю начальное значение
    });



    $(document).on('click', '.del-lesson', function(){
    var id_less = $(this).attr("data-id"); //сохраняю начальное значение

    if(confirm("Вы действительно хотите удалить этот столбец?"))
    {
      axios({
      method: 'post',
      url: '/api/del-lesson',
      data: {
        id: id_less,
        id_rs: id_rs
      }
    })
    .then(function (response) {

      var pos_left = $('#lesson-ajax-table').scrollLeft();


      $url = $('#url-lesson').val();

      axios.get($url).then(function (response) {
      $("#lesson-ajax").html(response.data);
      $('#lesson-ajax-table').scrollLeft(pos_left);
      $('[data-toggle="tooltip"]').tooltip('dispose');
      $('[data-toggle="tooltip"]').tooltip();
      });

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


    $(document).on('click', '#reset-color-lesson', function(){

      axios({
      method: 'post',
      url: '/api/save-color-date',
      data: {
        id: id_db
      }
    })
    .then(function (response) {

      $('#date' + id_db).attr('data-color', "#ffffff");
      $('#date' + id_db).css('background', "#ffffff00");
      $('.date_s' + id_db).css('background', "#ffffff00");

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);

    });




    });

    $(document).on('click', '#add-column-lesson', function(){

    if(confirm("Вы действительно хотите добавить столбец?"))
    {
      axios({
      method: 'post',
      url: '/api/add-lesson',
      data: {
        id: id_rs
      }
    })
    .then(function (response) {


      $url = $('#url-lesson').val();
      var pos_left = $('#lesson-ajax-table').scrollLeft();

      axios.get($url).then(function (response) {
      $("#lesson-ajax").html(response.data);
      $('#lesson-ajax-table').scrollLeft(pos_left);
      $('[data-toggle="tooltip"]').tooltip('dispose');
      $('[data-toggle="tooltip"]').tooltip();

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);

      });



    });
   }
    });

    $(document).on('click', '#add-more-lesson', function(){

    if(confirm("Вы действительно хотите добавить пары?"))
    {
      axios({
      method: 'post',
      url: '/api/add-more-lesson',
      data: {
        id: id_rs,
        count: $('#count-lesson').val()
      }
    })
    .then(function (response) {


      $url = $('#url-lesson').val();
      var pos_left = $('#lesson-ajax-table').scrollLeft();

      axios.get($url).then(function (response) {
      $("#lesson-ajax").html(response.data);
      $('#lesson-ajax-table').scrollLeft(pos_left);
      $('[data-toggle="tooltip"]').tooltip('dispose');
      $('[data-toggle="tooltip"]').tooltip();

      $('.save-show').addClass('showw');
      setTimeout(function () {
          $('.save-show').removeClass('showw');
      }, 1000);

      });



    });
   }
    });




    $(document).on({
        mouseenter: function () {
          var tr = $(this).find('[data-hover*="row_s"]');
          var class_css = $(this).attr("data-hover");
          tr.not('[data-hover='+class_css+']').removeClass("selected-row");
          $('[data-hover='+class_css+']').addClass("selected-row");
        },
        mouseleave: function () {
            var tr = $(this).find('[data-hover*="row_s"]');

            var class_css = $(this).attr("data-hover");
            $('[data-hover='+class_css+']').removeClass("selected-row");

        }
    },'div');


/*

  $('[data-hover*="row_s"]').hover(function() {
    var class_css = $(this).attr("data-hover");
    $('[data-hover*="row_s"]').not('[data-hover='+class_css+']').removeClass("selected-row");
    $('[data-hover='+class_css+']').addClass("selected-row");
  });


  $('[data-hover*="row_s"]').mouseleave(function(){
    var class_css = $(this).attr("data-hover");
    $('[data-hover='+class_css+']').removeClass("selected-row");
  });
  */




})

$(document).keydown(function(e) {

  switch (e.which) {
    case 13: // left arrow
      e.preventDefault();
      $(e.target).parent().parent().next().children().eq($(e.target).parent().index()).find('div').focus()
      break;
    case 37: // left arrow
      $(e.target).parent().prev().find('div').focus()
      break;
    case 39: // right arrow
      $(e.target).parent().next().find('div').focus()
      break;
    case 40: // down
      $(e.target).parent().parent().next().children().eq($(e.target).parent().index()).find('div').focus()
      break;
    case 38: // up
      $(e.target).parent().parent().prev().children().eq($(e.target).parent().index()).find('div').focus()
      break;
    default: // exit for other keys
      return;
  }

});
