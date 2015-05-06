<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$view1_list = NULL; // Initialize page object first

class cview1_list extends cview1 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{4488919F-A46E-4C9B-829B-4AB14E218D15}";

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_list';

	// Grid form hidden field names
	var $FormName = 'fview1list';
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

		// Table object (view1)
		if (!isset($GLOBALS["view1"]) || get_class($GLOBALS["view1"]) == "cview1") {
			$GLOBALS["view1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view1add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view1delete.php";
		$this->MultiUpdateUrl = "view1update.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

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
		$this->id1->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (count($arrKeyFlds) >= 2) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
			$this->id1->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->id1->FormValue))
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
		$this->BuildBasicSearchSQL($sWhere, $this->adv_email, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->activation_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->asiatech_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->first_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->last_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->melli_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $Keyword);
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
			$this->UpdateSort($this->id1); // id1
			$this->UpdateSort($this->adv_email); // adv_email
			$this->UpdateSort($this->activation_code); // activation_code
			$this->UpdateSort($this->asiatech_code); // asiatech_code
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->melli_code); // melli_code
			$this->UpdateSort($this->mobile1); // mobile1
			$this->UpdateSort($this->city_id1); // city_id1
			$this->UpdateSort($this->province_id1); // province_id1
			$this->UpdateSort($this->password); // password
			$this->UpdateSort($this->register_date1); // register_date1
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
				$this->id1->setSort("");
				$this->adv_email->setSort("");
				$this->activation_code->setSort("");
				$this->asiatech_code->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->melli_code->setSort("");
				$this->mobile1->setSort("");
				$this->city_id1->setSort("");
				$this->province_id1->setSort("");
				$this->password->setSort("");
				$this->register_date1->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->id1->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview1list, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id1->setDbValue($rs->fields('id1'));
		$this->adv_email->setDbValue($rs->fields('adv_email'));
		$this->activation_code->setDbValue($rs->fields('activation_code'));
		$this->asiatech_code->setDbValue($rs->fields('asiatech_code'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->melli_code->setDbValue($rs->fields('melli_code'));
		$this->mobile1->setDbValue($rs->fields('mobile1'));
		$this->city_id1->setDbValue($rs->fields('city_id1'));
		$this->province_id1->setDbValue($rs->fields('province_id1'));
		$this->address1->setDbValue($rs->fields('address1'));
		$this->password->setDbValue($rs->fields('password'));
		$this->register_date1->setDbValue($rs->fields('register_date1'));
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
		$this->id1->DbValue = $row['id1'];
		$this->adv_email->DbValue = $row['adv_email'];
		$this->activation_code->DbValue = $row['activation_code'];
		$this->asiatech_code->DbValue = $row['asiatech_code'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->melli_code->DbValue = $row['melli_code'];
		$this->mobile1->DbValue = $row['mobile1'];
		$this->city_id1->DbValue = $row['city_id1'];
		$this->province_id1->DbValue = $row['province_id1'];
		$this->address1->DbValue = $row['address1'];
		$this->password->DbValue = $row['password'];
		$this->register_date1->DbValue = $row['register_date1'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id1")) <> "")
			$this->id1->CurrentValue = $this->getKey("id1"); // id1
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
		// id1
		// adv_email
		// activation_code
		// asiatech_code
		// first_name
		// last_name
		// melli_code
		// mobile1
		// city_id1
		// province_id1
		// address1
		// password
		// register_date1

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

			// id1
			$this->id1->ViewValue = $this->id1->CurrentValue;
			$this->id1->ViewCustomAttributes = "";

			// adv_email
			$this->adv_email->ViewValue = $this->adv_email->CurrentValue;
			$this->adv_email->ViewCustomAttributes = "";

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

			// mobile1
			$this->mobile1->ViewValue = $this->mobile1->CurrentValue;
			$this->mobile1->ViewCustomAttributes = "";

			// city_id1
			$this->city_id1->ViewValue = $this->city_id1->CurrentValue;
			$this->city_id1->ViewCustomAttributes = "";

			// province_id1
			$this->province_id1->ViewValue = $this->province_id1->CurrentValue;
			$this->province_id1->ViewCustomAttributes = "";

			// password
			$this->password->ViewValue = $this->password->CurrentValue;
			$this->password->ViewCustomAttributes = "";

			// register_date1
			$this->register_date1->ViewValue = $this->register_date1->CurrentValue;
			$this->register_date1->ViewCustomAttributes = "";

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

			// id1
			$this->id1->LinkCustomAttributes = "";
			$this->id1->HrefValue = "";
			$this->id1->TooltipValue = "";

			// adv_email
			$this->adv_email->LinkCustomAttributes = "";
			$this->adv_email->HrefValue = "";
			$this->adv_email->TooltipValue = "";

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

			// mobile1
			$this->mobile1->LinkCustomAttributes = "";
			$this->mobile1->HrefValue = "";
			$this->mobile1->TooltipValue = "";

			// city_id1
			$this->city_id1->LinkCustomAttributes = "";
			$this->city_id1->HrefValue = "";
			$this->city_id1->TooltipValue = "";

			// province_id1
			$this->province_id1->LinkCustomAttributes = "";
			$this->province_id1->HrefValue = "";
			$this->province_id1->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// register_date1
			$this->register_date1->LinkCustomAttributes = "";
			$this->register_date1->HrefValue = "";
			$this->register_date1->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_view1\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view1',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fview1list,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($view1_list)) $view1_list = new cview1_list();

// Page init
$view1_list->Page_Init();

// Page main
$view1_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">

// Page object
var view1_list = new ew_Page("view1_list");
view1_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view1_list.PageID; // For backward compatibility

// Form object
var fview1list = new ew_Form("fview1list");
fview1list.FormKeyCountName = '<?php echo $view1_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview1list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1list.ValidateRequired = true;
<?php } else { ?>
fview1list.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fview1listsrch = new ew_Form("fview1listsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view1->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view1_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $view1_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$view1_list->TotalRecs = $view1->SelectRecordCount();
	} else {
		if ($view1_list->Recordset = $view1_list->LoadRecordset())
			$view1_list->TotalRecs = $view1_list->Recordset->RecordCount();
	}
	$view1_list->StartRec = 1;
	if ($view1_list->DisplayRecs <= 0 || ($view1->Export <> "" && $view1->ExportAll)) // Display all records
		$view1_list->DisplayRecs = $view1_list->TotalRecs;
	if (!($view1->Export <> "" && $view1->ExportAll))
		$view1_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view1_list->Recordset = $view1_list->LoadRecordset($view1_list->StartRec-1, $view1_list->DisplayRecs);
$view1_list->RenderOtherOptions();
?>
<?php if ($view1->Export == "" && $view1->CurrentAction == "") { ?>
<form name="fview1listsrch" id="fview1listsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fview1listsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fview1listsrch_SearchGroup" href="#fview1listsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fview1listsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fview1listsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view1">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($view1_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $view1_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($view1_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($view1_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($view1_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php $view1_list->ShowPageHeader(); ?>
<?php
$view1_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fview1list" id="fview1list" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="view1">
<div id="gmp_view1" class="ewGridMiddlePanel">
<?php if ($view1_list->TotalRecs > 0) { ?>
<table id="tbl_view1list" class="ewTable ewTableSeparate">
<?php echo $view1->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$view1_list->RenderListOptions();

// Render list options (header, left)
$view1_list->ListOptions->Render("header", "left");
?>
<?php if ($view1->id->Visible) { // id ?>
	<?php if ($view1->SortUrl($view1->id) == "") { ?>
		<td><div id="elh_view1_id" class="view1_id"><div class="ewTableHeaderCaption"><?php echo $view1->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->id) ?>',1);"><div id="elh_view1_id" class="view1_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->name->Visible) { // name ?>
	<?php if ($view1->SortUrl($view1->name) == "") { ?>
		<td><div id="elh_view1_name" class="view1_name"><div class="ewTableHeaderCaption"><?php echo $view1->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->name) ?>',1);"><div id="elh_view1_name" class="view1_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->cat_id->Visible) { // cat_id ?>
	<?php if ($view1->SortUrl($view1->cat_id) == "") { ?>
		<td><div id="elh_view1_cat_id" class="view1_cat_id"><div class="ewTableHeaderCaption"><?php echo $view1->cat_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->cat_id) ?>',1);"><div id="elh_view1_cat_id" class="view1_cat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->cat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->cat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->cat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->sub_cat_id->Visible) { // sub_cat_id ?>
	<?php if ($view1->SortUrl($view1->sub_cat_id) == "") { ?>
		<td><div id="elh_view1_sub_cat_id" class="view1_sub_cat_id"><div class="ewTableHeaderCaption"><?php echo $view1->sub_cat_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->sub_cat_id) ?>',1);"><div id="elh_view1_sub_cat_id" class="view1_sub_cat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->sub_cat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->sub_cat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->sub_cat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->slogan->Visible) { // slogan ?>
	<?php if ($view1->SortUrl($view1->slogan) == "") { ?>
		<td><div id="elh_view1_slogan" class="view1_slogan"><div class="ewTableHeaderCaption"><?php echo $view1->slogan->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->slogan) ?>',1);"><div id="elh_view1_slogan" class="view1_slogan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->slogan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->slogan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->slogan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->city_id->Visible) { // city_id ?>
	<?php if ($view1->SortUrl($view1->city_id) == "") { ?>
		<td><div id="elh_view1_city_id" class="view1_city_id"><div class="ewTableHeaderCaption"><?php echo $view1->city_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->city_id) ?>',1);"><div id="elh_view1_city_id" class="view1_city_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->city_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->city_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->city_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->province_id->Visible) { // province_id ?>
	<?php if ($view1->SortUrl($view1->province_id) == "") { ?>
		<td><div id="elh_view1_province_id" class="view1_province_id"><div class="ewTableHeaderCaption"><?php echo $view1->province_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->province_id) ?>',1);"><div id="elh_view1_province_id" class="view1_province_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->province_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->province_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->province_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->phone->Visible) { // phone ?>
	<?php if ($view1->SortUrl($view1->phone) == "") { ?>
		<td><div id="elh_view1_phone" class="view1_phone"><div class="ewTableHeaderCaption"><?php echo $view1->phone->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->phone) ?>',1);"><div id="elh_view1_phone" class="view1_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->mobile->Visible) { // mobile ?>
	<?php if ($view1->SortUrl($view1->mobile) == "") { ?>
		<td><div id="elh_view1_mobile" class="view1_mobile"><div class="ewTableHeaderCaption"><?php echo $view1->mobile->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->mobile) ?>',1);"><div id="elh_view1_mobile" class="view1_mobile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->mobile->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->mobile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->mobile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->_email->Visible) { // email ?>
	<?php if ($view1->SortUrl($view1->_email) == "") { ?>
		<td><div id="elh_view1__email" class="view1__email"><div class="ewTableHeaderCaption"><?php echo $view1->_email->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->_email) ?>',1);"><div id="elh_view1__email" class="view1__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->website->Visible) { // website ?>
	<?php if ($view1->SortUrl($view1->website) == "") { ?>
		<td><div id="elh_view1_website" class="view1_website"><div class="ewTableHeaderCaption"><?php echo $view1->website->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->website) ?>',1);"><div id="elh_view1_website" class="view1_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->keywords->Visible) { // keywords ?>
	<?php if ($view1->SortUrl($view1->keywords) == "") { ?>
		<td><div id="elh_view1_keywords" class="view1_keywords"><div class="ewTableHeaderCaption"><?php echo $view1->keywords->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->keywords) ?>',1);"><div id="elh_view1_keywords" class="view1_keywords">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->keywords->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->keywords->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->keywords->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->register_date->Visible) { // register_date ?>
	<?php if ($view1->SortUrl($view1->register_date) == "") { ?>
		<td><div id="elh_view1_register_date" class="view1_register_date"><div class="ewTableHeaderCaption"><?php echo $view1->register_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->register_date) ?>',1);"><div id="elh_view1_register_date" class="view1_register_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->register_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->register_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->register_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->activate->Visible) { // activate ?>
	<?php if ($view1->SortUrl($view1->activate) == "") { ?>
		<td><div id="elh_view1_activate" class="view1_activate"><div class="ewTableHeaderCaption"><?php echo $view1->activate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->activate) ?>',1);"><div id="elh_view1_activate" class="view1_activate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->activate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->activate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->activate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->user_id->Visible) { // user_id ?>
	<?php if ($view1->SortUrl($view1->user_id) == "") { ?>
		<td><div id="elh_view1_user_id" class="view1_user_id"><div class="ewTableHeaderCaption"><?php echo $view1->user_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->user_id) ?>',1);"><div id="elh_view1_user_id" class="view1_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->image->Visible) { // image ?>
	<?php if ($view1->SortUrl($view1->image) == "") { ?>
		<td><div id="elh_view1_image" class="view1_image"><div class="ewTableHeaderCaption"><?php echo $view1->image->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->image) ?>',1);"><div id="elh_view1_image" class="view1_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->id1->Visible) { // id1 ?>
	<?php if ($view1->SortUrl($view1->id1) == "") { ?>
		<td><div id="elh_view1_id1" class="view1_id1"><div class="ewTableHeaderCaption"><?php echo $view1->id1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->id1) ?>',1);"><div id="elh_view1_id1" class="view1_id1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->id1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->id1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->id1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->adv_email->Visible) { // adv_email ?>
	<?php if ($view1->SortUrl($view1->adv_email) == "") { ?>
		<td><div id="elh_view1_adv_email" class="view1_adv_email"><div class="ewTableHeaderCaption"><?php echo $view1->adv_email->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->adv_email) ?>',1);"><div id="elh_view1_adv_email" class="view1_adv_email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->adv_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->adv_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->adv_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->activation_code->Visible) { // activation_code ?>
	<?php if ($view1->SortUrl($view1->activation_code) == "") { ?>
		<td><div id="elh_view1_activation_code" class="view1_activation_code"><div class="ewTableHeaderCaption"><?php echo $view1->activation_code->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->activation_code) ?>',1);"><div id="elh_view1_activation_code" class="view1_activation_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->activation_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->activation_code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->activation_code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->asiatech_code->Visible) { // asiatech_code ?>
	<?php if ($view1->SortUrl($view1->asiatech_code) == "") { ?>
		<td><div id="elh_view1_asiatech_code" class="view1_asiatech_code"><div class="ewTableHeaderCaption"><?php echo $view1->asiatech_code->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->asiatech_code) ?>',1);"><div id="elh_view1_asiatech_code" class="view1_asiatech_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->asiatech_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->asiatech_code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->asiatech_code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->first_name->Visible) { // first_name ?>
	<?php if ($view1->SortUrl($view1->first_name) == "") { ?>
		<td><div id="elh_view1_first_name" class="view1_first_name"><div class="ewTableHeaderCaption"><?php echo $view1->first_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->first_name) ?>',1);"><div id="elh_view1_first_name" class="view1_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->last_name->Visible) { // last_name ?>
	<?php if ($view1->SortUrl($view1->last_name) == "") { ?>
		<td><div id="elh_view1_last_name" class="view1_last_name"><div class="ewTableHeaderCaption"><?php echo $view1->last_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->last_name) ?>',1);"><div id="elh_view1_last_name" class="view1_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->melli_code->Visible) { // melli_code ?>
	<?php if ($view1->SortUrl($view1->melli_code) == "") { ?>
		<td><div id="elh_view1_melli_code" class="view1_melli_code"><div class="ewTableHeaderCaption"><?php echo $view1->melli_code->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->melli_code) ?>',1);"><div id="elh_view1_melli_code" class="view1_melli_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->melli_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->melli_code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->melli_code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->mobile1->Visible) { // mobile1 ?>
	<?php if ($view1->SortUrl($view1->mobile1) == "") { ?>
		<td><div id="elh_view1_mobile1" class="view1_mobile1"><div class="ewTableHeaderCaption"><?php echo $view1->mobile1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->mobile1) ?>',1);"><div id="elh_view1_mobile1" class="view1_mobile1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->mobile1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->mobile1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->mobile1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->city_id1->Visible) { // city_id1 ?>
	<?php if ($view1->SortUrl($view1->city_id1) == "") { ?>
		<td><div id="elh_view1_city_id1" class="view1_city_id1"><div class="ewTableHeaderCaption"><?php echo $view1->city_id1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->city_id1) ?>',1);"><div id="elh_view1_city_id1" class="view1_city_id1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->city_id1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->city_id1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->city_id1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->province_id1->Visible) { // province_id1 ?>
	<?php if ($view1->SortUrl($view1->province_id1) == "") { ?>
		<td><div id="elh_view1_province_id1" class="view1_province_id1"><div class="ewTableHeaderCaption"><?php echo $view1->province_id1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->province_id1) ?>',1);"><div id="elh_view1_province_id1" class="view1_province_id1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->province_id1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->province_id1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->province_id1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->password->Visible) { // password ?>
	<?php if ($view1->SortUrl($view1->password) == "") { ?>
		<td><div id="elh_view1_password" class="view1_password"><div class="ewTableHeaderCaption"><?php echo $view1->password->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->password) ?>',1);"><div id="elh_view1_password" class="view1_password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($view1->register_date1->Visible) { // register_date1 ?>
	<?php if ($view1->SortUrl($view1->register_date1) == "") { ?>
		<td><div id="elh_view1_register_date1" class="view1_register_date1"><div class="ewTableHeaderCaption"><?php echo $view1->register_date1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->register_date1) ?>',1);"><div id="elh_view1_register_date1" class="view1_register_date1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->register_date1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->register_date1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->register_date1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view1_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view1->ExportAll && $view1->Export <> "") {
	$view1_list->StopRec = $view1_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view1_list->TotalRecs > $view1_list->StartRec + $view1_list->DisplayRecs - 1)
		$view1_list->StopRec = $view1_list->StartRec + $view1_list->DisplayRecs - 1;
	else
		$view1_list->StopRec = $view1_list->TotalRecs;
}
$view1_list->RecCnt = $view1_list->StartRec - 1;
if ($view1_list->Recordset && !$view1_list->Recordset->EOF) {
	$view1_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $view1_list->StartRec > 1)
		$view1_list->Recordset->Move($view1_list->StartRec - 1);
} elseif (!$view1->AllowAddDeleteRow && $view1_list->StopRec == 0) {
	$view1_list->StopRec = $view1->GridAddRowCount;
}

// Initialize aggregate
$view1->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view1->ResetAttrs();
$view1_list->RenderRow();
while ($view1_list->RecCnt < $view1_list->StopRec) {
	$view1_list->RecCnt++;
	if (intval($view1_list->RecCnt) >= intval($view1_list->StartRec)) {
		$view1_list->RowCnt++;

		// Set up key count
		$view1_list->KeyCount = $view1_list->RowIndex;

		// Init row class and style
		$view1->ResetAttrs();
		$view1->CssClass = "";
		if ($view1->CurrentAction == "gridadd") {
		} else {
			$view1_list->LoadRowValues($view1_list->Recordset); // Load row values
		}
		$view1->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view1->RowAttrs = array_merge($view1->RowAttrs, array('data-rowindex'=>$view1_list->RowCnt, 'id'=>'r' . $view1_list->RowCnt . '_view1', 'data-rowtype'=>$view1->RowType));

		// Render row
		$view1_list->RenderRow();

		// Render list options
		$view1_list->RenderListOptions();
?>
	<tr<?php echo $view1->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view1_list->ListOptions->Render("body", "left", $view1_list->RowCnt);
?>
	<?php if ($view1->id->Visible) { // id ?>
		<td<?php echo $view1->id->CellAttributes() ?>>
<span<?php echo $view1->id->ViewAttributes() ?>>
<?php echo $view1->id->ListViewValue() ?></span>
<a id="<?php echo $view1_list->PageObjName . "_row_" . $view1_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view1->name->Visible) { // name ?>
		<td<?php echo $view1->name->CellAttributes() ?>>
<span<?php echo $view1->name->ViewAttributes() ?>>
<?php echo $view1->name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->cat_id->Visible) { // cat_id ?>
		<td<?php echo $view1->cat_id->CellAttributes() ?>>
<span<?php echo $view1->cat_id->ViewAttributes() ?>>
<?php echo $view1->cat_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->sub_cat_id->Visible) { // sub_cat_id ?>
		<td<?php echo $view1->sub_cat_id->CellAttributes() ?>>
<span<?php echo $view1->sub_cat_id->ViewAttributes() ?>>
<?php echo $view1->sub_cat_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->slogan->Visible) { // slogan ?>
		<td<?php echo $view1->slogan->CellAttributes() ?>>
<span<?php echo $view1->slogan->ViewAttributes() ?>>
<?php echo $view1->slogan->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->city_id->Visible) { // city_id ?>
		<td<?php echo $view1->city_id->CellAttributes() ?>>
<span<?php echo $view1->city_id->ViewAttributes() ?>>
<?php echo $view1->city_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->province_id->Visible) { // province_id ?>
		<td<?php echo $view1->province_id->CellAttributes() ?>>
<span<?php echo $view1->province_id->ViewAttributes() ?>>
<?php echo $view1->province_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->phone->Visible) { // phone ?>
		<td<?php echo $view1->phone->CellAttributes() ?>>
<span<?php echo $view1->phone->ViewAttributes() ?>>
<?php echo $view1->phone->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->mobile->Visible) { // mobile ?>
		<td<?php echo $view1->mobile->CellAttributes() ?>>
<span<?php echo $view1->mobile->ViewAttributes() ?>>
<?php echo $view1->mobile->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->_email->Visible) { // email ?>
		<td<?php echo $view1->_email->CellAttributes() ?>>
<span<?php echo $view1->_email->ViewAttributes() ?>>
<?php echo $view1->_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->website->Visible) { // website ?>
		<td<?php echo $view1->website->CellAttributes() ?>>
<span<?php echo $view1->website->ViewAttributes() ?>>
<?php echo $view1->website->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->keywords->Visible) { // keywords ?>
		<td<?php echo $view1->keywords->CellAttributes() ?>>
<span<?php echo $view1->keywords->ViewAttributes() ?>>
<?php echo $view1->keywords->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->register_date->Visible) { // register_date ?>
		<td<?php echo $view1->register_date->CellAttributes() ?>>
<span<?php echo $view1->register_date->ViewAttributes() ?>>
<?php echo $view1->register_date->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->activate->Visible) { // activate ?>
		<td<?php echo $view1->activate->CellAttributes() ?>>
<span<?php echo $view1->activate->ViewAttributes() ?>>
<?php echo $view1->activate->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->user_id->Visible) { // user_id ?>
		<td<?php echo $view1->user_id->CellAttributes() ?>>
<span<?php echo $view1->user_id->ViewAttributes() ?>>
<?php echo $view1->user_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->image->Visible) { // image ?>
		<td<?php echo $view1->image->CellAttributes() ?>>
<span<?php echo $view1->image->ViewAttributes() ?>>
<?php echo $view1->image->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->id1->Visible) { // id1 ?>
		<td<?php echo $view1->id1->CellAttributes() ?>>
<span<?php echo $view1->id1->ViewAttributes() ?>>
<?php echo $view1->id1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->adv_email->Visible) { // adv_email ?>
		<td<?php echo $view1->adv_email->CellAttributes() ?>>
<span<?php echo $view1->adv_email->ViewAttributes() ?>>
<?php echo $view1->adv_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->activation_code->Visible) { // activation_code ?>
		<td<?php echo $view1->activation_code->CellAttributes() ?>>
<span<?php echo $view1->activation_code->ViewAttributes() ?>>
<?php echo $view1->activation_code->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->asiatech_code->Visible) { // asiatech_code ?>
		<td<?php echo $view1->asiatech_code->CellAttributes() ?>>
<span<?php echo $view1->asiatech_code->ViewAttributes() ?>>
<?php echo $view1->asiatech_code->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->first_name->Visible) { // first_name ?>
		<td<?php echo $view1->first_name->CellAttributes() ?>>
<span<?php echo $view1->first_name->ViewAttributes() ?>>
<?php echo $view1->first_name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->last_name->Visible) { // last_name ?>
		<td<?php echo $view1->last_name->CellAttributes() ?>>
<span<?php echo $view1->last_name->ViewAttributes() ?>>
<?php echo $view1->last_name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->melli_code->Visible) { // melli_code ?>
		<td<?php echo $view1->melli_code->CellAttributes() ?>>
<span<?php echo $view1->melli_code->ViewAttributes() ?>>
<?php echo $view1->melli_code->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->mobile1->Visible) { // mobile1 ?>
		<td<?php echo $view1->mobile1->CellAttributes() ?>>
<span<?php echo $view1->mobile1->ViewAttributes() ?>>
<?php echo $view1->mobile1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->city_id1->Visible) { // city_id1 ?>
		<td<?php echo $view1->city_id1->CellAttributes() ?>>
<span<?php echo $view1->city_id1->ViewAttributes() ?>>
<?php echo $view1->city_id1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->province_id1->Visible) { // province_id1 ?>
		<td<?php echo $view1->province_id1->CellAttributes() ?>>
<span<?php echo $view1->province_id1->ViewAttributes() ?>>
<?php echo $view1->province_id1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->password->Visible) { // password ?>
		<td<?php echo $view1->password->CellAttributes() ?>>
<span<?php echo $view1->password->ViewAttributes() ?>>
<?php echo $view1->password->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1->register_date1->Visible) { // register_date1 ?>
		<td<?php echo $view1->register_date1->CellAttributes() ?>>
<span<?php echo $view1->register_date1->ViewAttributes() ?>>
<?php echo $view1->register_date1->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view1_list->ListOptions->Render("body", "right", $view1_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view1->CurrentAction <> "gridadd")
		$view1_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view1->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view1_list->Recordset)
	$view1_list->Recordset->Close();
?>
<?php if ($view1->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cPrevNextPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs) ?>
<?php if ($view1_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($view1_list->SearchWhere == "0=101") { ?>
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
	foreach ($view1_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">
fview1listsrch.Init();
fview1list.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$view1_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view1_list->Page_Terminate();
?>
