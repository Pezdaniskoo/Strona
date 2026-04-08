<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login(): void
    {
        $register = $this->postJson('/api/v1/register', [
            'first_name' => 'Jan',
            'last_name' => 'Nowak',
            'email' => 'jan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $register->assertCreated()->assertJsonStructure(['token', 'user']);

        $login = $this->postJson('/api/v1/login', [
            'email' => 'jan@example.com',
            'password' => 'Password123!',
        ]);

        $login->assertOk()->assertJsonStructure(['token', 'user']);
    }

    public function test_user_can_get_own_tasks_and_create_update_task(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER->value]);
        $category = Category::factory()->create();

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/tasks', [
            'title' => 'Test task',
            'priority' => 'high',
            'category_id' => $category->id,
        ])->assertCreated();

        $task = Task::first();

        $this->withToken($token)->getJson('/api/v1/tasks')->assertOk();

        $this->withToken($token)->putJson('/api/v1/tasks/'.$task->id, [
            'title' => 'Updated task',
            'priority' => 'medium',
            'status' => 'in_progress',
        ])->assertOk();
    }

    public function test_user_cannot_access_foreign_task(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $token = $other->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/tasks/'.$task->id)->assertForbidden();
    }

    public function test_categories_are_available(): void
    {
        $user = User::factory()->create();
        Category::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/categories')->assertOk()->assertJsonStructure(['data']);
    }
}
