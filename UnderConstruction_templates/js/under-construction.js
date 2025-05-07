// Under Construction Page Designs
function initUnderConstruction(container) {
    const design = container.getAttribute('data-uc-design');
    const title = container.getAttribute('data-title') || 'Coming Soon';
    const subtitle = container.getAttribute('data-subtitle') || 'We\'re crafting something amazing';
    const designs = {
        minimalist: createMinimalistDesign,
        animated: createAnimatedDesign,
        business: createBusinessDesign,
        playful: createPlayfulDesign
    };

    if (designs[design]) {
        designs[design](container, title, subtitle);
    }
}

// Design 1: Modern Minimalist
function createMinimalistDesign(container, title, subtitle) {
    container.innerHTML = `
        <style>
            .uc-minimalist {
                text-align: center;
                padding: 60px 20px;
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .uc-minimalist h2 {
                font-size: 2.5em;
                color: #333;
                margin-bottom: 20px;
                font-weight: 300;
            }
            .uc-minimalist p {
                color: #666;
                font-size: 1.2em;
                margin-bottom: 30px;
            }
            .uc-minimalist .progress-bar {
                width: 200px;
                height: 4px;
                background: #eee;
                margin: 0 auto;
                position: relative;
                overflow: hidden;
            }
            .uc-minimalist .progress-bar::after {
                content: '';
                position: absolute;
                left: -50%;
                height: 100%;
                width: 50%;
                background: #333;
                animation: progress 2s infinite linear;
            }
            @keyframes progress {
                0% { left: -50%; }
                100% { left: 100%; }
            }
        </style>
        <div class="uc-minimalist">
            <h2>${title}</h2>
            <p>${subtitle}</p>
            <div class="progress-bar"></div>
        </div>
    `;
}

// Design 2: Creative Animated
function createAnimatedDesign(container, title, subtitle) {
    container.innerHTML = `
        <style>
            .uc-animated {
                text-align: center;
                padding: 60px 20px;
                background: linear-gradient(135deg, #6e8efb, #a777e3);
                color: white;
                border-radius: 8px;
                position: relative;
                overflow: hidden;
            }
            .uc-animated h2 {
                font-size: 2.5em;
                margin-bottom: 20px;
                position: relative;
                z-index: 1;
            }
            .uc-animated p {
                font-size: 1.2em;
                margin-bottom: 30px;
                position: relative;
                z-index: 1;
            }
            .uc-animated .circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.1);
                animation: float 6s infinite ease-in-out;
            }
            .uc-animated .circle:nth-child(1) {
                width: 100px;
                height: 100px;
                top: 20%;
                left: 20%;
                animation-delay: 0s;
            }
            .uc-animated .circle:nth-child(2) {
                width: 150px;
                height: 150px;
                top: 60%;
                right: 20%;
                animation-delay: 2s;
            }
            .uc-animated .circle:nth-child(3) {
                width: 70px;
                height: 70px;
                bottom: 20%;
                left: 40%;
                animation-delay: 4s;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }
        </style>
        <div class="uc-animated">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <h2>${title}</h2>
            <p>${subtitle}</p>
        </div>
    `;
}

// Design 3: Professional Business
function createBusinessDesign(container, title, subtitle) {
    container.innerHTML = `
        <style>
            .uc-business {
                text-align: center;
                padding: 60px 20px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }
            .uc-business h2 {
                font-size: 2.5em;
                color: #2c3e50;
                margin-bottom: 20px;
                font-weight: 600;
            }
            .uc-business p {
                color: #6c757d;
                font-size: 1.2em;
                margin-bottom: 30px;
            }
            .uc-business .countdown {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 30px;
            }
            .uc-business .countdown-item {
                background: white;
                padding: 15px;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                min-width: 80px;
            }
            .uc-business .countdown-item span {
                display: block;
                font-size: 1.8em;
                color: #2c3e50;
                font-weight: 600;
            }
            .uc-business .countdown-item small {
                color: #6c757d;
                font-size: 0.9em;
            }
        </style>
        <div class="uc-business">
            <h2>${title}</h2>
            <p>${subtitle}</p>
            <div class="countdown">
                <div class="countdown-item">
                    <span>30</span>
                    <small>Days</small>
                </div>
                <div class="countdown-item">
                    <span>12</span>
                    <small>Hours</small>
                </div>
                <div class="countdown-item">
                    <span>45</span>
                    <small>Minutes</small>
                </div>
            </div>
        </div>
    `;
}

// Design 4: Playful Interactive
function createPlayfulDesign(container, title, subtitle) {
    container.innerHTML = `
        <style>
            .uc-playful {
                text-align: center;
                padding: 60px 20px;
                background: #fff;
                border-radius: 8px;
                position: relative;
            }
            .uc-playful h2 {
                font-size: 2.5em;
                color: #ff6b6b;
                margin-bottom: 20px;
            }
            .uc-playful p {
                color: #4a4a4a;
                font-size: 1.2em;
                margin-bottom: 30px;
            }
            .uc-playful .construction-icon {
                font-size: 4em;
                margin-bottom: 20px;
                cursor: pointer;
                transition: transform 0.3s ease;
            }
            .uc-playful .construction-icon:hover {
                transform: rotate(360deg);
            }
            .uc-playful .progress-dots {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 20px;
            }
            .uc-playful .dot {
                width: 12px;
                height: 12px;
                background: #ddd;
                border-radius: 50%;
                animation: pulse 1.5s infinite;
            }
            .uc-playful .dot:nth-child(2) { animation-delay: 0.5s; }
            .uc-playful .dot:nth-child(3) { animation-delay: 1s; }
            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.5; }
                50% { transform: scale(1.2); opacity: 1; }
            }
        </style>
        <div class="uc-playful">
            <div class="construction-icon">🏗️</div>
            <h2>${title}</h2>
            <p>${subtitle}</p>
            <div class="progress-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    `;

    // Add interactive functionality
    const icon = container.querySelector('.construction-icon');
    icon.addEventListener('click', () => {
        icon.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            icon.style.transform = 'rotate(0deg)';
        }, 300);
    });
} 