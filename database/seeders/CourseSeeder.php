<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $instructorId = 2; // ID del instructor creado en UserSeeder

        $courses = [
            [
                'title'             => 'Laravel 10: De Principiante a Experto',
                'description'       => 'Aprende Laravel 10 desde cero y desarrolla aplicaciones web profesionales.',
                'price'             => 199.00,
                'promotion_price'   => 149.00,
                'category_id'       => 1,
                'level'             => 'beginner',
                'duration'          => 40,
                'requirements'      => ['Conocimientos básicos de PHP', 'HTML y CSS'],
                'what_you_learn'    => ['Desarrollo con Laravel', 'Bases de datos', 'APIs REST', 'Autenticación'],
            ],
            [
                'title'             => 'JavaScript Moderno ES6+',
                'description'       => 'Domina JavaScript moderno con todas las características de ES6 y beyond.',
                'price'             => 179.00,
                'category_id'       => 2,
                'level'             => 'intermediate',
                'duration'          => 35,
                'requirements'      => ['Conocimientos básicos de programación'],
                'what_you_learn'    => ['ES6+ Features', 'Async/Await', 'DOM Manipulation', 'Modern Frameworks'],
            ],
            [
                'title'             => 'Diseño UI/UX con Figma',
                'description'       => 'Crea interfaces de usuario profesionales y experiencias de usuario excepcionales.',
                'price'             => 159.00,
                'promotion_price'   => 129.00,
                'category_id'       => 3,
                'level'             => 'beginner',
                'duration'          => 30,
                'requirements'      => ['Computadora con Figma instalado'],
                'what_you_learn'    => ['Diseño de interfaces', 'Prototipado', 'Design Systems', 'User Research'],
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create(array_merge($courseData, [
                'instructor_id'     => $instructorId,
                'slug'              => Str::slug($courseData['title']),
                'short_description' => substr($courseData['description'], 0, 150) . '...',
                'is_active'         => true,
            ]));

            // Crear secciones del curso
            $sections = [
                ['title' => 'Introducción', 'order' => 1],
                ['title' => 'Conceptos Fundamentales', 'order' => 2],
                ['title' => 'Proyectos Prácticos', 'order' => 3],
                ['title' => 'Avanzado', 'order' => 4],
            ];

            foreach ($sections as $sectionData) {
                $section = CourseSection::create(array_merge($sectionData, [
                    'course_id' => $course->id,
                    'is_active' => true,
                ]));

                // Crear lecciones para cada sección
                $lessons = [
                    ['title' => 'Bienvenida al curso', 'duration' => 10, 'order' => 1, 'is_free' => true],
                    ['title' => 'Instalación del entorno', 'duration' => 25, 'order' => 2, 'is_free' => true],
                    ['title' => 'Primeros pasos', 'duration' => 30, 'order' => 3, 'is_free' => false],
                    ['title' => 'Ejercicios prácticos', 'duration' => 45, 'order' => 4, 'is_free' => false],
                ];

                foreach ($lessons as $lessonData) {
                    Lesson::create(array_merge($lessonData, [
                        'course_section_id' => $section->id,
                        'description'       => "Lección sobre {$lessonData['title']}",
                        'video_url'         => 'https://example.com/video.mp4',
                        'is_active'         => true,
                    ]));
                }
            }

            // Crear examen para el curso
            $exam = Exam::create([
                'course_id' => $course->id,
                'title' => "Examen Final - {$course->title}",
                'description' => "Examen de certificación para el curso {$course->title}",
                'duration' => 60,
                'passing_score' => 14.00,
                'max_attempts' => 3,
                'is_active' => true,
            ]);

            // Crear preguntas del examen
            $questions = [
                [
                    'question'          => '¿Qué es Laravel?',
                    'type'              => 'multiple_choice',
                    'options'           => ['Un framework de PHP', 'Un lenguaje de programación', 'Una base de datos', 'Un editor de código'],
                    'correct_answer'    => '0',
                    'points'            => 2,
                ],
                [
                    'question'          => '¿Qué significa MVC?',
                    'type'              => 'multiple_choice',
                    'options'           => ['Model View Controller', 'Main Visual Component', 'Modern View Control', 'Model Visual Control'],
                    'correct_answer'    => '0',
                    'points'            => 2,
                ],
                [
                    'question'          => 'Laravel utiliza Eloquent ORM',
                    'type'              => 'true_false',
                    'options'           => ['Verdadero', 'Falso'],
                    'correct_answer'    => '0',
                    'points'            => 1,
                ],
            ];

            foreach ($questions as $index => $questionData) {
                ExamQuestion::create(array_merge($questionData, [
                    'exam_id'   => $exam->id,
                    'order'     => $index + 1,
                ]));
            }
        }
    }
}
