import React, { useState, useEffect } from 'react';
import { useTexture } from '@react-three/drei';
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
    const position = isMobile ? [0, 0, 16] : [0, 0, 11];
    const xOffset = isMobile ? 0 : 1.2;

    return (
        <Lanyard
        position={position}
        xOffset={xOffset}
        gravity={[0, -40, 0]}
        lanyardImage="/images/lanyard.webp"
        frontImage="/images/gambar-depan.webp?v=2"
        backImage="/images/gambar-belakang.webp?v=2"
        imageFit="fill"
        />
    );
}

useTexture.preload("/images/lanyard.webp");
useTexture.preload("/images/gambar-depan.webp?v=2");
useTexture.preload("/images/gambar-belakang.webp?v=2");
