<!-- Preloader -->
<div class="preloader-container">
    <div class="logo-container">
        <div class="spinner"></div>
        <div class="spinner-inner"></div>
        <div class="wings-effect"></div>
        <img src="assets/images/ksahc_logo.png" alt="KSAHC Logo" class="logo">
    </div>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>
    <div class="loading-text">
        LOADING<span class="loading-dots">
            <span class="loading-dot" style="--dot-index: 1">.</span>
            <span class="loading-dot" style="--dot-index: 2">.</span>
            <span class="loading-dot" style="--dot-index: 3">.</span>
        </span>
    </div>
</div>

<style>
    /* Preloader Styles */
    .preloader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s, visibility 0.5s;
    }

    .logo-container {
        position: relative;
        width: 200px;
        height: 200px;
    }

    .logo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        animation: pulse 2s infinite alternate;
    }

    .spinner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 4px solid transparent;
        border-radius: 50%;
        border-top: 4px solid var(--primary-color);
        border-right: 4px solid var(--primary-color);
        animation: spin 1.5s linear infinite;
    }

    .spinner-inner {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border: 3px solid transparent;
        border-radius: 50%;
        border-top: 3px solid var(--secondary-color);
        border-left: 3px solid var(--secondary-color);
        animation: spin-reverse 1.2s linear infinite;
    }

    .loading-text {
        margin-top: 30px;
        color: var(--primary-color);
        font-size: 18px;
        letter-spacing: 3px;
        position: relative;
    }

    .loading-dots {
        display: inline-block;
        width: 30px;
        text-align: left;
    }

    .loading-dot {
        animation: dot-fade 1.5s infinite;
        animation-delay: calc(0.3s * var(--dot-index));
        opacity: 0;
    }

    .progress-bar {
        width: 250px;
        height: 6px;
        background: rgba(78, 115, 223, 0.2);
        border-radius: 3px;
        margin-top: 20px;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 3px;
        animation: progress 3s ease-in-out infinite;
    }

    .wings-effect {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0) 70%);
        opacity: 0;
        animation: wings-glow 3s infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes spin-reverse {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(-360deg); }
    }

    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.7; }
        100% { transform: scale(1.05); opacity: 1; }
    }

    @keyframes dot-fade {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }

    @keyframes progress {
        0% { width: 5%; }
        50% { width: 75%; }
        100% { width: 95%; }
    }

    @keyframes wings-glow {
        0%, 100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
        50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.2); }
    }
</style>

<script>
    // Preloader
    window.addEventListener('load', function() {
        const preloader = document.querySelector('.preloader-container');
        setTimeout(() => {
            preloader.style.opacity = '0';
            preloader.style.visibility = 'hidden';
        }, 1000);
    });
</script>