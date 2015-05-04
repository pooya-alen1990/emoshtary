<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "imagesinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$images_delete = NULL; // Initialize page object first

class cimages_delete extends cimages {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'images';

	// Page object name
	var $PageObjName = 'images_delete';

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

		// Table object (images)
		if (!isset($GLOBALS["images"]) || get_class($GLOBALS["images"]) == "cimages") {
			$GLOBALS["images"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["images"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'images', TRUE);

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
			$this->Page_Terminate("imageslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in images class, imagesinfo.php

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
		$this->original_image->setDbValue($rs->fields('original_image'));
		$this->thumbnail_image->setDbValue($rs->fields('thumbnail_image'));
		$this->ip_address->setDbValue($rs->fields('ip_address'));
		$this->advertise_id->setDbValue($rs->fields('advertise_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->original_image->DbValue = $row['original_image'];
		$this->thumbnail_image->DbValue = $row['thumbnail_image'];
		$this->ip_address->DbValue = $row['ip_address'];
		$this->advertise_id->DbValue = $row['advertise_id'];
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
		// original_image
		// thumbnail_image
		// ip_address
		// advertise_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// original_image
			$this->original_image->ViewValue = $this->original_image->CurrentValue;
			$this->original_image->ViewCustomAttributes = "";

			// thumbnail_image
			$this->thumbnail_image->ViewValue = $this->thumbnail_image->CurrentValue;
			$this->thumbnail_image->ViewCustomAttributes = "";

			// ip_address
			$this->ip_address->ViewValue = $this->ip_address->CurrentValue;
			$this->ip_address->ViewCustomAttributes = "";

			// advertise_id
			$this->advertise_id->ViewValue = $this->advertise_id->CurrentValue;
			$this->advertise_id->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// original_image
			$this->original_image->LinkCustomAttributes = "";
			$this->original_image->HrefValue = "";
			$this->original_image->TooltipValue = "";

			// thumbnail_image
			$this->thumbnail_image->LinkCustomAttributes = "";
			$this->thumbnail_image->HrefValue = "";
			$this->thumbnail_image->TooltipValue = "";

			// ip_address
			$this->ip_address->LinkCustomAttributes = "";
			$this->ip_address->HrefValue = "";
			$this->ip_address->TooltipValue = "";

			// advertise_id
			$this->advertise_id->LinkCustomAttributes = "";
			$this->advertise_id->HrefValue = "";
			$this->advertise_id->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "imageslist.php", $this->TableVar, TRUE);
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
if (!isset($images_delete)) $images_delete = new cimages_delete();

// Page init
$images_delete->Page_Init();

// Page main
$images_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$images_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var images_delete = new ew_Page("images_delete");
images_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = images_delete.PageID; // For backward compatibility

// Form object
var fimagesdelete = new ew_Form("fimagesdelete");

// Form_CustomValidate event
fimagesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fimagesdelete.ValidateRequired = true;
<?php } else { ?>
fimagesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($images_delete->Recordset = $images_delete->LoadRecordset())
	$images_deleteTotalRecs = $images_delete->Recordset->RecordCount(); // Get record count
if ($images_deleteTotalRecs <= 0) { // No record found, exit
	if ($images_delete->Recordset)
		$images_delete->Recordset->Close();
	$images_delete->Page_Terminate("imageslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $images_delete->ShowPageHeader(); ?>
<?php
$images_delete->ShowMessage();
?>
<form name="fimagesdelete" id="fimagesdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="images">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($images_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_imagesdelete" class="ewTable ewTableSeparate">
<?php echo $images->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($images->id->Visible) { // id ?>
		<td><span id="elh_images_id" class="images_id"><?php echo $images->id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($images->original_image->Visible) { // original_image ?>
		<td><span id="elh_images_original_image" class="images_original_image"><?php echo $images->original_image->FldCaption() ?></span></td>
<?php } ?>
<?php if ($images->thumbnail_image->Visible) { // thumbnail_image ?>
		<td><span id="elh_images_thumbnail_image" class="images_thumbnail_image"><?php echo $images->thumbnail_image->FldCaption() ?></span></td>
<?php } ?>
<?php if ($images->ip_address->Visible) { // ip_address ?>
		<td><span id="elh_images_ip_address" class="images_ip_address"><?php echo $images->ip_address->FldCaption() ?></span></td>
<?php } ?>
<?php if ($images->advertise_id->Visible) { // advertise_id ?>
		<td><span id="elh_images_advertise_id" class="images_advertise_id"><?php echo $images->advertise_id->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$images_delete->RecCnt = 0;
$i = 0;
while (!$images_delete->Recordset->EOF) {
	$images_delete->RecCnt++;
	$images_delete->RowCnt++;

	// Set row properties
	$images->ResetAttrs();
	$images->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$images_delete->LoadRowValues($images_delete->Recordset);

	// Render row
	$images_delete->RenderRow();
?>
	<tr<?php echo $images->RowAttributes() ?>>
<?php if ($images->id->Visible) { // id ?>
		<td<?php echo $images->id->CellAttributes() ?>>
<span id="el<?php echo $images_delete->RowCnt ?>_images_id" class="control-group images_id">
<span<?php echo $images->id->ViewAttributes() ?>>
<?php echo $images->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($images->original_image->Visible) { // original_image ?>
		<td<?php echo $images->original_image->CellAttributes() ?>>
<span id="el<?php echo $images_delete->RowCnt ?>_images_original_image" class="control-group images_original_image">
<span<?php echo $images->original_image->ViewAttributes() ?>>
<?php echo $images->original_image->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($images->thumbnail_image->Visible) { // thumbnail_image ?>
		<td<?php echo $images->thumbnail_image->CellAttributes() ?>>
<span id="el<?php echo $images_delete->RowCnt ?>_images_thumbnail_image" class="control-group images_thumbnail_image">
<span<?php echo $images->thumbnail_image->ViewAttributes() ?>>
<?php echo $images->thumbnail_image->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($images->ip_address->Visible) { // ip_address ?>
		<td<?php echo $images->ip_address->CellAttributes() ?>>
<span id="el<?php echo $images_delete->RowCnt ?>_images_ip_address" class="control-group images_ip_address">
<span<?php echo $images->ip_address->ViewAttributes() ?>>
<?php echo $images->ip_address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($images->advertise_id->Visible) { // advertise_id ?>
		<td<?php echo $images->advertise_id->CellAttributes() ?>>
<span id="el<?php echo $images_delete->RowCnt ?>_images_advertise_id" class="control-group images_advertise_id">
<span<?php echo $images->advertise_id->ViewAttributes() ?>>
<?php echo $images->advertise_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$images_delete->Recordset->MoveNext();
}
$images_delete->Recordset->Close();
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
fimagesdelete.Init();
</script>
<?php
$images_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$images_delete->Page_Terminate();
?>
