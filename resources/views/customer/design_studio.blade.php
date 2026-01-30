<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SJM | AI Design Engine</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root { --gold: #d4af37; --bg: #050505; --panel: #111; --border: #333; }
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; display: flex; height: 100vh; overflow: hidden; margin:0; }
        
        /* LAYOUT */
        .sidebar { width: 350px; background: var(--panel); border-right: 1px solid var(--border); padding: 30px; display: flex; flex-direction: column; overflow-y: auto; }
        .canvas-area { flex: 1; background: radial-gradient(circle at center, #1a1a1a 0%, #000 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; }

        /* CONTROLS */
        h1 { font-family: 'Playfair Display'; color: var(--gold); margin-bottom: 5px; }
        .subtitle { font-size: 0.8rem; color: #666; margin-bottom: 30px; font-family: 'JetBrains Mono'; }
        
        .control-group { margin-bottom: 25px; }
        label { display: block; color: #888; font-size: 0.75rem; margin-bottom: 10px; letter-spacing: 1px; font-weight: 600; }
        
        input, select, textarea { 
            width: 100%; background: #000; border: 1px solid #333; color: #fff; 
            padding: 12px; border-radius: 6px; font-family: 'Inter'; outline: none; transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--gold); }
        textarea { height: 100px; resize: none; }

        /* BUTTONS */
        .btn-generate { 
            width: 100%; padding: 15px; background: linear-gradient(135deg, var(--gold), #b8860b); 
            border: none; border-radius: 6px; font-weight: bold; color: #000; cursor: pointer; 
            font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
        }
        .btn-generate:hover { transform: translateY(-2px); box-shadow: 0 0 20px rgba(212, 175, 55, 0.4); }
        .btn-generate:disabled { background: #333; color: #666; cursor: not-allowed; transform: none; box-shadow: none; }

        /* CANVAS */
        .preview-container { 
            width: 512px; height: 512px; border: 1px solid var(--border); 
            border-radius: 12px; overflow: hidden; position: relative; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.8); background: #000;
        }
        .preview-img { width: 100%; height: 100%; object-fit: cover; display: none; }
        
        /* LOADING ANIMATION */
        .loader { 
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
            display: none; text-align: center;
        }
        .spinner { 
            width: 50px; height: 50px; border: 3px solid rgba(212,175,55,0.3); 
            border-top: 3px solid var(--gold); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 15px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        .history-bar { 
            position: absolute; bottom: 0; left: 0; width: 100%; height: 100px; 
            background: rgba(0,0,0,0.8); border-top: 1px solid var(--border); 
            display: flex; align-items: center; padding: 0 20px; gap: 10px; overflow-x: auto;
        }
        .history-item { 
            width: 70px; height: 70px; border-radius: 6px; border: 1px solid #333; 
            cursor: pointer; object-fit: cover; opacity: 0.7; transition: 0.3s; 
        }
        .history-item:hover { opacity: 1; border-color: var(--gold); }

        .back-btn { position: absolute; top: 20px; left: 20px; color: #666; text-decoration: none; }
        .back-btn:hover { color: #fff; }
    </style>
</head>
<body>

    <a href="{{ route('home') }}" class="back-link" style="position: absolute; top: 20px; left: 380px; color: #fff; text-decoration: none; z-index: 99;">← Exit Studio</a>

    <aside class="sidebar">
        <h1>AI Design Studio</h1>
        <div class="subtitle">POWERED BY SJM NEURAL ENGINE</div>

        <div class="control-group">
            <label>1. SELECT STYLE (TRENDS)</label>
            <select id="styleInput">
                <option value="Modern">Modern Minimalist (2025)</option>
                <option value="Vintage">Art Deco Revival</option>
                <option value="Royal">Royal Heirloom</option>
                <option value="Cyberpunk">Futuristic / Cyberpunk</option>
                <option value="Nature">Organic / Nature Inspired</option>
            </select>
        </div>

        <div class="control-group">
            <label>2. MATERIAL BASE</label>
            <select id="metalInput">
                <option value="18k Gold">18k Yellow Gold</option>
                <option value="Rose Gold">Rose Gold</option>
                <option value="Platinum">Platinum</option>
                <option value="Black Titanium">Black Titanium</option>
            </select>
        </div>

        <div class="control-group">
            <label>3. GEMSTONE</label>
            <select id="gemInput">
                <option value="Diamond">VVS1 Diamond</option>
                <option value="Sapphire">Royal Blue Sapphire</option>
                <option value="Emerald">Colombian Emerald</option>
                <option value="Ruby">Pigeon Blood Ruby</option>
            </select>
        </div>

        <div class="control-group">
            <label>4. CUSTOM PROMPT (MODIFICATIONS)</label>
            <textarea id="promptInput" placeholder="Describe the shape, details, or specific modifications (e.g., 'A ring shaped like a serpent wrapping around the finger')..."></textarea>
        </div>

        <button class="btn-generate" id="generateBtn" onclick="generateDesign()">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Design
        </button>
    </aside>

    <main class="canvas-area">
        <div class="preview-container">
            <div class="loader" id="loader">
                <div class="spinner"></div>
                <div style="font-family:'JetBrains Mono'; font-size:0.8rem; color:var(--gold);">RENDERING...</div>
            </div>
            
            <img id="resultImage" class="preview-img" alt="Generated Jewelry">
            
            <div id="placeholderText" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; color:#444;">
                <i class="fa-solid fa-gem" style="font-size:3rem; margin-bottom:10px;"></i><br>
                Configure parameters<br>to initialize render.
            </div>
        </div>
        
        <div style="margin-top: 20px; font-family: 'JetBrains Mono'; font-size: 0.7rem; color: #555;">
            * AI generated designs are approximate visualizations.
        </div>

        <div class="history-bar">
            <span style="font-size:0.7rem; color:#666; margin-right:10px;">RECENT<br>SCANS</span>
            @foreach($history as $design)
                <img src="{{ $design->image_url }}" class="history-item" onclick="loadHistory('{{ $design->image_url }}')">
            @endforeach
        </div>
    </main>

    <script>
        async function generateDesign() {
            const btn = document.getElementById('generateBtn');
            const loader = document.getElementById('loader');
            const img = document.getElementById('resultImage');
            const placeholder = document.getElementById('placeholderText');

            // 1. Gather Data
            const data = {
                style: document.getElementById('styleInput').value,
                metal: document.getElementById('metalInput').value,
                gemstone: document.getElementById('gemInput').value,
                description: document.getElementById('promptInput').value || "A masterpiece ring"
            };

            // 2. UI Updates (Loading)
            btn.disabled = true;
            btn.innerHTML = 'INITIALIZING NEURAL NET...';
            img.style.display = 'none';
            placeholder.style.display = 'none';
            loader.style.display = 'block';

            try {
                // 3. Call Laravel Backend
                const response = await fetch("{{ route('design.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // 4. Show Result
                    img.src = result.image_url;
                    img.onload = () => {
                        loader.style.display = 'none';
                        img.style.display = 'block';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Generate Design';
                    };
                } else {
                    alert('Generation Failed');
                    resetUI();
                }

            } catch (error) {
                console.error(error);
                alert('System Error');
                resetUI();
            }
        }

        function resetUI() {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('generateBtn').disabled = false;
            document.getElementById('generateBtn').innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Generate Design';
            document.getElementById('placeholderText').style.display = 'block';
        }

        function loadHistory(url) {
            document.getElementById('resultImage').src = url;
            document.getElementById('resultImage').style.display = 'block';
            document.getElementById('placeholderText').style.display = 'none';
        }
    </script>
</body>
</html>