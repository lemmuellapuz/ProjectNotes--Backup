<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Note;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lastId = Note::orderBy('created_at', 'DESC')->limit(1)->first();

        if($lastId) $lastId = $lastId->id;
        else $lastId = 1;

        $qr_code = base64_encode(uniqid($lastId, true));

        return [
            'user_id' => 2,
            'qr_code' => $qr_code,
            'title' => fake()->word(),
            'content' => fake()->text()
        ];
    }
}
