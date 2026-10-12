<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $casts = [
        'status' => Status::class,
    ];


    protected $fillable = [
        'name',
        'description',
        'cost_type',
        'amount',
        'deadline',
        'project_ulid',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_ulid', 'ulid');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }


    public function check_and_mark_as_done()
    {
        $allMilestonesDone = $this->tasks()->where('status', '!=', 'done')->doesntExist();

        if ($allMilestonesDone) {
            $this->status = 'done';
            $this->save();

            $this->project->check_and_mark_as_done();
        }
    }

    public function task_total($status = 'all')
    {
        $tasksTotal = $this->tasks();
        if ($status == 'done') {
            $tasksTotal = $tasksTotal->where('status', 'done');
        }
        return $tasksTotal->pluck('cost')->sum();
    }

    public function cost_left()
    {
        $tasksTotal = $this->task_total();
        return $this->amount - $tasksTotal;
    }

    public function get_progress(): float
    {
        $totalTask = $this->task_total('done');
        return ($totalTask) ? $this->calculatePercentage($this->amount, $totalTask) : 0.00;
    }


    public function calculatePercentage(float $totalAmount, float $amountTakenOut): float
    {
        if ($totalAmount == 0) {
            return 0;
        }
        return round(($amountTakenOut / $totalAmount) * 100, 2);
    }


    public function images()
    {
        return $this->hasMany(MilestoneImage::class, 'milestone_ulid', 'ulid');
    }
}
