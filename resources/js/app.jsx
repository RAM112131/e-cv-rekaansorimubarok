async function renderComponent(root, componentLoader, props) {
    const [{ createRoot }, { createElement }, { default: Component }] = await Promise.all([
        import('react-dom/client'),
        import('react'),
        componentLoader(),
    ]);

    if (!root.isConnected) {
        return;
    }

    createRoot(root).render(createElement(Component, props));
}

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('react-id-card-root');
    const hasFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (rootElement) {
        const loadIdCard = () => {
            if (rootElement.dataset.loading === 'true') {
                return;
            }

            rootElement.dataset.loading = 'true';

            renderComponent(rootElement, () => import('./components/IdCard'), {
                name: rootElement.dataset.name || 'USER',
                headline: rootElement.dataset.headline || 'Web Developer',
                avatar: rootElement.dataset.avatar || null,
                onReady: () => {
                    rootElement.classList.add('is-ready');
                },
            })
                .catch((error) => {
                    delete rootElement.dataset.loading;
                    console.error('Unable to load the interactive ID card.', error);
                });
        };

        // Render ID Card 3D immediately upon load
        loadIdCard();
    }

    const swarmCursorRoot = document.getElementById('react-swarm-cursor-root');
    if (swarmCursorRoot && hasFinePointer && !prefersReducedMotion) {
        document.addEventListener('pointermove', () => {
            const loadSwarmCursor = () => {
                renderComponent(swarmCursorRoot, () => import('./components/SwarmCursor'), {
                    color: '#eebb1b',
                    accentColor: '#f9c833',
                    opacity: 0.75,
                    count: 10,
                    size: 5,
                    speed: 2.5,
                    spread: 100,
                    wander: 0.25,
                    trail: 0.75,
                    scatterOnClick: true,
                })
                    .catch((error) => {
                        console.error('Unable to load the interactive cursor.', error);
                    });
            };

            if (typeof window.requestIdleCallback === 'function') {
                window.requestIdleCallback(loadSwarmCursor, { timeout: 1500 });
            } else {
                window.setTimeout(loadSwarmCursor, 500);
            }
        }, { once: true, passive: true });
    }
});
