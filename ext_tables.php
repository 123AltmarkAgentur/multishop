<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key,pages";
t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/rootpage','MultiShop Root Page Setup');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/corepage','MultiShop Core Page Setup');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/custom_css','MultiShop Custom CSS Setup');
//t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/ajax','MultiShop Ajax Setup');

/*
 * Extend fe_user table
 */
$tempColumns = array (
    "tx_multishop_discount" => array (
        "exclude" => 1,
        "label" => "Discount percentage:",
        "config" => array (
            "type" => "input",
            "size" => "2",
            "max" => "2",
            "eval" => "int",
            "checkbox" => "0",
            "range" => array (
                "upper" => "100",
                "lower" => "0"
            ),
            "default" => 0
        )
    ),
    "street_name" => array (
          "exclude" => 1,
          "label" => "Street name:",
          "config" => array (
              "type" => "input",
              "size" => "25",
              "max" => "75",
              "checkbox" => "0",
              "default" => 0
          )
      ),	
    "address_number" => array (
          "exclude" => 1,
          "label" => "Number:",
          "config" => array (
              "type" => "input",
              "size" => "10",
              "max" => "20",
              "checkbox" => "0",
              "default" => 0
          )
      ),
    "address_ext" => array (
          "exclude" => 1,
          "label" => "Number extension:",
          "config" => array (
              "type" => "input",
              "size" => "5",
              "max" => "5",
              "checkbox" => "0",
              "default" => 0
          )
      ),	  
    'mobile' => array(
    	'exclude' => 1,
    	'label' => 'Mobile:',
    	'config' => array(
    		'type' => 'input',
    		'eval' => 'trim',
    		'size' => '20',
    		'max' => '20'
    	 )
      ),
    'gender' => array(
    	'exclude' => 1,
    	'label' => 'Gender:',
		'config'  => array( 'type'  => 'select',
			'items'	=> array(
				array('Male', 0),
				array('Female', 1)
			)
		)
	)
);
t3lib_div::loadTCA("fe_users");
t3lib_extMgm::addTCAcolumns("fe_users",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_users",'--div--; Multishop, tx_multishop_discount;;;;1-1-1');

// Extending address with address_number and combine them in one new palette called "multishopaddress"
$TCA['fe_users']['palettes']['multishopaddress'] = array(
  'showitem' => 'address,street_name,address_number,address_ext'
);
t3lib_extMgm::addToAllTCAtypes('fe_users', '--palette--;Address;multishopaddress', '', 'replace:address');

// Adding mobilephone after telephone
t3lib_extMgm::addToAllTCAtypes('fe_users', 'mobile', '', 'after:telephone');
t3lib_extMgm::addToAllTCAtypes('fe_users', 'gender', '', 'after:address');

// prepare $tempColumns for fe_groups
unset($tempColumns['mobile']);
unset($tempColumns['gender']);
unset($tempColumns['street_name']);
unset($tempColumns['address_number']);
unset($tempColumns['address_ext']);


t3lib_div::loadTCA("fe_groups");
t3lib_extMgm::addTCAcolumns("fe_groups",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_groups",'--div--; Multishop, tx_multishop_discount;;;;1-1-1');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:multishop/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']=  'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');


if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_multishop_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_multishop_pi1_wizicon.php';
	t3lib_extMgm::addModulePath('web_txmultishopM1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
	t3lib_extMgm::addModule('web', 'txmultishopM1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}
include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_multishop_addMiscFieldsToFlexForm.php');
if (TYPO3_MODE == 'FE') {
	// Autoloader works great in TYPO3 4.7.7. But in TYPO3 4.5.X the invalid namespace classes are not autoloaded so lets load it manually then too	
	// PHP Fatal error:  Access to undeclared static property: t3lib_autoloader::$classNameToFileMapping in /shopcvs/skeleton/typo3_src-4.7.5/t3lib/class.t3lib_autoloader.php on line 151
	require_once(t3lib_extMgm::extPath('multishop').'res/Cache_Lite-1.7.15/Cache/class.cache_lite.php');
	require_once(t3lib_extMgm::extPath('multishop').'pi1/classes/class.mslib_fe.php');
	require_once(t3lib_extMgm::extPath('multishop').'pi1/classes/class.mslib_befe.php');
	require_once(t3lib_extMgm::extPath('multishop').'pi1/classes/class.mslib_payment.php');
}
?>