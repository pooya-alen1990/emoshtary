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

$advertises_view = NULL; // Initialize page object first

class cadvertises_view extends cadvertises {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'advertises';

	// Page object name
	var $PageObjName = 'advertises_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'advertises', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "advertiseslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "advertiseslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "advertiseslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_advertises\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_advertises',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fadvertisesview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "advertiseslist.php", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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
if (!isset($advertises_view)) $advertises_view = new cadvertises_view();

// Page init
$advertises_view->Page_Init();

// Page main
$advertises_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$advertises_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($advertises->Export == "") { ?>
<script type="text/javascript">

// Page object
var advertises_view = new ew_Page("advertises_view");
advertises_view.PageID = "view"; // Page ID
var EW_PAGE_ID = advertises_view.PageID; // For backward compatibility

// Form object
var fadvertisesview = new ew_Form("fadvertisesview");

// Form_CustomValidate event
fadvertisesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fadvertisesview.ValidateRequired = true;
<?php } else { ?>
fadvertisesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($advertises->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($advertises->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $advertises_view->ExportOptions->Render("body") ?>
<?php if (!$advertises_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($advertises_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $advertises_view->ShowPageHeader(); ?>
<?php
$advertises_view->ShowMessage();
?>
<form name="fadvertisesview" id="fadvertisesview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="advertises">
<table class="ewGrid"><tr><td>
<table id="tbl_advertisesview" class="table table-bordered table-striped">
<?php if ($advertises->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_advertises_id"><?php echo $advertises->id->FldCaption() ?></span></td>
		<td<?php echo $advertises->id->CellAttributes() ?>>
<span id="el_advertises_id" class="control-group">
<span<?php echo $advertises->id->ViewAttributes() ?>>
<?php echo $advertises->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_advertises_name"><?php echo $advertises->name->FldCaption() ?></span></td>
		<td<?php echo $advertises->name->CellAttributes() ?>>
<span id="el_advertises_name" class="control-group">
<span<?php echo $advertises->name->ViewAttributes() ?>>
<?php echo $advertises->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->cat_id->Visible) { // cat_id ?>
	<tr id="r_cat_id">
		<td><span id="elh_advertises_cat_id"><?php echo $advertises->cat_id->FldCaption() ?></span></td>
		<td<?php echo $advertises->cat_id->CellAttributes() ?>>
<span id="el_advertises_cat_id" class="control-group">
<span<?php echo $advertises->cat_id->ViewAttributes() ?>>
<?php echo $advertises->cat_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
	<tr id="r_sub_cat_id">
		<td><span id="elh_advertises_sub_cat_id"><?php echo $advertises->sub_cat_id->FldCaption() ?></span></td>
		<td<?php echo $advertises->sub_cat_id->CellAttributes() ?>>
<span id="el_advertises_sub_cat_id" class="control-group">
<span<?php echo $advertises->sub_cat_id->ViewAttributes() ?>>
<?php echo $advertises->sub_cat_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->slogan->Visible) { // slogan ?>
	<tr id="r_slogan">
		<td><span id="elh_advertises_slogan"><?php echo $advertises->slogan->FldCaption() ?></span></td>
		<td<?php echo $advertises->slogan->CellAttributes() ?>>
<span id="el_advertises_slogan" class="control-group">
<span<?php echo $advertises->slogan->ViewAttributes() ?>>
<?php echo $advertises->slogan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->city_id->Visible) { // city_id ?>
	<tr id="r_city_id">
		<td><span id="elh_advertises_city_id"><?php echo $advertises->city_id->FldCaption() ?></span></td>
		<td<?php echo $advertises->city_id->CellAttributes() ?>>
<span id="el_advertises_city_id" class="control-group">
<span<?php echo $advertises->city_id->ViewAttributes() ?>>
<?php echo $advertises->city_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->province_id->Visible) { // province_id ?>
	<tr id="r_province_id">
		<td><span id="elh_advertises_province_id"><?php echo $advertises->province_id->FldCaption() ?></span></td>
		<td<?php echo $advertises->province_id->CellAttributes() ?>>
<span id="el_advertises_province_id" class="control-group">
<span<?php echo $advertises->province_id->ViewAttributes() ?>>
<?php echo $advertises->province_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_advertises_address"><?php echo $advertises->address->FldCaption() ?></span></td>
		<td<?php echo $advertises->address->CellAttributes() ?>>
<span id="el_advertises_address" class="control-group">
<span<?php echo $advertises->address->ViewAttributes() ?>>
<?php echo $advertises->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td><span id="elh_advertises_phone"><?php echo $advertises->phone->FldCaption() ?></span></td>
		<td<?php echo $advertises->phone->CellAttributes() ?>>
<span id="el_advertises_phone" class="control-group">
<span<?php echo $advertises->phone->ViewAttributes() ?>>
<?php echo $advertises->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->mobile->Visible) { // mobile ?>
	<tr id="r_mobile">
		<td><span id="elh_advertises_mobile"><?php echo $advertises->mobile->FldCaption() ?></span></td>
		<td<?php echo $advertises->mobile->CellAttributes() ?>>
<span id="el_advertises_mobile" class="control-group">
<span<?php echo $advertises->mobile->ViewAttributes() ?>>
<?php echo $advertises->mobile->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_advertises__email"><?php echo $advertises->_email->FldCaption() ?></span></td>
		<td<?php echo $advertises->_email->CellAttributes() ?>>
<span id="el_advertises__email" class="control-group">
<span<?php echo $advertises->_email->ViewAttributes() ?>>
<?php echo $advertises->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_advertises_website"><?php echo $advertises->website->FldCaption() ?></span></td>
		<td<?php echo $advertises->website->CellAttributes() ?>>
<span id="el_advertises_website" class="control-group">
<span<?php echo $advertises->website->ViewAttributes() ?>>
<?php echo $advertises->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->keywords->Visible) { // keywords ?>
	<tr id="r_keywords">
		<td><span id="elh_advertises_keywords"><?php echo $advertises->keywords->FldCaption() ?></span></td>
		<td<?php echo $advertises->keywords->CellAttributes() ?>>
<span id="el_advertises_keywords" class="control-group">
<span<?php echo $advertises->keywords->ViewAttributes() ?>>
<?php echo $advertises->keywords->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->register_date->Visible) { // register_date ?>
	<tr id="r_register_date">
		<td><span id="elh_advertises_register_date"><?php echo $advertises->register_date->FldCaption() ?></span></td>
		<td<?php echo $advertises->register_date->CellAttributes() ?>>
<span id="el_advertises_register_date" class="control-group">
<span<?php echo $advertises->register_date->ViewAttributes() ?>>
<?php echo $advertises->register_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->google_map->Visible) { // google_map ?>
	<tr id="r_google_map">
		<td><span id="elh_advertises_google_map"><?php echo $advertises->google_map->FldCaption() ?></span></td>
		<td<?php echo $advertises->google_map->CellAttributes() ?>>
<span id="el_advertises_google_map" class="control-group">
<span<?php echo $advertises->google_map->ViewAttributes() ?>>
<?php echo $advertises->google_map->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->activate->Visible) { // activate ?>
	<tr id="r_activate">
		<td><span id="elh_advertises_activate"><?php echo $advertises->activate->FldCaption() ?></span></td>
		<td<?php echo $advertises->activate->CellAttributes() ?>>
<span id="el_advertises_activate" class="control-group">
<span<?php echo $advertises->activate->ViewAttributes() ?>>
<?php echo $advertises->activate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_advertises_user_id"><?php echo $advertises->user_id->FldCaption() ?></span></td>
		<td<?php echo $advertises->user_id->CellAttributes() ?>>
<span id="el_advertises_user_id" class="control-group">
<span<?php echo $advertises->user_id->ViewAttributes() ?>>
<?php echo $advertises->user_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($advertises->image->Visible) { // image ?>
	<tr id="r_image">
		<td><span id="elh_advertises_image"><?php echo $advertises->image->FldCaption() ?></span></td>
		<td<?php echo $advertises->image->CellAttributes() ?>>
<span id="el_advertises_image" class="control-group">
<span<?php echo $advertises->image->ViewAttributes() ?>>
<?php echo $advertises->image->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fadvertisesview.Init();
</script>
<?php
$advertises_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($advertises->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$advertises_view->Page_Terminate();
?>
