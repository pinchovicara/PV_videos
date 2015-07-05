<?php

	require_once 'conf/conf.php';

	function getListeVideo(){
		$resultat = '';

		try {
			$bdd = new PDO('mysql:host='.$GLOBALS['dbhost'].';dbname='.$GLOBALS['dbname'], $GLOBALS['dbuser'],$GLOBALS['dbpwd']);
		} catch (Exception $e) {
			die('Erreur de connexion à la base de données');
		}
		
		$bdd->exec("SET CHARACTER SET utf8");
		$reponse = $bdd->query('SELECT `id_video`, `titre_video`, `code_video`, YEAR(`date_video`) AS annee_video, `codehebergeur_video`, `commentaire_video` FROM `pv_videos_videos` ORDER BY date_video DESC');

		$resultat = getCodeListeVideo($reponse);

		echo $resultat;
	}

	function getCodeListeVideo($reponse){
		$anneeRef = 0;
		$resultat = '';
		$resultatTemp = '';

		while ($video = $reponse->fetch()) {
			$annee = $video['annee_video'];

			if ($anneeRef != $annee AND $anneeRef != 0) {
				$resultat .= getSeparateur($anneeRef);
				$resultat .= getDebutListe();
				$resultat .= $resultatTemp;
				$resultat .= getFinListe();

				$resultatTemp = '';
			}

			if ($anneeRef != $annee) {
					$anneeRef = $annee;
			}

			$resultatTemp .= getElement($video['id_video'], $video['titre_video'], $video['code_video'], $video['codehebergeur_video'], $video['commentaire_video']);

		}

		$resultat .= getSeparateur($anneeRef);
		$resultat .= getDebutListe();
		$resultat .= $resultatTemp;
		$resultat .= getFinListe();

		return $resultat;
	}

	function getSeparateur($annee){
		return '<div class="separateur"><span class="date">'.$annee.'</span></div>';
	}

	function getDebutListe(){
		return '<ul class="videos">';
	}

	function getFinListe(){
		return '</ul>';
	}

	function getElement($id, $titre, $code, $hebergeur, $commentaire){
		$resultat = '<li class="video" id="'. $id .'">';
		$resultat .= '<h1>'. $titre . '</h1>';
		$resultat .= '<div>';
		$resultat .= getCodeElement($code, $hebergeur);
		$resultat .= '</div>';

		if ($commentaire != "") {
			$resultat .= '<p class="commentaire">';
			$resultat .= $commentaire;
			$resultat .= '</p>';
		}
		
		$resultat .= '</li>';

		return $resultat;
	}

	function getCodeElement($code, $hebergeur){

		$video_width = 600;
		$video_height = 338;

		switch ($hebergeur) {
			case '1':
				$resultat = '<iframe frameborder="0" width="'. $video_width .'" height="'. $video_height .'" src="//www.dailymotion.com/embed/video/'.$code.'" allowfullscreen></iframe>';
				break;
			
			case '2':
				$resultat = '<iframe width="'. $video_width .'" height="'. $video_height .'" src="https://www.youtube.com/embed/' . $code . '?rel=0" frameborder="0" allowfullscreen></iframe>';
				break;
		}

		return $resultat;
	}
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Galerie vidéo</title>
		<style type="text/css">
			body{
				margin: 0;
				padding: 0;
				background: #272727;
				color: #BDBDBD;
				font: 12px Tahoma,Verdana,Arial,Helvetica, sans-serif;
			}

			ul{
				margin: 0;
				padding:0;
			}

			.videos{
				list-style: none;
				text-align: center;
			}

			.video{
				margin-top: 10px;
				margin-bottom: 50px;
			}

			#GoToTop a {
				position: fixed;
				bottom:10px;
				right: 10px;
				z-index: 2;
			}

			.separateur{
				border-bottom: solid 1px;
				display: block;
				width: 700px;
				margin-left: auto;
				margin-right: auto;
				margin-top: 30px;
				margin-bottom: 20px;
			}

			.date{
				margin-left: 10px;
				font-size: 2em;
			}

			.commentaire{
				margin-top: 10px;
				margin-bottom: 10px;
				text-align: left;
				width: 700px;
				margin-right: auto;
				margin-left: auto;
				text-indent: 10px;
				font-size: 1.1em;
			}
		</style>
	</head>
	<body>
	<div id="GoToTop">
		<a href="#">
			<img src="images/flèche.png" alt="Revenir en haut de la page" title="Revenir en haut de la page">
		</a>
	</div>
	<?php
		getListeVideo();
	?>
	</body>
</html>