<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Usindex extends BasePC_Controller{
	public function __construct() {
		parent::__construct();
	}

	function get_index() {
		$strUrl = 'http://www.itooza.com/stock/vUsindex.htm';
		$us_index_info = $this->get_content($strUrl);
	
		if(isset($us_index_info) && $us_index_info) {
			$us_index_file = 'us_index.inc';
			$strWPath = WEBDATA.'/'.$us_index_file;
			$strBakWPath = $strWPath . '.bak';

			file_put_contents($strBakWPath, $us_index_info);
			rename($strBakWPath, $strWPath);
			echo '['.date("Y-m-d H:i:s")."] success!!\n";
		}
		else {
			echo '['.date("Y-m-d H:i:s")."] error!!\n";
		}
	}
}
?>