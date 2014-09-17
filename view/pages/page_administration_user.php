<h1><?= $translator->getTranslation("Profil de l'utilisateur") ?></h1>
<form action="/" id="formUser">

<?= $translator->getTranslation("Identifiant") ?> <input type='text' name='userId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $activeUser->get('userId'); ?>" /><br />
<i><?= $translator->getTranslation("L'identifiant permet d'associer son profil utilisateur a un autre, à copier et à envoyer à son partenaire") ?></i><br />
<br />
<?= $translator->getTranslation("Nom") ?> <input type='text' name='name' size='41' value="<?= $activeUser->get('name') ?>" /><br /> 
<i><?= $translator->getTranslation("Le nom est affiché dans les différents écrans de l'application") ?></i><br />
<br /> 
<?= $translator->getTranslation("Email") ?> <input type='text' name='email' size='41' value="<?= $activeUser->get('email') ?>" /><br />
<i><?= $translator->getTranslation("L'email est utilisé pour l'authentification à l'application") ?></i><br />
<br /> 
<?= $translator->getTranslation("Date d'inscription") ?> <input name="subscriptionDate" type='text' size='15' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $activeUser->get('subscriptionDate') ?>" /><br />
<?= $translator->getTranslation("Culture") ?> <input type='text' name='culture' size='5' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $activeUser->get('culture') ?>" /> <i></i><br />
<br />
<font color='red'><?= $translator->getTranslation("Désactiver mon compte") ?> <input name='delete' type='checkbox' /></font><br />
<i><?= $translator->getTranslation("Cette opération est irréversible") ?></i><br /><br />
<input type="submit" id='submitFormUser' value="Mettre à jour" />
<div id='formUserResult'></div>
</form>

<script type='text/javascript'>
$("#formUser").submit( function () {
	document.getElementById("submitFormUser").disabled = true;
	$.post (
		'../controller/controller.php?action=user_modification',
		$(this).serialize(),
		function(response, status) {
			$("#formUserResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formUserResult").html(response);
				}
				else {
					$("#formUserResult").html(response);
				}
			}
			else {
				$("#formUserResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitFormUser").disabled = false;

			setTimeout(function() {
				$("#formUserResult").fadeOut("slow", function () {
					$('#formUserResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>

<h1><?= $translator->getTranslation("Profil couple") ?></h1>

<form action="/" id="formDuo">
<input type='hidden' name='userId' value="<?= $activeUser->get('userId'); ?>" />
<?= $translator->getTranslation("En couple avec") ?> <input type='text' name='partnerUserId' size='41' value="<?= $activeUser->GetPartnerId(); ?>" /> <i>(Entrez l'identifiant de votre partenaire)</i><br /> 
<br />
<font color='red'><?= $translator->getTranslation("Séparation de couple") ?> <input name='delete' type='checkbox' /></font> <i>Cocher pour retirer le couple</i><br /><br />
<input type="submit" id='submitFormDuo' value="Mettre à jour" />
<div id='formUserDuo'></div>
</form>

<script type='text/javascript'>
$("#formDuo").submit( function () {
	document.getElementById("submitFormDuo").disabled = true;
	$.post (
		'../controller/controller.php?action=user_duo',
		$(this).serialize(),
		function(response, status) {
			$("#formUserDuo").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formUserDuo").html(response);
				}
				else {
					$("#formUserDuo").html(response);
				}
			}
			else {
				$("#formUserDuo").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitFormDuo").disabled = false;

			setTimeout(function() {
				$("#formUserDuo").fadeOut("slow", function () {
					$('#formUserDuo').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>