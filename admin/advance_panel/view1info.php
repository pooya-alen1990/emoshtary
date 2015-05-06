<?php

// Global variable for table object
$view1 = NULL;

//
// Table class for view1
//
class cview1 extends cTable {
	var $id;
	var $name;
	var $cat_id;
	var $sub_cat_id;
	var $slogan;
	var $city_id;
	var $province_id;
	var $address;
	var $phone;
	var $mobile;
	var $_email;
	var $website;
	var $keywords;
	var $register_date;
	var $google_map;
	var $activate;
	var $user_id;
	var $image;
	var $id1;
	var $adv_email;
	var $activation_code;
	var $asiatech_code;
	var $first_name;
	var $last_name;
	var $melli_code;
	var $mobile1;
	var $city_id1;
	var $province_id1;
	var $address1;
	var $password;
	var $register_date1;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view1';
		$this->TableName = 'view1';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('view1', 'view1', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// name
		$this->name = new cField('view1', 'view1', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// cat_id
		$this->cat_id = new cField('view1', 'view1', 'x_cat_id', 'cat_id', '`cat_id`', '`cat_id`', 3, -1, FALSE, '`cat_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cat_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cat_id'] = &$this->cat_id;

		// sub_cat_id
		$this->sub_cat_id = new cField('view1', 'view1', 'x_sub_cat_id', 'sub_cat_id', '`sub_cat_id`', '`sub_cat_id`', 3, -1, FALSE, '`sub_cat_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sub_cat_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sub_cat_id'] = &$this->sub_cat_id;

		// slogan
		$this->slogan = new cField('view1', 'view1', 'x_slogan', 'slogan', '`slogan`', '`slogan`', 200, -1, FALSE, '`slogan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slogan'] = &$this->slogan;

		// city_id
		$this->city_id = new cField('view1', 'view1', 'x_city_id', 'city_id', '`city_id`', '`city_id`', 3, -1, FALSE, '`city_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->city_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['city_id'] = &$this->city_id;

		// province_id
		$this->province_id = new cField('view1', 'view1', 'x_province_id', 'province_id', '`province_id`', '`province_id`', 3, -1, FALSE, '`province_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->province_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['province_id'] = &$this->province_id;

		// address
		$this->address = new cField('view1', 'view1', 'x_address', 'address', '`address`', '`address`', 201, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address'] = &$this->address;

		// phone
		$this->phone = new cField('view1', 'view1', 'x_phone', 'phone', '`phone`', '`phone`', 200, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['phone'] = &$this->phone;

		// mobile
		$this->mobile = new cField('view1', 'view1', 'x_mobile', 'mobile', '`mobile`', '`mobile`', 200, -1, FALSE, '`mobile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mobile'] = &$this->mobile;

		// email
		$this->_email = new cField('view1', 'view1', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['email'] = &$this->_email;

		// website
		$this->website = new cField('view1', 'view1', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['website'] = &$this->website;

		// keywords
		$this->keywords = new cField('view1', 'view1', 'x_keywords', 'keywords', '`keywords`', '`keywords`', 200, -1, FALSE, '`keywords`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['keywords'] = &$this->keywords;

		// register_date
		$this->register_date = new cField('view1', 'view1', 'x_register_date', 'register_date', '`register_date`', '`register_date`', 20, -1, FALSE, '`register_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->register_date->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['register_date'] = &$this->register_date;

		// google_map
		$this->google_map = new cField('view1', 'view1', 'x_google_map', 'google_map', '`google_map`', '`google_map`', 201, -1, FALSE, '`google_map`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['google_map'] = &$this->google_map;

		// activate
		$this->activate = new cField('view1', 'view1', 'x_activate', 'activate', '`activate`', '`activate`', 16, -1, FALSE, '`activate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->activate->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activate'] = &$this->activate;

		// user_id
		$this->user_id = new cField('view1', 'view1', 'x_user_id', 'user_id', '`user_id`', '`user_id`', 3, -1, FALSE, '`user_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->user_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user_id'] = &$this->user_id;

		// image
		$this->image = new cField('view1', 'view1', 'x_image', 'image', '`image`', '`image`', 20, -1, FALSE, '`image`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->image->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['image'] = &$this->image;

		// id1
		$this->id1 = new cField('view1', 'view1', 'x_id1', 'id1', '`id1`', '`id1`', 3, -1, FALSE, '`id1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id1'] = &$this->id1;

		// adv_email
		$this->adv_email = new cField('view1', 'view1', 'x_adv_email', 'adv_email', '`adv_email`', '`adv_email`', 200, -1, FALSE, '`adv_email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['adv_email'] = &$this->adv_email;

		// activation_code
		$this->activation_code = new cField('view1', 'view1', 'x_activation_code', 'activation_code', '`activation_code`', '`activation_code`', 200, -1, FALSE, '`activation_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['activation_code'] = &$this->activation_code;

		// asiatech_code
		$this->asiatech_code = new cField('view1', 'view1', 'x_asiatech_code', 'asiatech_code', '`asiatech_code`', '`asiatech_code`', 200, -1, FALSE, '`asiatech_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['asiatech_code'] = &$this->asiatech_code;

		// first_name
		$this->first_name = new cField('view1', 'view1', 'x_first_name', 'first_name', '`first_name`', '`first_name`', 200, -1, FALSE, '`first_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['first_name'] = &$this->first_name;

		// last_name
		$this->last_name = new cField('view1', 'view1', 'x_last_name', 'last_name', '`last_name`', '`last_name`', 200, -1, FALSE, '`last_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['last_name'] = &$this->last_name;

		// melli_code
		$this->melli_code = new cField('view1', 'view1', 'x_melli_code', 'melli_code', '`melli_code`', '`melli_code`', 200, -1, FALSE, '`melli_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['melli_code'] = &$this->melli_code;

		// mobile1
		$this->mobile1 = new cField('view1', 'view1', 'x_mobile1', 'mobile1', '`mobile1`', '`mobile1`', 200, -1, FALSE, '`mobile1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mobile1'] = &$this->mobile1;

		// city_id1
		$this->city_id1 = new cField('view1', 'view1', 'x_city_id1', 'city_id1', '`city_id1`', '`city_id1`', 3, -1, FALSE, '`city_id1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->city_id1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['city_id1'] = &$this->city_id1;

		// province_id1
		$this->province_id1 = new cField('view1', 'view1', 'x_province_id1', 'province_id1', '`province_id1`', '`province_id1`', 3, -1, FALSE, '`province_id1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->province_id1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['province_id1'] = &$this->province_id1;

		// address1
		$this->address1 = new cField('view1', 'view1', 'x_address1', 'address1', '`address1`', '`address1`', 201, -1, FALSE, '`address1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address1'] = &$this->address1;

		// password
		$this->password = new cField('view1', 'view1', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['password'] = &$this->password;

		// register_date1
		$this->register_date1 = new cField('view1', 'view1', 'x_register_date1', 'register_date1', '`register_date1`', '`register_date1`', 20, -1, FALSE, '`register_date1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->register_date1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['register_date1'] = &$this->register_date1;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`view1`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`view1`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
			if (array_key_exists('id1', $rs))
				ew_AddFilter($where, ew_QuotedName('id1') . '=' . ew_QuotedValue($rs['id1'], $this->id1->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@ AND `id1` = @id1@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->id1->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id1@", ew_AdjustSql($this->id1->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "view1list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view1list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view1view.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view1view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "view1add.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view1edit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view1add.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view1delete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->id1->CurrentValue)) {
			$sUrl .= "&id1=" . urlencode($this->id1->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["id"]; // id
			$arKey[] = @$_GET["id1"]; // id1
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // id
				continue;
			if (!is_numeric($key[1])) // id1
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key[0];
			$this->id1->CurrentValue = $key[1];
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// address1
		$this->address1->ViewValue = $this->address1->CurrentValue;
		$this->address1->ViewCustomAttributes = "";

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

		// address1
		$this->address1->LinkCustomAttributes = "";
		$this->address1->HrefValue = "";
		$this->address1->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

		// register_date1
		$this->register_date1->LinkCustomAttributes = "";
		$this->register_date1->HrefValue = "";
		$this->register_date1->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->cat_id->Exportable) $Doc->ExportCaption($this->cat_id);
				if ($this->sub_cat_id->Exportable) $Doc->ExportCaption($this->sub_cat_id);
				if ($this->slogan->Exportable) $Doc->ExportCaption($this->slogan);
				if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
				if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
				if ($this->address->Exportable) $Doc->ExportCaption($this->address);
				if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
				if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
				if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
				if ($this->website->Exportable) $Doc->ExportCaption($this->website);
				if ($this->keywords->Exportable) $Doc->ExportCaption($this->keywords);
				if ($this->register_date->Exportable) $Doc->ExportCaption($this->register_date);
				if ($this->google_map->Exportable) $Doc->ExportCaption($this->google_map);
				if ($this->activate->Exportable) $Doc->ExportCaption($this->activate);
				if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
				if ($this->image->Exportable) $Doc->ExportCaption($this->image);
				if ($this->id1->Exportable) $Doc->ExportCaption($this->id1);
				if ($this->adv_email->Exportable) $Doc->ExportCaption($this->adv_email);
				if ($this->activation_code->Exportable) $Doc->ExportCaption($this->activation_code);
				if ($this->asiatech_code->Exportable) $Doc->ExportCaption($this->asiatech_code);
				if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
				if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
				if ($this->melli_code->Exportable) $Doc->ExportCaption($this->melli_code);
				if ($this->mobile1->Exportable) $Doc->ExportCaption($this->mobile1);
				if ($this->city_id1->Exportable) $Doc->ExportCaption($this->city_id1);
				if ($this->province_id1->Exportable) $Doc->ExportCaption($this->province_id1);
				if ($this->address1->Exportable) $Doc->ExportCaption($this->address1);
				if ($this->password->Exportable) $Doc->ExportCaption($this->password);
				if ($this->register_date1->Exportable) $Doc->ExportCaption($this->register_date1);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->cat_id->Exportable) $Doc->ExportCaption($this->cat_id);
				if ($this->sub_cat_id->Exportable) $Doc->ExportCaption($this->sub_cat_id);
				if ($this->slogan->Exportable) $Doc->ExportCaption($this->slogan);
				if ($this->city_id->Exportable) $Doc->ExportCaption($this->city_id);
				if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
				if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
				if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
				if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
				if ($this->website->Exportable) $Doc->ExportCaption($this->website);
				if ($this->keywords->Exportable) $Doc->ExportCaption($this->keywords);
				if ($this->register_date->Exportable) $Doc->ExportCaption($this->register_date);
				if ($this->activate->Exportable) $Doc->ExportCaption($this->activate);
				if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
				if ($this->image->Exportable) $Doc->ExportCaption($this->image);
				if ($this->id1->Exportable) $Doc->ExportCaption($this->id1);
				if ($this->adv_email->Exportable) $Doc->ExportCaption($this->adv_email);
				if ($this->activation_code->Exportable) $Doc->ExportCaption($this->activation_code);
				if ($this->asiatech_code->Exportable) $Doc->ExportCaption($this->asiatech_code);
				if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
				if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
				if ($this->melli_code->Exportable) $Doc->ExportCaption($this->melli_code);
				if ($this->mobile1->Exportable) $Doc->ExportCaption($this->mobile1);
				if ($this->city_id1->Exportable) $Doc->ExportCaption($this->city_id1);
				if ($this->province_id1->Exportable) $Doc->ExportCaption($this->province_id1);
				if ($this->password->Exportable) $Doc->ExportCaption($this->password);
				if ($this->register_date1->Exportable) $Doc->ExportCaption($this->register_date1);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->cat_id->Exportable) $Doc->ExportField($this->cat_id);
					if ($this->sub_cat_id->Exportable) $Doc->ExportField($this->sub_cat_id);
					if ($this->slogan->Exportable) $Doc->ExportField($this->slogan);
					if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
					if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
					if ($this->address->Exportable) $Doc->ExportField($this->address);
					if ($this->phone->Exportable) $Doc->ExportField($this->phone);
					if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
					if ($this->_email->Exportable) $Doc->ExportField($this->_email);
					if ($this->website->Exportable) $Doc->ExportField($this->website);
					if ($this->keywords->Exportable) $Doc->ExportField($this->keywords);
					if ($this->register_date->Exportable) $Doc->ExportField($this->register_date);
					if ($this->google_map->Exportable) $Doc->ExportField($this->google_map);
					if ($this->activate->Exportable) $Doc->ExportField($this->activate);
					if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
					if ($this->image->Exportable) $Doc->ExportField($this->image);
					if ($this->id1->Exportable) $Doc->ExportField($this->id1);
					if ($this->adv_email->Exportable) $Doc->ExportField($this->adv_email);
					if ($this->activation_code->Exportable) $Doc->ExportField($this->activation_code);
					if ($this->asiatech_code->Exportable) $Doc->ExportField($this->asiatech_code);
					if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
					if ($this->melli_code->Exportable) $Doc->ExportField($this->melli_code);
					if ($this->mobile1->Exportable) $Doc->ExportField($this->mobile1);
					if ($this->city_id1->Exportable) $Doc->ExportField($this->city_id1);
					if ($this->province_id1->Exportable) $Doc->ExportField($this->province_id1);
					if ($this->address1->Exportable) $Doc->ExportField($this->address1);
					if ($this->password->Exportable) $Doc->ExportField($this->password);
					if ($this->register_date1->Exportable) $Doc->ExportField($this->register_date1);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->cat_id->Exportable) $Doc->ExportField($this->cat_id);
					if ($this->sub_cat_id->Exportable) $Doc->ExportField($this->sub_cat_id);
					if ($this->slogan->Exportable) $Doc->ExportField($this->slogan);
					if ($this->city_id->Exportable) $Doc->ExportField($this->city_id);
					if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
					if ($this->phone->Exportable) $Doc->ExportField($this->phone);
					if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
					if ($this->_email->Exportable) $Doc->ExportField($this->_email);
					if ($this->website->Exportable) $Doc->ExportField($this->website);
					if ($this->keywords->Exportable) $Doc->ExportField($this->keywords);
					if ($this->register_date->Exportable) $Doc->ExportField($this->register_date);
					if ($this->activate->Exportable) $Doc->ExportField($this->activate);
					if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
					if ($this->image->Exportable) $Doc->ExportField($this->image);
					if ($this->id1->Exportable) $Doc->ExportField($this->id1);
					if ($this->adv_email->Exportable) $Doc->ExportField($this->adv_email);
					if ($this->activation_code->Exportable) $Doc->ExportField($this->activation_code);
					if ($this->asiatech_code->Exportable) $Doc->ExportField($this->asiatech_code);
					if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
					if ($this->melli_code->Exportable) $Doc->ExportField($this->melli_code);
					if ($this->mobile1->Exportable) $Doc->ExportField($this->mobile1);
					if ($this->city_id1->Exportable) $Doc->ExportField($this->city_id1);
					if ($this->province_id1->Exportable) $Doc->ExportField($this->province_id1);
					if ($this->password->Exportable) $Doc->ExportField($this->password);
					if ($this->register_date1->Exportable) $Doc->ExportField($this->register_date1);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
