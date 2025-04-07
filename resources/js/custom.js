import EasyMDE from 'easymde';

const textarea = document.getElementById('post-body');

// Listening for livewire:navigated so EasyMDE is loaded after liveware navigation
document.addEventListener('livewire:navigated', () => {
    if (location.pathname === '/create-post' || location.pathname.includes('edit')) {
        const easymode = new EasyMDE({
            element: document.getElementById('post-body'),
        });

        // Before submission populate ol textarea with EasyMDE's value.
        document.querySelector('form').addEventListener('submit', (e) => {
            textarea.value = easymode.value();
        });
    }
});
