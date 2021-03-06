<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

/***************************************************************
*  Copyright notice
*
*  (c) 2010 BVB Media BV - Bas van Beek <bvbmedia@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */
class tx_mslib_catalog {
	function sortCatalog($sortItem,$sortByField,$orderBy='asc') {
		set_time_limit(86400); 
		ignore_user_abort(true);		
		switch ($sortItem) {
			case 'manufacturers':
				switch($sortByField) {
					case 'manufacturers_name':
						$str="SELECT c.categories_id from tx_multishop_manufacturers m where m.status=1 order by m.manufacturers_name+0, m.manufacturers_name ".$orderBy;
						$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
						$counter=0;
						$content.='<div class="main-heading"><h2>Sorting Manufacturers on alphabet '.$orderBy.' done</h2></div>';
						while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
							$updateArray=array();
							$updateArray['sort_order']=$counter;
							$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_manufacturers', 'manufacturers_id='.$row['manufacturers_id'],$updateArray);
							$res = $GLOBALS['TYPO3_DB']->sql_query($query);
							$counter++;
						}
						break;
				}
				break;
			case 'categories':
				switch($sortByField) {
					case 'categories_name':
						$str="SELECT c.categories_id from tx_multishop_categories c, tx_multishop_categories_description cd where c.status=1 and c.parent_id='0' and c.page_uid='".$this->showCatalogFromPage."' and c.categories_id=cd.categories_id order by cd.categories_name+0,cd.categories_name ".$orderBy;
						$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
						$counter=0;
						$content.='<div class="main-heading"><h2>Sorting categories on alphabet '.$orderby.' done</h2></div>';		
						while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
							$updateArray=array();
							$updateArray['sort_order']=$counter;
							$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_categories', 'categories_id='.$row['categories_id'],$updateArray);
							$res = $GLOBALS['TYPO3_DB']->sql_query($query);		
							$content.= $row['categories_id'].'<br />';		
							$counter++;
						}		
						$subcategories_array = array();
						mslib_fe::getSubcats($subcategories_array,0);
						if (count($subcategories_array)) {
							foreach ($subcategories_array as $item) {
								// try to sort the subcats
								$content.= $item.'<br />';			
								$str="SELECT c.categories_id from tx_multishop_categories c, tx_multishop_categories_description cd where c.status=1 and c.parent_id='".$item."' and c.page_uid='".$this->showCatalogFromPage."' and c.categories_id=cd.categories_id order by cd.categories_name+0,cd.categories_name ".$orderBy;
								$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
								$counter=0;
								while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_categories', 'categories_id='.$row['categories_id'],$updateArray);
									$res = $GLOBALS['TYPO3_DB']->sql_query($query);		
									$counter++;
								}														
							}
						}					
					break;
				}
			break;
			case 'products':
				switch($sortByField) {
					case 'products_price':
						$content.='<div class="main-heading"><h2>Sorting products price '.$orderBy.' done</h2></div>';
						mslib_fe::getSubcats($subcategories_array,0);
						if (count($subcategories_array)) {
							foreach ($subcategories_array as $item) {
								// try to sort the subcats
								$content.= $item.'<br />';
								// try to find and sort the products
								$str="SELECT p2c.categories_id, p.products_id, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from tx_multishop_products p left join tx_multishop_specials s on p.products_id = s.products_id, tx_multishop_products_description pd, tx_multishop_products_to_categories p2c where p.products_status=1 and p.page_uid='".$this->showCatalogFromPage."' and p.products_id=pd.products_id and p.products_id=p2c.products_id and p2c.categories_id='".$item."' order by final_price ".$orderBy;
								$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
								$counter=0;
								while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query 	= $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products_to_categories', 'products_id='.$row['products_id'].' and categories_id='.$row['categories_id'],$updateArray);
									$res 	= $GLOBALS['TYPO3_DB']->sql_query($query);	
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query 	= $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products', 'products_id='.$row['products_id'],$updateArray);
									$res 	= $GLOBALS['TYPO3_DB']->sql_query($query);							
									$counter++;
								}							
							}
						}			
					break;
					case 'products_name':
						$content.='<div class="main-heading"><h2>Sorting products name on alphabet '.$orderBy.' done</h2></div>';
						$subcategories_array = array();
						mslib_fe::getSubcats($subcategories_array,0);
						if (count($subcategories_array)) {
							foreach ($subcategories_array as $item) {
								// try to find and sort the products
								$str="SELECT p2c.categories_id, p.products_id from tx_multishop_products p, tx_multishop_products_description pd, tx_multishop_products_to_categories p2c where p.products_status=1 and p.page_uid='".$this->showCatalogFromPage."' and p.products_id=pd.products_id and p.products_id=p2c.products_id and p2c.categories_id='".$item."' order by pd.products_name+0,pd.products_name ".$orderBy;
								$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
								$counter=0;
								while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products_to_categories', 'products_id='.$row['products_id'].' and categories_id='.$row['categories_id'],$updateArray);
									$res = $GLOBALS['TYPO3_DB']->sql_query($query);	
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products', 'products_id='.$row['products_id'],$updateArray);
									$res = $GLOBALS['TYPO3_DB']->sql_query($query);							
									$counter++;
								}							
							}
						}			
					break;
					case 'products_date_added':
						$content.='<div class="main-heading"><h2>Sorting products date added '.$orderBy.' done</h2></div>';
						$subcategories_array = array();
						mslib_fe::getSubcats($subcategories_array,0);
						if (count($subcategories_array)) {
							foreach ($subcategories_array as $item) {
								//$content.= $item.'<br />';		
								// try to find and sort the products
								$str="SELECT p2c.categories_id, p.products_id from tx_multishop_products p, tx_multishop_products_description pd, tx_multishop_products_to_categories p2c where p.products_status=1 and p.page_uid='".$this->showCatalogFromPage."' and p.products_id=pd.products_id and p.products_id=p2c.products_id and p2c.categories_id='".$item."' order by p.products_date_added ".$orderBy;
								$qry=$GLOBALS['TYPO3_DB']->sql_query($str);
								$no=time();
								while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) {
									$updateArray=array();
									$updateArray['sort_order']=$no;
									$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products_to_categories', 'products_id='.$row['products_id'].' and categories_id='.$row['categories_id'],$updateArray);
				
									$res = $GLOBALS['TYPO3_DB']->sql_query($query);	
									$updateArray=array();
									$updateArray['sort_order']=$no;
									$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products', 'products_id='.$row['products_id'],$updateArray);
									$res = $GLOBALS['TYPO3_DB']->sql_query($query);							
									if ($this->ms['MODULES']['PRODUCTS_LISTING_SORT_ORDER_OPTION']=='desc') $no--;
									else $no++;
								}							
							}
						}
							
					break;
				}			
			break;
			case 'attribute_values':
				switch($sortByField) {
					case 'products_options_values_name':			
						// manually (naturally) sort all attribute values
						$str = "select * from tx_multishop_products_options where language_id='0' order by sort_order";
						$qry = $GLOBALS['TYPO3_DB']->sql_query($str);
						$rows =$GLOBALS['TYPO3_DB']->sql_num_rows($qry);
						if ($rows) {
							while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry)) != false) {			
								$str2="select * from tx_multishop_products_options_values_to_products_options povp, tx_multishop_products_options_values pov where povp.products_options_id='".$row['products_options_id']."' and povp.products_options_values_id=pov.products_options_values_id and pov.language_id='0' order by pov.products_options_values_name+0, pov.products_options_values_name";	
								//INET_ATON(pov.products_options_values_name)
								//CAST(mid(pov.products_options_values_name, 6, LENGTH(c) -5) AS unsigned)
								$qry2 = $GLOBALS['TYPO3_DB']->sql_query($str2);
								$counter=0;
								while (($row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry2)) != false) {
								//		print_r($row2);
									$counter++;
									$updateArray=array();
									$updateArray['sort_order']=$counter;
									$query3 = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_multishop_products_options_values_to_products_options', 'products_options_id=\''.$row2['products_options_id'].'\' and products_options_values_id=\''.$row2['products_options_values_id'].'\'', $updateArray);
									$res3 = $GLOBALS['TYPO3_DB']->sql_query($query3);			
								}
								// update sort eof	
							}
						}
						$content.='Attribute value sorting completed';
					break;
				}
			break;			
		}
		return $content;
	}
}
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/multishop/pi1/classes/class.tx_mslib_catalog.php"]) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/multishop/pi1/classes/class.tx_mslib_catalog.php"]);
}
?>