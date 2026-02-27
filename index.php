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
		#menu {
		  position: absolute;
		  top: 20px;
		  left: 20px;
		  z-index: 10;
		}

		#btn-menu {
		  background: #1a1a2e;
		  color: white;
		  border: 1px solid #444;
		  padding: 10px 16px;
		  cursor: pointer;
		  font-size: 1rem;
		}

		#liste {
		  display: none;
		  background: #1a1a2e;
		  border: 1px solid #444;
		  margin-top: 5px;
		  min-width: 150px;
		}

		#liste a {
		  display: block;
		  color: white;
		  padding: 10px 16px;
		  cursor: pointer;
		  text-decoration: none;
		}

		#liste a:hover {
		  background: #2a2a4e;
		}
		
		#btn-reset {
		  position: absolute;
		  top: 20px;
		  left: 140px;
		  background: #1a1a2e;
		  color: white;
		  border: 1px solid #444;
		  padding: 11px 16px;
		  cursor: pointer;
		  font-size: 1rem;
		}

		#btn-reset:hover {
		  background: #2a2a4e;
		}
		
		#loading {
		  display: none;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  transform: translate(-50%, -50%);
		  color: white;
		  font-family: 'Inter', sans-serif;
		  font-size: 1.2rem;
		  background: rgba(0,0,0,0.6);
		  padding: 16px 32px;
		  border-radius: 4px;
		}
		
		#nom-modele {
		  position: absolute;
		  bottom: 20px;
		  left: 50%;
		  transform: translateX(-50%);
		  color: white;
		  font-family: 'Inter', sans-serif;
		  font-size: 1rem;
		  background: rgba(0,0,0,0.4);
		  padding: 8px 16px;
		  border-radius: 4px;
		}
	</style>
</head>

<body>

	<div id="menu">
		<button id="btn-menu">☰ Modèles</button>
		<div id="liste"></div>
	</div>
	
	<button id="btn-reset">⟳ Caméra</button>
	
	<div id="loading">Chargement...</div>
	
	<div id="nom-modele"></div>
	
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
  <script>
		// 1. La scène
		const scene = new THREE.Scene();
		scene.background = new THREE.Color(0x1a1a2e);
		
		// Lumière ambiante (éclaire tout uniformément)
		const ambientLight = new THREE.AmbientLight(0xffffff, 0.3);
		scene.add(ambientLight);

		// Lumière directionnelle (comme le soleil)
		const dirLight = new THREE.DirectionalLight(0xffffff, 2.5);
		dirLight.position.set(0, 10, 8);
		dirLight.castShadow = true;
		scene.add(dirLight);

		// 2. La caméra
		// (champ de vision, ratio largeur/hauteur, distance min, distance max)
		const camera = new THREE.PerspectiveCamera(
		  75,
		  window.innerWidth / window.innerHeight,
		  0.1,
		  1000
		);
		camera.position.z = 3;

		// 3. Le renderer
		const renderer = new THREE.WebGLRenderer({ antialias: true });
		renderer.setSize(window.innerWidth, window.innerHeight);
		renderer.shadowMap.enabled = true;
		document.body.appendChild(renderer.domElement);
		
		//gestion des déplacements
		const controls = new THREE.OrbitControls(camera, renderer.domElement);
		controls.enableDamping = true; // mouvement plus fluide
		
		//reset camera
		document.getElementById('btn-reset').addEventListener('click', function() {
		  camera.position.set(0, 0, 3);
		  controls.target.set(0, 0, 0);
		  controls.update();
		});
		
		//création du sol
		const solGeometry = new THREE.PlaneGeometry(10, 10);
		const solMaterial = new THREE.MeshStandardMaterial({ color: 0x111122 });
		const sol = new THREE.Mesh(solGeometry, solMaterial);
		sol.rotation.x = -Math.PI / 2;
		sol.position.y = -1;
		sol.receiveShadow = true;
		scene.add(sol);
		
		//Variable contenant le modèle actuel de la scène
		let modeleActuel = null;
		
		//modèle de départ
		const loader = new THREE.GLTFLoader();
		document.getElementById('loading').style.display = 'block';
		loader.load(
		  'modeles/robot.glb',
		  function(gltf) {
			gltf.scene.traverse(function(child) {
			if (child.isMesh) {
			  child.castShadow = true;
			  child.receiveShadow = true;
			}
		  });
			scene.add(gltf.scene);
			modeleActuel = gltf.scene;
			document.getElementById('loading').style.display = 'none';
			document.getElementById('nom-modele').textContent = "robot";
			console.log("Modèle chargé !");
		  },
		  function(progress) {
			console.log("Chargement...", progress.loaded, "/", progress.total);
		  },
		  function(error) {
			console.log("Erreur :", error);
		  }
		);
		
		//Gestion menu des modèles
		document.getElementById('btn-menu').addEventListener('click', function() {
		  const liste = document.getElementById('liste');
		  if (liste.style.display === 'block') {
			liste.style.display = 'none';
		  } else {
			liste.style.display = 'block';
		  }
		});
		
		fetch('liste_modeles.php')
		  .then(response => response.json())
		  .then(function(modeles) {
			const liste = document.getElementById('liste');

			modeles.forEach(function(modele) {
			  const lien = document.createElement('a');
			  lien.textContent = modele.nom;

			  lien.addEventListener('click', function() {
				console.log("Fichier à charger :", modele.fichier);
				// Supprimer l'ancien modèle
				if (modeleActuel) scene.remove(modeleActuel);

				// Charger le nouveau
				document.getElementById('loading').style.display = 'block';
				loader.load(modele.fichier, function(gltf) {
				  gltf.scene.traverse(function(child) {
					if (child.isMesh) {
					  child.castShadow = true;
					  child.receiveShadow = true;
					}
				  });
				  scene.add(gltf.scene);
				  modeleActuel = gltf.scene;
				  document.getElementById('loading').style.display = 'none';
				  document.getElementById('nom-modele').textContent = modele.nom;
				  console.log("Modèle chargé !");

				  liste.style.display = 'none';
				},
				function(progress) {
					console.log("Chargement...", progress.loaded, "/", progress.total);
				},
				function(error) {
					console.log("Erreur :", error);
				});
			  });

			  liste.appendChild(lien);
			});
		  });

		//rendu par frame
		function animate() {
		  requestAnimationFrame(animate);
		  controls.update();

		  renderer.render(scene, camera);
		}

		animate();
  </script>

</body>
<html>