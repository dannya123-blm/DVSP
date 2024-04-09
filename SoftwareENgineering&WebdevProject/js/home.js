document.addEventListener('DOMContentLoaded', () => {
    const banner = document.querySelector('.banner');
    const images = [
        '../images/test3.png',
        '../images/test3.png',
        '../images/test3.png'
    ];
    let currentIndex = 0;
    let startX = 0;
    let threshold = banner.clientWidth * 0.2; // 20% of banner width as swipe threshold

    banner.addEventListener('mousedown', (e) => {
        startX = e.clientX;
        banner.style.transition = 'none'; // Disable transition during swipe
    });

    banner.addEventListener('mousemove', (e) => {
        if (startX) {
            let moveX = e.clientX - startX;
            banner.style.transform = `translateX(${moveX}px)`;
        }
    });

    banner.addEventListener('mouseup', (e) => {
        if (startX) {
            let moveX = e.clientX - startX;
            banner.style.transition = 'transform 0.1s ease'; // Re-enable transition
            if (Math.abs(moveX) > threshold) {
                // Swipe detected, change image
                if (moveX > 0) {
                    // Swipe right (previous image)
                    currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
                } else {
                    // Swipe left (next image)
                    currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
                }
                banner.style.transform = `translateX(${moveX > 0 ? banner.clientWidth : -banner.clientWidth}px)`;
                setTimeout(() => {
                    banner.style.transform = `translateX(0)`;
                    banner.style.backgroundImage = `url('${images[currentIndex]}')`;
                }, 300); // Transition duration
            } else {
                // Reset to initial position
                banner.style.transform = `translateX(0)`;
            }
            startX = 0;
        }
    });

    banner.addEventListener('mouseleave', () => {
        if (startX) {
            banner.style.transition = 'transform 0.1s ease';
            banner.style.transform = `translateX(0)`;
            startX = 0;
        }
    });

    // Initial background image
    banner.style.backgroundImage = `url('${images[currentIndex]}')`;
});
