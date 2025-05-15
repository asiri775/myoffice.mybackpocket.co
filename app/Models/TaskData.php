<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskData extends Model
{
    use HasFactory;
    protected $table = 'task_data';

    protected $fillable = [
        'task',
        'value',
        'status'
    ];

    public function markAsPending($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
        $this->status = "pending";
        $this->save();
    }

    public function markAsCompleted($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
        $this->status = "completed";
        $this->save();
    }

    public static function getOrCreateTask($task)
    {
        $model = self::where(['task' => $task])->first();
        if ($model == null) {
            $model = new TaskData([
                'task' => $task,
                'status' => 'pending'
            ]);
            $model->save();
        }
        return $model;
    }

}
