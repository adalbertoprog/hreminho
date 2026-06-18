<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Quiz;
use App\Models\Training;
use App\Models\TrainingVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    use RefreshDatabase;

    // ── Flags booleanas ──────────────────────────────────────────────────

    public function test_has_video_defaults_to_false(): void
    {
        $training = Training::factory()->create(['has_video' => false]);

        $this->assertFalse($training->has_video);
    }

    public function test_has_quiz_defaults_to_false(): void
    {
        $training = Training::factory()->create(['has_quiz' => false]);

        $this->assertFalse($training->has_quiz);
    }

    public function test_has_video_and_has_quiz_are_cast_to_boolean(): void
    {
        $training = Training::factory()->create(['has_video' => 1, 'has_quiz' => 1]);

        $this->assertIsBool($training->has_video);
        $this->assertIsBool($training->has_quiz);
        $this->assertTrue($training->has_video);
        $this->assertTrue($training->has_quiz);
    }

    // ── Relação: vídeos ──────────────────────────────────────────────────

    public function test_training_has_many_videos(): void
    {
        $training = Training::factory()->create();

        TrainingVideo::factory()->count(3)
            ->sequence(fn ($seq) => ['order' => $seq->index + 1])
            ->create(['training_id' => $training->id]);

        $this->assertCount(3, $training->videos);
    }

    public function test_training_videos_are_ordered_by_order(): void
    {
        $training = Training::factory()->create();

        TrainingVideo::factory()->create(['training_id' => $training->id, 'order' => 3]);
        TrainingVideo::factory()->create(['training_id' => $training->id, 'order' => 1]);
        TrainingVideo::factory()->create(['training_id' => $training->id, 'order' => 2]);

        $orders = $training->videos->pluck('order')->toArray();

        $this->assertSame([1, 2, 3], $orders);
    }

    // ── Relação: quiz ────────────────────────────────────────────────────

    public function test_training_has_one_quiz(): void
    {
        $training = Training::factory()->create();
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);

        $this->assertInstanceOf(Quiz::class, $training->quiz);
        $this->assertSame($quiz->id, $training->quiz->id);
    }

    public function test_training_quiz_is_null_when_not_created(): void
    {
        $training = Training::factory()->create();

        $this->assertNull($training->quiz);
    }

    // ── Relação: employeeTrainings ───────────────────────────────────────

    public function test_training_has_many_employee_trainings(): void
    {
        $training = Training::factory()->create();

        EmployeeTraining::factory()->count(2)->create(['training_id' => $training->id]);

        $this->assertCount(2, $training->employeeTrainings);
    }

    // ── Relação: employees (belongsToMany) ───────────────────────────────

    public function test_training_belongs_to_many_employees(): void
    {
        $training  = Training::factory()->create();
        $employees = Employee::factory()->count(2)->create();

        foreach ($employees as $emp) {
            EmployeeTraining::factory()->create([
                'training_id' => $training->id,
                'employee_id' => $emp->id,
            ]);
        }

        $training->load('employees');

        $this->assertCount(2, $training->employees);
    }

    // ── Fillable ─────────────────────────────────────────────────────────

    public function test_training_fillable_fields(): void
    {
        $training = Training::factory()->create([
            'title'       => 'Segurança no Trabalho',
            'description' => 'Formação obrigatória.',
            'provider'    => 'IEFP',
        ]);

        $this->assertSame('Segurança no Trabalho', $training->title);
        $this->assertSame('IEFP', $training->provider);
    }
}
