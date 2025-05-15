<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPrompts extends Model
{
    use HasFactory;
    protected $table = 'ai_prompts';

    protected $fillable = [
        'type',
        'name',
        'prompt',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function markAsActive()
    {
        self::where('type', $this->type)->update(['is_active' => 0]);
        $this->is_active = 1;
        $this->save();
        return $this;
    }

    public static function activePrompt($type)
    {
        $model = self::where('type', $type)->first();
        if ($model != null) {
            return $model->prompt;
        }
        return null;
    }

}
