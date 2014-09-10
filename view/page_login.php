<?php include 'page_login_logic.php'; ?>
<!doctype html>
<html>
<head>
<?php include '../component/component_head.php'; ?>
<title><?= $translator->getTranslation("BudgetFox / Login") ?></title>
<link href="budgetfox_login.css" rel="stylesheet" />
<script src="../3rd_party/md5.js"></script>
<script src="budgetfox_login.js"></script>
</head>
<body>
<table width="100%">
<tr>
<td valign="top" align="left">
<img src="../media/gfc.ico" /> BudgetFox par <a href="http://stevefuchs.fr/">Steve Fuchs</a><br />
<br />
<a href="copyright.htm" target="blank">Mentions légales - Copyright</a>
</td>
<td valign="top" align="right">
<!-- Ad placeholder -->
</td>
</tr>
</table>
<br/>
<div class="centered">
<form id="saasLoginForm" action="/">
<table>

<tr>
<td colspan="2">
<table>
<tr>
	<td>Email</td>
	<td><input type="text" name="email" size="35" /></td>
</tr>
<tr>
	<td>Mot de passe</td>
	<td><input type="password" name="password" id="password" size="35" /></td>
</tr>
<tr>
    <td><i>Code de sécurité</i></td>
    <td><i><input id="passwordMD5" style="background-color : #d1d1d1;" readonly="readonly" type="text" name="passwordMD5" size="35" autocomplete="off" value="" /></i></td>
</tr>
</table>
</td>
</tr>

<tr>
<td align="left"><input value="Créer un compte" id="subscriptionButton" type="button"></td>
<td align="right"><input value="Se connecter" name="submit" type="submit"></td>
</tr>

<tr>
<td colspan="2"><div id="saasLoginFormResult"></div></td>
</tr>

</table>
</form>

</div>
</body>
</html>