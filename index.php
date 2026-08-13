<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-Zero Startup OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hidden { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center p-4 py-12">

    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                Day-Zero Startup OS
            </h1>
            <p class="text-slate-400 text-lg">
                From raw idea to functional MVP architecture in seconds.
            </p>
        </div>

        <!-- Idea Generator Section -->
        <div class="glass-panel rounded-2xl p-8 md:p-12 text-center shadow-2xl mb-8">
            <h2 class="text-2xl font-bold mb-6 text-slate-200">1. Generate or Enter an Idea</h2>
            
            <div id="idea-container" class="min-h-[120px] flex flex-col items-center justify-center mb-8 bg-slate-800/50 rounded-xl p-6 border border-slate-700">
                <textarea id="idea-input" class="w-full bg-transparent text-center text-xl md:text-2xl font-medium text-slate-200 resize-none outline-none placeholder-slate-500" rows="3" placeholder="Click generate or type your startup idea here..."></textarea>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button id="generate-btn" class="bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-full transition-all">
                    Generate Random Idea
                </button>
                <button id="scope-btn" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    Scope Architecture & Roadmap
                </button>
            </div>
        </div>

        <!-- Scoping & Architecture Engine Section -->
        <div id="scoping-results" class="glass-panel rounded-2xl p-8 md:p-12 shadow-2xl hidden">
            <h2 class="text-2xl font-bold mb-8 text-slate-200 border-b border-slate-700 pb-4">2. MVP Scoping & Architecture</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Tech Spec -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-blue-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Technical Specification
                    </h3>
                    <ul id="tech-spec-list" class="space-y-4 text-slate-300">
                        <!-- Populated by JS -->
                    </ul>
                </div>

                <!-- Roadmap -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-purple-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Feature Roadmap
                    </h3>
                    <div id="roadmap-list" class="space-y-6 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-700 before:to-transparent">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Idea Generator Logic
        const audiences = ["for remote workers", "for freelance designers", "for indie hackers", "for busy parents", "for pet owners", "for local restaurants", "for fitness enthusiasts", "for college students", "for content creators", "for real estate agents"];
        const problems = ["that automates tax calculations", "that gamifies habit tracking", "that simplifies meal planning", "that connects them with mentors", "that optimizes their daily schedule", "that helps them find niche communities", "that manages their subscriptions", "that generates personalized marketing copy", "that tracks their carbon footprint", "that curates weekly learning resources"];
        const technologies = ["using AI-generated insights.", "powered by blockchain.", "using a low-code mobile app.", "built as a Notion template.", "delivered via a daily newsletter.", "using a conversational SMS bot.", "powered by a hyper-local marketplace.", "using augmented reality.", "delivered through a Chrome extension.", "built as a Slack integration."];

        const ideaInput = document.getElementById('idea-input');
        const generateBtn = document.getElementById('generate-btn');
        const scopeBtn = document.getElementById('scope-btn');
        const scopingResults = document.getElementById('scoping-results');
        const techSpecList = document.getElementById('tech-spec-list');
        const roadmapList = document.getElementById('roadmap-list');

        function generateIdea() {
            const audience = audiences[Math.floor(Math.random() * audiences.length)];
            const problem = problems[Math.floor(Math.random() * problems.length)];
            const technology = technologies[Math.floor(Math.random() * technologies.length)];
            
            ideaInput.value = `A platform ${audience} ${problem} ${technology}`;
            
            // Auto-resize textarea
            ideaInput.style.height = 'auto';
            ideaInput.style.height = ideaInput.scrollHeight + 'px';
        }

        generateBtn.addEventListener('click', generateIdea);

        // Auto-resize on type
        ideaInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Scoping & Architecture Logic
        const techStacks = [
            { front: 'React / Next.js', back: 'Node.js / Express', db: 'PostgreSQL', host: 'Vercel / Heroku' },
            { front: 'Vue / Nuxt.js', back: 'Python / FastAPI', db: 'MongoDB', host: 'AWS / Render' },
            { front: 'HTML / Tailwind CSS', back: 'PHP / Laravel', db: 'MySQL', host: 'DigitalOcean' },
            { front: 'React Native', back: 'Firebase / Serverless', db: 'Firestore', host: 'Firebase Hosting' }
        ];

        const roadmapTemplates = [
            {
                mvp: "User Authentication, Core Data Input, Basic Dashboard",
                growth: "Social Sharing, Advanced Analytics, Email Notifications",
                scale: "API Access, Enterprise SSO, Machine Learning Recommendations"
            },
            {
                mvp: "Landing Page, Payment Integration, Single Core Feature",
                growth: "User Profiles, Community Forum, Subscription Tiers",
                scale: "Mobile App Launch, Multi-language Support, B2B White-labeling"
            },
            {
                mvp: "CRUD Operations, Search functionality, Basic UI",
                growth: "Push Notifications, Real-time Chat, Third-party Integrations",
                scale: "Automated Workflows, Advanced Security Audit, Global CDN"
            }
        ];

        function scopeIdea() {
            const idea = ideaInput.value.trim();
            if (!idea) {
                alert("Please generate or enter an idea first!");
                return;
            }

            // Pseudo-random based on idea length to keep it consistent for the same idea
            const hash = idea.length;
            const stack = techStacks[hash % techStacks.length];
            const roadmap = roadmapTemplates[hash % roadmapTemplates.length];

            // Render Tech Spec
            techSpecList.innerHTML = `
                <li class="bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                    <span class="block text-sm text-slate-500 mb-1">Frontend</span>
                    <strong class="text-slate-200">${stack.front}</strong>
                </li>
                <li class="bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                    <span class="block text-sm text-slate-500 mb-1">Backend</span>
                    <strong class="text-slate-200">${stack.back}</strong>
                </li>
                <li class="bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                    <span class="block text-sm text-slate-500 mb-1">Database</span>
                    <strong class="text-slate-200">${stack.db}</strong>
                </li>
                <li class="bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                    <span class="block text-sm text-slate-500 mb-1">Hosting / DevOps</span>
                    <strong class="text-slate-200">${stack.host}</strong>
                </li>
            `;

            // Render Roadmap
            roadmapList.innerHTML = `
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-800 text-slate-300 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                        <h4 class="font-bold text-slate-200 mb-1">Phase 1: MVP</h4>
                        <p class="text-sm text-slate-400">${roadmap.mvp}</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mt-6">
                    <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-800 text-slate-300 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                        <h4 class="font-bold text-slate-200 mb-1">Phase 2: Growth</h4>
                        <p class="text-sm text-slate-400">${roadmap.growth}</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mt-6">
                    <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-800 text-slate-300 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] bg-slate-800/50 p-4 rounded-lg border border-slate-700">
                        <h4 class="font-bold text-slate-200 mb-1">Phase 3: Scale</h4>
                        <p class="text-sm text-slate-400">${roadmap.scale}</p>
                    </div>
                </div>
            `;

            // Show results
            scopingResults.classList.remove('hidden');
            scopingResults.classList.add('fade-in');
            
            // Scroll to results
            setTimeout(() => {
                scopingResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }

        scopeBtn.addEventListener('click', scopeIdea);
    </script>
</body>
</html>