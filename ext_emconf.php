<?php

########################################################################
# Extension Manager/Repository config file for ext "multishop".
#
# Auto generated 02-04-2012 13:21
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Multishop',
	'description' => 'TYPO3 Multishop is an E-Commerce plugin for the TYPO3 CMS which supports front-end editing and multiple web shops within the same pagetree.',
	'category' => 'plugin',
	'shy' => '',
	'dependencies' => 'static_info_tables,static_info_tables_taxes,tt_address,t3jquery,rzcolorbox,phpexcel_service',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => 'tx_multishop_products_flat,fe_users,fe_groups,tt_address',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Bas van Beek',
	'author_email' => 'bvbmedia@gmail.com',
	'author_company' => '<a href="http://www.bvbmedia.com/?utm_source=Typo3&utm_medium=cpc&utm_term=multishop+module&utm_content=Listing&utm_campaign=Typo3" target="_blank">BVB Media</a>',
	'version' => '3.0.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.3.15-5.4.11',
			'typo3' => '4.7.7-6.1.0',
			'static_info_tables' => '0.0.0',
			'static_info_tables_taxes' => '0.0.0',
			'tt_address' => '0.0.0',
			't3jquery' => '0.0.0',
			'rzcolorbox' => '0.0.0',
			'phpexcel_service' => '1.7.6',			
		),
		'conflicts' => array(
			'dbal' => '0.0.0'
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);
?>