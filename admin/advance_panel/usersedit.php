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

$users_edit = NULL; // Initialize page object first

class cusers_edit extends cusers {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("userslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->activation_code->FldIsDetailKey) {
			$this->activation_code->setFormValue($objForm->GetValue("x_activation_code"));
		}
		if (!$this->asiatech_code->FldIsDetailKey) {
			$this->asiatech_code->setFormValue($objForm->GetValue("x_asiatech_code"));
		}
		if (!$this->first_name->FldIsDetailKey) {
			$this->first_name->setFormValue($objForm->GetValue("x_first_name"));
		}
		if (!$this->last_name->FldIsDetailKey) {
			$this->last_name->setFormValue($objForm->GetValue("x_last_name"));
		}
		if (!$this->melli_code->FldIsDetailKey) {
			$this->melli_code->setFormValue($objForm->GetValue("x_melli_code"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->mobile->FldIsDetailKey) {
			$this->mobile->setFormValue($objForm->GetValue("x_mobile"));
		}
		if (!$this->city_id->FldIsDetailKey) {
			$this->city_id->setFormValue($objForm->GetValue("x_city_id"));
		}
		if (!$this->province_id->FldIsDetailKey) {
			$this->province_id->setFormValue($objForm->GetValue("x_province_id"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->register_date->FldIsDetailKey) {
			$this->register_date->setFormValue($objForm->GetValue("x_register_date"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->activation_code->CurrentValue = $this->activation_code->FormValue;
		$this->asiatech_code->CurrentValue = $this->asiatech_code->FormValue;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->melli_code->CurrentValue = $this->melli_code->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->mobile->CurrentValue = $this->mobile->FormValue;
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->province_id->CurrentValue = $this->province_id->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->register_date->CurrentValue = $this->register_date->FormValue;
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

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

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

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// register_date
			$this->register_date->LinkCustomAttributes = "";
			$this->register_date->HrefValue = "";
			$this->register_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// activation_code
			$this->activation_code->EditCustomAttributes = "";
			$this->activation_code->EditValue = ew_HtmlEncode($this->activation_code->CurrentValue);
			$this->activation_code->PlaceHolder = ew_RemoveHtml($this->activation_code->FldCaption());

			// asiatech_code
			$this->asiatech_code->EditCustomAttributes = "";
			$this->asiatech_code->EditValue = ew_HtmlEncode($this->asiatech_code->CurrentValue);
			$this->asiatech_code->PlaceHolder = ew_RemoveHtml($this->asiatech_code->FldCaption());

			// first_name
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// melli_code
			$this->melli_code->EditCustomAttributes = "";
			$this->melli_code->EditValue = ew_HtmlEncode($this->melli_code->CurrentValue);
			$this->melli_code->PlaceHolder = ew_RemoveHtml($this->melli_code->FldCaption());

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// mobile
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->CurrentValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// city_id
			$this->city_id->EditCustomAttributes = "";
			$this->city_id->EditValue = ew_HtmlEncode($this->city_id->CurrentValue);
			$this->city_id->PlaceHolder = ew_RemoveHtml($this->city_id->FldCaption());

			// province_id
			$this->province_id->EditCustomAttributes = "";
			$this->province_id->EditValue = ew_HtmlEncode($this->province_id->CurrentValue);
			$this->province_id->PlaceHolder = ew_RemoveHtml($this->province_id->FldCaption());

			// address
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = $this->address->CurrentValue;
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// password
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// register_date
			$this->register_date->EditCustomAttributes = "";
			$this->register_date->EditValue = ew_HtmlEncode($this->register_date->CurrentValue);
			$this->register_date->PlaceHolder = ew_RemoveHtml($this->register_date->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// activation_code
			$this->activation_code->HrefValue = "";

			// asiatech_code
			$this->asiatech_code->HrefValue = "";

			// first_name
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->HrefValue = "";

			// melli_code
			$this->melli_code->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// mobile
			$this->mobile->HrefValue = "";

			// city_id
			$this->city_id->HrefValue = "";

			// province_id
			$this->province_id->HrefValue = "";

			// address
			$this->address->HrefValue = "";

			// password
			$this->password->HrefValue = "";

			// register_date
			$this->register_date->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->activation_code->FldIsDetailKey && !is_null($this->activation_code->FormValue) && $this->activation_code->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->activation_code->FldCaption());
		}
		if (!$this->asiatech_code->FldIsDetailKey && !is_null($this->asiatech_code->FormValue) && $this->asiatech_code->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->asiatech_code->FldCaption());
		}
		if (!$this->first_name->FldIsDetailKey && !is_null($this->first_name->FormValue) && $this->first_name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->first_name->FldCaption());
		}
		if (!$this->last_name->FldIsDetailKey && !is_null($this->last_name->FormValue) && $this->last_name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->last_name->FldCaption());
		}
		if (!$this->melli_code->FldIsDetailKey && !is_null($this->melli_code->FormValue) && $this->melli_code->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->melli_code->FldCaption());
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_email->FldCaption());
		}
		if (!$this->mobile->FldIsDetailKey && !is_null($this->mobile->FormValue) && $this->mobile->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->mobile->FldCaption());
		}
		if (!$this->city_id->FldIsDetailKey && !is_null($this->city_id->FormValue) && $this->city_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->city_id->FldCaption());
		}
		if (!ew_CheckInteger($this->city_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->city_id->FldErrMsg());
		}
		if (!$this->province_id->FldIsDetailKey && !is_null($this->province_id->FormValue) && $this->province_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->province_id->FldCaption());
		}
		if (!ew_CheckInteger($this->province_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->province_id->FldErrMsg());
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->address->FldCaption());
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->password->FldCaption());
		}
		if (!$this->register_date->FldIsDetailKey && !is_null($this->register_date->FormValue) && $this->register_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->register_date->FldCaption());
		}
		if (!ew_CheckInteger($this->register_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->register_date->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		if ($this->activation_code->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`activation_code` = '" . ew_AdjustSql($this->activation_code->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->activation_code->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->activation_code->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// activation_code
			$this->activation_code->SetDbValueDef($rsnew, $this->activation_code->CurrentValue, "", $this->activation_code->ReadOnly);

			// asiatech_code
			$this->asiatech_code->SetDbValueDef($rsnew, $this->asiatech_code->CurrentValue, "", $this->asiatech_code->ReadOnly);

			// first_name
			$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, "", $this->first_name->ReadOnly);

			// last_name
			$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, "", $this->last_name->ReadOnly);

			// melli_code
			$this->melli_code->SetDbValueDef($rsnew, $this->melli_code->CurrentValue, "", $this->melli_code->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// mobile
			$this->mobile->SetDbValueDef($rsnew, $this->mobile->CurrentValue, "", $this->mobile->ReadOnly);

			// city_id
			$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, 0, $this->city_id->ReadOnly);

			// province_id
			$this->province_id->SetDbValueDef($rsnew, $this->province_id->CurrentValue, 0, $this->province_id->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// password
			$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", $this->password->ReadOnly);

			// register_date
			$this->register_date->SetDbValueDef($rsnew, $this->register_date->CurrentValue, 0, $this->register_date->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "userslist.php", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($users_edit)) $users_edit = new cusers_edit();

// Page init
$users_edit->Page_Init();

// Page main
$users_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var users_edit = new ew_Page("users_edit");
users_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = users_edit.PageID; // For backward compatibility

// Form object
var fusersedit = new ew_Form("fusersedit");

// Validate form
fusersedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_activation_code");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->activation_code->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_asiatech_code");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->asiatech_code->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_first_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->first_name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_last_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->last_name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_melli_code");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->melli_code->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->_email->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_mobile");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->mobile->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->city_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->city_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->province_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->province_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->address->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->password->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_register_date");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($users->register_date->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_register_date");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->register_date->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fusersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersedit.ValidateRequired = true;
<?php } else { ?>
fusersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $users_edit->ShowPageHeader(); ?>
<?php
$users_edit->ShowMessage();
?>
<form name="fusersedit" id="fusersedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_usersedit" class="table table-bordered table-striped">
<?php if ($users->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_users_id"><?php echo $users->id->FldCaption() ?></span></td>
		<td<?php echo $users->id->CellAttributes() ?>>
<span id="el_users_id" class="control-group">
<span<?php echo $users->id->ViewAttributes() ?>>
<?php echo $users->id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($users->id->CurrentValue) ?>">
<?php echo $users->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->activation_code->Visible) { // activation_code ?>
	<tr id="r_activation_code">
		<td><span id="elh_users_activation_code"><?php echo $users->activation_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->activation_code->CellAttributes() ?>>
<span id="el_users_activation_code" class="control-group">
<input type="text" data-field="x_activation_code" name="x_activation_code" id="x_activation_code" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->activation_code->PlaceHolder) ?>" value="<?php echo $users->activation_code->EditValue ?>"<?php echo $users->activation_code->EditAttributes() ?>>
</span>
<?php echo $users->activation_code->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->asiatech_code->Visible) { // asiatech_code ?>
	<tr id="r_asiatech_code">
		<td><span id="elh_users_asiatech_code"><?php echo $users->asiatech_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->asiatech_code->CellAttributes() ?>>
<span id="el_users_asiatech_code" class="control-group">
<input type="text" data-field="x_asiatech_code" name="x_asiatech_code" id="x_asiatech_code" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->asiatech_code->PlaceHolder) ?>" value="<?php echo $users->asiatech_code->EditValue ?>"<?php echo $users->asiatech_code->EditAttributes() ?>>
</span>
<?php echo $users->asiatech_code->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
	<tr id="r_first_name">
		<td><span id="elh_users_first_name"><?php echo $users->first_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->first_name->CellAttributes() ?>>
<span id="el_users_first_name" class="control-group">
<input type="text" data-field="x_first_name" name="x_first_name" id="x_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->first_name->PlaceHolder) ?>" value="<?php echo $users->first_name->EditValue ?>"<?php echo $users->first_name->EditAttributes() ?>>
</span>
<?php echo $users->first_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
	<tr id="r_last_name">
		<td><span id="elh_users_last_name"><?php echo $users->last_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->last_name->CellAttributes() ?>>
<span id="el_users_last_name" class="control-group">
<input type="text" data-field="x_last_name" name="x_last_name" id="x_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->last_name->PlaceHolder) ?>" value="<?php echo $users->last_name->EditValue ?>"<?php echo $users->last_name->EditAttributes() ?>>
</span>
<?php echo $users->last_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->melli_code->Visible) { // melli_code ?>
	<tr id="r_melli_code">
		<td><span id="elh_users_melli_code"><?php echo $users->melli_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->melli_code->CellAttributes() ?>>
<span id="el_users_melli_code" class="control-group">
<input type="text" data-field="x_melli_code" name="x_melli_code" id="x_melli_code" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->melli_code->PlaceHolder) ?>" value="<?php echo $users->melli_code->EditValue ?>"<?php echo $users->melli_code->EditAttributes() ?>>
</span>
<?php echo $users->melli_code->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_users__email"><?php echo $users->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email" class="control-group">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->_email->PlaceHolder) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
<?php echo $users->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->mobile->Visible) { // mobile ?>
	<tr id="r_mobile">
		<td><span id="elh_users_mobile"><?php echo $users->mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->mobile->CellAttributes() ?>>
<span id="el_users_mobile" class="control-group">
<input type="text" data-field="x_mobile" name="x_mobile" id="x_mobile" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->mobile->PlaceHolder) ?>" value="<?php echo $users->mobile->EditValue ?>"<?php echo $users->mobile->EditAttributes() ?>>
</span>
<?php echo $users->mobile->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_users_city_id"><?php echo $users->city_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->city_id->CellAttributes() ?>>
<span id="el_users_city_id" class="control-group">
<input type="text" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" placeholder="<?php echo ew_HtmlEncode($users->city_id->PlaceHolder) ?>" value="<?php echo $users->city_id->EditValue ?>"<?php echo $users->city_id->EditAttributes() ?>>
</span>
<?php echo $users->city_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->province_id->Visible) { // province_id ?>
	<tr id="r_province_id">
		<td><span id="elh_users_province_id"><?php echo $users->province_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->province_id->CellAttributes() ?>>
<span id="el_users_province_id" class="control-group">
<input type="text" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" placeholder="<?php echo ew_HtmlEncode($users->province_id->PlaceHolder) ?>" value="<?php echo $users->province_id->EditValue ?>"<?php echo $users->province_id->EditAttributes() ?>>
</span>
<?php echo $users->province_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_users_address"><?php echo $users->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->address->CellAttributes() ?>>
<span id="el_users_address" class="control-group">
<textarea data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->address->PlaceHolder) ?>"<?php echo $users->address->EditAttributes() ?>><?php echo $users->address->EditValue ?></textarea>
</span>
<?php echo $users->address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
	<tr id="r_password">
		<td><span id="elh_users_password"><?php echo $users->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->password->CellAttributes() ?>>
<span id="el_users_password" class="control-group">
<input type="text" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($users->password->PlaceHolder) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
<?php echo $users->password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->register_date->Visible) { // register_date ?>
	<tr id="r_register_date">
		<td><span id="elh_users_register_date"><?php echo $users->register_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->register_date->CellAttributes() ?>>
<span id="el_users_register_date" class="control-group">
<input type="text" data-field="x_register_date" name="x_register_date" id="x_register_date" size="30" placeholder="<?php echo ew_HtmlEncode($users->register_date->PlaceHolder) ?>" value="<?php echo $users->register_date->EditValue ?>"<?php echo $users->register_date->EditAttributes() ?>>
</span>
<?php echo $users->register_date->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fusersedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$users_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_edit->Page_Terminate();
?>
