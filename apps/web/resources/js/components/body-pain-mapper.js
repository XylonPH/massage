import * as THREE from 'three';

export function createBodyPainMapper(container, onSelectRegion) {
    if (!container) return null;

    let width = container.clientWidth || 360;
    let height = container.clientHeight || 480;

    // 1. Scene setup
    const scene = new THREE.Scene();

    // 2. Camera setup
    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.set(0, 0, 9);

    // 3. Renderer setup
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.replaceChildren(renderer.domElement);

    // 4. Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
    scene.add(ambientLight);

    const dirLight1 = new THREE.DirectionalLight(0xf59e0b, 1.2); // Ember warm light
    dirLight1.position.set(5, 8, 5);
    scene.add(dirLight1);

    const dirLight2 = new THREE.DirectionalLight(0x10b981, 0.8); // Leaf teal backlight
    dirLight2.position.set(-5, -4, -5);
    scene.add(dirLight2);

    // Group to hold the entire 3D figure
    const bodyGroup = new THREE.Group();
    scene.add(bodyGroup);

    // Interactive anatomical mesh regions map
    const regionMeshes = [];
    const regionData = [
        { id: 'neck', name: 'Head, Neck & Trapezius', color: 0x38bdf8, pos: [0, 2.2, 0], scale: [0.65, 0.65, 0.65], shape: 'sphere' },
        { id: 'shoulders', name: 'Shoulders & Upper Back', color: 0xf59e0b, pos: [0, 1.4, 0], scale: [1.5, 0.6, 0.7], shape: 'box' },
        { id: 'lower_back', name: 'Lower Back & Lumbar', color: 0x10b981, pos: [0, 0.5, 0], scale: [1.1, 0.8, 0.7], shape: 'box' },
        { id: 'arms', name: 'Arms & Hands', color: 0xa855f7, pos: [1.2, 0.8, 0], scale: [0.35, 1.4, 0.35], shape: 'cylinder', dual: [-1.2, 0.8, 0] },
        { id: 'legs', name: 'Thighs & Calves', color: 0x3b82f6, pos: [0.45, -1.2, 0], scale: [0.45, 1.6, 0.45], shape: 'cylinder', dual: [-0.45, -1.2, 0] },
        { id: 'feet', name: 'Feet & Reflexology Points', color: 0xec4899, pos: [0.45, -2.4, 0.1], scale: [0.4, 0.3, 0.7], shape: 'box', dual: [-0.45, -2.4, 0.1] },
    ];

    // Create 3D Meshes for regions
    regionData.forEach((region) => {
        const material = new THREE.MeshStandardMaterial({
            color: region.color,
            roughness: 0.3,
            metalness: 0.2,
            transparent: true,
            opacity: 0.85,
        });

        let geo;
        if (region.shape === 'sphere') {
            geo = new THREE.SphereGeometry(1, 32, 32);
        } else if (region.shape === 'cylinder') {
            geo = new THREE.CylinderGeometry(1, 0.8, 1, 32);
        } else {
            geo = new THREE.BoxGeometry(1, 1, 1);
        }

        const mesh = new THREE.Mesh(geo, material);
        mesh.position.set(...region.pos);
        mesh.scale.set(...region.scale);
        mesh.userData = { id: region.id, name: region.name, baseColor: region.color, baseOpacity: 0.85 };
        bodyGroup.add(mesh);
        regionMeshes.push(mesh);

        // Add dual mesh if bilateral (arms, legs, feet)
        if (region.dual) {
            const dualMesh = new THREE.Mesh(geo, material);
            dualMesh.position.set(...region.dual);
            dualMesh.scale.set(...region.scale);
            dualMesh.userData = { id: region.id, name: region.name, baseColor: region.color, baseOpacity: 0.85 };
            bodyGroup.add(dualMesh);
            regionMeshes.push(dualMesh);
        }
    });

    // Add subtle ambient spine/core connector
    const spineGeo = new THREE.CylinderGeometry(0.15, 0.15, 4.5, 16);
    const spineMat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.3, wireframe: true });
    const spineMesh = new THREE.Mesh(spineGeo, spineMat);
    bodyGroup.add(spineMesh);

    // 5. Interactivity: Raycasting for Hover & Click
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let hoveredMesh = null;
    let selectedRegionId = null;
    let isDragging = false;
    let previousMousePosition = { x: 0, y: 0 };

    const getCanvasRelativeMouse = (event) => {
        const rect = renderer.domElement.getBoundingClientRect();
        return {
            x: ((event.clientX - rect.left) / rect.width) * 2 - 1,
            y: -((event.clientY - rect.top) / rect.height) * 2 + 1,
        };
    };

    const handlePointerMove = (event) => {
        if (isDragging) {
            const deltaX = event.clientX - previousMousePosition.x;
            const deltaY = event.clientY - previousMousePosition.y;
            bodyGroup.rotation.y += deltaX * 0.01;
            bodyGroup.rotation.x += deltaY * 0.005;
            previousMousePosition = { x: event.clientX, y: event.clientY };
            return;
        }

        const coords = getCanvasRelativeMouse(event);
        mouse.x = coords.x;
        mouse.y = coords.y;

        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(regionMeshes);

        if (intersects.length > 0) {
            const hit = intersects[0].object;
            container.style.cursor = 'pointer';
            if (hoveredMesh !== hit) {
                if (hoveredMesh && hoveredMesh.userData.id !== selectedRegionId) {
                    hoveredMesh.material.opacity = hoveredMesh.userData.baseOpacity;
                }
                hoveredMesh = hit;
                hoveredMesh.material.opacity = 1.0;
            }
        } else {
            container.style.cursor = isDragging ? 'grabbing' : 'grab';
            if (hoveredMesh && hoveredMesh.userData.id !== selectedRegionId) {
                hoveredMesh.material.opacity = hoveredMesh.userData.baseOpacity;
                hoveredMesh = null;
            }
        }
    };

    const handlePointerDown = (event) => {
        isDragging = true;
        previousMousePosition = { x: event.clientX, y: event.clientY };
    };

    const handlePointerUp = (event) => {
        const deltaX = Math.abs(event.clientX - previousMousePosition.x);
        const deltaY = Math.abs(event.clientY - previousMousePosition.y);
        isDragging = false;

        // If it was a click (not a drag), process region selection
        if (deltaX < 5 && deltaY < 5) {
            const coords = getCanvasRelativeMouse(event);
            mouse.x = coords.x;
            mouse.y = coords.y;

            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(regionMeshes);

            if (intersects.length > 0) {
                const hit = intersects[0].object;
                selectedRegionId = hit.userData.id;
                const selectedData = regionData.find((r) => r.id === selectedRegionId);

                // Highlight selected region meshes
                regionMeshes.forEach((m) => {
                    if (m.userData.id === selectedRegionId) {
                        m.material.opacity = 1.0;
                    } else {
                        m.material.opacity = 0.4;
                    }
                });

                if (typeof onSelectRegion === 'function' && selectedData) {
                    onSelectRegion(selectedData);
                }
            }
        }
    };

    const domElement = renderer.domElement;
    domElement.style.cursor = 'grab';
    domElement.addEventListener('pointermove', handlePointerMove);
    domElement.addEventListener('pointerdown', handlePointerDown);
    window.addEventListener('pointerup', handlePointerUp);

    // 6. Animation loop
    let animationFrameId;
    const animate = () => {
        animationFrameId = requestAnimationFrame(animate);

        // Gentle auto rotation when idle/not dragging
        if (!isDragging) {
            bodyGroup.rotation.y += 0.005;
        }

        renderer.render(scene, camera);
    };
    animate();

    // 7. Handle resize
    const handleResize = () => {
        if (!container) return;
        width = container.clientWidth || 360;
        height = container.clientHeight || 480;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    };
    window.addEventListener('resize', handleResize);

    // Return control API
    return {
        selectRegion(id) {
            selectedRegionId = id;
            const selectedData = regionData.find((r) => r.id === id);
            regionMeshes.forEach((m) => {
                m.material.opacity = m.userData.id === id ? 1.0 : 0.4;
            });
            if (typeof onSelectRegion === 'function' && selectedData) {
                onSelectRegion(selectedData);
            }
        },
        reset() {
            selectedRegionId = null;
            bodyGroup.rotation.set(0, 0, 0);
            regionMeshes.forEach((m) => {
                m.material.opacity = m.userData.baseOpacity;
            });
        },
        destroy() {
            cancelAnimationFrame(animationFrameId);
            window.removeEventListener('resize', handleResize);
            window.removeEventListener('pointerup', handlePointerUp);
            domElement.removeEventListener('pointermove', handlePointerMove);
            domElement.removeEventListener('pointerdown', handlePointerDown);
            renderer.dispose();
        },
    };
}
