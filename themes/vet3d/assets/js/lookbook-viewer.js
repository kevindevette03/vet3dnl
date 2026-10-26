/**
 * Vet3D - Lookbook 3D Viewer
 * Toont 3D modellen in lookbook tegels bij hover
 */

import * as THREE from 'three';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';
import { ThreeMFLoader } from 'three/addons/loaders/3MFLoader.js';
import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

class LookbookViewer {
    constructor() {
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupViewers());
        } else {
            this.setupViewers();
        }
    }

    getFileExtension(url) {
        return url.split('.').pop().toLowerCase().split('?')[0];
    }

    setupViewers() {
        const tiles3D = document.querySelectorAll('.vet-tile[data-stl-url]');
        
        tiles3D.forEach(tile => {
            const modelUrl = tile.dataset.stlUrl;
            if (!modelUrl) return;
            
            const viewerContainer = document.createElement('div');
            viewerContainer.className = 'vet-3d-viewer';
            viewerContainer.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;pointer-events:none;transition:opacity 0.4s ease;z-index:10;background:rgba(245,245,245,0.95);';
            
            tile.style.position = 'relative';
            tile.appendChild(viewerContainer);
            
            // Setup 3D scene
            this.createViewer(tile, viewerContainer, modelUrl);
            
            // Hover events
            let hoverTimeout;
            tile.addEventListener('mouseenter', () => {
                hoverTimeout = setTimeout(() => {
                    viewerContainer.style.opacity = '1';
                    viewerContainer.style.pointerEvents = 'auto';
                    this.startAnimation(tile);
                }, 300);
            });
            
            tile.addEventListener('mouseleave', () => {
                clearTimeout(hoverTimeout);
                viewerContainer.style.opacity = '0';
                viewerContainer.style.pointerEvents = 'none';
                this.stopAnimation(tile);
            });
        });
    }

    createViewer(tile, container, modelUrl) {
        const width = container.offsetWidth || 300;
        const height = container.offsetHeight || 300;
        
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(35, width / height, 0.1, 1000);
        camera.position.set(0, 0, 100);
        
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);
        
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);
        
        const directionalLight1 = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight1.position.set(1, 1, 1);
        scene.add(directionalLight1);
        
        const directionalLight2 = new THREE.DirectionalLight(0xffffff, 0.4);
        directionalLight2.position.set(-1, -1, -0.5);
        scene.add(directionalLight2);
        
        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.autoRotate = true;
        controls.autoRotateSpeed = 2.0;
        
        // Bepaal loader op basis van extensie
        const ext = this.getFileExtension(modelUrl);
        let loader;
        
        if (ext === 'stl') {
            loader = new STLLoader();
        } else if (ext === '3mf') {
            loader = new ThreeMFLoader();
        } else if (ext === 'obj') {
            loader = new OBJLoader();
        } else {
            console.error('Unsupported format:', ext);
            return;
        }
        
        loader.load(modelUrl, (result) => {
            let mesh;
            
            if (ext === 'stl') {
                const geometry = result;
                const material = new THREE.MeshPhongMaterial({
                    color: 0xFF8C42,
                    specular: 0x111111,
                    shininess: 200
                });
                
                mesh = new THREE.Mesh(geometry, material);
                
                // Center en schaal het model
                geometry.computeBoundingBox();
                const boundingBox = geometry.boundingBox;
                const center = new THREE.Vector3();
                boundingBox.getCenter(center);
                mesh.geometry.translate(-center.x, -center.y, -center.z);
                
                const size = new THREE.Vector3();
                boundingBox.getSize(size);
                const maxDim = Math.max(size.x, size.y, size.z);
                const scale = 50 / maxDim;
                mesh.scale.setScalar(scale);
                
                scene.add(mesh);
            } else if (ext === '3mf' || ext === 'obj') {
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
                
                // Center en schaal het object
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
            
            tile._viewer = { scene, camera, renderer, controls, mesh, isAnimating: false };
        }, undefined, (error) => {
            console.error('Error loading 3D model:', error);
        });
    }

    startAnimation(tile) {
        const viewer = tile._viewer;
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

    stopAnimation(tile) {
        const viewer = tile._viewer;
        if (viewer) {
            viewer.isAnimating = false;
        }
    }
}

// Initialiseer
new LookbookViewer();
