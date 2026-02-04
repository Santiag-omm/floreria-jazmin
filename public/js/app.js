document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length) {
        setTimeout(() => alerts.forEach(alert => alert.classList.add('fade', 'show')), 50);
    }

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-flower-burst]');
        if (!trigger) return;

        const burst = document.createElement('div');
        burst.className = 'flower-burst';
        document.body.appendChild(burst);

        const { innerWidth, innerHeight } = window;
        for (let i = 0; i < 18; i += 1) {
            const petal = document.createElement('span');
            petal.className = 'flower-petal';
            const angle = Math.random() * Math.PI * 2;
            const distance = 20 + Math.random() * 40;
            petal.style.left = `${innerWidth / 2 + (Math.random() * 80 - 40)}px`;
            petal.style.top = `${innerHeight / 2 + (Math.random() * 80 - 40)}px`;
            petal.style.setProperty('--x', `${Math.cos(angle) * distance}px`);
            petal.style.setProperty('--y', `${Math.sin(angle) * distance}px`);
            burst.appendChild(petal);
        }

        setTimeout(() => burst.remove(), 1600);
    });
});
