<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-Zero Startup Idea Generator</title>
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
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <div class="max-w-3xl w-full glass-panel rounded-2xl p-8 md:p-12 text-center shadow-2xl">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
            Day-Zero Startup Idea Generator
        </h1>
        <p class="text-slate-400 text-lg mb-12">
            Click the button below to generate a market-tested, AI-orchestrated startup concept.
        </p>

        <div id="idea-container" class="min-h-[150px] flex flex-col items-center justify-center mb-10">
            <p id="idea-text" class="text-2xl md:text-3xl font-medium text-slate-200 transition-opacity duration-300">
                Ready to build the next big thing?
            </p>
        </div>

        <button id="generate-btn" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-4 px-8 rounded-full text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
            Generate Startup Idea
        </button>
    </div>

    <script>
        const audiences = [
            "for remote workers",
            "for freelance designers",
            "for indie hackers",
            "for busy parents",
            "for pet owners",
            "for local restaurants",
            "for fitness enthusiasts",
            "for college students",
            "for content creators",
            "for real estate agents"
        ];

        const problems = [
            "that automates tax calculations",
            "that gamifies habit tracking",
            "that simplifies meal planning",
            "that connects them with mentors",
            "that optimizes their daily schedule",
            "that helps them find niche communities",
            "that manages their subscriptions",
            "that generates personalized marketing copy",
            "that tracks their carbon footprint",
            "that curates weekly learning resources"
        ];

        const technologies = [
            "using AI-generated insights.",
            "powered by blockchain.",
            "using a low-code mobile app.",
            "built as a Notion template.",
            "delivered via a daily newsletter.",
            "using a conversational SMS bot.",
            "powered by a hyper-local marketplace.",
            "using augmented reality.",
            "delivered through a Chrome extension.",
            "built as a Slack integration."
        ];

        const ideaText = document.getElementById('idea-text');
        const generateBtn = document.getElementById('generate-btn');

        function generateIdea() {
            const audience = audiences[Math.floor(Math.random() * audiences.length)];
            const problem = problems[Math.floor(Math.random() * problems.length)];
            const technology = technologies[Math.floor(Math.random() * technologies.length)];

            ideaText.style.opacity = 0;
            
            setTimeout(() => {
                ideaText.innerHTML = `A platform <span class="text-blue-400">${audience}</span> <br/> <span class="text-purple-400">${problem}</span> <br/> <span class="text-emerald-400">${technology}</span>`;
                ideaText.style.opacity = 1;
            }, 300);
        }

        generateBtn.addEventListener('click', generateIdea);
    </script>
</body>
</html>