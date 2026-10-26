/**
 * Vet3D - 3D Model Viewer
 * Toont STL en 3MF bestanden in een 3D viewer bij hover
 */

import * as THREE from 'three';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';
import { ThreeMFLoader } from 'three/addons/loaders/3MFLoader.js';
import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

class Vet3DViewer {
    constructor() {
        this.viewers = new Map();
        this.init();
    }

    init() {
        // Wacht tot DOM geladen is
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupViewers());
        } else {
            this.setupViewers();
        }
    }

    setupViewers() {
        // Zoek alle producten met 3D model
        const products = document.querySelectorAll('[data-stl-url]');
        
        products.forEach(product => {
            const modelUrl = product.dataset.stlUrl;
            if (!modelUrl) return;

            // Maak viewer container
            const viewerContainer = document.createElement('div');
            viewerContainer.className = 'vet-3d-viewer';
            viewerContainer.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                z-index: 10;
            `;
            
            // Voeg toe aan product
            const imageContainer = product.querySelector('.woocommerce-loop-product__link, .product-image, a');
            if (imageContainer) {
                imageContainer.style.position = 'relative';
                imageContainer.appendChild(viewerContainer);

                // Setup 3D viewer
                this.createViewer(viewerContainer, modelUrl);

                // Hover events
                let hoverTimeout;
                product.addEventListener('mouseenter', () => {
                    hoverTimeout = setTimeout(() => {
                        viewerContainer.style.opacity = '1';
                        viewerContainer.style.pointerEvents = 'auto';
                        this.startAnimation(viewerContainer);
                    }, 200); // Kleine delay voor betere UX
                });

                product.addEventListener('mouseleave', () => {
                    clearTimeout(hoverTimeout);
                    viewerContainer.style.opacity = '0';
                    viewerContainer.style.pointerEvents = 'none';
                    this.stopAnimation(viewerContainer);
                });
            }
        });
    }

    getFileExtension(url) {
        return url.split('.').pop().toLowerCase().split('?')[0];
    }

    createViewer(container, modelUrl) {
        const width = container.offsetWidth || 300;
        const height = container.offsetHeight || 300;

        // Scene setup
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf5f5f5);

        // Camera
        const camera = new THREE.PerspectiveCamera(35, width / height, 0.1, 1000);
        camera.position.set(0, 0, 100);

        // Renderer
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true;
        container.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const directionalLight1 = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight1.position.set(1, 1, 1);
        scene.add(directionalLight1);

        const directionalLight2 = new THREE.DirectionalLight(0xffffff, 0.4);
        directionalLight2.position.set(-1, -1, -0.5);
        scene.add(directionalLight2);

        // Controls
        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.enableZoom = true;
        controls.autoRotate = true;
        controls.autoRotateSpeed = 2.0;

        // Bepaal welke loader te gebruiken
        const ext = this.getFileExtension(modelUrl);
        let loader;

        if (ext === 'stl') {
            loader = new STLLoader();
        } else if (ext === '3mf') {
            loader = new ThreeMFLoader();
        } else if (ext === 'obj') {
            loader = new OBJLoader();
        } else {
            console.error('Unsupported file format:', ext);
            container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#999;">Formaat niet ondersteund</div>';
            return;
        }

        // Load model
        loader.load(
            modelUrl,
            (result) => {
                let mesh;

                if (ext === 'stl') {
                    // STL loader geeft geometry terug
                    const geometry = result;
                    const material = new THREE.MeshPhongMaterial({
                        color: 0xFF8C42,
                        specular: 0x111111,
                        shininess: 200,
                        flatShading: false
                    });
                    
                    mesh = new THREE.Mesh(geometry, material);
                    
                    // Center geometry
                    geometry.computeBoundingBox();
                    const boundingBox = geometry.boundingBox;
                    const center = new THREE.Vector3();
                    boundingBox.getCenter(center);
                    geometry.translate(-center.x, -center.y, -center.z);

                    const size = new THREE.Vector3();
                    boundingBox.getSize(size);
                    const maxDim = Math.max(size.x, size.y, size.z);
                    const scale = 50 / maxDim;
                    mesh.scale.setScalar(scale);
                    
                    scene.add(mesh);
                } else if (ext === '3mf') {
                    // 3MF loader geeft een object3D terug
                    const object = result;
                    
                    // Kleur alle meshes in het object
                    object.traverse((child) => {
                        if (child.isMesh) {
                            child.material = new THREE.MeshPhongMaterial({
                                color: 0xFF8C42,
                                specular: 0x111111,
                                shininess: 200
                            });
                        }
                    });
                    
                    // Center en schaal
                    const box = new THREE.Box3().setFromObject(object);
                    const center = new THREE.Vector3();
                    box.getCenter(center);
                    object.position.set(-center.x, -center.y, -center.z);

                    const size = new THREE.Vector3();
                    box.getSize(size);
                    const maxDim = Math.max(size.x, size.y, size.z);
                    const scale = 50 / maxDim;
                    object.scale.setScalar(scale);
                    
                    mesh = object;
                    scene.add(object);
                } else if (ext === 'obj') {
                    // OBJ loader geeft een Group terug
                    const object = result;
                    
                    object.traverse((child) => {
                        if (child.isMesh) {
                            child.material = new THREE.MeshPhongMaterial({
                                color: 0xFF8C42,
                                specular: 0x111111,
                                shininess: 200
                            });
                        }
                    });
                    
                    const box = new THREE.Box3().setFromObject(object);
                    const center = new THREE.Vector3();
                    box.getCenter(center);
                    object.position.set(-center.x, -center.y, -center.z);

                    const size = new THREE.Vector3();
                    box.getSize(size);
                    const maxDim = Math.max(size.x, size.y, size.z);
                    const scale = 50 / maxDim;
                    object.scale.setScalar(scale);
                    
                    mesh = object;
                    scene.add(object);
                }

                // Sla referenties op
                container._viewer = {
                    scene,
                    camera,
                    renderer,
                    controls,
                    mesh,
                    isAnimating: false
                };
            },
            (progress) => {
                // Loading progress
                if (progress.total > 0) {
                    const percent = (progress.loaded / progress.total) * 100;
                    console.log(`Loading: ${percent.toFixed(0)}%`);
                }
            },
            (error) => {
                console.error('Error loading 3D model:', error);
                container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#999;">3D model niet beschikbaar</div>';
            }
        );

        // Handle resize
        const resizeObserver = new ResizeObserver(() => {
            const newWidth = container.offsetWidth;
            const newHeight = container.offsetHeight;
            
            if (newWidth && newHeight) {
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            }
        });
        resizeObserver.observe(container);
    }

    startAnimation(container) {
        const viewer = container._viewer;
        if (!viewer || viewer.isAnimating) return;

        viewer.isAnimating = true;

        const animate = () => {
            if (!viewer.isAnimating) return;

            requestAnimationFrame(animate);
            viewer.controls.update();
            viewer.renderer.render(viewer.scene, viewer.camera);
        };

        animate();
    }

    stopAnimation(container) {
        const viewer = container._viewer;
        if (viewer) {
            viewer.isAnimating = false;
        }
    }
}

// Initialiseer viewer
new Vet3DViewer();
