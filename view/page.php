<?php
include '../component/component_security.php';

$page = '';
if (isset($_GET['page']))
	$page = $_GET['page'];

$id = '';
if (isset($_GET['id']))
	$id = $_GET['id'];

$data = '';
if (isset($_GET['data']))
	$data = $_GET['data'];

$windowTitle = '';

$_SESSION['account_id'] = $id;
$_SESSION['page'] = $page;
$_SESSION['data'] = $data;

if (!isset($_POST))
	exit();

$page = isset($_POST['page']) ? $_POST['page'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$data = isset($_POST['data']) ? $_POST['data'] : '';

$translator = new Translator();

$accountsHandler = new AccountsHandler();

//if ($id != '')
//	$activeAccount = $accountsManager->GetAccount($id);
//else
$activeAccount = $accountsHandler->GetCurrentActiveAccount();
$accountType = $activeAccount->get('type');

$pageName = $page;

$fullRecordsView = false;

if ($pageName == 'records-fullview')
{
	$pageName = 'records';
	echo 'FULLVIEW';
	$fullRecordsView = true;
	
}

if ($pageName == '-')
	$pageName = 'records';

if ($accountType == -50 && $pageName == 'records')
	$pageName = 'home';

if ($accountType == -100 && $pageName == 'records')
	$pageName = '';

//if ($accountType == 100 && $pageName == 'records')
	//$pageName = 'investment_records_dashboard';

$categoriesHandler = new CategoriesHandler();

$recordsHandler = new RecordsHandler();

$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();
$partnerUser = $usersHandler->GetUser($activeUser->GetPartnerId());

if ($accountType >= 1 && $accountType <= 10)
	$windowTitle .= $activeAccount->get('name');

// --------------------------------------------------------------------------------------------------

if ($pageName == 'record' && $area == 'investment')
	$pageName = 'investment_record';

// Default page for administration
if ($pageName == 'administration' && $area == 'administration' && $id == '')
	$pageName = 'administration_connection';

switch ($pageName)
{
	case 'record_remark':
	case 'record_transfer':
	case 'record_payment':
	case 'record_deposit':
	case 'investment_record_value':
	case 'investment_record_deposit';
	case 'investment_record_withdrawal';
	case 'investment_record_remark';
		include 'pages/page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'investment_record_statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'pages/page_'.$pageName.'_global.php';
		else if ($accountType == 1)
			include 'pages/page_'.$pageName.'_private.php';
		else if ($accountType == 10)
			include 'pages/page_'.$pageName.'.php';
		else if ($accountType == 100)
			include 'pages/page_'.$pageName.'_global.php';
		else
			include 'pages/page_'.$pageName.'_duo.php';
		break;

	case 'statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'pages/page_'.$pageName.'_global.php';
		else if ($accountType == 2 || $accountType == 3)
			include 'pages/page_'.$pageName.'_duo.php';
		else if ($accountType == 10)
			include 'pages/page_'.$pageName.'_investment.php';
		else if ($accountType == 100)
			include 'pages/page_'.$pageName.'_investment_global.php';
		else
			include 'pages/page_'.$pageName.'_private.php';
		break;


	case 'configuration';
		break;

	default:
		include 'pages/page_'.$pageName.'.php';
		break;
}

// ------------------------------------------------------------------------------------------------

function AddFormManagementEnd($pageName)
{
?>
<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$.post (
		'../controller/controller.php?action=<?php echo $pageName; ?>',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formResult").html(response);
				}
				else {
					ChangeContext_Page('record');
				}
			}
			else {
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});

$( "#datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "../media/calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
});

$( ".datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "../media/calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
});
</script>
<?php
}
?>
<script type="text/javascript">
SetTitle('<?php echo $windowTitle; ?>');
</script>
