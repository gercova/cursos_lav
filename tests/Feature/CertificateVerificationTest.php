<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enterprise;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Enterprise::firstOrCreate([], [
            'company_name'         => 'IPF CONSULTORES SAC',
            'ruc'                  => '20601234567',
            'legal_representative' => 'Pablo Torres García',
            'favicon_path'         => 'favicon.ico',
        ]);
    }

    private function createDummyCertificate(array $attributes = []): Certificate
    {
        $user = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor', 'profession' => 'Ingeniero']);
        
        $category = Category::first() ?? Category::create([
            'name'        => 'Seguridad',
            'slug'        => 'seguridad-' . uniqid(),
            'description' => 'Test Cat',
        ]);

        $course = Course::create([
            'title'         => 'Curso de Seguridad Industrial',
            'slug'          => 'curso-seguridad-' . uniqid(),
            'description'   => 'Descripción de prueba',
            'instructor_id' => $instructor->id,
            'category_id'   => $category->id,
            'duration'      => 8.0,
            'level'         => 'intermediate',
            'price'         => 100,
            'status'        => 'published',
            'is_published'  => true,
        ]);

        $exam = Exam::create([
            'course_id'     => $course->id,
            'title'         => 'Examen Final',
            'passing_score' => 70,
            'time_limit'    => 60,
            'is_active'     => true,
        ]);

        $attempt = ExamAttempt::create([
            'exam_id'       => $exam->id,
            'user_id'       => $user->id,
            'score'         => 90,
            'passed'        => true,
            'attempt_count' => 1,
            'started_at'    => now()->subHour(),
            'completed_at'  => now(),
        ]);

        return Certificate::create(array_merge([
            'user_id'            => $user->id,
            'course_id'          => $course->id,
            'exam_attempt_id'    => $attempt->id,
            'certificate_code'   => 'CERT-' . strtoupper(uniqid()) . '-2026',
            'certificate_number' => '0001-2026-IPF-EDUCA',
            'total_hours'        => 8.0,
            'download_count'     => 0,
        ], $attributes));
    }

    /** @test */
    public function certificate_automatically_calculates_one_year_expiration_date_on_creation(): void
    {
        $issueDate = Carbon::parse('2026-03-15 10:00:00');
        $certificate = $this->createDummyCertificate([
            'issue_date' => $issueDate,
        ]);

        $this->assertNotNull($certificate->expiry_date);
        $this->assertEquals('2027-03-15 10:00:00', $certificate->expiry_date->format('Y-m-d H:i:s'));
        $this->assertEquals($certificate->expiry_date->timestamp, $certificate->expiration_date->timestamp);
        $this->assertFalse($certificate->isExpired());
        $this->assertTrue($certificate->isValid());
        $this->assertStringContainsString('marzo del 2027', $certificate->getFormattedExpiryDate());
    }

    /** @test */
    public function verify_view_validates_active_valid_certificate(): void
    {
        $certificate = $this->createDummyCertificate([
            'issue_date'  => now()->subMonths(2),
            'expiry_date' => now()->addMonths(10),
        ]);

        $response = $this->get(route('verify.certificate', $certificate->certificate_code));

        $response->assertStatus(200);
        $response->assertViewIs('student.certificates.verify');
        $response->assertViewHas('valid', true);
        $response->assertViewHas('status', 'valid');
        $response->assertSee('CERTIFICADO VÁLIDO Y VIGENTE');
        $response->assertSee('VIGENTE');
        $response->assertSee($certificate->certificate_code);
        $response->assertSee($certificate->user->names);
        $response->assertSee($certificate->course->title);
        $response->assertSee($certificate->expiry_date->format('d/m/Y'));
    }

    /** @test */
    public function verify_view_validates_expired_certificate_and_shows_details(): void
    {
        $certificate = $this->createDummyCertificate([
            'issue_date'  => Carbon::parse('2024-01-10 10:00:00'),
            'expiry_date' => Carbon::parse('2025-01-10 10:00:00'),
        ]);

        $response = $this->get(route('verify.certificate', $certificate->certificate_code));

        $response->assertStatus(200);
        $response->assertViewIs('student.certificates.verify');
        $response->assertViewHas('valid', false);
        $response->assertViewHas('isExpired', true);
        $response->assertViewHas('status', 'expired');
        $response->assertSee('CERTIFICADO AUTÉNTICO - VIGENCIA EXPIRADA');
        $response->assertSee('VIGENCIA EXPIRADA');
        $response->assertSee($certificate->certificate_code);
        $response->assertSee($certificate->user->names);
        $response->assertSee($certificate->course->title);
        $response->assertSee('10/01/2025');
    }

    /** @test */
    public function verify_view_handles_invalid_or_non_existent_certificate_code(): void
    {
        $response = $this->get(route('verify.certificate', 'CERT-INVALIDO-99999'));

        $response->assertStatus(200);
        $response->assertViewIs('student.certificates.verify');
        $response->assertViewHas('valid', false);
        $response->assertViewHas('status', 'not_found');
        $response->assertSee('CERTIFICADO NO VÁLIDO');
        $response->assertSee('CERT-INVALIDO-99999');
    }

    /** @test */
    public function verify_view_without_code_renders_search_form(): void
    {
        $response = $this->get(route('verify.certificate'));

        $response->assertStatus(200);
        $response->assertViewIs('student.certificates.verify');
        $response->assertViewHas('status', 'search');
        $response->assertSee('¿Deseas verificar otro certificado?');
    }
}
