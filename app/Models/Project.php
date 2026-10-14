<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $casts = [
        'status' => Status::class,
    ];

    protected $fillable = [
        'ulid',
        'name',
        'description',
        'department_id',
        'directorate_id',
        'unit_id',
        'contractor_id',
        'consultant_id',
        'duration',
        'duration_period',
        'cost',
        'location',
        'award_date',
        'office_id',
        'deadline',
        'award_letter',
        'created_by_id',
        'project_type_id',
        'priority'
    ];

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'id')->where('firm_type_id', 1);
    }

    public function consultant()
    {
        return $this->belongsTo(Contractor::class, 'consultant_id', 'id')->where('firm_type_id', 2);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function project_type()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'project_ulid', 'ulid');
    }

    public function project_personnel()
    {
        return $this->hasMany(ProjectPersonnel::class, 'project_ulid', 'ulid');
    }

    public function getTotalPaidAttribute(): float
    {
        $total_paid = $this->milestone_total('done');

        return round($total_paid, 2);
    }

    public function getTotalLeftAttribute(): float
    {
        $total_paid = $this->cost - $this->total_paid ?? 0;

        return round($total_paid, 2);
    }

    public function getMilestoneCompletionAttribute(): array
    {
        $milestones = $this->milestones();

        $completed = $milestones->where('status', 'done')->count();
        $uncompleted = $milestones->whereIn('status', ['pending', 'in_progress'])->count() ?? 0;

        return [$completed, $uncompleted];
    }

    public function get_progress(): float
    {
        $milestonesTotal = $this->milestone_total('done');
        return ($milestonesTotal) ? $this->calculatePercentage($this->cost, $milestonesTotal) : 0.00;
    }

    public function cost_left()
    {
        $milestonesTotal = $this->milestone_total();
        return $this->cost - $milestonesTotal;
    }

    public function milestone_total($status = 'all')
    {
        $milestonesTotal = $this->milestones();
        if ($status == 'done') {
            $milestonesTotal = $milestonesTotal->where('status', 'done');
        }

        if ($status == 'undone') {
            $milestonesTotal = $milestonesTotal->where('status', 'in_progress')->orWhere('status', 'pending');
        }

        return $milestonesTotal->pluck('amount')->sum();
    }

    public function check_and_mark_as_done()
    {
        $allTasksDone = $this->milestones()->where('status', '!=', 'done')->doesntExist();

        if ($allTasksDone) {
            $this->status = 'done';
            $this->save();
        }
    }

    public function calculatePercentage(float $totalAmount, float $amountTakenOut): float
    {
        if ($totalAmount == 0) {
            return 0;
        }
        return round(($amountTakenOut / $totalAmount) * 100, 2);
    }


    public function getTimeLeftAttribute()
    {
        $startDate = $this->created_at ? Carbon::parse($this->created_at) : Carbon::now();
        $durationValue = (int)$this->duration;
        $durationPeriod = $this->duration_period;

        $start_date_copy = $startDate->copy();

        $endDate = match ($durationPeriod) {
            'days' => $start_date_copy->addDays($durationValue),
            'weeks' => $start_date_copy->addWeeks($durationValue),
            'months' => $start_date_copy->addMonths($durationValue),
            'years' => $start_date_copy->addYears($durationValue),
            default => $startDate
        };


        $remainingSeconds = max(0,  Carbon::now()->diffInSeconds($endDate, false));

        if ($remainingSeconds <= 0) {

            return "Expired";
        }

        $years = floor($remainingSeconds / (365 * 24 * 60 * 60));
        $remainingSeconds %= (365 * 24 * 60 * 60);

        $months = floor($remainingSeconds / (30 * 24 * 60 * 60));
        $remainingSeconds %= (30 * 24 * 60 * 60);

        $weeks = floor($remainingSeconds / (7 * 24 * 60 * 60));
        $remainingSeconds %= (7 * 24 * 60 * 60);

        $days = floor($remainingSeconds / (24 * 60 * 60));
        $remainingSeconds %= (24 * 60 * 60);

        $hours = floor($remainingSeconds / (60 * 60));
        $remainingSeconds %= (60 * 60);

        $minutes = floor($remainingSeconds / 60);
        $seconds = $remainingSeconds % 60;

        $parts = [];
        if ($years > 0) $parts[] = "$years year" . ($years > 1 ? 's' : '');
        if ($months > 0) $parts[] = "$months month" . ($months > 1 ? 's' : '');
        if ($weeks > 0) $parts[] = "$weeks week" . ($weeks > 1 ? 's' : '');
        if ($days > 0) $parts[] = "$days day" . ($days > 1 ? 's' : '');
        if ($hours > 0) $parts[] = "$hours hour" . ($hours > 1 ? 's' : '');
        if ($minutes > 0) $parts[] = "$minutes minute" . ($minutes > 1 ? 's' : '');
        if ($seconds > 0) $parts[] = "$seconds second" . ($seconds > 1 ? 's' : '');

        return implode(', ', $parts) . ' left';
    }
}
