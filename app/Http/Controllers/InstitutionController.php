<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateInstitutionRequest;
use App\Institution;
use App\Group;
use App\Specialty;
use App\Discipline;

class InstitutionController extends Controller
{
  public function index()
  {
    $specialties = Specialty::all();
    $groups = Group::all();
    $disciplines = Discipline::all();

    $data = array(
      'specialties' => $specialties,
      'groups' => $groups,
      'disciplines' => $disciplines
    );

    return view('foundation.index', $data);
  }
  public function create(CreateInstitutionRequest $request)
  {
    $objInstitution = new Institution;

    $institution = $objInstitution->create([
        'name' => $request->input('name'),
        'type' => $request->input('type')
    ]);

    return view('home');

  }

  public function createGroup(Request $request)
  {
    $groups = Group::all();

    $objGroup = new Group;

    $group = $objGroup->create([
        'year_adms' => $request->year_adms,
        'id_specialty' => $request->id_specialty
    ]);

  }

  public function freshAfterGroup()
  {
    return view('home');
  }



  public function createSpecialty(Request $request)
  {


    $objSpecialty = new Specialty;

    $specialty = $objSpecialty->create([
        'number' => $request->number,
        'name' => $request->name
    ]);

  }

  public function createDiscipline(Request $request)
  {
    $objDiscipline = new Discipline;

    $discipline = $objDiscipline->create([
        'name' => $request->name,
        'number' => $request->number,
        'mdk' => $request->mdk
    ]);


  }

}
