import React from 'react';
import { createRoot } from 'react-dom/client';
import IdCard from './components/IdCard';

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
});
