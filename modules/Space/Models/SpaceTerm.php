<?php
namespace Modules\Space\Models;

use App\BaseModel;
use Modules\Core\Models\Terms;
use Modules\Space\Models\Space;

class SpaceTerm extends BaseModel
{
    protected $table = 'bravo_space_term';
    protected $fillable = [
        'term_id',
        'target_id'
    ];

    public function term(){
        return $this->belongsTo(Terms::class);
     }


     public function space(){
        return $this->belongsTo(Space::class , 'target_id');
     }

}