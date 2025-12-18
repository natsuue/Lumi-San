
document.addEventListener("DOMContentLoaded", function() {
    
    // Initialize modals only if they exist on the page
    let loginModalInstance = null;
    const authEl = document.getElementById("loginModal");
    if (authEl) {
        loginModalInstance = new bootstrap.Modal(authEl);
    }
    
    // 2. LOGIN/REGISTRATION 

    if (window.isUserLoggedIn === false) {
        document.querySelectorAll(".nav-link, .guarded-link").forEach(function(link) {
            link.addEventListener("click", function(event) {
                event.preventDefault(); // Stop navigation
                
                // Show the login modal if not logged in
                loginModalInstance?.show(); 
            });
        });
    }

  
    // 3. VIDEO PLAYER LOGIC
  
    const video = document.getElementById("introVideo");
    const pauseBtn = document.getElementById("pauseBtn");
    const playFullBtn = document.getElementById("playFullBtn");
    const overlay = document.querySelector(".video-overlay");
    const container = document.querySelector(".videoSplash"); 

    // Guard against running if elements don't exist
    if (video && pauseBtn && playFullBtn && overlay && container) {
        let fullVideoMode = false;

        // Pause/Play or Exit Full Video
        pauseBtn.addEventListener("click", () => {
            if (fullVideoMode) {
                // Exit full video mode
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
                video.pause();
                video.controls = false;
                video.muted = true;
                overlay.style.display = "block"; // Show overlay again
                pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                fullVideoMode = false;
                video.play();
            } else {
                // Normal pause/play toggle
                if (video.paused) {
                    video.play();
                    pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    video.pause();
                    pauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                }
            }
        });

        // Play Full Video
        playFullBtn.addEventListener("click", () => {
            video.muted = false;
            video.controls = true;
            video.play();
            overlay.style.display = "none"; // Hide overlay for full view
            pauseBtn.innerHTML = '<i class="fas fa-times"></i>'; // Change icon to 'X' to exit
            fullVideoMode = true;

            // Request fullscreen on the container (video + controls)
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
        });

        // Overlay Fade-In Logic
        setTimeout(() => {
             overlay.classList.add("show");
        }, 2000);
    }
});