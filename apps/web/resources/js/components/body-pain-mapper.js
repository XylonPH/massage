import * as THREE from 'three';

export function createBodyPainMapper(container, onSelectRegion) {
    if (!container) return null;

    // Clear placeholder content if any
    container.replaceChildren();

    let width = container.clientWidth || 340;
    let height = container.clientHeight || 420;

    // 1. Scene & Background
    const scene = new THREE.Scene();

    // 2. Camera
    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.set(0, 0, 7.8);

    // 3. Renderer
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // 4. Vibrant Multi-Light Rig
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
    scene.add(ambientLight);

    const mainLight = new THREE.DirectionalLight(0xfff176, 1.8); // Warm gold key light
    mainLight.position.set(4, 6, 6);
    scene.add(mainLight);

    const fillLight = new THREE.DirectionalLight(0x38bdf8, 1.2); // Cool cyan fill light
    fillLight.position.set(-5, 2, -4);
    scene.add(fillLight);

    const rimLight = new THREE.PointLight(0x10b981, 2, 20); // Emerald rim light
    rimLight.position.set(0, -3, 3);
    scene.add(rimLight);

    // Group to hold the entire 3D figure
    const bodyGroup = new THREE.Group();
    scene.add(bodyGroup);

    // Anatomical regions definition
    const regionMeshes = [];
    const regionData = [
        { id: 'neck', name: 'Head, Neck & Trapezius', color: 0x38bdf8, emissive: 0x0284c7, pos: [0, 2.0, 0], scale: [0.65, 0.65, 0.65], shape: 'sphere' },
        { id: 'shoulders', name: 'Shoulders & Upper Back', color: 0xf59e0b, emissive: 0xd97706, pos: [0, 1.25, 0], scale: [1.6, 0.65, 0.75], shape: 'box' },
        { id: 'lower_back', name: 'Lower Back & Lumbar', color: 0x10b981, emissive: 0x059669, pos: [0, 0.35, 0], scale: [1.2, 0.85, 0.75], shape: 'box' },
        { id: 'arms', name: 'Arms & Hands', color: 0xc084fc, emissive: 0x9333ea, pos: [1.25, 0.7, 0], scale: [0.38, 1.45, 0.38], shape: 'cylinder', dual: [-1.25, 0.7, 0] },
        { id: 'legs', name: 'Thighs & Calves', color: 0x3b82f6, emissive: 0x1d4ed8, pos: [0.48, -1.3, 0], scale: [0.48, 1.7, 0.48], shape: 'cylinder', dual: [-0.48, -1.3, 0] },
        { id: 'feet', name: 'Feet & Reflexology Points', color: 0xf43f5e, emissive: 0xe11d48, pos: [0.48, -2.55, 0.15], scale: [0.42, 0.32, 0.75], shape: 'box', dual: [-0.48, -2.55, 0.15] },
    ];

    // Build 3D Mannequin Parts
    regionData.forEach((region) => {
        const material = new THREE.MeshStandardMaterial({
            color: region.color,
            emissive: region.emissive,
            emissiveIntensity: 0.35,
            roughness: 0.25,
            metalness: 0.3,
            transparent: true,
            opacity: 0.9,
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
        mesh.userData = { id: region.id, name: region.name, baseColor: region.color, baseEmissive: region.emissive };
        bodyGroup.add(mesh);
        regionMeshes.push(mesh);

        if (region.dual) {
            const dualMesh = new THREE.Mesh(geo, material.clone());
            dualMesh.position.set(...region.dual);
            dualMesh.scale.set(...region.scale);
            dualMesh.userData = { id: region.id, name: region.name, baseColor: region.color, baseEmissive: region.emissive };
            bodyGroup.add(dualMesh);
            regionMeshes.push(dualMesh);
        }
    });

    // Add glowing spine energy column
    const spineGeo = new THREE.CylinderGeometry(0.12, 0.12, 4.8, 16);
    const spineMat = new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: 0x38bdf8, emissiveIntensity: 0.8, wireframe: true });
    const spineMesh = new THREE.Mesh(spineGeo, spineMat);
    bodyGroup.add(spineMesh);

    // Add joint connector spheres (Shoulders, Elbows, Knees)
    const jointGeo = new THREE.SphereGeometry(0.22, 16, 16);
    const jointMat = new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: 0xf59e0b, emissiveIntensity: 0.5 });
    [
        [0.9, 1.45, 0], [-0.9, 1.45, 0], // Shoulders
        [0.48, -0.4, 0], [-0.48, -0.4, 0], // Hips
        [0.48, -2.1, 0], [-0.48, -2.1, 0], // Knees
    ].forEach((pos) => {
        const joint = new THREE.Mesh(jointGeo, jointMat);
        joint.position.set(...pos);
        bodyGroup.add(joint);
    });

    // Raycaster for hover & click interaction
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
                    hoveredMesh.material.emissiveIntensity = 0.35;
                    hoveredMesh.scale.multiplyScalar(0.95);
                }
                hoveredMesh = hit;
                hoveredMesh.material.emissiveIntensity = 0.9;
                hoveredMesh.scale.multiplyScalar(1.05);
            }
        } else {
            container.style.cursor = isDragging ? 'grabbing' : 'grab';
            if (hoveredMesh && hoveredMesh.userData.id !== selectedRegionId) {
                hoveredMesh.material.emissiveIntensity = 0.35;
                hoveredMesh.scale.multiplyScalar(0.95);
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

        if (deltaX < 6 && deltaY < 6) {
            const coords = getCanvasRelativeMouse(event);
            mouse.x = coords.x;
            mouse.y = coords.y;

            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(regionMeshes);

            if (intersects.length > 0) {
                const hit = intersects[0].object;
                selectedRegionId = hit.userData.id;
                const selectedData = regionData.find((r) => r.id === selectedRegionId);

                regionMeshes.forEach((m) => {
                    if (m.userData.id === selectedRegionId) {
                        m.material.emissiveIntensity = 1.0;
                        m.material.opacity = 1.0;
                    } else {
                        m.material.emissiveIntensity = 0.15;
                        m.material.opacity = 0.35;
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

    // Animation Loop
    let animationFrameId;
    const animate = () => {
        animationFrameId = requestAnimationFrame(animate);

        if (!isDragging) {
            bodyGroup.rotation.y += 0.006;
        }

        renderer.render(scene, camera);
    };
    animate();

    // Resize Handler
    const handleResize = () => {
        if (!container) return;
        width = container.clientWidth || 340;
        height = container.clientHeight || 420;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    };
    window.addEventListener('resize', handleResize);
    // Initial size trigger
    handleResize();

    return {
        selectRegion(id) {
            selectedRegionId = id;
            const selectedData = regionData.find((r) => r.id === id);
            regionMeshes.forEach((m) => {
                if (m.userData.id === id) {
                    m.material.emissiveIntensity = 1.0;
                    m.material.opacity = 1.0;
                } else {
                    m.material.emissiveIntensity = 0.15;
                    m.material.opacity = 0.35;
                }
            });
            if (typeof onSelectRegion === 'function' && selectedData) {
                onSelectRegion(selectedData);
            }
        },
        reset() {
            selectedRegionId = null;
            bodyGroup.rotation.set(0, 0, 0);
            regionMeshes.forEach((m) => {
                m.material.emissiveIntensity = 0.35;
                m.material.opacity = 0.9;
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
