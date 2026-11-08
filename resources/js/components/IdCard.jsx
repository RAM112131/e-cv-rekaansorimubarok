import React, { useState, useEffect } from 'react';
import { useTexture } from '@react-three/drei';
import Lanyard from './Lanyard';

export default function IdCard({ onReady }) {
    const [isMobile, setIsMobile] = useState(() => typeof window !== 'undefined' && window.innerWidth < 1024);

    useEffect(() => {
        const checkMobile = () => setIsMobile(window.innerWidth < 1024);
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    // Desktop: kamera [0, 0, 11] agar ID card berukuran besar seperti biasa di sebelah kanan.
    // Mobile: kamera [0, 0, 14.5] agar ID card menggantung pas di bawah topbar & di atas "Hello, I'm".
    const position = isMobile ? [0, 0, 14.5] : [0, 0, 11];
    const xOffset = isMobile ? 0 : 1.2;

    return (
        <Lanyard
            key={isMobile ? 'mobile' : 'desktop'}
            position={position}
            xOffset={xOffset}
            gravity={[0, -40, 0]}
            lanyardImage="/images/lanyard.webp"
            frontImage="/images/gambar-depan.webp?v=2"
            backImage="/images/gambar-belakang.webp?v=2"
            imageFit="fill"
            onReady={onReady}
        />
    );
}

useTexture.preload("/images/lanyard.webp");
useTexture.preload("/images/gambar-depan.webp?v=2");
useTexture.preload("/images/gambar-belakang.webp?v=2");
