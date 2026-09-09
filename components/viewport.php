<div id="three-viewport" class="three-viewport" aria-label="Vista 3D de una corona dental">
    <canvas id="three-canvas"></canvas>
</div>

<script type="module">
    import * as THREE from '/node_modules/three/build/three.module.js';
    import { PLYLoader } from '/node_modules/three/examples/jsm/loaders/PLYLoader.js';

    (() => {
        const viewport = document.getElementById('three-viewport');
        const canvas = document.getElementById('three-canvas');

        if (!viewport || !canvas) {
            return;
        }

        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xffffff);

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
        };

        const animate = () => {
            if (mesh_1 && mesh_2) {
                mesh_1.rotation.y += 0.01;
                mesh_2.rotation.y += 0.01;
            }
            renderer.render(scene, camera);
            requestAnimationFrame(animate);
        };

        let mesh_1 = null;
        const loader = new PLYLoader();
        loader.load(
            '/mesh/16-maxilla.ply',
            (geometry) => {
                geometry.computeVertexNormals();
                mesh_1 = new THREE.Mesh(
                    geometry,
                    new THREE.MeshStandardMaterial({
                        color: 0xe8d9a0,
                        metalness: 0.45,
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
            '/mesh/16-mandibula.ply',
            (geometry) => {
                geometry.computeVertexNormals();
                mesh_2 = new THREE.Mesh(
                    geometry,
                    new THREE.MeshStandardMaterial({
                        color: 0xe8d9a0,
                        metalness: 0.45,
                        roughness: 0.05,
                        side: THREE.DoubleSide
                    })
                );
                scene.add(mesh_2);
            },
            undefined,
            (error) => console.error('Unable to load the 3D mesh.', error)
        );

        window.addEventListener('resize', resize);
        resize();
        animate();
    })();
</script>