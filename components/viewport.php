<div class="rounded-circle">
    <div id="three-viewport" class="three-viewport" aria-label="Vista 3D de una corona dental">
        <canvas id="three-canvas"></canvas>
    </div>
</div>

<script type="module">
    import * as THREE from '/node_modules/three/build/three.module.js';
    import {
        PLYLoader
    } from '/node_modules/three/examples/jsm/loaders/PLYLoader.js';

    (() => {
        const viewport = document.getElementById('three-viewport');
        const canvas = document.getElementById('three-canvas');

        if (!viewport || !canvas) {
            return;
        }

        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf5f5f5);

        const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
        camera.position.set(0, 0, 100);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: true,
            alpha: false
        });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        scene.add(new THREE.HemisphereLight(0xffffff, 0x26547c, 2));

        const keyLight = new THREE.DirectionalLight(0xffffff, 2.5);
        keyLight.position.set(2, 3, 4);
        scene.add(keyLight);

        const resize = () => {
            const width = viewport.clientWidth;
            const height = viewport.clientHeight;

            camera.aspect = width / height;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        };

        let rotationProgress = 0;
        const targetRotation = Math.PI / 6; // 45 degrees
        const rotationDuration = 5500; // 3 seconds for 360 degrees
        const slowRotationDuration = 25000; // 2 seconds to slow down to 45 degrees
        let startTime = null;
        let rotationPhase = 0; // 0: fast 360, 1: slow to 45
        let phaseTransitionTime = 0;

        const easeOutCubic = (t) => 1 - Math.pow(1 - t, 2);

        const animate = () => {
            if (mesh_1 && mesh_2) {
                if (startTime === null) {
                    startTime = Date.now();
                }

                const elapsed = Date.now() - startTime;
                if (elapsed < slowRotationDuration) {
                    const progress = easeOutCubic(elapsed / slowRotationDuration);
                    rotationProgress = (Math.PI * 4 + targetRotation) * progress;
                } else {
                    rotationProgress = targetRotation;
                }

                mesh_1.rotation.y = rotationProgress;
                mesh_2.rotation.y = rotationProgress;
                mesh_3.rotation.y = rotationProgress;
            }
            renderer.render(scene, camera);
            requestAnimationFrame(animate);
        };

        let mesh_1 = null;
        const loader = new PLYLoader();
        loader.load(
            '/mesh/lowerRRR.ply',
            (geometry) => {
                geometry.computeVertexNormals();
                mesh_1 = new THREE.Mesh(
                    geometry,
                    new THREE.MeshStandardMaterial({
                        color: 0x94866b,
                        metalness: 0.07,
                        roughness: 0.05,
                        side: THREE.DoubleSide
                    })
                );
                scene.add(mesh_1);
            },
            undefined,
            (error) => console.error('Unable to load the 3D mesh.', error)
        );

        let mesh_2 = null;
        loader.load(
            '/mesh/upperRRR.ply',
            (geometry) => {
                geometry.computeVertexNormals();
                mesh_2 = new THREE.Mesh(
                    geometry,
                    new THREE.MeshStandardMaterial({
                        color: 0x94866b,
                        metalness: 0.07,
                        roughness: 0.05,
                        side: THREE.DoubleSide
                    })
                );
                scene.add(mesh_2);
            },
            undefined,
            (error) => console.error('Unable to load the 3D mesh.', error)
        );

        let mesh_3 = null;
        loader.load(
            '/mesh/crown16RRR.ply',
            (geometry) => {
                geometry.computeVertexNormals();
                mesh_3 = new THREE.Mesh(
                    geometry,
                    new THREE.MeshStandardMaterial({
                        color: 0xeadba2,
                        metalness: 0.45,
                        roughness: 0.05,
                        side: THREE.DoubleSide
                    })
                );
                scene.add(mesh_3);
            },
            undefined,
            (error) => console.error('Unable to load the 3D mesh.', error)
        );

        window.addEventListener('resize', resize);
        resize();
        animate();
    })();
</script>