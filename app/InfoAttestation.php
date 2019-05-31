<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoAttestation extends Model
{
  protected $table = "info_attestations";

  protected $fillable = [
      'id_rs', 'id_task_info', 'value', 'type'
  ];
}
