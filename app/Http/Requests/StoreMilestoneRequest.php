<?php

namespace App\Http\Requests;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreMilestoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_ulid' => 'required|exists:projects,ulid',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cost_type' => 'nullable|in:payable,non-payable',
            'amount' => 'nullable|numeric|min:1',
            'deadline' => 'required|date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $project = Project::where('ulid', $this->project_ulid)->first();

            if (!$project) {
                return;
            }

            $startDate = Carbon::parse($project->award_date);

            $duration = (int) $project->duration;
            switch ($project->duration_period) {
                case 'days':
                    $endDate = $startDate->copy()->addDays($duration);
                    break;
                case 'weeks':
                    $endDate = $startDate->copy()->addWeeks($duration);
                    break;
                case 'months':
                    $endDate = $startDate->copy()->addMonths($duration);
                    break;
                case 'years':
                    $endDate = $startDate->copy()->addYears($duration);
                    break;
                default:
                    $validator->errors()->add('duration_period', 'Invalid duration type.');
                    return;
            }


            $milestoneDeadline = Carbon::parse($this->deadline);

            if ($milestoneDeadline->lt($startDate) || $milestoneDeadline->gt($endDate)) {
                $validator->errors()->add(
                    'deadline',
                    "The milestone deadline must be between {$startDate->format('d-m-Y')} and {$endDate->format('d-m-Y')}."
                );
            }
        });
    }
}
