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

$advertises_edit = NULL; // Initialize page object first

class cadvertises_edit extends cadvertises {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'advertises';

	// Page object name
	var $PageObjName = 'advertises_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
			$this->Page_Terminate("advertiseslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("advertiseslist.php"); // No matching record, return to list
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
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->cat_id->FldIsDetailKey) {
			$this->cat_id->setFormValue($objForm->GetValue("x_cat_id"));
		}
		if (!$this->sub_cat_id->FldIsDetailKey) {
			$this->sub_cat_id->setFormValue($objForm->GetValue("x_sub_cat_id"));
		}
		if (!$this->slogan->FldIsDetailKey) {
			$this->slogan->setFormValue($objForm->GetValue("x_slogan"));
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
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->mobile->FldIsDetailKey) {
			$this->mobile->setFormValue($objForm->GetValue("x_mobile"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->keywords->FldIsDetailKey) {
			$this->keywords->setFormValue($objForm->GetValue("x_keywords"));
		}
		if (!$this->register_date->FldIsDetailKey) {
			$this->register_date->setFormValue($objForm->GetValue("x_register_date"));
		}
		if (!$this->google_map->FldIsDetailKey) {
			$this->google_map->setFormValue($objForm->GetValue("x_google_map"));
		}
		if (!$this->activate->FldIsDetailKey) {
			$this->activate->setFormValue($objForm->GetValue("x_activate"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->image->FldIsDetailKey) {
			$this->image->setFormValue($objForm->GetValue("x_image"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->cat_id->CurrentValue = $this->cat_id->FormValue;
		$this->sub_cat_id->CurrentValue = $this->sub_cat_id->FormValue;
		$this->slogan->CurrentValue = $this->slogan->FormValue;
		$this->city_id->CurrentValue = $this->city_id->FormValue;
		$this->province_id->CurrentValue = $this->province_id->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->mobile->CurrentValue = $this->mobile->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->keywords->CurrentValue = $this->keywords->FormValue;
		$this->register_date->CurrentValue = $this->register_date->FormValue;
		$this->google_map->CurrentValue = $this->google_map->FormValue;
		$this->activate->CurrentValue = $this->activate->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->image->CurrentValue = $this->image->FormValue;
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

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

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

			// google_map
			$this->google_map->ViewValue = $this->google_map->CurrentValue;
			$this->google_map->ViewCustomAttributes = "";

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

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

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

			// google_map
			$this->google_map->LinkCustomAttributes = "";
			$this->google_map->HrefValue = "";
			$this->google_map->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// cat_id
			$this->cat_id->EditCustomAttributes = "";
			$this->cat_id->EditValue = ew_HtmlEncode($this->cat_id->CurrentValue);
			$this->cat_id->PlaceHolder = ew_RemoveHtml($this->cat_id->FldCaption());

			// sub_cat_id
			$this->sub_cat_id->EditCustomAttributes = "";
			$this->sub_cat_id->EditValue = ew_HtmlEncode($this->sub_cat_id->CurrentValue);
			$this->sub_cat_id->PlaceHolder = ew_RemoveHtml($this->sub_cat_id->FldCaption());

			// slogan
			$this->slogan->EditCustomAttributes = "";
			$this->slogan->EditValue = ew_HtmlEncode($this->slogan->CurrentValue);
			$this->slogan->PlaceHolder = ew_RemoveHtml($this->slogan->FldCaption());

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

			// phone
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// mobile
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->CurrentValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// website
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// keywords
			$this->keywords->EditCustomAttributes = "";
			$this->keywords->EditValue = ew_HtmlEncode($this->keywords->CurrentValue);
			$this->keywords->PlaceHolder = ew_RemoveHtml($this->keywords->FldCaption());

			// register_date
			$this->register_date->EditCustomAttributes = "";
			$this->register_date->EditValue = ew_HtmlEncode($this->register_date->CurrentValue);
			$this->register_date->PlaceHolder = ew_RemoveHtml($this->register_date->FldCaption());

			// google_map
			$this->google_map->EditCustomAttributes = "";
			$this->google_map->EditValue = $this->google_map->CurrentValue;
			$this->google_map->PlaceHolder = ew_RemoveHtml($this->google_map->FldCaption());

			// activate
			$this->activate->EditCustomAttributes = "";
			$this->activate->EditValue = ew_HtmlEncode($this->activate->CurrentValue);
			$this->activate->PlaceHolder = ew_RemoveHtml($this->activate->FldCaption());

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// image
			$this->image->EditCustomAttributes = "";
			$this->image->EditValue = ew_HtmlEncode($this->image->CurrentValue);
			$this->image->PlaceHolder = ew_RemoveHtml($this->image->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// cat_id
			$this->cat_id->HrefValue = "";

			// sub_cat_id
			$this->sub_cat_id->HrefValue = "";

			// slogan
			$this->slogan->HrefValue = "";

			// city_id
			$this->city_id->HrefValue = "";

			// province_id
			$this->province_id->HrefValue = "";

			// address
			$this->address->HrefValue = "";

			// phone
			$this->phone->HrefValue = "";

			// mobile
			$this->mobile->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// website
			$this->website->HrefValue = "";

			// keywords
			$this->keywords->HrefValue = "";

			// register_date
			$this->register_date->HrefValue = "";

			// google_map
			$this->google_map->HrefValue = "";

			// activate
			$this->activate->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// image
			$this->image->HrefValue = "";
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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
		}
		if (!$this->cat_id->FldIsDetailKey && !is_null($this->cat_id->FormValue) && $this->cat_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->cat_id->FldCaption());
		}
		if (!ew_CheckInteger($this->cat_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->cat_id->FldErrMsg());
		}
		if (!$this->sub_cat_id->FldIsDetailKey && !is_null($this->sub_cat_id->FormValue) && $this->sub_cat_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->sub_cat_id->FldCaption());
		}
		if (!ew_CheckInteger($this->sub_cat_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->sub_cat_id->FldErrMsg());
		}
		if (!$this->slogan->FldIsDetailKey && !is_null($this->slogan->FormValue) && $this->slogan->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->slogan->FldCaption());
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
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->phone->FldCaption());
		}
		if (!$this->mobile->FldIsDetailKey && !is_null($this->mobile->FormValue) && $this->mobile->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->mobile->FldCaption());
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_email->FldCaption());
		}
		if (!$this->website->FldIsDetailKey && !is_null($this->website->FormValue) && $this->website->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->website->FldCaption());
		}
		if (!$this->keywords->FldIsDetailKey && !is_null($this->keywords->FormValue) && $this->keywords->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->keywords->FldCaption());
		}
		if (!$this->register_date->FldIsDetailKey && !is_null($this->register_date->FormValue) && $this->register_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->register_date->FldCaption());
		}
		if (!ew_CheckInteger($this->register_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->register_date->FldErrMsg());
		}
		if (!$this->google_map->FldIsDetailKey && !is_null($this->google_map->FormValue) && $this->google_map->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->google_map->FldCaption());
		}
		if (!$this->activate->FldIsDetailKey && !is_null($this->activate->FormValue) && $this->activate->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->activate->FldCaption());
		}
		if (!ew_CheckInteger($this->activate->FormValue)) {
			ew_AddMessage($gsFormError, $this->activate->FldErrMsg());
		}
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!$this->image->FldIsDetailKey && !is_null($this->image->FormValue) && $this->image->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->image->FldCaption());
		}
		if (!ew_CheckInteger($this->image->FormValue)) {
			ew_AddMessage($gsFormError, $this->image->FldErrMsg());
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

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", $this->name->ReadOnly);

			// cat_id
			$this->cat_id->SetDbValueDef($rsnew, $this->cat_id->CurrentValue, 0, $this->cat_id->ReadOnly);

			// sub_cat_id
			$this->sub_cat_id->SetDbValueDef($rsnew, $this->sub_cat_id->CurrentValue, 0, $this->sub_cat_id->ReadOnly);

			// slogan
			$this->slogan->SetDbValueDef($rsnew, $this->slogan->CurrentValue, "", $this->slogan->ReadOnly);

			// city_id
			$this->city_id->SetDbValueDef($rsnew, $this->city_id->CurrentValue, 0, $this->city_id->ReadOnly);

			// province_id
			$this->province_id->SetDbValueDef($rsnew, $this->province_id->CurrentValue, 0, $this->province_id->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", $this->phone->ReadOnly);

			// mobile
			$this->mobile->SetDbValueDef($rsnew, $this->mobile->CurrentValue, "", $this->mobile->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// website
			$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", $this->website->ReadOnly);

			// keywords
			$this->keywords->SetDbValueDef($rsnew, $this->keywords->CurrentValue, "", $this->keywords->ReadOnly);

			// register_date
			$this->register_date->SetDbValueDef($rsnew, $this->register_date->CurrentValue, 0, $this->register_date->ReadOnly);

			// google_map
			$this->google_map->SetDbValueDef($rsnew, $this->google_map->CurrentValue, "", $this->google_map->ReadOnly);

			// activate
			$this->activate->SetDbValueDef($rsnew, $this->activate->CurrentValue, 0, $this->activate->ReadOnly);

			// user_id
			$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, $this->user_id->ReadOnly);

			// image
			$this->image->SetDbValueDef($rsnew, $this->image->CurrentValue, 0, $this->image->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "advertiseslist.php", $this->TableVar, TRUE);
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
if (!isset($advertises_edit)) $advertises_edit = new cadvertises_edit();

// Page init
$advertises_edit->Page_Init();

// Page main
$advertises_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$advertises_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var advertises_edit = new ew_Page("advertises_edit");
advertises_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = advertises_edit.PageID; // For backward compatibility

// Form object
var fadvertisesedit = new ew_Form("fadvertisesedit");

// Validate form
fadvertisesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_cat_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->cat_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_cat_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->cat_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_cat_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->sub_cat_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_sub_cat_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->sub_cat_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_slogan");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->slogan->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->city_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_city_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->city_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->province_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->province_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->address->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->phone->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_mobile");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->mobile->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->_email->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->website->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_keywords");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->keywords->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_register_date");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->register_date->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_register_date");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->register_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_google_map");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->google_map->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_activate");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->activate->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_activate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->activate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->user_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_image");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($advertises->image->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_image");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($advertises->image->FldErrMsg()) ?>");

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
fadvertisesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fadvertisesedit.ValidateRequired = true;
<?php } else { ?>
fadvertisesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $advertises_edit->ShowPageHeader(); ?>
<?php
$advertises_edit->ShowMessage();
?>
<form name="fadvertisesedit" id="fadvertisesedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="advertises">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_advertisesedit" class="table table-bordered table-striped">
<?php if ($advertises->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_advertises_id"><?php echo $advertises->id->FldCaption() ?></span></td>
		<td<?php echo $advertises->id->CellAttributes() ?>>
<span id="el_advertises_id" class="control-group">
<span<?php echo $advertises->id->ViewAttributes() ?>>
<?php echo $advertises->id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($advertises->id->CurrentValue) ?>">
<?php echo $advertises->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_advertises_name"><?php echo $advertises->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->name->CellAttributes() ?>>
<span id="el_advertises_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->name->PlaceHolder) ?>" value="<?php echo $advertises->name->EditValue ?>"<?php echo $advertises->name->EditAttributes() ?>>
</span>
<?php echo $advertises->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->cat_id->Visible) { // cat_id ?>
	<tr id="r_cat_id">
		<td><span id="elh_advertises_cat_id"><?php echo $advertises->cat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->cat_id->CellAttributes() ?>>
<span id="el_advertises_cat_id" class="control-group">
<input type="text" data-field="x_cat_id" name="x_cat_id" id="x_cat_id" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->cat_id->PlaceHolder) ?>" value="<?php echo $advertises->cat_id->EditValue ?>"<?php echo $advertises->cat_id->EditAttributes() ?>>
</span>
<?php echo $advertises->cat_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
	<tr id="r_sub_cat_id">
		<td><span id="elh_advertises_sub_cat_id"><?php echo $advertises->sub_cat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->sub_cat_id->CellAttributes() ?>>
<span id="el_advertises_sub_cat_id" class="control-group">
<input type="text" data-field="x_sub_cat_id" name="x_sub_cat_id" id="x_sub_cat_id" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->sub_cat_id->PlaceHolder) ?>" value="<?php echo $advertises->sub_cat_id->EditValue ?>"<?php echo $advertises->sub_cat_id->EditAttributes() ?>>
</span>
<?php echo $advertises->sub_cat_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->slogan->Visible) { // slogan ?>
	<tr id="r_slogan">
		<td><span id="elh_advertises_slogan"><?php echo $advertises->slogan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->slogan->CellAttributes() ?>>
<span id="el_advertises_slogan" class="control-group">
<input type="text" data-field="x_slogan" name="x_slogan" id="x_slogan" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->slogan->PlaceHolder) ?>" value="<?php echo $advertises->slogan->EditValue ?>"<?php echo $advertises->slogan->EditAttributes() ?>>
</span>
<?php echo $advertises->slogan->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_advertises_city_id"><?php echo $advertises->city_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->city_id->CellAttributes() ?>>
<span id="el_advertises_city_id" class="control-group">
<input type="text" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->city_id->PlaceHolder) ?>" value="<?php echo $advertises->city_id->EditValue ?>"<?php echo $advertises->city_id->EditAttributes() ?>>
</span>
<?php echo $advertises->city_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->province_id->Visible) { // province_id ?>
	<tr id="r_province_id">
		<td><span id="elh_advertises_province_id"><?php echo $advertises->province_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->province_id->CellAttributes() ?>>
<span id="el_advertises_province_id" class="control-group">
<input type="text" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->province_id->PlaceHolder) ?>" value="<?php echo $advertises->province_id->EditValue ?>"<?php echo $advertises->province_id->EditAttributes() ?>>
</span>
<?php echo $advertises->province_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_advertises_address"><?php echo $advertises->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->address->CellAttributes() ?>>
<span id="el_advertises_address" class="control-group">
<textarea data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($advertises->address->PlaceHolder) ?>"<?php echo $advertises->address->EditAttributes() ?>><?php echo $advertises->address->EditValue ?></textarea>
</span>
<?php echo $advertises->address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td><span id="elh_advertises_phone"><?php echo $advertises->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->phone->CellAttributes() ?>>
<span id="el_advertises_phone" class="control-group">
<input type="text" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->phone->PlaceHolder) ?>" value="<?php echo $advertises->phone->EditValue ?>"<?php echo $advertises->phone->EditAttributes() ?>>
</span>
<?php echo $advertises->phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->mobile->Visible) { // mobile ?>
	<tr id="r_mobile">
		<td><span id="elh_advertises_mobile"><?php echo $advertises->mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->mobile->CellAttributes() ?>>
<span id="el_advertises_mobile" class="control-group">
<input type="text" data-field="x_mobile" name="x_mobile" id="x_mobile" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->mobile->PlaceHolder) ?>" value="<?php echo $advertises->mobile->EditValue ?>"<?php echo $advertises->mobile->EditAttributes() ?>>
</span>
<?php echo $advertises->mobile->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_advertises__email"><?php echo $advertises->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->_email->CellAttributes() ?>>
<span id="el_advertises__email" class="control-group">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->_email->PlaceHolder) ?>" value="<?php echo $advertises->_email->EditValue ?>"<?php echo $advertises->_email->EditAttributes() ?>>
</span>
<?php echo $advertises->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_advertises_website"><?php echo $advertises->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->website->CellAttributes() ?>>
<span id="el_advertises_website" class="control-group">
<input type="text" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->website->PlaceHolder) ?>" value="<?php echo $advertises->website->EditValue ?>"<?php echo $advertises->website->EditAttributes() ?>>
</span>
<?php echo $advertises->website->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->keywords->Visible) { // keywords ?>
	<tr id="r_keywords">
		<td><span id="elh_advertises_keywords"><?php echo $advertises->keywords->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->keywords->CellAttributes() ?>>
<span id="el_advertises_keywords" class="control-group">
<input type="text" data-field="x_keywords" name="x_keywords" id="x_keywords" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($advertises->keywords->PlaceHolder) ?>" value="<?php echo $advertises->keywords->EditValue ?>"<?php echo $advertises->keywords->EditAttributes() ?>>
</span>
<?php echo $advertises->keywords->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->register_date->Visible) { // register_date ?>
	<tr id="r_register_date">
		<td><span id="elh_advertises_register_date"><?php echo $advertises->register_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->register_date->CellAttributes() ?>>
<span id="el_advertises_register_date" class="control-group">
<input type="text" data-field="x_register_date" name="x_register_date" id="x_register_date" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->register_date->PlaceHolder) ?>" value="<?php echo $advertises->register_date->EditValue ?>"<?php echo $advertises->register_date->EditAttributes() ?>>
</span>
<?php echo $advertises->register_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->google_map->Visible) { // google_map ?>
	<tr id="r_google_map">
		<td><span id="elh_advertises_google_map"><?php echo $advertises->google_map->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->google_map->CellAttributes() ?>>
<span id="el_advertises_google_map" class="control-group">
<textarea data-field="x_google_map" name="x_google_map" id="x_google_map" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($advertises->google_map->PlaceHolder) ?>"<?php echo $advertises->google_map->EditAttributes() ?>><?php echo $advertises->google_map->EditValue ?></textarea>
</span>
<?php echo $advertises->google_map->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->activate->Visible) { // activate ?>
	<tr id="r_activate">
		<td><span id="elh_advertises_activate"><?php echo $advertises->activate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->activate->CellAttributes() ?>>
<span id="el_advertises_activate" class="control-group">
<input type="text" data-field="x_activate" name="x_activate" id="x_activate" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->activate->PlaceHolder) ?>" value="<?php echo $advertises->activate->EditValue ?>"<?php echo $advertises->activate->EditAttributes() ?>>
</span>
<?php echo $advertises->activate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_advertises_user_id"><?php echo $advertises->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->user_id->CellAttributes() ?>>
<span id="el_advertises_user_id" class="control-group">
<input type="text" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->user_id->PlaceHolder) ?>" value="<?php echo $advertises->user_id->EditValue ?>"<?php echo $advertises->user_id->EditAttributes() ?>>
</span>
<?php echo $advertises->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($advertises->image->Visible) { // image ?>
	<tr id="r_image">
		<td><span id="elh_advertises_image"><?php echo $advertises->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $advertises->image->CellAttributes() ?>>
<span id="el_advertises_image" class="control-group">
<input type="text" data-field="x_image" name="x_image" id="x_image" size="30" placeholder="<?php echo ew_HtmlEncode($advertises->image->PlaceHolder) ?>" value="<?php echo $advertises->image->EditValue ?>"<?php echo $advertises->image->EditAttributes() ?>>
</span>
<?php echo $advertises->image->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fadvertisesedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$advertises_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$advertises_edit->Page_Terminate();
?>
