import React, { useState, useEffect } from 'react';
import Lanyard from './Lanyard';

export default function IdCard() {
    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => setIsMobile(window.innerWidth < 1024);
        checkMobile(); // Check on mount
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    // Posisi kamera ditetapkan statis di tengah [0, 0, 15].
    // Pergeseran ke kanan dilakukan di dalam Lanyard.jsx (group position).
    const position = isMobile ? [0, 0, 16] : [0, 0, 10];
    const xOffset = isMobile ? 0 : 1;

    return (
        <Lanyard position={position} xOffset={xOffset} gravity={[0, -40, 0]} lanyardImage="/images/lanyard.png" />
    );
}
