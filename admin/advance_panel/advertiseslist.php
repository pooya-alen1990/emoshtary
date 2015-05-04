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

$advertises_list = NULL; // Initialize page object first

class cadvertises_list extends cadvertises {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'advertises';

	// Page object name
	var $PageObjName = 'advertises_list';

	// Grid form hidden field names
	var $FormName = 'fadvertiseslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "advertisesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "advertisesdelete.php";
		$this->MultiUpdateUrl = "advertisesupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'advertises', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->slogan, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->phone, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->keywords, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->google_map, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->cat_id); // cat_id
			$this->UpdateSort($this->sub_cat_id); // sub_cat_id
			$this->UpdateSort($this->slogan); // slogan
			$this->UpdateSort($this->city_id); // city_id
			$this->UpdateSort($this->province_id); // province_id
			$this->UpdateSort($this->phone); // phone
			$this->UpdateSort($this->mobile); // mobile
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->website); // website
			$this->UpdateSort($this->keywords); // keywords
			$this->UpdateSort($this->register_date); // register_date
			$this->UpdateSort($this->activate); // activate
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->image); // image
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->name->setSort("");
				$this->cat_id->setSort("");
				$this->sub_cat_id->setSort("");
				$this->slogan->setSort("");
				$this->city_id->setSort("");
				$this->province_id->setSort("");
				$this->phone->setSort("");
				$this->mobile->setSort("");
				$this->_email->setSort("");
				$this->website->setSort("");
				$this->keywords->setSort("");
				$this->register_date->setSort("");
				$this->activate->setSort("");
				$this->user_id->setSort("");
				$this->image->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fadvertiseslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$item->Body = "<a id=\"emf_advertises\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_advertises',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fadvertiseslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($advertises_list)) $advertises_list = new cadvertises_list();

// Page init
$advertises_list->Page_Init();

// Page main
$advertises_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$advertises_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($advertises->Export == "") { ?>
<script type="text/javascript">

// Page object
var advertises_list = new ew_Page("advertises_list");
advertises_list.PageID = "list"; // Page ID
var EW_PAGE_ID = advertises_list.PageID; // For backward compatibility

// Form object
var fadvertiseslist = new ew_Form("fadvertiseslist");
fadvertiseslist.FormKeyCountName = '<?php echo $advertises_list->FormKeyCountName ?>';

// Form_CustomValidate event
fadvertiseslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fadvertiseslist.ValidateRequired = true;
<?php } else { ?>
fadvertiseslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fadvertiseslistsrch = new ew_Form("fadvertiseslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($advertises->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($advertises_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $advertises_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$advertises_list->TotalRecs = $advertises->SelectRecordCount();
	} else {
		if ($advertises_list->Recordset = $advertises_list->LoadRecordset())
			$advertises_list->TotalRecs = $advertises_list->Recordset->RecordCount();
	}
	$advertises_list->StartRec = 1;
	if ($advertises_list->DisplayRecs <= 0 || ($advertises->Export <> "" && $advertises->ExportAll)) // Display all records
		$advertises_list->DisplayRecs = $advertises_list->TotalRecs;
	if (!($advertises->Export <> "" && $advertises->ExportAll))
		$advertises_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$advertises_list->Recordset = $advertises_list->LoadRecordset($advertises_list->StartRec-1, $advertises_list->DisplayRecs);
$advertises_list->RenderOtherOptions();
?>
<?php if ($advertises->Export == "" && $advertises->CurrentAction == "") { ?>
<form name="fadvertiseslistsrch" id="fadvertiseslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fadvertiseslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fadvertiseslistsrch_SearchGroup" href="#fadvertiseslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fadvertiseslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fadvertiseslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="advertises">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($advertises_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $advertises_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($advertises_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($advertises_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($advertises_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php $advertises_list->ShowPageHeader(); ?>
<?php
$advertises_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fadvertiseslist" id="fadvertiseslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="advertises">
<div id="gmp_advertises" class="ewGridMiddlePanel">
<?php if ($advertises_list->TotalRecs > 0) { ?>
<table id="tbl_advertiseslist" class="ewTable ewTableSeparate">
<?php echo $advertises->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$advertises_list->RenderListOptions();

// Render list options (header, left)
$advertises_list->ListOptions->Render("header", "left");
?>
<?php if ($advertises->id->Visible) { // id ?>
	<?php if ($advertises->SortUrl($advertises->id) == "") { ?>
		<td><div id="elh_advertises_id" class="advertises_id"><div class="ewTableHeaderCaption"><?php echo $advertises->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->id) ?>',1);"><div id="elh_advertises_id" class="advertises_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->name->Visible) { // name ?>
	<?php if ($advertises->SortUrl($advertises->name) == "") { ?>
		<td><div id="elh_advertises_name" class="advertises_name"><div class="ewTableHeaderCaption"><?php echo $advertises->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->name) ?>',1);"><div id="elh_advertises_name" class="advertises_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->cat_id->Visible) { // cat_id ?>
	<?php if ($advertises->SortUrl($advertises->cat_id) == "") { ?>
		<td><div id="elh_advertises_cat_id" class="advertises_cat_id"><div class="ewTableHeaderCaption"><?php echo $advertises->cat_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->cat_id) ?>',1);"><div id="elh_advertises_cat_id" class="advertises_cat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->cat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->cat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->cat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
	<?php if ($advertises->SortUrl($advertises->sub_cat_id) == "") { ?>
		<td><div id="elh_advertises_sub_cat_id" class="advertises_sub_cat_id"><div class="ewTableHeaderCaption"><?php echo $advertises->sub_cat_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->sub_cat_id) ?>',1);"><div id="elh_advertises_sub_cat_id" class="advertises_sub_cat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->sub_cat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->sub_cat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->sub_cat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->slogan->Visible) { // slogan ?>
	<?php if ($advertises->SortUrl($advertises->slogan) == "") { ?>
		<td><div id="elh_advertises_slogan" class="advertises_slogan"><div class="ewTableHeaderCaption"><?php echo $advertises->slogan->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->slogan) ?>',1);"><div id="elh_advertises_slogan" class="advertises_slogan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->slogan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->slogan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->slogan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->city_id->Visible) { // city_id ?>
	<?php if ($advertises->SortUrl($advertises->city_id) == "") { ?>
		<td><div id="elh_advertises_city_id" class="advertises_city_id"><div class="ewTableHeaderCaption"><?php echo $advertises->city_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->city_id) ?>',1);"><div id="elh_advertises_city_id" class="advertises_city_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->city_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->city_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->city_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->province_id->Visible) { // province_id ?>
	<?php if ($advertises->SortUrl($advertises->province_id) == "") { ?>
		<td><div id="elh_advertises_province_id" class="advertises_province_id"><div class="ewTableHeaderCaption"><?php echo $advertises->province_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->province_id) ?>',1);"><div id="elh_advertises_province_id" class="advertises_province_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->province_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->province_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->province_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->phone->Visible) { // phone ?>
	<?php if ($advertises->SortUrl($advertises->phone) == "") { ?>
		<td><div id="elh_advertises_phone" class="advertises_phone"><div class="ewTableHeaderCaption"><?php echo $advertises->phone->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->phone) ?>',1);"><div id="elh_advertises_phone" class="advertises_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->mobile->Visible) { // mobile ?>
	<?php if ($advertises->SortUrl($advertises->mobile) == "") { ?>
		<td><div id="elh_advertises_mobile" class="advertises_mobile"><div class="ewTableHeaderCaption"><?php echo $advertises->mobile->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->mobile) ?>',1);"><div id="elh_advertises_mobile" class="advertises_mobile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->mobile->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->mobile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->mobile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->_email->Visible) { // email ?>
	<?php if ($advertises->SortUrl($advertises->_email) == "") { ?>
		<td><div id="elh_advertises__email" class="advertises__email"><div class="ewTableHeaderCaption"><?php echo $advertises->_email->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->_email) ?>',1);"><div id="elh_advertises__email" class="advertises__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->website->Visible) { // website ?>
	<?php if ($advertises->SortUrl($advertises->website) == "") { ?>
		<td><div id="elh_advertises_website" class="advertises_website"><div class="ewTableHeaderCaption"><?php echo $advertises->website->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->website) ?>',1);"><div id="elh_advertises_website" class="advertises_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->keywords->Visible) { // keywords ?>
	<?php if ($advertises->SortUrl($advertises->keywords) == "") { ?>
		<td><div id="elh_advertises_keywords" class="advertises_keywords"><div class="ewTableHeaderCaption"><?php echo $advertises->keywords->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->keywords) ?>',1);"><div id="elh_advertises_keywords" class="advertises_keywords">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->keywords->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($advertises->keywords->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->keywords->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->register_date->Visible) { // register_date ?>
	<?php if ($advertises->SortUrl($advertises->register_date) == "") { ?>
		<td><div id="elh_advertises_register_date" class="advertises_register_date"><div class="ewTableHeaderCaption"><?php echo $advertises->register_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->register_date) ?>',1);"><div id="elh_advertises_register_date" class="advertises_register_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->register_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->register_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->register_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->activate->Visible) { // activate ?>
	<?php if ($advertises->SortUrl($advertises->activate) == "") { ?>
		<td><div id="elh_advertises_activate" class="advertises_activate"><div class="ewTableHeaderCaption"><?php echo $advertises->activate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->activate) ?>',1);"><div id="elh_advertises_activate" class="advertises_activate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->activate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->activate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->activate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->user_id->Visible) { // user_id ?>
	<?php if ($advertises->SortUrl($advertises->user_id) == "") { ?>
		<td><div id="elh_advertises_user_id" class="advertises_user_id"><div class="ewTableHeaderCaption"><?php echo $advertises->user_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->user_id) ?>',1);"><div id="elh_advertises_user_id" class="advertises_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($advertises->image->Visible) { // image ?>
	<?php if ($advertises->SortUrl($advertises->image) == "") { ?>
		<td><div id="elh_advertises_image" class="advertises_image"><div class="ewTableHeaderCaption"><?php echo $advertises->image->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $advertises->SortUrl($advertises->image) ?>',1);"><div id="elh_advertises_image" class="advertises_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $advertises->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($advertises->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($advertises->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$advertises_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($advertises->ExportAll && $advertises->Export <> "") {
	$advertises_list->StopRec = $advertises_list->TotalRecs;
} else {

	// Set the last record to display
	if ($advertises_list->TotalRecs > $advertises_list->StartRec + $advertises_list->DisplayRecs - 1)
		$advertises_list->StopRec = $advertises_list->StartRec + $advertises_list->DisplayRecs - 1;
	else
		$advertises_list->StopRec = $advertises_list->TotalRecs;
}
$advertises_list->RecCnt = $advertises_list->StartRec - 1;
if ($advertises_list->Recordset && !$advertises_list->Recordset->EOF) {
	$advertises_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $advertises_list->StartRec > 1)
		$advertises_list->Recordset->Move($advertises_list->StartRec - 1);
} elseif (!$advertises->AllowAddDeleteRow && $advertises_list->StopRec == 0) {
	$advertises_list->StopRec = $advertises->GridAddRowCount;
}

// Initialize aggregate
$advertises->RowType = EW_ROWTYPE_AGGREGATEINIT;
$advertises->ResetAttrs();
$advertises_list->RenderRow();
while ($advertises_list->RecCnt < $advertises_list->StopRec) {
	$advertises_list->RecCnt++;
	if (intval($advertises_list->RecCnt) >= intval($advertises_list->StartRec)) {
		$advertises_list->RowCnt++;

		// Set up key count
		$advertises_list->KeyCount = $advertises_list->RowIndex;

		// Init row class and style
		$advertises->ResetAttrs();
		$advertises->CssClass = "";
		if ($advertises->CurrentAction == "gridadd") {
		} else {
			$advertises_list->LoadRowValues($advertises_list->Recordset); // Load row values
		}
		$advertises->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$advertises->RowAttrs = array_merge($advertises->RowAttrs, array('data-rowindex'=>$advertises_list->RowCnt, 'id'=>'r' . $advertises_list->RowCnt . '_advertises', 'data-rowtype'=>$advertises->RowType));

		// Render row
		$advertises_list->RenderRow();

		// Render list options
		$advertises_list->RenderListOptions();
?>
	<tr<?php echo $advertises->RowAttributes() ?>>
<?php

// Render list options (body, left)
$advertises_list->ListOptions->Render("body", "left", $advertises_list->RowCnt);
?>
	<?php if ($advertises->id->Visible) { // id ?>
		<td<?php echo $advertises->id->CellAttributes() ?>>
<span<?php echo $advertises->id->ViewAttributes() ?>>
<?php echo $advertises->id->ListViewValue() ?></span>
<a id="<?php echo $advertises_list->PageObjName . "_row_" . $advertises_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($advertises->name->Visible) { // name ?>
		<td<?php echo $advertises->name->CellAttributes() ?>>
<span<?php echo $advertises->name->ViewAttributes() ?>>
<?php echo $advertises->name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->cat_id->Visible) { // cat_id ?>
		<td<?php echo $advertises->cat_id->CellAttributes() ?>>
<span<?php echo $advertises->cat_id->ViewAttributes() ?>>
<?php echo $advertises->cat_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->sub_cat_id->Visible) { // sub_cat_id ?>
		<td<?php echo $advertises->sub_cat_id->CellAttributes() ?>>
<span<?php echo $advertises->sub_cat_id->ViewAttributes() ?>>
<?php echo $advertises->sub_cat_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->slogan->Visible) { // slogan ?>
		<td<?php echo $advertises->slogan->CellAttributes() ?>>
<span<?php echo $advertises->slogan->ViewAttributes() ?>>
<?php echo $advertises->slogan->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->city_id->Visible) { // city_id ?>
		<td<?php echo $advertises->city_id->CellAttributes() ?>>
<span<?php echo $advertises->city_id->ViewAttributes() ?>>
<?php echo $advertises->city_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->province_id->Visible) { // province_id ?>
		<td<?php echo $advertises->province_id->CellAttributes() ?>>
<span<?php echo $advertises->province_id->ViewAttributes() ?>>
<?php echo $advertises->province_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->phone->Visible) { // phone ?>
		<td<?php echo $advertises->phone->CellAttributes() ?>>
<span<?php echo $advertises->phone->ViewAttributes() ?>>
<?php echo $advertises->phone->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->mobile->Visible) { // mobile ?>
		<td<?php echo $advertises->mobile->CellAttributes() ?>>
<span<?php echo $advertises->mobile->ViewAttributes() ?>>
<?php echo $advertises->mobile->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->_email->Visible) { // email ?>
		<td<?php echo $advertises->_email->CellAttributes() ?>>
<span<?php echo $advertises->_email->ViewAttributes() ?>>
<?php echo $advertises->_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->website->Visible) { // website ?>
		<td<?php echo $advertises->website->CellAttributes() ?>>
<span<?php echo $advertises->website->ViewAttributes() ?>>
<?php echo $advertises->website->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->keywords->Visible) { // keywords ?>
		<td<?php echo $advertises->keywords->CellAttributes() ?>>
<span<?php echo $advertises->keywords->ViewAttributes() ?>>
<?php echo $advertises->keywords->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->register_date->Visible) { // register_date ?>
		<td<?php echo $advertises->register_date->CellAttributes() ?>>
<span<?php echo $advertises->register_date->ViewAttributes() ?>>
<?php echo $advertises->register_date->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->activate->Visible) { // activate ?>
		<td<?php echo $advertises->activate->CellAttributes() ?>>
<span<?php echo $advertises->activate->ViewAttributes() ?>>
<?php echo $advertises->activate->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->user_id->Visible) { // user_id ?>
		<td<?php echo $advertises->user_id->CellAttributes() ?>>
<span<?php echo $advertises->user_id->ViewAttributes() ?>>
<?php echo $advertises->user_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($advertises->image->Visible) { // image ?>
		<td<?php echo $advertises->image->CellAttributes() ?>>
<span<?php echo $advertises->image->ViewAttributes() ?>>
<?php echo $advertises->image->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$advertises_list->ListOptions->Render("body", "right", $advertises_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($advertises->CurrentAction <> "gridadd")
		$advertises_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($advertises->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($advertises_list->Recordset)
	$advertises_list->Recordset->Close();
?>
<?php if ($advertises->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($advertises->CurrentAction <> "gridadd" && $advertises->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($advertises_list->Pager)) $advertises_list->Pager = new cPrevNextPager($advertises_list->StartRec, $advertises_list->DisplayRecs, $advertises_list->TotalRecs) ?>
<?php if ($advertises_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($advertises_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $advertises_list->PageUrl() ?>start=<?php echo $advertises_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($advertises_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $advertises_list->PageUrl() ?>start=<?php echo $advertises_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $advertises_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($advertises_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $advertises_list->PageUrl() ?>start=<?php echo $advertises_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($advertises_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $advertises_list->PageUrl() ?>start=<?php echo $advertises_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $advertises_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $advertises_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $advertises_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $advertises_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($advertises_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($advertises_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($advertises->Export == "") { ?>
<script type="text/javascript">
fadvertiseslistsrch.Init();
fadvertiseslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$advertises_list->ShowPageFooter();
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
$advertises_list->Page_Terminate();
?>
