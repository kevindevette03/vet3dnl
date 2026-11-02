/**
 * Vet3D - Lookbook 3D Viewer
 * Print de foto's in beeld en toont 3D modellen na een klik op 'Bekijk in 3D'
 */

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
        this.setupPrintReveal();
        this.setupArrows();

        document.querySelectorAll('.vet-log__entry').forEach(entry => {
            const photo = entry.querySelector('.vet-log__photo[data-stl-url]');
            const button = entry.querySelector('.vet-log__3d');
            if (!photo || !button) return;

            let container;
            button.addEventListener('click', async () => {
                const open = button.getAttribute('aria-pressed') !== 'true';
                button.setAttribute('aria-pressed', String(open));
                button.textContent = open ? 'Terug naar de foto' : 'Bekijk in 3D';

                if (!container) {
                    container = document.createElement('div');
                    container.className = 'vet-3d-viewer';
                    photo.appendChild(container);
                    await this.createViewer(photo, container, photo.dataset.stlUrl);
                }

                container.classList.toggle('is-open', open);
                if (open) {
                    this.startAnimation(photo);
                } else {
                    this.stopAnimation(photo);
                }
            });
        });
    }

    // Pijlknoppen voor de strook; alleen zichtbaar als niet alles in beeld past
    setupArrows() {
        const strip = document.querySelector('.vet-log__strip');
        const nav = document.querySelector('.vet-log__nav');
        if (!strip || !nav) return;

        const update = () => {
            const max = strip.scrollWidth - strip.clientWidth;
            nav.hidden = max <= 1;
            nav.querySelector('[data-dir="-1"]').disabled = strip.scrollLeft <= 1;
            nav.querySelector('[data-dir="1"]').disabled = strip.scrollLeft >= max - 1;
        };

        nav.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                strip.scrollBy({ left: Number(button.dataset.dir) * strip.clientWidth * 0.7, behavior: 'smooth' });
            });
        });
        strip.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    }

    // Foto's verschijnen laag voor laag zodra ze in beeld komen
    setupPrintReveal() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const photos = document.querySelectorAll('.vet-log__photo');
        if (!photos.length || !('IntersectionObserver' in window)) return;

        document.querySelector('.vet-log').classList.add('vet-log--anim');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-printed');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15 });
        photos.forEach(photo => observer.observe(photo));
    }

    async createViewer(tile, container, modelUrl) {
        const THREE = await import('three');
        const { OrbitControls } = await import('three/addons/controls/OrbitControls.js');
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
            const { STLLoader } = await import('three/addons/loaders/STLLoader.js');
            loader = new STLLoader();
        } else if (ext === '3mf') {
            const { ThreeMFLoader } = await import('three/addons/loaders/3MFLoader.js');
            loader = new ThreeMFLoader();
        } else if (ext === 'obj') {
            const { OBJLoader } = await import('three/addons/loaders/OBJLoader.js');
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
            if (container.classList.contains('is-open')) this.startAnimation(tile);
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
