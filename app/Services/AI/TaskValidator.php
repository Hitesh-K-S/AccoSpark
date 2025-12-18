<?php

namespace App\Services\AI;

class TaskValidator
{
    /**
     * Validate LLM-generated task structure
     *
     * @param array $data LLM response data
     * @return array ['isValid' => bool, 'errors' => array]
     */
    public function validate(array $data): array
    {
        $errors = [];

        // Check required top-level fields
        if (!isset($data['goal_title']) || empty($data['goal_title'])) {
            $errors[] = 'Missing or empty goal_title';
        }

        if (!isset($data['duration_days']) || !is_numeric($data['duration_days'])) {
            $errors[] = 'Missing or invalid duration_days';
        } elseif ($data['duration_days'] <= 0) {
            $errors[] = 'duration_days must be greater than 0';
        }

        if (!isset($data['tasks']) || !is_array($data['tasks'])) {
            $errors[] = 'Missing or invalid tasks array';
        } else {
            // Validate task count
            $taskCount = count($data['tasks']);
            if ($taskCount === 0) {
                $errors[] = 'At least one task is required';
            } elseif ($taskCount > 7) {
                $errors[] = "Too many tasks ({$taskCount}). Maximum is 7.";
            }

            // Validate each task
            foreach ($data['tasks'] as $index => $task) {
                $taskErrors = $this->validateTask($task, $index);
                $errors = array_merge($errors, $taskErrors);
            }
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Validate a single task
     *
     * @param array $task Task data
     * @param int $index Task index for error messages
     * @return array Array of error messages
     */
    protected function validateTask(array $task, int $index): array
    {
        $errors = [];
        $prefix = "Task " . ($index + 1);

        // Required fields
        if (!isset($task['title']) || empty(trim($task['title']))) {
            $errors[] = "{$prefix}: Missing or empty title";
        }

        if (!isset($task['description']) || empty(trim($task['description']))) {
            $errors[] = "{$prefix}: Missing or empty description";
        }

        // Validate estimated_minutes
        if (!isset($task['estimated_minutes']) || !is_numeric($task['estimated_minutes'])) {
            $errors[] = "{$prefix}: Missing or invalid estimated_minutes";
        } else {
            $minutes = (int) $task['estimated_minutes'];
            if ($minutes < 10 || $minutes > 120) {
                $errors[] = "{$prefix}: estimated_minutes must be between 10 and 120 (got {$minutes})";
            }
        }

        // Validate frequency_per_week
        if (!isset($task['frequency_per_week']) || !is_numeric($task['frequency_per_week'])) {
            $errors[] = "{$prefix}: Missing or invalid frequency_per_week";
        } else {
            $frequency = (int) $task['frequency_per_week'];
            if ($frequency < 1 || $frequency > 7) {
                $errors[] = "{$prefix}: frequency_per_week must be between 1 and 7 (got {$frequency})";
            }
        }

        return $errors;
    }
}

