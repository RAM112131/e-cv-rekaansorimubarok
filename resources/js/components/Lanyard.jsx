/* eslint-disable react/no-unknown-property */
'use client';
import { useEffect, useMemo, useRef, useState } from 'react';
import { Canvas, extend, useFrame } from '@react-three/fiber';
import { useGLTF, useTexture, Environment, Lightformer } from '@react-three/drei';
import { BallCollider, CuboidCollider, Physics, RigidBody, useRopeJoint, useSphericalJoint } from '@react-three/rapier';
import { MeshLineGeometry, MeshLineMaterial } from 'meshline';

// Use public URLs to bypass Vite import analysis (avoids requiring a restart)
const cardGLB = '/models/card.glb?v=2';
const lanyard = '/images/lanyard.webp';

try {
  useGLTF.preload(cardGLB);
  useTexture.preload(lanyard);
  useTexture.preload('/images/gambar-depan.webp?v=2');
  useTexture.preload('/images/gambar-belakang.webp?v=2');
} catch (e) {
  // Ignore in SSR
}

import * as THREE from 'three';

extend({ MeshLineGeometry, MeshLineMaterial });

// 1x1 transparent pixel — lets useTexture be called unconditionally when a
// front/back image isn't supplied.
const BLANK_PIXEL =
  'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

// The card model's front face is UV-mapped to the LEFT half of the texture
// atlas and the back face to the RIGHT half (measured from card.glb). Each
// custom image is composited into its own half so the two faces render
// independently, aspect-preserving (no stretching).
const FRONT_UV_RECT = { x: 0, y: 0, w: 0.5, h: 0.755 };
const BACK_UV_RECT = { x: 0.5, y: 0, w: 0.5, h: 0.757 };

// Reusable vectors — created ONCE outside component to avoid GC pressure
const vec = new THREE.Vector3();
const dir = new THREE.Vector3();
const anchorVec = new THREE.Vector3();

export default function Lanyard({
  position = [0, 0, 30],
  gravity = [0, -40, 0],
  fov = 20,
  transparent = true,
  frontImage = null,
  backImage = null,
  imageFit = 'cover',
  lanyardImage = null,
  lanyardWidth = 1,
  xOffset = 0,
  onReady
}) {
  const containerRef = useRef(null);
  const [isVisible, setIsVisible] = useState(true);
  const [isMobile, setIsMobile] = useState(() => typeof window !== 'undefined' && window.innerWidth < 1024);

  useEffect(() => {
    const container = containerRef.current;

    if (!container || !('IntersectionObserver' in window)) {
      return;
    }

    const observer = new IntersectionObserver(([entry]) => {
      setIsVisible(entry.isIntersecting);
    }, { rootMargin: '100px' });

    observer.observe(container);

    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 1024);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  // ─── DPR: Cap di 1.5 untuk smooth 60fps. ─────────────────────────────────
  // Desktop layar biasa = 1.0, HiDPI max = 1.5 (bukan 2 yang berat)
  const dpr = isMobile ? [1, 1.25] : [1, 1.5];

  return (
    <div ref={containerRef} className="relative z-0 w-full h-screen flex justify-center items-center transform scale-100 origin-center touch-none select-none">
      <Canvas
        style={{ touchAction: 'none' }}
        camera={{ position: position, fov: fov }}
        dpr={dpr}
        gl={{
          alpha: transparent,
          // ─── GPU optimizations ────────────────────────────────────────
          antialias: !isMobile,       // Matikan antialiasing di mobile (berat)
          powerPreference: 'high-performance', // Request dedicated GPU
          stencil: false,             // Tidak pakai stencil buffer (hemat memory)
          depth: true,
        }}
        frameloop={isVisible ? 'always' : 'never'}
        performance={{ min: 0.5 }}    // R3F auto-degrade DPR jika fps turun di bawah 30
        onCreated={({ gl }) => {
          gl.setClearColor(new THREE.Color(0x000000), transparent ? 0 : 1);
          // Batasi shadow map precision
          gl.shadowMap.enabled = false;
        }}
      >
        <ambientLight intensity={Math.PI} />
        {/* ─── Physics timeStep: 'vary' = adaptive timestep, lebih smooth ─── */}
        <Physics gravity={gravity} timeStep="vary" maxVelocityIterations={4} maxPositionIterations={4}>
          <Band
            isMobile={isMobile}
            frontImage={frontImage}
            backImage={backImage}
            imageFit={imageFit}
            lanyardImage={lanyardImage}
            lanyardWidth={lanyardWidth}
            xOffset={xOffset}
            onReady={onReady}
          />
        </Physics>
        <Environment blur={0.75}>
          {/* Kurangi jumlah lightformer: 4 → 2 (hemat GPU) */}
          <Lightformer
            intensity={3}
            color="white"
            position={[0, -1, 5]}
            rotation={[0, 0, Math.PI / 3]}
            scale={[100, 0.1, 1]}
          />
          <Lightformer
            intensity={8}
            color="white"
            position={[-10, 0, 14]}
            rotation={[0, Math.PI / 2, Math.PI / 3]}
            scale={[100, 10, 1]}
          />
        </Environment>
      </Canvas>
    </div>
  );
}
function Band({
  maxSpeed = 50,
  minSpeed = 0,
  isMobile = false,
  frontImage = null,
  backImage = null,
  imageFit = 'cover',
  lanyardImage = null,
  lanyardWidth = 1,
  xOffset = 0,
  onReady
}) {
  const band = useRef(),
    fixed = useRef(),
    j1 = useRef(),
    j2 = useRef(),
    j3 = useRef(),
    card = useRef(),
    lanyardAnchor = useRef();

  const segmentProps = { type: 'dynamic', canSleep: false, colliders: false, angularDamping: 1, linearDamping: 0.8 };
  const { nodes, materials } = useGLTF(cardGLB);
  const lanyardTexture = useTexture(lanyardImage || lanyard);
  const texture = useMemo(() => {
    const strapTexture = lanyardTexture.clone();
    strapTexture.wrapS = strapTexture.wrapT = THREE.RepeatWrapping;
    // Maksimalkan ketajaman tekstur — matikan mipmap blur & aktifkan anisotropic filtering
    strapTexture.anisotropy = 16;
    strapTexture.generateMipmaps = false;
    strapTexture.minFilter = THREE.LinearFilter;
    strapTexture.magFilter = THREE.LinearFilter;
    strapTexture.needsUpdate = true;

    return strapTexture;
  }, [lanyardTexture]);
  const frontTex = useTexture(frontImage || BLANK_PIXEL);
  const backTex = useTexture(backImage || BLANK_PIXEL);

  useEffect(() => {
    texture.needsUpdate = true;

    return () => texture.dispose();
  }, [texture]);

  const cardMap = useMemo(() => {
    const baseMap = materials.base.map;
    if (!frontImage && !backImage) return baseMap;

    const baseImg = baseMap.image;
    const W = baseImg.width;
    const H = baseImg.height;
    const canvas = document.createElement('canvas');
    canvas.width = W;
    canvas.height = H;
    const ctx = canvas.getContext('2d');
    if (!ctx) return baseMap;
    ctx.drawImage(baseImg, 0, 0, W, H);

    const drawFitted = (img, rect) => {
      const rx = rect.x * W;
      const ry = rect.y * H;
      const rw = rect.w * W;
      const rh = rect.h * H;

      let dw, dh, dx, dy;

      if (imageFit === 'fill') {
        dw = rw;
        dh = rh;
        dx = rx;
        dy = ry;
      } else {
        const pick = imageFit === 'contain' ? Math.min : Math.max;
        const scale = pick(rw / img.width, rh / img.height);
        dw = img.width * scale;
        dh = img.height * scale;
        dx = rx + (rw - dw) / 2;
        dy = ry + (rh - dh) / 2;
      }

      ctx.save();
      ctx.beginPath();
      ctx.rect(rx, ry, rw, rh);
      ctx.clip();
      ctx.drawImage(img, dx, dy, dw, dh);
      ctx.restore();
    };

    // console.log removed — unnecessary in production
    if (frontImage && frontTex.image) drawFitted(frontTex.image, FRONT_UV_RECT);
    if (backImage && backTex.image) drawFitted(backTex.image, BACK_UV_RECT);

    const composite = new THREE.CanvasTexture(canvas);
    composite.colorSpace = THREE.SRGBColorSpace;
    composite.flipY = baseMap.flipY;
    composite.anisotropy = 4; // Turun dari 16 → 4 (visual tetap bagus, GPU jauh lebih ringan)
    composite.needsUpdate = true;
    return composite;
  }, [frontImage, backImage, imageFit, frontTex, backTex, materials.base.map]);

  const [curve] = useState(
    () =>
      new THREE.CatmullRomCurve3([
        new THREE.Vector3(),
        new THREE.Vector3(),
        new THREE.Vector3(),
        new THREE.Vector3(),
        new THREE.Vector3()
      ])
  );
  const [dragged, drag] = useState(false);
  const [hovered, hover] = useState(false);
  const swayStartTime = useRef(null);
  const initialSwayComplete = useRef(false);
  const cardVisual = useRef(null);
  const cardSpin = useRef(null);
  const readyFrames = useRef(0);
  const readySent = useRef(false);

  useEffect(() => {
    const timer = setTimeout(() => {
      if (onReady && !readySent.current) {
        readySent.current = true;
        onReady();
      }
    }, 350);
    return () => clearTimeout(timer);
  }, [onReady]);

  const ropeLen = isMobile ? 0.65 : 1;
  const fixedY = isMobile ? 4.1 : 4.3;
  const j1Y = fixedY - ropeLen;
  const j2Y = j1Y - ropeLen;
  const j3Y = j2Y - ropeLen;
  const cardAttachmentY = isMobile ? 1.195 : 1.555;
  const cardY = j3Y - cardAttachmentY;
  const cardScale = isMobile ? 1.75 : 2.25;

  // panjang tali id card
  useRopeJoint(fixed, j1, [[0, 0, 0], [0, 0, 0], ropeLen]);
  useRopeJoint(j1, j2, [[0, 0, 0], [0, 0, 0], ropeLen]);
  useRopeJoint(j2, j3, [[0, 0, 0], [0, 0, 0], ropeLen]);
  useSphericalJoint(j3, card, [
    [0, 0, 0],
    [0, cardAttachmentY, 0]
  ]);

  useEffect(() => {
    if (hovered) {
      document.body.style.cursor = dragged ? 'grabbing' : 'grab';
      return () => void (document.body.style.cursor = 'auto');
    }
  }, [hovered, dragged]);

  // ─── Curve segment count: mobile=12, desktop=24 (turun dari 32) ──────────
  const curveSegments = isMobile ? 12 : 24;

  useFrame((state, delta) => {
    if (dragged) {
      vec.set(state.pointer.x, state.pointer.y, 0.5).unproject(state.camera);
      dir.copy(vec).sub(state.camera.position).normalize();
      vec.add(dir.multiplyScalar(state.camera.position.length()));
      [card, j1, j2, j3, fixed].forEach(ref => ref.current?.wakeUp());
      card.current?.setNextKinematicTranslation({ x: vec.x - dragged.x, y: vec.y - dragged.y, z: vec.z - dragged.z });
    }

    if (cardSpin.current && cardVisual.current && !dragged) {
      const spin = cardSpin.current;
      spin.elapsed = Math.min(spin.elapsed + delta, spin.duration);
      const progress = spin.elapsed / spin.duration;
      const easedProgress = 1 - (1 - progress) ** 3;

      cardVisual.current.rotation.y = spin.direction * spin.turns * Math.PI * 2 * easedProgress;

      if (progress === 1) {
        cardVisual.current.rotation.y = 0;
        cardSpin.current = null;
      }
    }

    if (fixed.current) {
      if (!initialSwayComplete.current) {
        const time = state.clock.elapsedTime;
        if (swayStartTime.current === null) {
          swayStartTime.current = time;
        }
        const swayTime = time - swayStartTime.current;

        if (!dragged) {
          if (swayTime < 5) {
            const initialSway = Math.sin(swayTime * 2.4) * Math.exp(-swayTime * 1.1);
            fixed.current.setNextKinematicTranslation({
              x: xOffset + initialSway * (isMobile ? 0.08 : 0.12),
              y: fixedY,
              z: 0,
            });
          } else {
            fixed.current.setNextKinematicTranslation({ x: xOffset, y: fixedY, z: 0 });
            initialSwayComplete.current = true;
          }
        }
      }

      [j1, j2, j3].forEach(ref => {
        if (!ref.current.lerped) ref.current.lerped = new THREE.Vector3().copy(ref.current.translation());
        const followSpeed = dragged ? 60 : 25;
        ref.current.lerped.lerp(
          ref.current.translation(),
          delta * followSpeed
        );
      });

      if (lanyardAnchor.current) {
        lanyardAnchor.current.getWorldPosition(anchorVec);
      } else if (card.current) {
        anchorVec.copy(card.current.translation()).add(vec.set(0, cardAttachmentY, 0));
      }

      // Point 0: Permukaan besi datar pengait
      curve.points[0].copy(anchorVec);
      // Point 1: Titik vertikal lurus persis di atas pengait (mencegah tali miring/melengkung di pangkal)
      curve.points[1].set(anchorVec.x, anchorVec.y + (isMobile ? 0.35 : 0.45), anchorVec.z);
      // Point 2 & 3: Posisi fisika tengah untuk ayunan yang halus & natural
      curve.points[2].copy(j2.current.lerped);
      curve.points[3].copy(j1.current.lerped);
      // Point 4: Jangkar atas di plafon
      curve.points[4].copy(fixed.current.translation());
      band.current.geometry.setPoints(curve.getPoints(curveSegments));

      if (onReady && !readySent.current) {
        readyFrames.current += 1;
        if (readyFrames.current >= 12) {
          readySent.current = true;
          onReady();
        }
      }
    }
  });

  curve.curveType = 'centripetal';
  return (
    <>
      <RigidBody position={[xOffset, fixedY, 0]} ref={fixed} {...segmentProps} type="kinematicPosition" />
      <RigidBody position={[xOffset, j1Y, 0]} ref={j1} {...segmentProps}>
        <BallCollider args={[0.1]} />
      </RigidBody>
      <RigidBody position={[xOffset, j2Y, 0]} ref={j2} {...segmentProps}>
        <BallCollider args={[0.1]} />
      </RigidBody>
      <RigidBody position={[xOffset, j3Y, 0]} ref={j3} {...segmentProps}>
        <BallCollider args={[0.1]} />
      </RigidBody>
      <RigidBody position={[xOffset, cardY, 0]} ref={card} {...segmentProps} enabledRotations={[false, false, false]} linearDamping={1} angularDamping={2} type={dragged ? 'kinematicPosition' : 'dynamic'}>
          <CuboidCollider args={[0.8 * (isMobile ? 0.77 : 1), 1.125 * (isMobile ? 0.77 : 1), 0.01]} />
          <group
            scale={cardScale}
            position={[0, isMobile ? -0.95 : -1.2, -0.05]}
            onPointerOver={() => hover(true)}
            onPointerOut={() => hover(false)}
            onPointerUp={e => {
              e.stopPropagation();
              try {
                e.target.releasePointerCapture(e.pointerId);
              } catch (err) {}
              cardSpin.current = {
                elapsed: 0,
                duration: 1.15,
                direction: Math.random() < 0.5 ? -1 : 1,
                turns: Math.random() < 0.5 ? 1 : 2,
              };
              drag(false);
            }}
            onPointerCancel={e => {
              e.stopPropagation();
              try {
                e.target.releasePointerCapture(e.pointerId);
              } catch (err) {}
              drag(false);
            }}
            onPointerDown={e => {
              e.stopPropagation();
              try {
                e.target.setPointerCapture(e.pointerId);
              } catch (err) {}
              cardSpin.current = null;
              if (cardVisual.current) {
                cardVisual.current.rotation.y = 0;
              }
              drag(new THREE.Vector3().copy(e.point).sub(vec.copy(card.current.translation())));
            }}
          >
            {/* Invisible expanded hit area for finger touch on mobile */}
            <mesh visible={false}>
              <planeGeometry args={[1.3, 2.0]} />
              <meshBasicMaterial transparent opacity={0} />
            </mesh>
            <group ref={cardVisual}>
              <mesh geometry={nodes.card.geometry}>
                <meshPhysicalMaterial
                  map={cardMap}
                  map-anisotropy={4}
                  clearcoat={isMobile ? 0 : 0.5}       // Turun dari 1 → 0.5 (lebih ringan)
                  clearcoatRoughness={0.15}
                  roughness={0.9}
                  metalness={0.5}                       // Turun dari 0.8 → 0.5 (hemat PBR cost)
                />
              </mesh>
              <mesh geometry={nodes.clip.geometry} material={materials.metal} material-color="#ffffff" material-roughness={0.3} material-metalness={1} />
              <mesh geometry={nodes.clamp.geometry} material={materials.metal} material-color="#ffffff" material-roughness={0.3} material-metalness={1} />
              {/* Tali menempel presisi di bagian besi datar atas ring pengait */}
              <group ref={lanyardAnchor} position={[0, 1.225, 0]} />
            </group>
          </group>
        </RigidBody>
      <mesh ref={band}>
        <meshLineGeometry />
        <meshLineMaterial
          color="white"
          depthTest={false}
          resolution={[1000, 1000]}
          useMap
          map={texture}
          repeat={[isMobile ? -0.42 : -0.48, 1]}
          lineWidth={isMobile ? 1.30 : 1}
        />
      </mesh>
    </>
  );
}

useGLTF.preload(cardGLB);
useTexture.preload(lanyard);
