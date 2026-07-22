@extends('layouts.app')

@section('title', 'Massage Campus — Institute Education, Training & Course Catalog')
@section('meta_description', 'Structured education, professional practitioner training, accredited institute courses, and wellness learning paths for students, instructors, and spa teams.')

@section('content')
{{-- Campus Hero Header --}}
<div class="bg-gradient-to-br from-ink-950 via-leaf-950 to-charcoal-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-leaf-300">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <span>Massage Campus Education Portal</span>
        </div>
        <h1 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-5xl">Professional Practitioner Education & Accredited Institute Courses</h1>
        <p class="mt-3 max-w-2xl text-base text-ink-200">Structured learning for massage students, certified practitioners, instructors, and spa managers. Explore course modules, class rosters, accredited institutes, and interactive knowledge checks.</p>

        {{-- Role Switcher Preview Tabs --}}
        <div x-data="{ activeRole: 'student' }" class="mt-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-bold uppercase tracking-wider text-ink-300">View Portal Perspective:</span>
                <button type="button" @click="activeRole = 'student'"
                        :class="activeRole === 'student' ? 'bg-leaf-500 text-white font-bold' : 'bg-white/10 text-white/80 hover:bg-white/20'"
                        class="rounded-xl px-4 py-2 text-xs font-semibold transition">
                    👨‍🎓 Student / Learner
                </button>
                <button type="button" @click="activeRole = 'instructor'"
                        :class="activeRole === 'instructor' ? 'bg-leaf-500 text-white font-bold' : 'bg-white/10 text-white/80 hover:bg-white/20'"
                        class="rounded-xl px-4 py-2 text-xs font-semibold transition">
                    👩‍🏫 Instructor / Teacher
                </button>
                <button type="button" @click="activeRole = 'admin'"
                        :class="activeRole === 'admin' ? 'bg-leaf-500 text-white font-bold' : 'bg-white/10 text-white/80 hover:bg-white/20'"
                        class="rounded-xl px-4 py-2 text-xs font-semibold transition">
                    🏫 Institute Administrator
                </button>
            </div>

            {{-- Role Overview Banners --}}
            <div x-show="activeRole === 'student'" class="mt-4 rounded-2xl bg-white/10 p-4 backdrop-blur text-xs text-ink-100">
                <strong class="font-bold text-white">Student Portal:</strong> Access enrolled course modules, view lesson plans, track progress certificates, complete quizzes, and perform class session sign-in/out.
            </div>
            <div x-show="activeRole === 'instructor'" x-cloak class="mt-4 rounded-2xl bg-white/10 p-4 backdrop-blur text-xs text-ink-100">
                <strong class="font-bold text-white">Instructor Portal:</strong> Manage course syllabi, review practical student assignments, oversee class session sign-in sheets, and grade module quizzes.
            </div>
            <div x-show="activeRole === 'admin'" x-cloak class="mt-4 rounded-2xl bg-white/10 p-4 backdrop-blur text-xs text-ink-100">
                <strong class="font-bold text-white">Administrator Portal:</strong> Manage institute accreditation, program catalogs, faculty assignments, student rosters, and graduation records.
            </div>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 space-y-14">
    {{-- Section 1: Class Attendance (Session Sign-In / Sign-Out Demonstration) --}}
    <section aria-labelledby="class-attendance" class="rounded-3xl border border-leaf-200 bg-gradient-to-br from-leaf-50 via-white to-ink-50 p-6 shadow-md lg:p-8 dark:border-leaf-900/60 dark:from-leaf-950/60 dark:via-ink-900 dark:to-charcoal-950">
        <div class="grid gap-6 lg:grid-cols-[1fr_22rem] lg:items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-leaf-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Class Session Attendance System</span>
                </div>
                <h2 id="class-attendance" class="mt-3 text-2xl font-black text-ink-950 dark:text-ink-50">Institute Class Sign-In & Sign-Out</h2>
                <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">For students enrolled in active institute courses and clinical workshops. Class sign-in/out records practical clock-in hours required for accredited certification, distinct from your platform user account authentication.</p>

                <div class="mt-4 rounded-2xl border border-leaf-200 bg-white p-4 dark:border-leaf-800 dark:bg-ink-950 space-y-2 text-xs">
                    <div class="flex items-center justify-between font-bold text-ink-900 dark:text-ink-100">
                        <span>Active Session: {{ $sampleSessionAttendance['course_title'] }}</span>
                        <span class="rounded bg-leaf-100 px-2 py-0.5 text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ $sampleSessionAttendance['current_status'] }}</span>
                    </div>
                    <p class="text-ink-500">Instructor: {{ $sampleSessionAttendance['instructor'] }} &bull; Location: {{ $sampleSessionAttendance['room'] }}</p>
                    <p class="text-ink-500">Scheduled: {{ $sampleSessionAttendance['scheduled_time'] }}</p>
                </div>
            </div>

            {{-- Interactive Sign-In / Sign-Out Action Widget --}}
            <div x-data="{ isSignedIn: false, attendanceTime: null }" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm text-center dark:border-ink-800 dark:bg-ink-900 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-ink-500">Student Attendance Action</h3>

                <template x-if="!isSignedIn">
                    <div class="space-y-3">
                        <div class="rounded-xl bg-leaf-50 p-3 text-xs text-leaf-800 dark:bg-leaf-950/60 dark:text-leaf-300">
                            Ready for Practical Session Sign-In
                        </div>
                        <button type="button" @click="isSignedIn = true; attendanceTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"
                                class="w-full rounded-xl bg-leaf-600 py-3 text-sm font-bold text-white shadow transition hover:bg-leaf-700">
                            🔑 Sign In to Class Session
                        </button>
                    </div>
                </template>

                <template x-if="isSignedIn">
                    <div class="space-y-3">
                        <div class="rounded-xl bg-ember-50 p-3 text-xs text-ember-900 dark:bg-ember-950/60 dark:text-ember-200">
                            Signed In at <strong x-text="attendanceTime"></strong> (Hours Recording...)
                        </div>
                        <button type="button" @click="isSignedIn = false"
                                class="w-full rounded-xl bg-ember-600 py-3 text-sm font-bold text-white shadow transition hover:bg-ember-700">
                            🚪 Sign Out from Class Session
                        </button>
                    </div>
                </template>
                <p class="text-[11px] text-ink-400">Class sign-in requires active course enrollment.</p>
            </div>
        </div>
    </section>

    {{-- Section 2: Course Catalog & Modules --}}
    <section aria-labelledby="courses-heading">
        <div class="flex items-center justify-between gap-4 border-b border-ink-100 pb-4 dark:border-ink-800">
            <div>
                <h2 id="courses-heading" class="text-2xl font-black text-ink-950 dark:text-ink-50">Accredited Course Catalog</h2>
                <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Browse professional courses, module structures, and practical requirements.</p>
            </div>
            <span class="rounded-full bg-leaf-100 px-3 py-1 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                {{ count($courses) }} Courses Available
            </span>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2">
            @foreach ($courses as $course)
                <article class="group relative flex flex-col justify-between rounded-2xl border border-ink-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-full bg-leaf-100 px-2.5 py-0.5 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                                {{ $course['badge'] }}
                            </span>
                            <span class="text-xs font-semibold text-ink-400">{{ $course['duration'] }}</span>
                        </div>

                        <h3 class="mt-3 text-lg font-black text-ink-950 group-hover:text-leaf-700 dark:text-ink-50 dark:group-hover:text-leaf-400">{{ $course['title'] }}</h3>
                        <p class="mt-1 text-xs font-bold text-leaf-700 dark:text-leaf-400">{{ $course['institute'] }}</p>
                        <p class="mt-2 text-xs text-ink-600 dark:text-ink-300">{{ $course['description'] }}</p>

                        {{-- Modules Accordion Preview --}}
                        <div x-data="{ openModules: false }" class="mt-4 border-t border-ink-100 pt-3 dark:border-ink-800">
                            <button type="button" @click="openModules = !openModules" class="flex items-center justify-between w-full text-xs font-bold text-ink-700 dark:text-ink-300 hover:text-leaf-700">
                                <span>Module Outline ({{ $course['modules_count'] }} Modules)</span>
                                <span x-text="openModules ? '▲ Hide' : '▼ View'"></span>
                            </button>
                            <ul x-show="openModules" x-cloak class="mt-2 space-y-1.5 text-xs text-ink-600 dark:text-ink-400">
                                @foreach ($course['modules'] as $mod)
                                    <li class="flex items-center gap-2 rounded bg-ink-50 p-2 dark:bg-ink-950">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5 text-leaf-600 shrink-0"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                                        <span>{{ $mod }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-ink-100 pt-4 flex items-center justify-between text-xs dark:border-ink-800">
                        <span class="text-ink-500">Instructor: <strong>{{ $course['instructor'] }}</strong></span>
                        <button type="button" onclick="alert('Course Details: {{ $course['title'] }}\nInstructor: {{ $course['instructor'] }}\nDelivery: {{ $course['delivery'] }}')"
                                class="rounded-xl bg-leaf-600 px-4 py-2 font-bold text-white shadow transition hover:bg-leaf-700">
                            Course Details
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Section 3: Accredited Institutes & Faculty Instructors --}}
    <section aria-labelledby="institutes-heading" class="grid gap-8 lg:grid-cols-2">
        <div>
            <h2 id="institutes-heading" class="text-2xl font-black text-ink-950 dark:text-ink-50">Affiliated Partner Institutes</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Recognized training schools and wellness academies.</p>

            <div class="mt-4 space-y-4">
                @foreach ($institutes as $inst)
                    <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                        <h3 class="text-base font-black text-ink-950 dark:text-ink-50">{{ $inst['name'] }}</h3>
                        <p class="mt-1 text-xs text-ink-500">{{ $inst['location'] }} &bull; {{ $inst['programs'] }}</p>
                        <span class="mt-2 inline-block rounded bg-leaf-100 px-2.5 py-1 text-[11px] font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                            {{ $inst['accreditation'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-black text-ink-950 dark:text-ink-50">Featured Faculty Instructors</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Licensed teachers, traditional masters, and physical therapists.</p>

            <div class="mt-4 space-y-4">
                @foreach ($instructors as $inst)
                    <div class="flex items-center gap-4 rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                        <div class="inline-flex size-12 items-center justify-center rounded-full bg-leaf-100 text-leaf-700 font-bold text-lg dark:bg-leaf-950 dark:text-leaf-300">
                            {{ substr($inst['name'], 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-base font-black text-ink-950 dark:text-ink-50">{{ $inst['name'] }}</h3>
                            <p class="text-xs text-leaf-700 dark:text-leaf-400">{{ $inst['title'] }} &bull; {{ $inst['institute'] }}</p>
                            <p class="mt-1 text-xs text-ink-500">Specialty: <strong>{{ $inst['specialty'] }}</strong> ({{ $inst['students_count'] }} Students Taught)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Section 4: Interactive Campus Quiz Preview --}}
    <section aria-labelledby="campus-quiz" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-md lg:p-8 dark:border-ink-800 dark:bg-ink-900">
        <div class="max-w-2xl" x-data="{ selected: null, submitted: false }">
            <span class="rounded-full bg-ember-100 px-3 py-1 text-xs font-bold text-ember-900 dark:bg-ember-950 dark:text-ember-200">
                Interactive Knowledge Check
            </span>
            <h3 id="campus-quiz" class="mt-3 text-xl font-black text-ink-950 dark:text-ink-50">{{ $sampleQuiz['question'] }}</h3>
            <p class="mt-1 text-xs text-ink-500">Course: {{ $sampleQuiz['course'] }}</p>

            <div class="mt-4 space-y-2.5">
                @foreach ($sampleQuiz['options'] as $key => $opt)
                    <button type="button" @click="selected = '{{ $key }}'"
                            :class="selected === '{{ $key }}' ? 'border-ember-500 bg-ember-50 dark:bg-ember-950/60 font-bold' : 'border-ink-100 bg-ink-50 hover:bg-ink-100 dark:border-ink-800 dark:bg-ink-950'"
                            class="w-full rounded-xl border p-3.5 text-left text-xs text-ink-900 dark:text-ink-100 transition flex items-center justify-between">
                        <span><strong>{{ $key }}.</strong> {{ $opt }}</span>
                        <span x-show="selected === '{{ $key }}'" class="text-ember-600 font-bold">Selected</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-5">
                <button type="button" @click="submitted = true" :disabled="!selected"
                        class="rounded-xl bg-ember-500 px-6 py-2.5 text-xs font-bold text-white shadow transition hover:bg-ember-600 disabled:opacity-50">
                    Submit Answer
                </button>
            </div>

            <div x-show="submitted" x-cloak class="mt-4 rounded-xl border border-leaf-300 bg-leaf-50 p-4 text-xs dark:border-leaf-700 dark:bg-leaf-950">
                <p class="font-bold text-leaf-800 dark:text-leaf-300" x-text="selected === '{{ $sampleQuiz['correct_option'] }}' ? '✓ Correct! +10 Campus Points' : '× Incorrect. The correct answer is {{ $sampleQuiz['correct_option'] }}.'"></p>
                <p class="mt-1 text-leaf-700 dark:text-leaf-200">{{ $sampleQuiz['explanation'] }}</p>
            </div>
        </div>
    </section>
</div>
@endsection
