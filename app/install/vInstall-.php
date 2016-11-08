<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cloud-ARAY - Installation</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>


<div style="padding-top:70px" class="container">
	<div class="col-md-6">
		<fieldset>
			<legend>Formulaire d'installation</legend>
			<form action="" method="post">
				<div class="form-group">
					<div class="well">
						<div>
							<label>Base de donnée : </label> <?=$message?>
						</div>
						<div>
							<label>Configuration : </label> <?=$messageConf." / ".$messageCreateConf?>
						</div>
						<div>
							<label>Php Version : </label> <?=phpversion()?>
						</div>
						<div>
							<label>Mysql version/ Etat : </label> <?=substr(mysqli_get_client_info(), 7,7)?>/Connecté
						</div>
					</div>
				</div>
				<?php if(isset($postErreur)):?>
				<div class="alert alert-danger"><?=$postErreur?></div>
				<?php endif;?>
				<div class="form-group">
					<label>Url <em>*</em></label>
					<input name="url" class="form-control" type="text" name="url" value="127.0.0.1" autocomplete="off">
				</div>
				 <div class="form-group">
					<label>Base de données <em>*</em></label>
					<input name="base" class="form-control" type="text" value="<?=$base?>" autocomplete="off">
				</div>
				 <div class="form-group">
					<label>Nom d'utilisateur <em>*</em></label>
					<input name="utilisateur" class="form-control" type="text" value="<?=$utilisateur?>" autocomplete="off">
				</div>
				 <div class="form-group">
					<label>Mot de passe</label>
					<input name="password" class="form-control" type="password">
				</div>
				 <div class="form-group">
					<input name="<?=$action?>" class="form-control btn btn-success" type="submit" value="Valider">
				</div>
			</form>
		</fieldset>
	</div>
</div>
</body>
</html>