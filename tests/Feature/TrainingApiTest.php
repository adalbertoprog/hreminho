<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TrainingApiTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ───────────────────────────────────────────────────────────

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function hrUser(): User
    {
        return User::factory()->create([
            'role' => 'hr', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function employeeUser(): User
    {
        return User::factory()->create([
            'role' => 'employee', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function quizPayload(): array
    {
        return [
            'title'         => 'Quiz de Teste',
            'passing_score' => 70,
            'questions'     => [[
                'question' => 'Qual é a cor do céu?',
                'type'     => 'mc',
                'options'  => [
                    ['text' => 'Azul',     'is_correct' => true],
                    ['text' => 'Vermelho', 'is_correct' => false],
                ],
            ]],
        ];
    }

    // ── Trainings CRUD ────────────────────────────────────────────────────

    public function test_anyone_authenticated_can_list_trainings(): void
    {
        Training::factory()->count(3)->create();

        $this->actingAs($this->employeeUser())
             ->getJson('/api/v1/trainings')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_training(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/trainings', [
                 'title'       => 'Formação de Segurança',
                 'description' => 'Descrição da formação',
                 'provider'    => 'IEFP',
             ])
             ->assertCreated()
             ->assertJsonFragment(['title' => 'Formação de Segurança']);

        $this->assertDatabaseHas('trainings', ['title' => 'Formação de Segurança']);
    }

    public function test_employee_cannot_create_training(): void
    {
        $this->actingAs($this->employeeUser())
             ->postJson('/api/v1/trainings', ['title' => 'X'])
             ->assertForbidden();
    }

    public function test_admin_can_update_training(): void
    {
        $training = Training::factory()->create(['title' => 'Original']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/trainings/{$training->id}", ['title' => 'Actualizado'])
             ->assertOk()
             ->assertJsonFragment(['title' => 'Actualizado']);
    }

    public function test_admin_can_delete_training(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/trainings/{$training->id}")
             ->assertNoContent();

        $this->assertDatabaseMissing('trainings', ['id' => $training->id]);
    }

    // ── Enrollments ───────────────────────────────────────────────────────

    public function test_admin_can_list_enrollments(): void
    {
        EmployeeTraining::factory()->count(3)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/enrollments')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_admin_can_enroll_employee_in_training(): void
    {
        $employee = Employee::factory()->create();
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
             ->postJson('/api/v1/enrollments', [
                 'employee_id' => $employee->id,
                 'training_id' => $training->id,
                 'status'      => 'enrolled',
             ])
             ->assertCreated();

        $this->assertDatabaseHas('employee_trainings', [
            'employee_id' => $employee->id,
            'training_id' => $training->id,
        ]);
    }

    public function test_admin_can_update_enrollment(): void
    {
        $enrollment = EmployeeTraining::factory()->create(['status' => 'enrolled']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/enrollments/{$enrollment->id}", [
                 'status' => 'completed',
                 'score'  => 85,
             ])
             ->assertOk();

        $this->assertDatabaseHas('employee_trainings', [
            'id'     => $enrollment->id,
            'status' => 'completed',
            'score'  => 85,
        ]);
    }

    public function test_admin_can_delete_enrollment(): void
    {
        $enrollment = EmployeeTraining::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/enrollments/{$enrollment->id}")
             ->assertNoContent();

        $this->assertDatabaseMissing('employee_trainings', ['id' => $enrollment->id]);
    }

    public function test_enrollments_can_be_filtered_by_employee(): void
    {
        $emp1 = Employee::factory()->create();
        $emp2 = Employee::factory()->create();
        EmployeeTraining::factory()->create(['employee_id' => $emp1->id]);
        EmployeeTraining::factory()->create(['employee_id' => $emp2->id]);

        $response = $this->actingAs($this->admin())
             ->getJson("/api/v1/enrollments?employee_id={$emp1->id}")
             ->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    // ── Quiz ──────────────────────────────────────────────────────────────

    public function test_admin_can_create_quiz_for_training(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/trainings/{$training->id}/quiz", $this->quizPayload())
             ->assertCreated()
             ->assertJsonPath('data.title', 'Quiz de Teste');

        $this->assertDatabaseHas('quizzes', ['training_id' => $training->id]);
    }

    public function test_creating_duplicate_quiz_returns_409(): void
    {
        $training = Training::factory()->create();
        Quiz::factory()->create(['training_id' => $training->id]);

        $this->actingAs($this->admin())
             ->postJson("/api/v1/trainings/{$training->id}/quiz", $this->quizPayload())
             ->assertStatus(409);
    }

    public function test_employee_can_view_quiz_without_correct_answers(): void
    {
        $training = Training::factory()->create(['has_quiz' => true]);
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);
        $question = QuizQuestion::factory()->create(['quiz_id' => $quiz->id]);
        QuizOption::factory()->create(['question_id' => $question->id, 'is_correct' => true]);
        QuizOption::factory()->create(['question_id' => $question->id, 'is_correct' => false]);

        $response = $this->actingAs($this->employeeUser())
             ->getJson("/api/v1/trainings/{$training->id}/quiz")
             ->assertOk();

        // Funcionários não devem ver is_correct
        $options = $response->json('data.questions.0.options');
        foreach ($options as $opt) {
            $this->assertArrayNotHasKey('is_correct', $opt);
        }
    }

    public function test_admin_can_view_quiz_with_correct_answers(): void
    {
        $training = Training::factory()->create(['has_quiz' => true]);
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);
        $question = QuizQuestion::factory()->create(['quiz_id' => $quiz->id]);
        QuizOption::factory()->create(['question_id' => $question->id, 'is_correct' => true]);

        $response = $this->actingAs($this->admin())
             ->getJson("/api/v1/trainings/{$training->id}/quiz")
             ->assertOk();

        $options = $response->json('data.questions.0.options');
        $this->assertArrayHasKey('is_correct', $options[0]);
    }

    public function test_employee_can_submit_quiz_attempt(): void
    {
        $user     = $this->employeeUser();
        $training = Training::factory()->create(['has_quiz' => true]);
        $quiz     = Quiz::factory()->create(['training_id' => $training->id, 'passing_score' => 70]);
        $question = QuizQuestion::factory()->create(['quiz_id' => $quiz->id, 'type' => 'mc']);
        $correct  = QuizOption::factory()->create(['question_id' => $question->id, 'is_correct' => true]);
        QuizOption::factory()->create(['question_id' => $question->id, 'is_correct' => false]);

        $response = $this->actingAs($user)
             ->postJson("/api/v1/quiz/{$training->id}/attempt", [
                 'answers' => [
                     ['question_id' => $question->id, 'option_id' => $correct->id],
                 ],
             ])
             ->assertOk();

        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
        ]);
        $this->assertTrue($response->json('data.passed'));
    }

    public function test_employee_can_view_own_quiz_attempts(): void
    {
        $user     = $this->employeeUser();
        $training = Training::factory()->create(['has_quiz' => true]);
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);

        \App\Models\QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->getJson("/api/v1/quiz/{$training->id}/my-attempts")
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_admin_can_view_quiz_results(): void
    {
        $training = Training::factory()->create(['has_quiz' => true]);
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);

        $this->actingAs($this->admin())
             ->getJson("/api/v1/trainings/{$training->id}/quiz/results")
             ->assertOk();
    }

    public function test_employee_cannot_view_quiz_results(): void
    {
        $training = Training::factory()->create(['has_quiz' => true]);
        Quiz::factory()->create(['training_id' => $training->id]);

        $this->actingAs($this->employeeUser())
             ->getJson("/api/v1/trainings/{$training->id}/quiz/results")
             ->assertForbidden();
    }
}
