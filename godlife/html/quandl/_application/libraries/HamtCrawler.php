<?php

// 로그인 필요부.
define('COOKIE_DIR', realpath(dirname(__FILE__).'/cookies'));
ini_set('include_path', realpath(dirname(__FILE__).'/PEAR'));
require 'HTTP/Request.php';

class HamtCrawler
{
	 protected $req;
	 protected $body;
	 protected $cookies;
	 protected $referer;

	function __construct() {
		$this->req =  new HTTP_Request();
		$this->req->addHeader(
			'User-Agent',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/536.29.13 (KHTML, like Gecko) Version/6.0.4 Safari/536.29.13'
		);

		$this->req->addHeader(
			'Accept-Language',
			'ko-kr'
		);

		$this->req->addHeader('Keep-Alive', 115);
		$this->req->addHeader('Connection', 'keep-alive');
	}

	/*
	public function getItemInfo($login_url, $id='', $pass='') {
		// 쿠키 존재안함. 새 아뒤 혹은 쿠키 만료

		$current_cookies= $this->cookies;
		$this->cookies   = array();

		if(strlen($id) > 0 && strlen($pass) > 0) {
			$this->req->setBasicAuth($id, $pass);
		}
		$content = $this->getBody($login_url, null);
		$content = iconv('euc-jp', 'utf-8', $content);

		$result = array(
			'item_name' => '',
			'price' => '',
			'option_h' => array(),
			'option_v' => array(),
		);
		// price
		preg_match_all('/<span itemprop="price" content="([0-9]+)" class="tax_postage">/', $content, $match);
		if(isset($match[1][0])) {
			$result['price'] = $match[1][0];
		}

		// item_name
		preg_match_all('/<span class="item_name"><b>(.+)<\/b><\/span>/', $content, $match);
		if(isset($match[1][0])) {
			$result['item_name'] = $match[1][0];
		}

		// option h, v names
		if(strpos($content, '<td class="floating-cart-sku-table">') !== false) {
			$table = array_shift(explode('</table>', array_pop(explode('<td class="floating-cart-sku-table">', $content, 2)),2));
			$trs = explode('</tr>', $table);
			foreach($trs as $row => $tr) {
				if(strlen(trim($tr)) <= 0) continue;
				if($row == 0) {
					// option_h 수집
					preg_match_all('/<span class="inventory_choice_name">(.*?)<\/span>/', $tr, $match);
					foreach($match[1] as $k => $h_text) {
						$result['option_h'][$k+1] = $h_text;
					}
					continue;
				}
				preg_match_all('/<span class="inventory_choice_name">(.*?)<\/span>/', $tr, $match);
				if(isset($match[1][0]) && strlen(trim($match[1][0])) > 0) {
					$result['option_v'][$row] = $match[1][0];
				}
			}
		}

		return $result;
	}
	*/


	/////////////////////////
	// @ public method 
	/////////////////////////
	public function getHeaders($headername = null) { 
			// 통신 헤더를 반환. 헤더로 리다이렉션 시킬때 대상 URL 가져오는데 사용.
			return $this->req->getResponseHeader($headername);
	}
	

	public function getBody($url, $referer = '')
	{
			// URL 결과 받아오기 .GET.
			if (empty($url)) {
					return null;
			}
			if(strlen($referer)==0) {
				$referer= $this->referer;
			}
			$this->referer = $url;
			$this->req->setURL($url);
			$this->req->addHeader('Referer', $referer);
			$this->req->clearCookies();
			if (!empty($this->cookies)) {
					foreach ($this->cookies as $cookie) {
							$this->req->addCookie($cookie['name'], $cookie['value']);
					}
			}
			$this->req->sendRequest();
			$this->_updateCookies();
			$this->body = $this->req->getResponseBody();
			return $this->body;
	}

	public function getBodyWithPost($url, $post_data, $referer='') 
	{
			// URL에 POST 데이터 전송 후 결과 받아오기 .POST.
			$this->req->setMethod(HTTP_REQUEST_METHOD_POST);
			foreach($post_data as $k => $v) {
					$this->req->addPostData($k, $v);
			}
			return $this->getBody($url, $referer);
	}


	/////////////////////////
	// @ protected method
	/////////////////////////

	protected function _updateCookies($response_cookies = array())
	{
			if (empty($response_cookies)) {
					$response_cookies = $this->req->getResponseCookies();
			}
			if (empty($response_cookies)) {
					return false;
			}
			for ($i=0; $i < count($response_cookies); $i++) {
					$create = true;
					for ($j=0; $j < count($this->cookies); $j++) {
							if ($this->cookies[$j]['name'] === $response_cookies[$i]['name']) {
									$this->cookies[$j]['value'] = $response_cookies[$i]['value'];
									$create = false;
							}
					}
					if ($create) {
							$new_cookies[] = array(
											'id' => '',
											//'service_id' => $this->id,
											'name' => $response_cookies[$i]['name'],
											'value' => $response_cookies[$i]['value']
											);
					}
			}
			if (!empty($new_cookies)) {
					foreach ($new_cookies as $new_cookie) {
							$this->cookies[] = $new_cookie;
					}
			}
	}
	protected function saveCookie($filename) {
			file_put_contents($filename, serialize($this->cookies));
			exec('chmod 777 '.$filename);
	}
}
