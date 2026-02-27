<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Bisiaux Valentin"/>
	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Site de visualisation 3D'; ?>"/>
	<meta name="keywords" content="Valentin, Bisiaux, 3D"/>
    <meta name="date" content="2026-02-27"/>
    <meta name="location" content="Cergy, France"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="purpose" content="Site permettant la visualisation de modèle 3D"/>
	<link href="https://fonts.googleapis.com/css2?family=Inter&amp;display=swap" rel="stylesheet"/>
	<title><?php echo isset($pageTitle) ? $pageTitle : 'Site visualisation 3D'; ?></title>
	<!-- Favicon -->
    <link rel="icon" type="image/png" href="images/icon.png"/>
	<style>
		* { margin: 0; padding: 0; box-sizing: border-box; }
		body { overflow: hidden; background: #1a1a2e; }
		canvas { display: block; }
	</style>
</head>

<body>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script>
		// 1. La scène
		const scene = new THREE.Scene();

		// 2. La caméra
		// (champ de vision, ratio largeur/hauteur, distance min, distance max)
		const camera = new THREE.PerspectiveCamera(
		  75,
		  window.innerWidth / window.innerHeight,
		  0.1,
		  1000
		);
		camera.position.z = 3; // on recule la caméra pour voir la scène

		// 3. Le renderer (il crée automatiquement un <canvas>)
		const renderer = new THREE.WebGLRenderer({ antialias: true });
		renderer.setSize(window.innerWidth, window.innerHeight);
		document.body.appendChild(renderer.domElement);
		
		// La forme : un cube
		const geometry = new THREE.BoxGeometry(1, 1, 1);

		// L'apparence : une couleur basique
		const material = new THREE.MeshBasicMaterial({ color: 0x6a7cff });

		// On combine les deux
		const cube = new THREE.Mesh(geometry, material);

		// On l'ajoute à la scène
		scene.add(cube);

		function animate() {
		  requestAnimationFrame(animate); // rappelle animate() à chaque frame

		  // On fait tourner le cube un peu à chaque frame
		  cube.rotation.x += 0.01;
		  cube.rotation.y += 0.01;

		  renderer.render(scene, camera);
		}

		animate(); // on démarre la boucle
  </script>

</body>
<html>