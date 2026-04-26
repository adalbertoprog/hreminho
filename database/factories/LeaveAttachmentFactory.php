<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\LeaveAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveAttachmentFactory extends Factory
{
    protected $model = LeaveAttachment::class;

    public function definition(): array
    {
        $fileType = $this->faker->randomElement(['pdf', 'jpg', 'png']);
        $fileName = $this->faker->slug(3) . '.' . $fileType;

        return [
            'leave_id'    => Leave::factory(),
            'file_name'   => $fileName,
            'file_path'   => 'attachments/leaves/' . $fileName,
            'file_type'   => $fileType,
            'uploaded_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
