import React from 'react';
import { createRoot } from 'react-dom/client';
import IdCard from './components/IdCard';
import SwarmCursor from './components/SwarmCursor';

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('react-id-card-root');

    if (rootElement) {
        // Read props from data attributes
        const name = rootElement.dataset.name || 'USER';
        const headline = rootElement.dataset.headline || 'Web Developer';
        const avatar = rootElement.dataset.avatar || null;

        const root = createRoot(rootElement);
        root.render(
            <IdCard name={name} headline={headline} avatar={avatar} />
        );
    }

    const swarmCursorRoot = document.getElementById('react-swarm-cursor-root');
    if (swarmCursorRoot) {
        const root = createRoot(swarmCursorRoot);
        root.render(
            <SwarmCursor
                color="#eebb1b"
                accentColor="#f9c833"
                opacity={0.75}
                count={10}
                size={5}
                speed={2.5}
                spread={100}
                wander={0.25}
                trail={0.75}
                scatterOnClick
            />
        );
    }
});
