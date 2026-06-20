<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Anna Admin',
            'email' => 'admin@jira.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $manager = User::create([
            'name' => 'Marek Manager',
            'email' => 'manager@jira.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MANAGER,
            'email_verified_at' => now(),
        ]);

        $devs = collect([
            ['Jan Kowalski', 'jan@jira.test'],
            ['Ewa Nowak', 'ewa@jira.test'],
            ['Piotr Dev', 'piotr@jira.test'],
            ['Karolina Test', 'karolina@jira.test'],
        ])->map(fn($d) => User::create([
            'name' => $d[0],
            'email' => $d[1],
            'password' => Hash::make('password'),
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]));

        $tags = collect([
            ['bug', '#dc2626'],
            ['feature', '#16a34a'],
            ['frontend', '#0ea5e9'],
            ['backend', '#7c3aed'],
            ['urgent', '#ea580c'],
            ['docs', '#64748b'],
            ['design', '#db2777'],
        ])->map(fn($t) => Tag::create(['name' => $t[0], 'color' => $t[1]]));

        $projects = [
            ['Platforma e-learningowa', 'Kompleksowy system kursów online z testami i certyfikatami.', 'active', now()->addMonths(2)],
            ['Aplikacja mobilna iOS', 'Wersja mobilna głównego produktu z offline modem.', 'active', now()->addMonth()],
            ['Migracja do mikroserwisów', 'Rozbicie monolitu na 6 mikroserwisów.', 'on_hold', now()->addMonths(6)],
            ['Redesign strony www', 'Przeprojektowanie strony marketingowej.', 'active', now()->addWeeks(3)],
            ['Wewnętrzny dashboard analityczny', 'BI dla zespołu sprzedaży.', 'archived', null],
        ];

        foreach ($projects as [$name, $desc, $status, $deadline]) {
            $p = Project::create([
                'name' => $name,
                'description' => $desc,
                'status' => $status,
                'deadline' => $deadline,
                'created_by' => $manager->id,
            ]);

            $members = $devs->random(rand(2, 3))->pluck('id')->toArray();
            $sync = collect($members)->mapWithKeys(fn($id) => [$id => ['project_role' => 'member']])->toArray();
            $sync[$manager->id] = ['project_role' => 'owner'];
            $p->members()->sync($sync);

            $taskTitles = [
                'Konfiguracja CI/CD',
                'Implementacja autoryzacji',
                'Endpoint API: /users',
                'Refaktor warstwy serwisowej',
                'Testy integracyjne modułu płatności',
                'Dokumentacja techniczna',
                'Wycieki pamięci w kolektorze',
                'Optymalizacja zapytań SQL',
            ];

            foreach (array_slice($taskTitles, 0, rand(3, 6)) as $title) {
                $task = Task::create([
                    'project_id' => $p->id,
                    'title' => $title,
                    'description' => 'Opis zadania: ' . $title . '. Wymaga przeanalizowania, implementacji i przetestowania.',
                    'status' => collect(Task::STATUSES)->random(),
                    'priority' => collect(Task::PRIORITIES)->random(),
                    'assigned_to' => $devs->random()->id,
                    'created_by' => $manager->id,
                    'due_date' => now()->addDays(rand(3, 30)),
                ]);
                $task->tags()->sync($tags->random(rand(1, 3))->pluck('id')->toArray());
            }
        }
    }
}
