<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$users_delete = NULL; // Initialize page object first

class cusers_delete extends cusers {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("userslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in users class, usersinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->activation_code->setDbValue($rs->fields('activation_code'));
		$this->asiatech_code->setDbValue($rs->fields('asiatech_code'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->melli_code->setDbValue($rs->fields('melli_code'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->mobile->setDbValue($rs->fields('mobile'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->province_id->setDbValue($rs->fields('province_id'));
		$this->address->setDbValue($rs->fields('address'));
		$this->password->setDbValue($rs->fields('password'));
		$this->register_date->setDbValue($rs->fields('register_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->activation_code->DbValue = $row['activation_code'];
		$this->asiatech_code->DbValue = $row['asiatech_code'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->melli_code->DbValue = $row['melli_code'];
		$this->_email->DbValue = $row['email'];
		$this->mobile->DbValue = $row['mobile'];
		$this->city_id->DbValue = $row['city_id'];
		$this->province_id->DbValue = $row['province_id'];
		$this->address->DbValue = $row['address'];
		$this->password->DbValue = $row['password'];
		$this->register_date->DbValue = $row['register_date'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// activation_code
		// asiatech_code
		// first_name
		// last_name
		// melli_code
		// email
		// mobile
		// city_id
		// province_id
		// address
		// password
		// register_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// activation_code
			$this->activation_code->ViewValue = $this->activation_code->CurrentValue;
			$this->activation_code->ViewCustomAttributes = "";

			// asiatech_code
			$this->asiatech_code->ViewValue = $this->asiatech_code->CurrentValue;
			$this->asiatech_code->ViewCustomAttributes = "";

			// first_name
			$this->first_name->ViewValue = $this->first_name->CurrentValue;
			$this->first_name->ViewCustomAttributes = "";

			// last_name
			$this->last_name->ViewValue = $this->last_name->CurrentValue;
			$this->last_name->ViewCustomAttributes = "";

			// melli_code
			$this->melli_code->ViewValue = $this->melli_code->CurrentValue;
			$this->melli_code->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// mobile
			$this->mobile->ViewValue = $this->mobile->CurrentValue;
			$this->mobile->ViewCustomAttributes = "";

			// city_id
			$this->city_id->ViewValue = $this->city_id->CurrentValue;
			$this->city_id->ViewCustomAttributes = "";

			// province_id
			$this->province_id->ViewValue = $this->province_id->CurrentValue;
			$this->province_id->ViewCustomAttributes = "";

			// password
			$this->password->ViewValue = $this->password->CurrentValue;
			$this->password->ViewCustomAttributes = "";

			// register_date
			$this->register_date->ViewValue = $this->register_date->CurrentValue;
			$this->register_date->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// activation_code
			$this->activation_code->LinkCustomAttributes = "";
			$this->activation_code->HrefValue = "";
			$this->activation_code->TooltipValue = "";

			// asiatech_code
			$this->asiatech_code->LinkCustomAttributes = "";
			$this->asiatech_code->HrefValue = "";
			$this->asiatech_code->TooltipValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// melli_code
			$this->melli_code->LinkCustomAttributes = "";
			$this->melli_code->HrefValue = "";
			$this->melli_code->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";
			$this->mobile->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";
			$this->province_id->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// register_date
			$this->register_date->LinkCustomAttributes = "";
			$this->register_date->HrefValue = "";
			$this->register_date->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "userslist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($users_delete)) $users_delete = new cusers_delete();

// Page init
$users_delete->Page_Init();

// Page main
$users_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var users_delete = new ew_Page("users_delete");
users_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = users_delete.PageID; // For backward compatibility

// Form object
var fusersdelete = new ew_Form("fusersdelete");

// Form_CustomValidate event
fusersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersdelete.ValidateRequired = true;
<?php } else { ?>
fusersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($users_delete->Recordset = $users_delete->LoadRecordset())
	$users_deleteTotalRecs = $users_delete->Recordset->RecordCount(); // Get record count
if ($users_deleteTotalRecs <= 0) { // No record found, exit
	if ($users_delete->Recordset)
		$users_delete->Recordset->Close();
	$users_delete->Page_Terminate("userslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $users_delete->ShowPageHeader(); ?>
<?php
$users_delete->ShowMessage();
?>
<form name="fusersdelete" id="fusersdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($users_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_usersdelete" class="ewTable ewTableSeparate">
<?php echo $users->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($users->id->Visible) { // id ?>
		<td><span id="elh_users_id" class="users_id"><?php echo $users->id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->activation_code->Visible) { // activation_code ?>
		<td><span id="elh_users_activation_code" class="users_activation_code"><?php echo $users->activation_code->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->asiatech_code->Visible) { // asiatech_code ?>
		<td><span id="elh_users_asiatech_code" class="users_asiatech_code"><?php echo $users->asiatech_code->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
		<td><span id="elh_users_first_name" class="users_first_name"><?php echo $users->first_name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
		<td><span id="elh_users_last_name" class="users_last_name"><?php echo $users->last_name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->melli_code->Visible) { // melli_code ?>
		<td><span id="elh_users_melli_code" class="users_melli_code"><?php echo $users->melli_code->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
		<td><span id="elh_users__email" class="users__email"><?php echo $users->_email->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->mobile->Visible) { // mobile ?>
		<td><span id="elh_users_mobile" class="users_mobile"><?php echo $users->mobile->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->city_id->Visible) { // city_id ?>
		<td><span id="elh_users_city_id" class="users_city_id"><?php echo $users->city_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->province_id->Visible) { // province_id ?>
		<td><span id="elh_users_province_id" class="users_province_id"><?php echo $users->province_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
		<td><span id="elh_users_password" class="users_password"><?php echo $users->password->FldCaption() ?></span></td>
<?php } ?>
<?php if ($users->register_date->Visible) { // register_date ?>
		<td><span id="elh_users_register_date" class="users_register_date"><?php echo $users->register_date->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$users_delete->RecCnt = 0;
$i = 0;
while (!$users_delete->Recordset->EOF) {
	$users_delete->RecCnt++;
	$users_delete->RowCnt++;

	// Set row properties
	$users->ResetAttrs();
	$users->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$users_delete->LoadRowValues($users_delete->Recordset);

	// Render row
	$users_delete->RenderRow();
?>
	<tr<?php echo $users->RowAttributes() ?>>
<?php if ($users->id->Visible) { // id ?>
		<td<?php echo $users->id->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_id" class="control-group users_id">
<span<?php echo $users->id->ViewAttributes() ?>>
<?php echo $users->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->activation_code->Visible) { // activation_code ?>
		<td<?php echo $users->activation_code->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_activation_code" class="control-group users_activation_code">
<span<?php echo $users->activation_code->ViewAttributes() ?>>
<?php echo $users->activation_code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->asiatech_code->Visible) { // asiatech_code ?>
		<td<?php echo $users->asiatech_code->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_asiatech_code" class="control-group users_asiatech_code">
<span<?php echo $users->asiatech_code->ViewAttributes() ?>>
<?php echo $users->asiatech_code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
		<td<?php echo $users->first_name->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_first_name" class="control-group users_first_name">
<span<?php echo $users->first_name->ViewAttributes() ?>>
<?php echo $users->first_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
		<td<?php echo $users->last_name->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_last_name" class="control-group users_last_name">
<span<?php echo $users->last_name->ViewAttributes() ?>>
<?php echo $users->last_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->melli_code->Visible) { // melli_code ?>
		<td<?php echo $users->melli_code->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_melli_code" class="control-group users_melli_code">
<span<?php echo $users->melli_code->ViewAttributes() ?>>
<?php echo $users->melli_code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
		<td<?php echo $users->_email->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users__email" class="control-group users__email">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->mobile->Visible) { // mobile ?>
		<td<?php echo $users->mobile->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_mobile" class="control-group users_mobile">
<span<?php echo $users->mobile->ViewAttributes() ?>>
<?php echo $users->mobile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->city_id->Visible) { // city_id ?>
		<td<?php echo $users->city_id->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_city_id" class="control-group users_city_id">
<span<?php echo $users->city_id->ViewAttributes() ?>>
<?php echo $users->city_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->province_id->Visible) { // province_id ?>
		<td<?php echo $users->province_id->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_province_id" class="control-group users_province_id">
<span<?php echo $users->province_id->ViewAttributes() ?>>
<?php echo $users->province_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
		<td<?php echo $users->password->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_password" class="control-group users_password">
<span<?php echo $users->password->ViewAttributes() ?>>
<?php echo $users->password->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->register_date->Visible) { // register_date ?>
		<td<?php echo $users->register_date->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_register_date" class="control-group users_register_date">
<span<?php echo $users->register_date->ViewAttributes() ?>>
<?php echo $users->register_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$users_delete->Recordset->MoveNext();
}
$users_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fusersdelete.Init();
</script>
<?php
$users_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_delete->Page_Terminate();
?>
