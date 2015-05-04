<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "advertisesinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$advertises_delete = NULL; // Initialize page object first

class cadvertises_delete extends cadvertises {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'advertises';

	// Page object name
	var $PageObjName = 'advertises_delete';

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

		// Table object (advertises)
		if (!isset($GLOBALS["advertises"]) || get_class($GLOBALS["advertises"]) == "cadvertises") {
			$GLOBALS["advertises"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["advertises"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'advertises', TRUE);

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
			$this->Page_Terminate("advertiseslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in advertises class, advertisesinfo.php

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
		$this->name->setDbValue($rs->fields('name'));
		$this->cat_id->setDbValue($rs->fields('cat_id'));
		$this->sub_cat_id->setDbValue($rs->fields('sub_cat_id'));
		$this->slogan->setDbValue($rs->fields('slogan'));
		$this->city_id->setDbValue($rs->fields('city_id'));
		$this->province_id->setDbValue($rs->fields('province_id'));
		$this->address->setDbValue($rs->fields('address'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->mobile->setDbValue($rs->fields('mobile'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->keywords->setDbValue($rs->fields('keywords'));
		$this->register_date->setDbValue($rs->fields('register_date'));
		$this->google_map->setDbValue($rs->fields('google_map'));
		$this->activate->setDbValue($rs->fields('activate'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->image->setDbValue($rs->fields('image'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->cat_id->DbValue = $row['cat_id'];
		$this->sub_cat_id->DbValue = $row['sub_cat_id'];
		$this->slogan->DbValue = $row['slogan'];
		$this->city_id->DbValue = $row['city_id'];
		$this->province_id->DbValue = $row['province_id'];
		$this->address->DbValue = $row['address'];
		$this->phone->DbValue = $row['phone'];
		$this->mobile->DbValue = $row['mobile'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->keywords->DbValue = $row['keywords'];
		$this->register_date->DbValue = $row['register_date'];
		$this->google_map->DbValue = $row['google_map'];
		$this->activate->DbValue = $row['activate'];
		$this->user_id->DbValue = $row['user_id'];
		$this->image->DbValue = $row['image'];
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
		// name
		// cat_id
		// sub_cat_id
		// slogan
		// city_id
		// province_id
		// address
		// phone
		// mobile
		// email
		// website
		// keywords
		// register_date
		// google_map
		// activate
		// user_id
		// image

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// cat_id
			$this->cat_id->ViewValue = $this->cat_id->CurrentValue;
			$this->cat_id->ViewCustomAttributes = "";

			// sub_cat_id
			$this->sub_cat_id->ViewValue = $this->sub_cat_id->CurrentValue;
			$this->sub_cat_id->ViewCustomAttributes = "";

			// slogan
			$this->slogan->ViewValue = $this->slogan->CurrentValue;
			$this->slogan->ViewCustomAttributes = "";

			// city_id
			$this->city_id->ViewValue = $this->city_id->CurrentValue;
			$this->city_id->ViewCustomAttributes = "";

			// province_id
			$this->province_id->ViewValue = $this->province_id->CurrentValue;
			$this->province_id->ViewCustomAttributes = "";

			// phone
			$this->phone->ViewValue = $this->phone->CurrentValue;
			$this->phone->ViewCustomAttributes = "";

			// mobile
			$this->mobile->ViewValue = $this->mobile->CurrentValue;
			$this->mobile->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// website
			$this->website->ViewValue = $this->website->CurrentValue;
			$this->website->ViewCustomAttributes = "";

			// keywords
			$this->keywords->ViewValue = $this->keywords->CurrentValue;
			$this->keywords->ViewCustomAttributes = "";

			// register_date
			$this->register_date->ViewValue = $this->register_date->CurrentValue;
			$this->register_date->ViewCustomAttributes = "";

			// activate
			$this->activate->ViewValue = $this->activate->CurrentValue;
			$this->activate->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// image
			$this->image->ViewValue = $this->image->CurrentValue;
			$this->image->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// cat_id
			$this->cat_id->LinkCustomAttributes = "";
			$this->cat_id->HrefValue = "";
			$this->cat_id->TooltipValue = "";

			// sub_cat_id
			$this->sub_cat_id->LinkCustomAttributes = "";
			$this->sub_cat_id->HrefValue = "";
			$this->sub_cat_id->TooltipValue = "";

			// slogan
			$this->slogan->LinkCustomAttributes = "";
			$this->slogan->HrefValue = "";
			$this->slogan->TooltipValue = "";

			// city_id
			$this->city_id->LinkCustomAttributes = "";
			$this->city_id->HrefValue = "";
			$this->city_id->TooltipValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";
			$this->province_id->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";
			$this->mobile->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// keywords
			$this->keywords->LinkCustomAttributes = "";
			$this->keywords->HrefValue = "";
			$this->keywords->TooltipValue = "";

			// register_date
			$this->register_date->LinkCustomAttributes = "";
			$this->register_date->HrefValue = "";
			$this->register_date->TooltipValue = "";

			// activate
			$this->activate->LinkCustomAttributes = "";
			$this->activate->HrefValue = "";
			$this->activate->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "advertiseslist.php", $this->TableVar, TRUE);
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
if (!isset($advertises_delete)) $advertises_delete = new cadvertises_delete();

// Page init
$advertises_delete->Page_Init();

// Page main
$advertises_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$advertises_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var advertises_delete = new ew_Page("advertises_delete");
advertises_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = advertises_delete.PageID; // For backward compatibility

// Form object
var fadvertisesdelete = new ew_Form("fadvertisesdelete");

// Form_CustomValidate event
fadvertisesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fadvertisesdelete.ValidateRequired = true;
<?php } else { ?>
fadvertisesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($advertises_delete->Recordset = $advertises_delete->LoadRecordset())
	$advertises_deleteTotalRecs = $advertises_delete->Recordset->RecordCount(); // Get record count
if ($advertises_deleteTotalRecs <= 0) { // No record found, exit
	if ($advertises_delete->Recordset)
		$advertises_delete->Recordset->Close();
	$advertises_delete->Page_Terminate("advertiseslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $advertises_delete->ShowPageHeader(); ?>
<?php
$advertises_delete->ShowMessage();
?>
<form name="fadvertisesdelete" id="fadvertisesdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="advertises">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($advertises_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_advertisesdelete" class="ewTable ewTableSeparate">
<?php echo $advertises->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($advertises->id->Visible) { // id ?>
		<td><span id="elh_advertises_id" class="advertises_id"><?php echo $advertises->id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->name->Visible) { // name ?>
		<td><span id="elh_advertises_name" class="advertises_name"><?php echo $advertises->name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->cat_id->Visible) { // cat_id ?>
		<td><span id="elh_advertises_cat_id" class="advertises_cat_id"><?php echo $advertises->cat_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
		<td><span id="elh_advertises_sub_cat_id" class="advertises_sub_cat_id"><?php echo $advertises->sub_cat_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->slogan->Visible) { // slogan ?>
		<td><span id="elh_advertises_slogan" class="advertises_slogan"><?php echo $advertises->slogan->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->city_id->Visible) { // city_id ?>
		<td><span id="elh_advertises_city_id" class="advertises_city_id"><?php echo $advertises->city_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->province_id->Visible) { // province_id ?>
		<td><span id="elh_advertises_province_id" class="advertises_province_id"><?php echo $advertises->province_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->phone->Visible) { // phone ?>
		<td><span id="elh_advertises_phone" class="advertises_phone"><?php echo $advertises->phone->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->mobile->Visible) { // mobile ?>
		<td><span id="elh_advertises_mobile" class="advertises_mobile"><?php echo $advertises->mobile->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->_email->Visible) { // email ?>
		<td><span id="elh_advertises__email" class="advertises__email"><?php echo $advertises->_email->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->website->Visible) { // website ?>
		<td><span id="elh_advertises_website" class="advertises_website"><?php echo $advertises->website->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->keywords->Visible) { // keywords ?>
		<td><span id="elh_advertises_keywords" class="advertises_keywords"><?php echo $advertises->keywords->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->register_date->Visible) { // register_date ?>
		<td><span id="elh_advertises_register_date" class="advertises_register_date"><?php echo $advertises->register_date->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->activate->Visible) { // activate ?>
		<td><span id="elh_advertises_activate" class="advertises_activate"><?php echo $advertises->activate->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->user_id->Visible) { // user_id ?>
		<td><span id="elh_advertises_user_id" class="advertises_user_id"><?php echo $advertises->user_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($advertises->image->Visible) { // image ?>
		<td><span id="elh_advertises_image" class="advertises_image"><?php echo $advertises->image->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$advertises_delete->RecCnt = 0;
$i = 0;
while (!$advertises_delete->Recordset->EOF) {
	$advertises_delete->RecCnt++;
	$advertises_delete->RowCnt++;

	// Set row properties
	$advertises->ResetAttrs();
	$advertises->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$advertises_delete->LoadRowValues($advertises_delete->Recordset);

	// Render row
	$advertises_delete->RenderRow();
?>
	<tr<?php echo $advertises->RowAttributes() ?>>
<?php if ($advertises->id->Visible) { // id ?>
		<td<?php echo $advertises->id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_id" class="control-group advertises_id">
<span<?php echo $advertises->id->ViewAttributes() ?>>
<?php echo $advertises->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->name->Visible) { // name ?>
		<td<?php echo $advertises->name->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_name" class="control-group advertises_name">
<span<?php echo $advertises->name->ViewAttributes() ?>>
<?php echo $advertises->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->cat_id->Visible) { // cat_id ?>
		<td<?php echo $advertises->cat_id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_cat_id" class="control-group advertises_cat_id">
<span<?php echo $advertises->cat_id->ViewAttributes() ?>>
<?php echo $advertises->cat_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
		<td<?php echo $advertises->sub_cat_id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_sub_cat_id" class="control-group advertises_sub_cat_id">
<span<?php echo $advertises->sub_cat_id->ViewAttributes() ?>>
<?php echo $advertises->sub_cat_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->slogan->Visible) { // slogan ?>
		<td<?php echo $advertises->slogan->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_slogan" class="control-group advertises_slogan">
<span<?php echo $advertises->slogan->ViewAttributes() ?>>
<?php echo $advertises->slogan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->city_id->Visible) { // city_id ?>
		<td<?php echo $advertises->city_id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_city_id" class="control-group advertises_city_id">
<span<?php echo $advertises->city_id->ViewAttributes() ?>>
<?php echo $advertises->city_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->province_id->Visible) { // province_id ?>
		<td<?php echo $advertises->province_id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_province_id" class="control-group advertises_province_id">
<span<?php echo $advertises->province_id->ViewAttributes() ?>>
<?php echo $advertises->province_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->phone->Visible) { // phone ?>
		<td<?php echo $advertises->phone->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_phone" class="control-group advertises_phone">
<span<?php echo $advertises->phone->ViewAttributes() ?>>
<?php echo $advertises->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->mobile->Visible) { // mobile ?>
		<td<?php echo $advertises->mobile->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_mobile" class="control-group advertises_mobile">
<span<?php echo $advertises->mobile->ViewAttributes() ?>>
<?php echo $advertises->mobile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->_email->Visible) { // email ?>
		<td<?php echo $advertises->_email->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises__email" class="control-group advertises__email">
<span<?php echo $advertises->_email->ViewAttributes() ?>>
<?php echo $advertises->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->website->Visible) { // website ?>
		<td<?php echo $advertises->website->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_website" class="control-group advertises_website">
<span<?php echo $advertises->website->ViewAttributes() ?>>
<?php echo $advertises->website->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->keywords->Visible) { // keywords ?>
		<td<?php echo $advertises->keywords->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_keywords" class="control-group advertises_keywords">
<span<?php echo $advertises->keywords->ViewAttributes() ?>>
<?php echo $advertises->keywords->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->register_date->Visible) { // register_date ?>
		<td<?php echo $advertises->register_date->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_register_date" class="control-group advertises_register_date">
<span<?php echo $advertises->register_date->ViewAttributes() ?>>
<?php echo $advertises->register_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->activate->Visible) { // activate ?>
		<td<?php echo $advertises->activate->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_activate" class="control-group advertises_activate">
<span<?php echo $advertises->activate->ViewAttributes() ?>>
<?php echo $advertises->activate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->user_id->Visible) { // user_id ?>
		<td<?php echo $advertises->user_id->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_user_id" class="control-group advertises_user_id">
<span<?php echo $advertises->user_id->ViewAttributes() ?>>
<?php echo $advertises->user_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($advertises->image->Visible) { // image ?>
		<td<?php echo $advertises->image->CellAttributes() ?>>
<span id="el<?php echo $advertises_delete->RowCnt ?>_advertises_image" class="control-group advertises_image">
<span<?php echo $advertises->image->ViewAttributes() ?>>
<?php echo $advertises->image->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$advertises_delete->Recordset->MoveNext();
}
$advertises_delete->Recordset->Close();
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
fadvertisesdelete.Init();
</script>
<?php
$advertises_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$advertises_delete->Page_Terminate();
?>
