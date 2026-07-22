<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function index(Request $request): View
    {
        $courses = [
            [
                'id' => 'CRS-101',
                'title' => 'Foundations of Therapeutic Massage & Client Ethics',
                'category' => 'Core Practitioner Training',
                'institute' => 'Philippine Academy of Wellness & Massage',
                'instructor' => 'Dr. Elena Santos, LMT',
                'level' => 'Beginner / Fundamental',
                'delivery' => 'Hybrid (Online + In-Clinic Practice)',
                'duration' => '6 Weeks (40 Hours)',
                'modules_count' => 5,
                'lessons_count' => 24,
                'enrolled_students' => 142,
                'rating' => 4.9,
                'badge' => 'Accredited Foundation',
                'description' => 'Comprehensive introduction to human anatomy, muscle groups, body mechanics, client intake, hygiene standards, and ethical boundary management.',
                'modules' => [
                    'Module 1: Human Anatomy & Musculoskeletal Ergonomics',
                    'Module 2: Client Consultation, Intake & Contraindications',
                    'Module 3: Sanitation, Drape Management & Hygiene Protocols',
                    'Module 4: Fundamental Effleurage & Petrissage Strokes',
                    'Module 5: Practical Assessment & Ethics Exam',
                ],
            ],
            [
                'id' => 'CRS-202',
                'title' => 'Traditional Hilot Healing & Indigenous Wellness Practices',
                'category' => 'Cultural & Traditional Techniques',
                'institute' => 'Visayas Traditional Healing Institute',
                'instructor' => 'Master Mang Pedro Alcantara',
                'level' => 'Intermediate Practitioner',
                'delivery' => 'In-Person Institute Workshops',
                'duration' => '4 Weeks (30 Hours)',
                'modules_count' => 4,
                'lessons_count' => 18,
                'enrolled_students' => 98,
                'rating' => 5.0,
                'badge' => 'Heritage Masterclass',
                'description' => 'Deep dive into traditional Filipino Hilot techniques, warm banana leaf diagnosis (Pagsusuri), botanical oils, nerve realignment, and holistic wellness philosophy.',
                'modules' => [
                    'Module 1: History & Philosophy of Philippine Hilot',
                    'Module 2: Pagsusuri: Leaf Diagnosis & Temperature Reading',
                    'Module 3: Dagdagay Foot Massage & Herbal Compresses',
                    'Module 4: Supervised Clinical Hilot Practice',
                ],
            ],
            [
                'id' => 'CRS-305',
                'title' => 'Deep Tissue & Myofascial Trigger Point Therapy',
                'category' => 'Clinical & Recovery Massage',
                'institute' => 'Manila Sports & Rehabilitation College',
                'instructor' => 'Prof. Gabriel Reyes, PT, LMT',
                'level' => 'Advanced Clinical Specialist',
                'delivery' => 'In-Person Clinical Lab',
                'duration' => '8 Weeks (60 Hours)',
                'modules_count' => 6,
                'lessons_count' => 32,
                'enrolled_students' => 76,
                'rating' => 4.8,
                'badge' => 'Clinical Specialist',
                'description' => 'Advanced anatomical target assessment, trigger point palpation, myofascial release, joint mobility restoration, and sports recovery protocols.',
                'modules' => [
                    'Module 1: Neuromuscular Anatomy & Pain Pathways',
                    'Module 2: Trigger Point Identification & Ischemic Compression',
                    'Module 3: Myofascial Release Techniques',
                    'Module 4: Post-Injury Recovery Protocols',
                    'Module 5: Ergonomic Protection for Practitioners',
                    'Module 6: Final Clinical Supervised Assessment',
                ],
            ],
            [
                'id' => 'CRS-401',
                'title' => 'Spa Operations, Sanitation & Team Leadership',
                'category' => 'Management & Administration',
                'institute' => 'Massage Nexus Leadership Academy',
                'instructor' => 'Maria Theresa Cruz, MBA',
                'level' => 'Spa Manager / Administrator',
                'delivery' => 'Online Self-Paced',
                'duration' => '3 Weeks (20 Hours)',
                'modules_count' => 3,
                'lessons_count' => 12,
                'enrolled_students' => 215,
                'rating' => 4.9,
                'badge' => 'Management Certificate',
                'description' => 'Operational management for spa directors and senior therapists: scheduling, sanitation audits, staff compliance, client satisfaction, and facility safety.',
                'modules' => [
                    'Module 1: Facility Hygiene Audits & Infection Control',
                    'Module 2: Practitioner Scheduling & Staff Wellbeing',
                    'Module 3: Service Quality Assurance & Review Handling',
                ],
            ],
        ];

        $instructors = [
            [
                'name' => 'Dr. Elena Santos, LMT',
                'title' => 'Director of Anatomical Studies',
                'institute' => 'Philippine Academy of Wellness & Massage',
                'courses_count' => 6,
                'students_count' => 1240,
                'specialty' => 'Anatomy & Ergonomics',
            ],
            [
                'name' => 'Master Mang Pedro Alcantara',
                'title' => 'Traditional Hilot Master',
                'institute' => 'Visayas Traditional Healing Institute',
                'courses_count' => 4,
                'students_count' => 890,
                'specialty' => 'Indigenous Hilot & Botanical Healing',
            ],
            [
                'name' => 'Prof. Gabriel Reyes, PT, LMT',
                'title' => 'Lead Clinical Instructor',
                'institute' => 'Manila Sports & Rehabilitation College',
                'courses_count' => 5,
                'students_count' => 650,
                'specialty' => 'Myofascial Release & Sports Therapy',
            ],
        ];

        $institutes = [
            [
                'name' => 'Philippine Academy of Wellness & Massage',
                'location' => 'Quezon City, Metro Manila',
                'programs' => '8 Accredited Certification Programs',
                'accreditation' => 'DOH-DOH Accredited Massage Training Center',
            ],
            [
                'name' => 'Visayas Traditional Healing Institute',
                'location' => 'Cebu City & Mandaue',
                'programs' => '5 Heritage Hilot & Spa Masterclasses',
                'accreditation' => 'Heritage Healing Guild Certified',
            ],
            [
                'name' => 'Manila Sports & Rehabilitation College',
                'location' => 'Makati City',
                'programs' => '6 Clinical & Sports Recovery Courses',
                'accreditation' => 'Physical Therapy Association Partner',
            ],
        ];

        $sampleQuiz = [
            'id' => 'QZ-101',
            'course' => 'Foundations of Therapeutic Massage',
            'question' => 'Which muscle group is primarily targeted during effleurage strokes along the upper back to relieve postural tension?',
            'options' => [
                'A' => 'Trapezius and Rhomboids',
                'B' => 'Quadriceps Femoris',
                'C' => 'Gastrocnemius and Soleus',
                'D' => 'Brachioradialis',
            ],
            'correct_option' => 'A',
            'explanation' => 'The Trapezius and Rhomboid muscle groups span the upper back and neck, making them the primary focus during upper-back effleurage and postural relief.',
        ];

        $sampleSessionAttendance = [
            'class_code' => 'CLASS-2026-HILOT-04',
            'course_title' => 'Traditional Hilot Healing (Module 2 Practical Session)',
            'instructor' => 'Master Mang Pedro Alcantara',
            'room' => 'Clinic Lab Room 3B',
            'scheduled_time' => '14:00 - 17:00 (Today)',
            'current_status' => 'Session Active',
            'student_enrolled_count' => 18,
            'students' => [
                ['name' => 'Ana Ramirez', 'status' => 'Signed In', 'time' => '13:52 PM'],
                ['name' => 'Carlos Mendoza', 'status' => 'Signed In', 'time' => '13:58 PM'],
                ['name' => 'Josephine Tan', 'status' => 'Signed In', 'time' => '14:01 PM'],
                ['name' => 'Mark Bautista', 'status' => 'Signed Out', 'time' => '17:02 PM'],
            ],
        ];

        return view('campus.index', [
            'courses' => $courses,
            'instructors' => $instructors,
            'institutes' => $institutes,
            'sampleQuiz' => $sampleQuiz,
            'sampleSessionAttendance' => $sampleSessionAttendance,
        ]);
    }
}
