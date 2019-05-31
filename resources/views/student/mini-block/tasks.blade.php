<?
use \App\Http\Controllers\Student\CalculateController;
$id_user = Auth::user()->id;
$bb_info = CalculateController::getBBStudent($rs->id, $id_user);

?>
<b-tabs pills>
  @if($rs->rstasks->count() > 0)
  @foreach($rs->rstasks as $task)
  <b-tab title="{{$task->name_task}}">

      <b-tabs pills>
        @foreach($task->tasks as $i_task)
        <? $work = CalculateController::getStudentWork($rs->id, $id_user, $i_task->id);?>


          @if($work != null && $i_task->date_start != null)
          @if($i_task->info != NULL && $i_task->info != "<p><br></p>" || $work->value != NULL)

          <b-tab title="{{$i_task->number}}">

            <div class="block-task">
              <div class="title-task">
                @if($i_task->necessary == 1)
                <i data-toggle="tooltip" data-html="true" title="Обязательная работа" class="fas fa-exclamation-circle"></i>
                @endif

                @if($work->value != NULL)
                <span style="color:#20c997;">{{$task->name_task}} №{{$i_task->number}}</span>
                <i class="fas fa-check"></i>
                @else
                {{$task->name_task}} №{{$i_task->number}}
                @endif
                <span class="right-text">
                  @if($i_task->date_start != NULL)  от: <span style="color: rgba(32, 201, 151,0.7)">{{$i_task->date_start}} </span>@endif
                  @if($i_task->date_end != NULL) по: <span style="
                  @if(CalculateController::moreTodayDate($i_task->date_end))
                  color:#eb606e;
                  @endif
                  ">{{$i_task->date_end}}@endif </span></span>

              </div>

              <div class="body-task">
                <p class="name-work">{{$i_task->name}}</p>
                <p class="info-task"><i class="fas fa-file-alt"></i> За 100% выполнение работы: <span class="b-fork">{{$i_task->total_score}} Б</span> </p>
                <hr />

                @if($work->value != NULL)
                <p class="percent-work"><i class="fas fa-check"></i> Выполнено на: {{$work->value}}% </p>
                <p class="percent-work"><i class="fas fa-check"></i> Баллов за работу: {{CalculateController::scoreOneTaskStudent($rs->id, $id_user, $i_task->id)}} </p>
                @endif


                @if($work->comment != NULL && $work->comment != "<p><br></p>")
                <div class="comment-work"><i class="fas fa-comment"></i> Комментарий по работе: {{$work->comment}}</div>
                @endif
                <hr />

                @if($i_task->pattern != "<p><br></p>" || $i_task->pattern != NULL)
                <p class="crit-work"><i class="fas fa-star"></i> Критерии оценки работы: </p>
                {!! $i_task->pattern !!}
                <hr />
                @endif

                {!! $i_task->info !!}

              </div>


            </div>


          </b-tab>
          @endif
          @endif
        @endforeach
      </b-tabs>

  </b-tab>
  @endforeach
  @endif

    @if($rs->total_test > 0)
    <b-tab title="Тесты">
      <b-tabs pills>
      @foreach($rs->infotasks as $test)


        @if($test->type == "test")
        <? $work = CalculateController::getStudentWork($rs->id, $id_user, $test->id); ?>
        @if($work != null)
      @if(($test->info != NULL && $test->info != "<p><br></p>" ) || $work->value > 0)
      <b-tab title="{{$test->number}}">
        <div class="block-task">
          <div class="title-task">
            @if($test->necessary == 1)
            <i data-toggle="tooltip" data-html="true" title="Обязательный тест" class="fas fa-exclamation-circle"></i>
            @endif

            @if($work->value > 0)
            <span style="color:#20c997;">Тест №{{$test->number}}</span>
            <i class="fas fa-check"></i>
            @else
            <span>Тест №{{$test->number}}</span>
            @endif


            <span class="right-text">
              @if($test->date_start != NULL) от: <span style="color: rgba(32, 201, 151,0.7)">{{$test->date_start}}</span> @endif
              @if($test->date_end != NULL) по: <span style="
              @if(CalculateController::moreTodayDate($test->date_end))
              color:#eb606e;
              @endif
              "> {{$test->date_end}}
              @endif </span> </span>

          </div>

          <div class="body-task">
            <p class="name-work">{{$test->name}}</p>
            <p class="info-task"><i class="fas fa-file-alt"></i> За тест можно получить: {{$test->total_score}} Б </p>
            <hr />

            @if($test->pattern != "<p><br></p>" || $test->pattern != NULL)
            <p class="crit-work"><i class="fas fa-star"></i> Критерии оценки работы: </p>
            {!! $test->pattern !!}

            @endif

            @if($work->value > 0)
            <p class="percent-work"><i class="fas fa-check"></i> Правильных ответов: {{$work->value}} </p>
            <p class="percent-work"><i class="fas fa-check"></i> Баллов за тест: {{CalculateController::scoreOneTestStudent($rs->id, $id_user, $test->id)}} </p>
            @endif



            @if($work->comment != NULL && $work->comment != "<p><br></p>")
            <div class="comment-work"><i class="fas fa-comment"></i> Комментарий по работе: {{$work->comment}}</div>
            @endif



            <hr />

            {!! $test->info !!}

          </div>


        </div>


      </b-tab>
      @endif
      @endif
      @endif

      @endforeach
      </b-tabs>

    </b-tab>
    @endif

    @if($rs->total_main_test > 0)
    <b-tab title="Итоговые тесты">

      <b-tabs pills>
      @foreach($rs->infotasks as $test)
      @if($test->type == "main_test")
        <? $work = CalculateController::getStudentWork($rs->id, $id_user, $test->id);?>
        @if($work != null)
      @if(($test->info != NULL && $test->info != "<p><br></p>" ) || $work->value > 0)
      <b-tab title="{{$test->number}}">
        <div class="block-task">
          <div class="title-task">
            @if($test->necessary == 1)
            <i data-toggle="tooltip" data-html="true" title="Обязательный итоговый тест" class="fas fa-exclamation-circle"></i>
            @endif

            @if($work->value > 0)
            <span style="color:#20c997;">Итоговый тест №{{$test->number}}</span>
            <i class="fas fa-check"></i>
            @else
            <span>Итоговый тест №{{$test->number}}</span>
            @endif


            <span class="right-text">
              @if($test->date_start != NULL) от: <span style="color: rgba(32, 201, 151,0.7)">{{$test->date_start}}</span> @endif
              @if($test->date_end != NULL) по: <span style="
              @if(CalculateController::moreTodayDate($test->date_end))
              color:#eb606e;
              @endif
              "> {{$test->date_end}}
              @endif </span> </span>

          </div>

          <div class="body-task">
            <p class="name-work">{{$test->name}}</p>
            <p class="info-task"><i class="fas fa-file-alt"></i> За тест можно получить: {{$test->total_score}} Б </p>
            <hr />

            @if($i_task->pattern != "<p><br></p>" || $i_task->pattern != NULL)
            <p class="crit-work"><i class="fas fa-star"></i> Критерии оценки работы: </p>
            {!! $i_task->pattern !!}
            <hr />
            @endif

            @if($work->value > 0)
            <p class="percent-work"><i class="fas fa-check"></i> Правильных ответов: {{$work->value}} </p>
            <p class="percent-work"><i class="fas fa-check"></i> Баллов за тест: {{CalculateController::scoreOneTestStudent($rs->id, $id_user, $test->id)}} </p>

            @endif



            @if($work->comment != NULL && $work->comment != "<p><br></p>")
            <div class="comment-work"><i class="fas fa-comment"></i> Комментарий по работе: {{$work->comment}}</div>
            @endif

            <hr />

            {!! $test->info !!}

          </div>


        </div>


      </b-tab>
      @endif
      @endif
      @endif

      @endforeach
      </b-tabs>

    </b-tab>
    @endif

    @if(count($bb_info['bb']) > 0)
    <b-tab title="Бонусные работы">

      <div class="block-task">
        <div class="title-task">
          Бонусные баллы

        </div>

        <div class="body-task">
          @foreach($bb_info['bb'] as $bb)
          @if($bb->value != NULL)
          <div class="bb-val">
            <p>
              <span class="bb-title">
                 {{$bb_info['bb_date'][$bb->id]->name}}:
              </span> {{$bb->value}} Б <span class="date-bb">({{$bb_info['bb_date'][$bb->id]->date}})</span>
            </p>
            @if($bb->comment != NULL)
            <p><i class="fas fa-comment"></i> Комментарий: {{$bb->comment}}</p>
            @endif
          </div>
          @endif
          @endforeach

        </div>

      </div>

    </b-tab>
    @endif

    @if(Auth::user()->id_group == 4 || Auth::user()->id_group == 10)

    <b-tab title="Дополнительные способы получения баллов">

      <div class="block-task">
        <div class="title-task">
          10 способов получить больше баллов
        </div>

        <div class="body-task acent">
              <p>1)	Вся ваша группа закрывает мой предмет без двоек: + 150 баллов каждому;</p>

              <p>2)	Вся ваша группа закрывает мой предмет без троек: + 250 баллов каждому;</p>

              <p>3)	Призер или победитель региональных и всероссийских чемпионатов WorldSkillsRussia:</p>
              <ul>
              <li>1 место: 1500 баллов + освобождение от всех обязательных работ</li>
              <li>2 место: 1250 баллов + освобождение от всех обязательных работ</li>
              <li>3 место: 1000 баллов + освобождение от всех обязательных работ</li>
              <li>4 и ниже место: 400 баллов</li>
            </ul>

              <p>4)	Призер или победитель всероссийских и международных очных мероприятиях:</p>
              <ul>
              <li>1 место: 1500 баллов + освобождение от всех обязательных работ</li>
              <li>2 место: 1250 баллов + освобождение от всех обязательных работ</li>
              <li>3 место: 1000 баллов + освобождение от всех обязательных работ</li>
              <li>4 и ниже место: 400 баллов</li>
            </ul>

              <p>5)	Призер или победитель региональных очных мероприятиях:</p>
              <ul>
              <li>1 место: 1000 баллов + освобождение от всех обязательных работ</li>
              <li>2 место: 850 баллов</li>
              <li>3 место: 700 баллов</li>
              <li>4 и ниже место: 200 баллов</li>
            </ul>

              <p>6)	Призер или победитель региональных очных мероприятиях внутривузовских:</p>
              <ul>
              <li>1 место: 300 баллов</li>
              <li>2 место: 200 баллов</li>
              <li>3 место: 100 баллов</li>
              <li>4 и ниже место: 50 баллов</li>
            </ul>

              <p>7)	Призер или победитель всероссийских, международных и региональных заочных мероприятиях:</p>
              <ul>
              <li>1 место: 400 баллов</li>
              <li>2 место: 350 баллов</li>
              <li>3 место: 300 баллов</li>
              <li>4 и ниже место: 100 баллов</li>
            </ul>

              <p>8)	Сборка полностью рабочего действующего стенда: от 100 до 500 баллов (в зависимости от сложности)</p>

              <p>9)	Различные экскурсии, мероприятия и т.д. проходящие внутри вуза под моим руководством: от 25 до 200 баллов (В зависимости от сложности)</p>

              <p>10)	 Курирование (помощь в обучении) другого студента (у которого оценка ниже 3). Баллы начисляются только от практических, лабораторных, курсовых работ и различных схем, если они входят в бонусные баллы (за посещение, тесты и прочее баллы не начисляются). Вы также можете получить минус от курирующего вами студента. Как только студент набирает баллов на оценку «3» вы больше не сможете его курировать. Курировать можно только если прошло более от предмета прошло 60% пар.</p>
              <ul>
              <li>1 студент: 50% от полученных баллов двоечника начисляться вам.</li>
              <li>2 студента: 35% от полученных баллов двоечника начисляться вам (т.е. за 2-х студентов вы получаете 70% от их баллов).</li>
            </ul>

        </div>

      </div>

      </b-tab>
      @endif



  </b-tabs>
