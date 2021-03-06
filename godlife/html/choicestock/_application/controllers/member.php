<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base_mobile.php';
class Member extends BaseMobile_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	private function _withdrawal_check($user_id) {
		if(!isset($user_id) && $user_id == '') {
			$this->common->alert('회원 인증에 실패했습니다.');
			$this->common->locationhref('/');
			exit;
		}
		else {
			$this->load->model(DBNAME.'/withdrawal_tb_model');
			
			$params = array();
			$params['=']['wd_user_id'] = $user_id;
			$params['=']['wd_code'] = SRV_CODE;
			$params['<']['wd_date'] = date('Y-m-d H:i:s');
			$params['>']['wd_end_date'] = date('Ymd');

			$extra = array(
				'fields' => '*',
				'order_by' => 'wd_date DESC',
				'limit' => 1,
				'slavedb' => true
			);
			$wd_data = array();
			$wd_data = array_shift($this->withdrawal_tb_model->getList($params, $extra)->getData());

			if(is_array($wd_data) && sizeof($wd_data)>0) {
				$this->session->sess_destroy();
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}

	private function _paycheck($user_id) {

		//유료회원 체크
		// pay_tb 확인(서비스코드, 결제상태(P), 서비스 종료일 체크

		if(!isset($user_id) && $user_id == '') {
			$this->common->alert('회원 정보가 없습니다.');
			$this->common->locationhref('/');
			exit;
		}

		$this->load->model(DBNAME.'/pay_tb_model');
		
		$params = array();
		$params['=']['p_user_id'] = $user_id;
		$params['=']['p_code'] = SRV_CODE;
		$params['=']['p_status'] = 'P';
		//$params['>=']['p_end_date'] = date('Ymd');

		$extra = array(
			'fields' => '*',
			'order_by' => 'p_end_date DESC',
			'limit' => 1,
			//'slavedb' => true
		);
		$paydata = array();
		$paydata = array_shift($this->pay_tb_model->getList($params, $extra)->getData());

		if(is_array($paydata) && sizeof($paydata)>0) {
			if($paydata['p_end_date'] >= date('Ymd')) {
				return TRUE;
			}
			else {
				$update_params = array(
					'p_status' => 'E', 
				);

				$this->pay_tb_model->doUpdate($paydata['p_id'], $update_params);
				
				$this->load->model(DBNAME.'/member_tb_model');

				$update_params = array(
					'm_paid' => 'N', 
				);

				$this->member_tb_model->doUpdate($user_id, $update_params);
				return FALSE;
			}
		
		}
		else {
			$this->load->model(DBNAME.'/freepay_tb_model');

			$params = array();
			$params['=']['fp_user_id'] = $user_id;
			$params['=']['fp_code'] = SRV_CODE;
			$params['>=']['fp_end_date'] = date('Ymd');

			$extra = array(
				'fields' => '*',
				'slavedb' => true
			);

			$freepay_data = array();
			$freepay_data = array_shift($this->freepay_tb_model->getList($params, $extra)->getData());

			if(is_array($freepay_data) && sizeof($freepay_data)>0) {

				//핸드폰 체크
				$this->load->model(DBNAME.'/member_tb_model');

				$member_params = array();
				$member_params['=']['m_id'] = $user_id;

				$member_extra = array(
					'fields' => 'm_phone',
					'slavedb' => true
				);
		
				$member_info = array_shift($this->member_tb_model->getList($member_params, $member_extra)->getData());

				if($member_info['m_phone'] == '') {
					$free_notice = '알림 서비스 수신을 위해 <b>휴대폰번호를 등록</b>해주세요. <a href="/member/info">[등록]</a>';
				}
				else {
					$s_date = strtotime($freepay_data['fp_start_date']);
					$e_date = strtotime($freepay_data['fp_end_date']); 
					$during =  ceil(($e_date - $s_date)/(60*60*24))+1; // 일차이

					$free_notice = '<a href="/main/service_prm">프리미엄 '.$during.'일 무료 체험</a> 중 입니다(~'.date('m/d', strtotime($freepay_data['fp_end_date'])).'까지)';
				}
				$this->session->set_userdata('free_notice', $free_notice);
				return TRUE;
			}
			else {
				$this->session->unset_userdata('free_notice');

				$this->load->model(DBNAME.'/member_tb_model');

				$update_params = array(
					'm_paid' => 'N', 
				);

				$this->member_tb_model->doUpdate($user_id, $update_params);

				return FALSE;
			}
		}
	}

	public function oauth() {
	    
		$code = $this->input->get('code');
		$state = $this->input->get('state');

		if( $this->session->userdata('cs_state_code') != $state ) {

            $this->common->alert('카카오 로그인이 정상적으로 진행되지 않았습니다.[1]');
			$this->common->locationhref('/member/login');
			exit;
		}

		if( isset($code) && $code != '' ) {

			$redirectURI = urlencode(HOME_URL."/member/oauth");

			$params = sprintf( 'grant_type=authorization_code&client_id=%s&redirect_uri=%s&code=%s&client_secret=%s', KAKAO_CLIENT_ID, $redirectURI, $code, KAKAO_CLIENT_SECRET);
			$TOKEN_API_URL = "https://kauth.kakao.com/oauth/token";
		
			$opts = array( 
				CURLOPT_URL => $TOKEN_API_URL, 
				CURLOPT_SSL_VERIFYPEER => false, 
				CURLOPT_SSLVERSION => 1, // TLS 
				CURLOPT_POST => true, 
				CURLOPT_POSTFIELDS => $params, 
				CURLOPT_RETURNTRANSFER => true, 
				CURLOPT_HEADER => false
			); 
		
			$curlSession = curl_init(); 
			curl_setopt_array($curlSession, $opts);
			$accessTokenJson = curl_exec($curlSession); 
			curl_close($curlSession); 

			$responseArr = json_decode($accessTokenJson, true); 
		
			//$_SESSION['kakao_access_token'] = $responseArr['access_token']; 
			//$_SESSION['kakao_refresh_token'] = $responseArr['refresh_token']; 
			//$_SESSION['kakao_refresh_token_expires_in'] = $responseArr['refresh_token_expires_in']; 
			
			$this->session->set_userdata('refresh_token', $responseArr['refresh_token']);

			//사용자 정보 가저오기 
			$USER_API_URL= "https://kapi.kakao.com/v2/user/me";
				
			$opts = array( 
				CURLOPT_URL => $USER_API_URL, 
				CURLOPT_SSL_VERIFYPEER => false, 
				CURLOPT_SSLVERSION => 1, 
				CURLOPT_POST => true, 
				CURLOPT_POSTFIELDS => false, 
				CURLOPT_RETURNTRANSFER => true, 
				CURLOPT_HTTPHEADER => array( "Authorization: Bearer " . $responseArr['access_token'] ) 
			); 
			
			$curlSession = curl_init();
			curl_setopt_array($curlSession, $opts); 
			$accessUserJson = curl_exec($curlSession);
			curl_close($curlSession); 
			
			$me_responseArr = json_decode($accessUserJson, true); 

			/*
			Array
			(
				[id] => 134232****
				[connected_at] => 2020-04-24T05:31:56Z
				[properties] => Array
					(
						[nickname] => 홍길동
						[profile_image] => http://k.kakaocdn.net/dn/duI6CU/btqzNNtzgd4/owPTi6bRb4dqKSPm5GYAsK/img_640x640.jpg
						[thumbnail_image] => http://k.kakaocdn.net/dn/duI6CU/btqzNNtzgd4/owPTi6bRb4dqKSPm5GYAsK/img_110x110.jpg
					)

				[kakao_account] => Array
					(
						[profile_needs_agreement] => 
						[profile] => Array
							(
								[nickname] => 홍길동
								[thumbnail_image_url] => http://k.kakaocdn.net/dn/duI6CU/btqzNNtzgd4/owPTi6bRb4dqKSPm5GYAsK/img_110x110.jpg
								[profile_image_url] => http://k.kakaocdn.net/dn/duI6CU/btqzNNtzgd4/owPTi6bRb4dqKSPm5GYAsK/img_640x640.jpg
							)

						[has_email] => 1
						[email_needs_agreement] => 
						[is_email_valid] => 1
						[is_email_verified] => 1
						[email] => c**@kakao.com
						[has_gender] => 1
						[gender_needs_agreement] => 
						[gender] => male
					)

			)
			$mb_nickname = $me_responseArr['properties']['nickname']; // 닉네임 
			$mb_profile_image = $me_responseArr['properties']['profile_image']; // 프로필 이미지
			$mb_thumbnail_image = $me_responseArr['properties']['thumbnail_image']; // 프로필 이미지
			$mb_email = $me_responseArr['kakao_account']['email']; // 이메일
			$mb_gender = $me_responseArr['kakao_account']['gender']; // 성별 female/male
			$mb_age = $me_responseArr['kakao_account']['age_range']; // 연령대
			$mb_birthday = $me_responseArr['kakao_account']['birthday']; // 생일 
			*/
			if($me_responseArr['id']) { 
				$strKakaoId		= 'kakao_'.$me_responseArr['id'];
				$strKakaoEmail	= $me_responseArr['kakao_account']['email'];
				$strKakaoName	= $me_responseArr['kakao_account']['profile']['nickname'];

				$this->load->model(DBNAME.'/member_tb_model');

				if($this->member_tb_model->get(array('m_id' => $strKakaoId))->isSuccess()) {

					$dbrow = $this->member_tb_model->getData();

					//유료회원 체크
					$is_paid = FALSE;
					if($this->_paycheck($strKakaoId)) {
						$is_paid = TRUE;
					}
					else {
						if($dbrow['m_level'] == 9) {
							$is_paid = TRUE;
						}
					}

					$sess_data = array(
						//'is_paid' => ($dbrow['m_paid']=='Y') ? TRUE:FALSE, 
						'is_paid' => $is_paid, 
						'user_id' => $strKakaoId,
						'user_email' => $dbrow['m_email'],
						'user_name' => $dbrow['m_name'],
						'user_phone' => $dbrow['m_phone'],
						'user_level' => $dbrow['m_level'],
						'user_auto_pay' => $dbrow['m_auto_pay'],
						//'cho_user_id' =>							
					);
					$this->set_session($sess_data);

					$this->common->write_login_log($strKakaoId);

					$update_params = array(
						'm_login_date' => date('Y-m-d H:i:s')
					);

					$this->member_tb_model->doUpdate($strKakaoId , $update_params);

					$return_url = explode('|', $state); 
					$return_url = $return_url[1];

					$this->common->locationhref(HOME_URL.'/'.$return_url);
					exit;
				}
				else {

					//탈퇴회원체크
					if($this->_withdrawal_check($strKakaoId)) {
						$this->common->alert('이미 탈퇴한 회원입니다.');
						$this->common->locationhref('/');
						exit;
					}

					$redirect_url = '/member/login_idchk?ue='.$strKakaoEmail."&up=K";
					$this->common->locationhref($redirect_url);
					exit;
				}
			}
			else {
				$this->common->alert('카카오 로그인이 정상적으로 진행되지 않았습니다.[3]');
				$this->common->locationhref('/member/login');
				exit;
			}
		} 
		else { 
		 // 회원정보를 가져오지 못했습니다. 
            $this->common->alert('카카오 로그인이 정상적으로 진행되지 않았습니다.[2]');
			$this->common->locationhref('/member/login');
			exit;
		}
	}

	public function nauth() {
	    
		$code = $this->input->get('code');
		$state = $this->input->get('state');

		if( $this->session->userdata('cs_state_code') != $state ) {
            $this->common->alert('네이버 로그인이 정상적으로 진행되지 않았습니다.[1]');
			$this->common->locationhref('/member/login');
			exit;
		}
		
		$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&code=".$code."&state=".$state;

		$is_post = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $is_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$headers = array();
		$response = curl_exec ($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close ($ch);

		if($status_code == 200) {
		    
			//회원정보 가져오기
			$strArrToken = json_decode($response, true);
			/*
				Array
				(
					[access_token] => AAAAOnfpYIAz6lxD1WAy4BO0uEhbRrSkC6H1_dD_YAeB1odiHaloZjPE7aAveAw774zt7s0ClD7CLjlhX54Y72RI3hQ
					[refresh_token] => ao9bpOulwVHspMsDatrIIGipKaVHKoEHsmWT9Dd6WeuJNNR6A4XnoMcpSfDipujxW2jFdXdHuTQtYQsEDrAcipUD9O1eO0XNQczqLWYYl3CwiiKv6jd0tQLKKW1Gum3L9494
					[token_type] => bearer
					[expires_in] => 3600
				)
				//$_SESSION['kakao_refresh_token'] = $responseArr['refresh_token']; 

				$this->session->unset_userdata('cs_state_code');
				$this->session->set_userdata('cs_state_code', $state);

			*/

			//$this->session->unset_userdata('naver_refresh_token');
			$this->session->set_userdata('refresh_token', $strArrToken['refresh_token']);

			$header = "Bearer ".$strArrToken['access_token']; // Bearer 다음에 공백 추가

			$url = "https://openapi.naver.com/v1/nid/me";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, $is_post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$headers = array();
			$headers[] = "Authorization: ".$header;
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$response = curl_exec ($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ($ch);

			if($status_code == 200) {
				$strArrMemInfo = json_decode($response, true);
				if( $strArrMemInfo['resultcode'] == '00' && $strArrMemInfo['message'] == 'success' ) {

					$strNaverId		= 'naver_'.$strArrMemInfo['response']['id'];
					$strNaverName	= $strArrMemInfo['response']['name'];
					$strNaverEmail	= $strArrMemInfo['response']['email'];
					//$strNaverGender	= $strArrMemInfo['response']['gender'];
					/* - F: 여성 - M: 남성 - U: 확인불가*/

					$this->load->model(DBNAME.'/member_tb_model');

					if($this->member_tb_model->get(array('m_id' => $strNaverId))->isSuccess()) {
						
						$dbrow = $this->member_tb_model->getData();

						//유료회원 체크
						$is_paid = FALSE;
						if($this->_paycheck($strNaverId)) {
							$is_paid = TRUE;
						}
						else {
							if($dbrow['m_level'] == 9) {
								$is_paid = TRUE;
							}
						}

						$sess_data = array(
							//'is_paid' => ($dbrow['m_paid']=='Y') ? TRUE:FALSE, 
							'is_paid' => $is_paid, 
							'user_id' => $strNaverId,
							'user_email' => $dbrow['m_email'],
							'user_name' => $dbrow['m_name'],
							'user_phone' => $dbrow['m_phone'],
							'user_level' => $dbrow['m_level'],
							'user_auto_pay' => $dbrow['m_auto_pay'],
							//'cho_user_id' =>							
						);
						$this->set_session($sess_data);

						$this->common->write_login_log($strNaverId);

						$update_params = array(
							'm_login_date' => date('Y-m-d H:i:s')
						);

	                    $this->member_tb_model->doUpdate($strNaverId , $update_params);
						
						$return_url = explode('|', $state); 
						$return_url = $return_url[1];

						$this->common->locationhref(HOME_URL.'/'.$return_url);
						exit;
					}
					else {

						//탈퇴회원체크
						if($this->_withdrawal_check($strNaverId)) {
							$this->common->alert('이미 탈퇴한 회원입니다.');
							$this->common->locationhref('/');
							exit;
						}

						$redirect_url = '/member/login_idchk?ue='.$strNaverEmail."&up=N";
						$this->common->locationhref($redirect_url);
						exit;
					}
				}
				else {
					$this->common->alert('네이버 로그인이 정상적으로 진행되지 않았습니다.[4]');
					$this->common->locationhref('/member/login');
					exit;
				}			
			}
			else {
				$this->common->alert('네이버 로그인이 정상적으로 진행되지 않았습니다.[3]');
				$this->common->locationhref('/member/login');
				exit;
			}
		} else {
            $this->common->alert('네이버 로그인이 정상적으로 진행되지 않았습니다.[2]');
			$this->common->locationhref('/member/login');
			exit;
		}
	}

	public function login_idchk() {

		$user_email = $this->input->get('ue');
		$user_path = $this->input->get('up');

		if(isset($user_email) && isset($user_path)) {
			$data = array();

			$this->header_data['header_template'] = '11';
			$this->header_data['head_url'] = '/';
			$this->header_data['show_alarm'] = false;

			$this->header_data['back_url'] = '/';
	        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
			$data['user_email'] = $user_email;
			$data['user_path'] = $user_path;
			$this->_view('/member/login_idchk', $data);
		}
		else {
            $this->common->alert('로그인이 정상적으로 진행되지 않았습니다.[check]');
			$this->common->locationhref('/member/login');
			exit;
		}
	}

	public function request_free() {
		return;
		$new_phone_number = $this->input->post('new_phone_number');
		if($this->session->userdata('user_id') != '' && $new_phone_number != '') {
			
			$this->load->model(DBNAME.'/member_tb_model');

			$update_params = array(
				'm_phone' => str_replace('-', '', $new_phone_number),
				'm_push_ticker' => 'Y',
				'm_push_service' => 'Y',
				'm_push_date' => date('Y-m-d H:i:s'),
			);

			$this->member_tb_model->doUpdate($this->session->userdata('user_id'), $update_params);
			$this->_freepay($this->session->userdata('user_id'));

			$this->session->set_userdata('user_phone', str_replace('-','',$new_phone_number));

			$this->common->alert('3일 무료체험 신청이 완료되었습니다.');
			$this->common->locationhref('/');
			exit;
		}
		else {
			$this->common->locationhref('/');
			exit;
		}
	}

	private function _freepay($user_id) {
		
		if(isset($user_id) && $user_id !='') {

			$this->load->model(DBNAME.'/freepay_tb_model');

			$params = array();
			$params['=']['fp_code'] = SRV_CODE;
			$params['=']['fp_user_id'] = $user_id;
			$params['slavedb'] = true;
			$user_count = $this->freepay_tb_model->getCount($params)->getData();

			$freeday = 2;

			if($this->session->userdata('partner_code') == 'CSPART015' || $this->session->userdata('partner_code') == 'CSPART016') {
				$freeday = 6;
			}

			if($user_count==0) {
				$fp_end_date = date('Ymd', time()+86400*$freeday);
				$params = array(
					'fp_user_id' => $user_id,
					'fp_code' => SRV_CODE,
					'fp_start_date' => date('Ymd'),
					'fp_end_date' => $fp_end_date,
					'fp_date' => date('Y-m-d H:i:s'),
				);

				$this->freepay_tb_model->doInsert($params);

				//$free_notice = '<a href="/main/service_prm">프리미엄 3일 무료 체험</a> 중 입니다(~'.date('m/d', strtotime($fp_end_date)).'까지)';
				$free_notice = '알림 서비스 수신을 위해 <b>휴대폰번호를 등록</b>해주세요. <a href="/member/info">[등록]</a>';
				$this->session->set_userdata('free_notice', $free_notice);
				$this->session->set_userdata('is_paid', TRUE);
			}
/*
			if($user_count>0) {
				$this->common->alert('이미 3일 무료체험을 사용하셨습니다.');
				$this->common->locationhref('/');
				exit;
			}
			else {
				$fp_end_date = date('Ymd', time()+86400*2);
				$params = array(
					'fp_user_id' => $user_id,
					'fp_code' => SRV_CODE,
					'fp_start_date' => date('Ymd'),
					'fp_end_date' => $fp_end_date,
					'fp_date' => date('Y-m-d H:i:s'),
				);

				$this->freepay_tb_model->doInsert($params);

				//$free_notice = '<a href="/main/service_prm">프리미엄 3일 무료 체험</a> 중 입니다(~'.date('m/d', strtotime($fp_end_date)).'까지)';
				$free_notice = '알림 서비스 수신을 위해 <b>휴대폰번호를 등록</b>해주세요.<a href="/member/info">[등록]</a>';
				$this->session->set_userdata('free_notice', $free_notice);
				$this->session->set_userdata('is_paid', TRUE);
			}
*/
		}
	}

	public function login_complete() {

		$refresh_token = $this->session->userdata('refresh_token');
        $user_path = $this->input->get('up');
        $push_marketing = $this->input->get('push_marketing');

		if( isset($refresh_token) &&  $refresh_token != '' && in_array($user_path, array('N', 'K'))) {

			if( $user_path == 'N' ) {

				$url = "https://nid.naver.com/oauth2.0/token?grant_type=refresh_token&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&refresh_token=".$refresh_token;

				$is_post = false;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, $is_post);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$headers = array();
				$response = curl_exec ($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close ($ch);

				if($status_code == 200) {
				
					//회원정보 가져오기
					$strArrToken = json_decode($response, true);

					$header = "Bearer ".$strArrToken['access_token']; // Bearer 다음에 공백 추가

					$url = "https://openapi.naver.com/v1/nid/me";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, $is_post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$headers = array();
					$headers[] = "Authorization: ".$header;
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					$response = curl_exec ($ch);
					$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close ($ch);

					if($status_code == 200) {
						$strArrMemInfo = json_decode($response, true);
						if( $strArrMemInfo['resultcode'] == '00' && $strArrMemInfo['message'] == 'success' ) {

							$strNaverId		= 'naver_'.$strArrMemInfo['response']['id'];
							$strNaverName	= $strArrMemInfo['response']['name'];
							$strNaverEmail	= $strArrMemInfo['response']['email'];
							$strNaverGender	= $strArrMemInfo['response']['gender'];
							/* - F: 여성 - M: 남성 - U: 확인불가 */
							if( !isset($strNaverGender) && $strNaverGender == '' ) $strNaverGender = 'U';

							//탈퇴회원체크
							if($this->_withdrawal_check($strNaverId)) {
								$this->common->alert('이미 탈퇴한 회원입니다.');
								$this->common->locationhref('/');
								exit;
							}

							//파트너코드 체크
							$partner_code = array();
							$strState = $this->session->userdata('cs_state_code');
							$partner_code = explode('|', $strState);

							$this->load->model(DBNAME.'/member_tb_model');

							$params = array(
								'm_id' => $strNaverId,
								//'m_pw' =>  $nt_table,
								'm_name' => $strNaverName,
								'm_email' => $strNaverEmail,
								'm_gender' => $strNaverGender,
								'm_path' => 'N',
								'm_pt_code' => $partner_code[2],
								'm_reg_date' => date('Y-m-d H:i:s'),
								'm_mod_date' => date('Y-m-d H:i:s'),
								'm_login_date' => date('Y-m-d H:i:s'),
							);

							if($push_marketing == 'Y') {
								$params['m_push_marketing'] = 'Y';
								$params['m_push_date'] = date('Y-m-d H:i:s');
							}

							/*
							$npay_click_key = $this->session->userdata('nPayClickKey');
							if(isset($npay_click_key) && $npay_click_key !='') {
								$params['m_pt_code'] = 'CSPART013';
							}
							*/

							if($this->member_tb_model->doInsert($params)->isSuccess()) {

								//$this->_freepay($strNaverId);

								$sess_data = array(
									'user_id' => $strNaverId,
									'user_email' => $strNaverEmail,
									'user_name' => $strNaverName,
									//'cho_user_id' =>
								);
								$this->set_session($sess_data);

								$this->common->write_login_log($strNaverId);

								/*
								if(isset($npay_click_key) && $npay_click_key !='') {
									$this->common->locationhref('/payment/pay_free');
									exit;
								}
								*/

								$data = array();
								$this->header_data['header_template'] = '11';
								$this->header_data['head_url'] = '/';
								$this->header_data['show_alarm'] = false;

								$this->header_data['back_url'] = '';
						        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
								$data['is_iphone'] = $this->is_iphone;

								$this->_freepay($this->session->userdata('user_id'));
								$data['part'] = $this->session->userdata('partner_code');
								$this->_view('/member/login_complete', $data);
							} 
							else {
								$this->common->alert('회원 가입에 실패했습니다[N4].');
								$this->common->locationhref('/member/login');
								exit;
							}
						}
						else {
							$this->common->alert('회원 가입에 실패했습니다[N3].');
							$this->common->locationhref('/member/login');
							exit;
						}
					}
					else {
						$this->common->alert('회원 가입에 실패했습니다[N2].');
						$this->common->locationhref('/member/login');
						exit;
					}
				}
				else {
					$this->common->alert('회원 가입에 실패했습니다[N1].');
					$this->common->locationhref('/member/login');
					exit;
				}
			}
			else {

				$params = sprintf( 'grant_type=refresh_token&client_id=%s&client_secret=%s&refresh_token=%s', KAKAO_CLIENT_ID, KAKAO_CLIENT_SECRET, $refresh_token);
				$TOKEN_API_URL = "https://kauth.kakao.com/oauth/token";
		
				$opts = array( 
					CURLOPT_URL => $TOKEN_API_URL, 
					CURLOPT_SSL_VERIFYPEER => false, 
					CURLOPT_SSLVERSION => 1, // TLS 
					CURLOPT_POST => true, 
					CURLOPT_POSTFIELDS => $params, 
					CURLOPT_RETURNTRANSFER => true, 
					CURLOPT_HEADER => false
				); 
		
				$curlSession = curl_init(); 
				curl_setopt_array($curlSession, $opts);
				$accessTokenJson = curl_exec($curlSession); 
				curl_close($curlSession); 

				$responseArr = json_decode($accessTokenJson, true); 
		
				//$_SESSION['kakao_access_token'] = $responseArr['access_token']; 
				//$_SESSION['kakao_refresh_token'] = $responseArr['refresh_token']; 
				//$_SESSION['kakao_refresh_token_expires_in'] = $responseArr['refresh_token_expires_in']; 
				//$this->session->set_userdata('refresh_token', $responseArr['refresh_token']);

				//사용자 정보 가저오기 
				$USER_API_URL= "https://kapi.kakao.com/v2/user/me";
					
				$opts = array( 
					CURLOPT_URL => $USER_API_URL, 
					CURLOPT_SSL_VERIFYPEER => false, 
					CURLOPT_SSLVERSION => 1, 
					CURLOPT_POST => true, 
					CURLOPT_POSTFIELDS => false, 
					CURLOPT_RETURNTRANSFER => true, 
					CURLOPT_HTTPHEADER => array( "Authorization: Bearer " . $responseArr['access_token'] ) 
				); 
			
				$curlSession = curl_init();
				curl_setopt_array($curlSession, $opts); 
				$accessUserJson = curl_exec($curlSession);
				curl_close($curlSession); 
			
				$me_responseArr = json_decode($accessUserJson, true); 

				if($me_responseArr['id']) {

					$strKakaoId		= 'kakao_'.$me_responseArr['id'];
					$strKakaoEmail	= $me_responseArr['kakao_account']['email'];
					$strKakaoName	= $me_responseArr['kakao_account']['profile']['nickname'];
					$strKakaoGender = $me_responseArr['kakao_account']['gender']; // 성별 female/male
					if($strKakaoGender=='male') $strKakaoGender = 'M';
					else if($strKakaoGender=='female')  $strKakaoGender = 'F';
					else  $strKakaoGender = 'U';

					//탈퇴회원체크
					if($this->_withdrawal_check($strKakaoId)) {
						$this->common->alert('이미 탈퇴한 회원입니다.');
						$this->common->locationhref('/');
						exit;
					}

					//파트너코드 체크
					$partner_code = array();
					$strState = $this->session->userdata('cs_state_code');
					$partner_code = explode('|', $strState);
					
					$this->load->model(DBNAME.'/member_tb_model');

					$params = array(
						'm_id' => $strKakaoId,
						//'m_pw' =>  $nt_table,
						'm_name' => $strKakaoName,
						'm_email' => $strKakaoEmail,
						'm_gender' => $strKakaoGender,
						'm_path' => 'K',
						'm_pt_code' => $partner_code[2],
						'm_reg_date' => date('Y-m-d H:i:s'),
						'm_mod_date' => date('Y-m-d H:i:s'),
						'm_login_date' => date('Y-m-d H:i:s'),
					);

					if($push_marketing == 'Y') {
						$params['m_push_marketing'] = 'Y';
						$params['m_push_date'] = date('Y-m-d H:i:s');
					}
					
					/*
					$npay_click_key = $this->session->userdata('nPayClickKey');
					if(isset($npay_click_key) && $npay_click_key !='') {
						$params['m_pt_code'] = 'CSPART013';
					}
					*/

					if($this->member_tb_model->doInsert($params)->isSuccess()) {

						//$this->_freepay($strKakaoId);

						$sess_data = array(
							'user_id' => $strKakaoId,
							'user_email' => $strKakaoEmail,
							'user_name' => $strKakaoName,
							//'cho_user_id' =>
						);

						$this->set_session($sess_data);
						$this->common->write_login_log($strKakaoId);

						/*
						if(isset($npay_click_key) && $npay_click_key !='') {
							$this->common->locationhref('/payment/pay_free');
							exit;
						}
						*/

						$data = array();
						$this->header_data['header_template'] = '11';
						$this->header_data['head_url'] = '/';
						$this->header_data['show_alarm'] = false;

						$this->header_data['back_url'] = '';
				        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
						$data['is_iphone'] = $this->is_iphone;

						$this->_freepay($this->session->userdata('user_id'));
						$data['part'] = $this->session->userdata('partner_code');
						$this->_view('/member/login_complete', $data);
					} 
					else {
						$this->common->alert('회원 가입에 실패했습니다[K2].');
						$this->common->locationhref('/member/login');
						exit;
					}				
				}
				else {
					$this->common->alert('회원 가입에 실패했습니다[K1].');
					$this->common->locationhref('/member/login');
					exit;
				}
			}
		}
		else {
            $this->common->alert('회원 가입에 실패했습니다.');
			$this->common->locationhref('/member/login');
			exit;
		}	
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$this->common->locationhref('/');
		exit;
	}

	public function login()
	{
        if($this->session->userdata('is_login')) {
            $this->common->alert('이미 로그인 되어 있습니다.');
			$this->common->locationhref('/');
			exit;
		}
		//https://capdev.choicestock.co.kr/member/login?pt=partner001
		//echo '<pre>'; print_r($this->session->all_userdata());

		$data = array();

        $this->header_data['header_template'] = '11';
        $this->header_data['head_url'] = '/';
        $this->header_data['show_alarm'] = false;

        $this->header_data['back_url'] = '/';
        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';

	    $naver_redirectURI = urlencode(HOME_URL."/member/nauth");
	    $kakao_redirectURI = urlencode(HOME_URL."/member/oauth");

		$return_page = $this->input->get('ru');

		$partner_code = $this->session->userdata('partner_code');
		//$partner_code = $this->input->get('pt');

		$state = md5(microtime().mt_rand()).'|'.$return_page.'|'.$partner_code;

		$this->session->unset_userdata('cs_state_code');
		$this->session->set_userdata('cs_state_code', $state);

		//delete_cookie('cs_state_code');
		//set_cookie('cs_state_code', $state, time()+500, '.choicestock.co.kr' );

		//echo 'cookie===>'.get_cookie('nv_state_code');
		$naver_apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".NAVER_CLIENT_ID."&redirect_uri=".$naver_redirectURI."&state=".$state;
		$kakao_apiURL = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=".KAKAO_CLIENT_ID."&redirect_uri=".$kakao_redirectURI."&state=".$state;

		$data['naver_login_url'] = $naver_apiURL;
		$data['kakao_login_url'] = $kakao_apiURL;

		$this->_view('/member/login', $data);
	}

	public function save_info() {

        $this->loginCheck();

        $user_id = $this->session->userdata('user_id');
		$m_phone = $this->input->post('new_phone_number'); //핸드폰 번호
		$m_push_marketing = $this->input->post('m_push_check'); //마케팅 수신 동의

		if(isset($m_phone) && $m_phone!='') {
			$update_params = array(
				'm_phone' => str_replace('-', '', $m_phone),
				'm_push_ticker' => 'Y',
				'm_push_service' => 'Y',
				'm_push_date' => date('Y-m-d H:i:s'),
			);
		}

		//if($m_push_marketing == 'Y') {
		$update_params['m_push_marketing'] =  $m_push_marketing;
		//}

		if(is_array($update_params) && sizeof($update_params) > 0) {

			$this->load->model(DBNAME.'/member_tb_model');
			$this->member_tb_model->doUpdate($user_id, $update_params);

			if($update_params['m_phone'] != '') {
				$this->load->model(DBNAME.'/freepay_tb_model');
				$free_params = array();
				$free_params['=']['fp_user_id'] = $user_id;
				$free_params['=']['fp_code'] = SRV_CODE;
				$free_params['>=']['fp_end_date'] = date('Ymd');
				$free_extra = array(
					'fields' => '*',
					'slavedb' => true
				);

				$freepay_data = array();
				$freepay_data = array_shift($this->freepay_tb_model->getList($free_params, $free_extra)->getData());

				$s_date = strtotime($freepay_data['fp_start_date']);
				$e_date = strtotime($freepay_data['fp_end_date']); 
				$during =  ceil(($e_date - $s_date)/(60*60*24))+1; // 일차이

				if(sizeof($freepay_data) > 0) {				
					$free_notice = '<a href="/main/service_prm">프리미엄 '.$during.'일 무료 체험</a> 중 입니다(~'.date('m/d', strtotime($freepay_data['fp_end_date'])).'까지)';
					$this->session->set_userdata('free_notice', $free_notice);
				}
			}
		}

		$this->common->locationhref('/');
	}

// 푸시테스트
	public function push_test($template) {
/*
$nickname = "이지코드이지코드";
$nickname2 = "abcedfe";
$nickname3 = "Pbcedfe";
echo iconv_substr($nickname, 0, 1, "utf-8");
exit;
*/
		
		$push_info = array();
		$push_info['template']= $template; 
		$push_info['push_type']= 'at'; 
		$push_info['from']= PUSH_FROM; 
		$push_info['to']= '01076121487'; 

		//SMS일 경우
		//$push_info['sms_message'] = '내용입력';

		//$push_info['at_message']['replace_0'] = '미국주식를 탐구해 보세요';
		//$push_info['at_message']['replace_1'] = 'H&R 블록 예상 EPS 2.82';

		//$push_info['at_message']['replace_0'] = '종목분석';
		//$push_info['at_message']['replace_1'] = '신규 종목분석이 등록되었습니다. - 한글종목명(AT)';

		//$push_info['at_message']['replace_0'] = '종목추천';
		//$push_info['at_message']['replace_1'] = '한글종목명(AT)이(가) 목표가에 도달했습니다.';

		//$auth_no = str_pad(mt_rand(0,9999),4,'0');
		//$push_info['at_message']['replace_0'] = '초이스스탁US';
		//$push_info['at_message']['replace_1'] = $auth_no;

		$push_info['at_message']['replace_0'] = '종목분석';
		$push_info['at_message']['replace_1'] = '신규 종목분석이 등록되었습니다. 신규 종목분석이 등록되었습니다. 신규 종목분석이 등록되었습니다.';

		$push_info['count']= '2'; 

		$result = $this->send_push($push_info);	
		echo '<pre>'; print_r($result);
	}

	public function check_authno() {
        if($this->input->is_ajax_request() === FALSE) {
            $this->common->alert('잘못된 접근입니다.');
			$this->common->locationhref('/');
			exit;
		}

		$this->loginCheck();

		$auth_no = $this->input->get('no');

		if(isset($auth_no) && $auth_no) {

			if($auth_no == $this->session->userdata('auth_no')) {
				$success = TRUE;
				$result = array('success' => $success, 'msg' => '휴대폰 번호를 인증하였습니다.');
				exit(json_encode($result));
			}
			else {
				$error = TRUE;
				$result = array('error' => $error, 'msg' => '휴대폰 인증코드가 일치하지 않습니다.');
				exit(json_encode($result));
			}
		}
		else {
			$error = TRUE;
			$result = array('error' => $error, 'msg' => '인증번호가 입력되지 않았습니다.');
			exit(json_encode($result));
		}

	}

	public function send_authno() {

        if($this->input->is_ajax_request() === FALSE) {
            $this->common->alert('잘못된 접근입니다.');
			$this->common->locationhref('/');
			exit;
		}

		$this->loginCheck();

		$phone_no = $this->input->get('no');

		if(isset($phone_no) && $phone_no) {
			
			//네이버페이 중복 체크
			$this->load->model(DBNAME.'/member_tb_model');
	        $user_id = $this->session->userdata('user_id');

			if($this->member_tb_model->get(array('m_id' => $user_id))->isSuccess()) {
				$user_info = $this->member_tb_model->getData();
				if($user_info['m_pt_code'] == 'CSPART013') {

					//핸드폰 번호 체크
					$params = array();
					$params['=']['m_phone'] = str_replace('-', '', $phone_no);
					$params['!=']['m_id'] = $user_id;
					$params['=']['m_auto_pay'] = 'Y';
					$params['slavedb'] = true;
					$user_count = $this->member_tb_model->getCount($params)->getData();

					if($user_count>0) {
						$error = TRUE;
						$result = array('error' => $error, 'msg' => 'dup');
						exit(json_encode($result));
					}
				}
			}

			$push_info = array();
			$push_info['template']= '7'; 
			$push_info['push_type']= 'at'; 
			$push_info['from']= PUSH_FROM; 
			$push_info['to']= str_replace('-', '', $phone_no); 

			$auth_no = str_pad(mt_rand(0,9999),4,'0');
			$push_info['at_message']['replace_0'] = '초이스스탁US';
			$push_info['at_message']['replace_1'] = $auth_no;
			$push_info['count']= '2'; 

			$result = $this->send_push($push_info);	
			/*
			Array
			(
				[code] => 1000
				[description] => ok
				[refkey] => CS01at305SuJw20200529153822
				[messagekey] => 200529153822889#at026972dataF1xq
			)
			*/
			//print_r(json_decode($result));
			//{"code":1000,"description":"ok","refkey":"CS01at3982vpN20200529145610","messagekey":"200529145611124#at027014dataqo9b"}
				//$success = TRUE;
				//$result = array('success' => $success, 'msg' => $result['code'] );
				//exit(json_encode($result));
			$this->output->set_content_type('application/json');
			
			//$auth_no = '1111';
			if($result['code'] == '1000') {
				$success = TRUE;
				$result = array('success' => $success);
				$this->session->set_userdata('auth_no', $auth_no);
				exit(json_encode($result));
			}
			else {
				$error = TRUE;
				$result = array('error' => $error, 'msg' => '문자전송이 실패했습니다.');
				exit(json_encode($result));
			}
		}
		else {
			$error = TRUE;
			$result = array('error' => $error, 'msg' => '핸드폰 번호가 잘못 입력되었습니다.');
			exit(json_encode($result));
		}
	}

	public function info() {

        $this->loginCheck();

        $user_id = $this->session->userdata('user_id');

		$this->load->model(DBNAME.'/member_tb_model');

		if($this->member_tb_model->get(array('m_id' => $user_id))->isSuccess()) {

			$user_info = $this->member_tb_model->getData();

			$data = array();
			$this->header_data['header_template'] = '11';
			$this->header_data['show_alarm'] = false;
			//$this->header_data['back_url'] = '/member/menu';
			$this->header_data['back_url'] = '/';
			$this->header_data['head_title'] = '내 정보';

	        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
			$data['user_name'] = $user_info['m_name'];
			$data['user_email'] = $user_info['m_email'];
			$data['user_path'] = $user_info['m_path'];
			$data['user_gender'] = $user_info['m_gender'];
			$data['user_phone'] = $user_info['m_phone'];
			$data['user_marketing'] = $user_info['m_push_marketing'];

			//$this->load->library('user_agent');
			//$is_iphone = FALSE;
			//if($this->agent->is_mobile('iphone')) {
			//	$is_iphone = TRUE;
			//}

			$data['is_iphone'] = $this->is_iphone;

			$data['info_submenu'] = 'info';
			$this->_view('/member/info', $data);
		}
		else {
            $this->common->alert('회원 정보가 없습니다.');
			$this->common->locationhref('/');
			exit;
		}
	}

	public function paylist() {

        $this->loginCheck();

		$this->load->model(DBNAME.'/pay_tb_model');

        $params = array();
        $params['=']['p_user_id'] = $this->session->userdata('user_id');
        $extra = array(
            'fields' => '*',
            'order_by' => 'p_id DESC',
			'slavedb' => true
        );

		$paylist = array();
		$autopay = array();

		$today = date('Ymd');
        $paylist = $this->pay_tb_model->getList($params, $extra)->getData();
		if(sizeof($paylist) > 0) {
			//if($paylist[0]['p_type'] == 'A') {
			if($paylist[0]['p_type'] == 'A' && in_array($paylist[0]['p_status'], array('P', 'W')) && $paylist[0]['p_end_date'] >= date('Ymd')) {
				$autopay = array_shift($paylist);

				//카드변경일 체크
				$autopay['is_update'] = TRUE;

				if($autopay['p_at_day'] == $today || $autopay['p_card_code'] == 'PA') {
					$autopay['is_update'] = FALSE;
				}
				else if( substr($autopay['p_at_day'], 0, 6) == substr($today, 0, 6) && $autopay['p_at_day'] > $today) {
					
					$check_day = date('Y-m-d');
					$lastday = date("Ymt", strtotime($check_day));
					//오늘이 마지막 날이면
					if( $today == $lastday && $lastday < $autopay['p_at_day'] ) {
						$autopay['is_update'] = FALSE;
					}
				}
			}
			//else if($paylist[0]['p_type'] == 'G' && $paylist[0]['p_status'] == 'P') {
			//	$termpay = array_shift($paylist);
			//}

			//결제 취소 시 환불계좌 처리
			$this->load->model(DBNAME.'/bankcard_tb_model');

			$bank_params = array();
			$bank_params['=']['bc_type'] = 'B';
			$bank_extra = array(
				'fields' => 'bc_code, bc_name',
				'order_by' => 'bc_code ASC',
				'slavedb' => true
			);

			$bank_list = array();
			$bank_list = $this->bankcard_tb_model->getList($bank_params, $bank_extra)->getData();
		}
		//echo '<pre>'; print_r($paylist);

		//3일 무료 회원 데이터
		$this->load->model(DBNAME.'/freepay_tb_model');

		$free_params = array();
        $free_params['=']['fp_user_id'] = $this->session->userdata('user_id');
		$free_params['=']['fp_code'] = SRV_CODE;
		$free_params['>=']['fp_end_date'] = date('Ymd');
        $free_extra = array(
            'fields' => '*',
			'slavedb' => true
        );

		$freepay_data = array();
		$freepay_data = array_shift($this->freepay_tb_model->getList($free_params, $free_extra)->getData());

		$data = array();
        $this->header_data['header_template'] = '11';
        $this->header_data['show_alarm'] = false;

        $this->header_data['back_url'] = '/';
        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
        $this->header_data['head_title'] = '내 정보';
		
		$data['info_submenu'] = 'paylist';
		$data['termpay'] = $termpay;
		$data['autopay'] = $autopay;
		$data['paylist'] = $paylist;
		$data['pay_name'] = $this->pay_name;
		$data['pay_method_name'] = $this->pay_method_name;
		$data['srv_name'] = SRV_NAME;
		$data['pay_info'] = $this->pay_info;
		$data['bank_list'] = $bank_list;
		$data['freepay_data'] = $freepay_data;
		$this->_view('/member/paylist', $data);
	}

    public function policy() {
        $this->header_data['header_template'] = '11';
        $this->header_data['show_alarm'] = false;
        $this->header_data['head_title'] = '개인정보처리방침';
        //$this->header_data['back_url'] = '-1';
        $this->header_data['back_url'] = '/';
        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
        $this->_view('/member/policy');
    }

    public function terms() {
        $this->header_data['header_template'] = '11';
        $this->header_data['show_alarm'] = false;
        $this->header_data['head_title'] = '이용약관';
        //$this->header_data['back_url'] = '-1';
        $this->header_data['back_url'] = '/';
        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
        $this->_view('/member/terms');
    }

    public function menu() {
        $this->header_data['header_template'] = '16';
        $this->header_data['show_alarm'] = TRUE;
        $this->header_data['head_title'] = '';
        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
        $this->_view('/member/menu');
    }

    public function notice() {
        $this->loginCheck();
	
        $user_id = $this->session->userdata('user_id');

		$this->load->model(DBNAME.'/member_tb_model');

		if($this->member_tb_model->get(array('m_id' => $user_id))->isSuccess()) {

			$user_info = $this->member_tb_model->getData();

			$data = array();
			$this->header_data['header_template'] = '11';
			$this->header_data['show_alarm'] = false;
			$this->header_data['head_title'] = '설정';
			$this->header_data['back_url'] = '/';
			//$this->header_data['back_url'] = '/member/menu';
			
	        $data['meta_title'] = '투자를 쉽고 편리하게 - 초이스스탁US';
			$data['m_push_ticker'] = $user_info['m_push_ticker'];
			$data['m_push_service'] = $user_info['m_push_service'];
			$data['m_push_marketing'] = $user_info['m_push_marketing'];
			$data['m_push_date'] = $user_info['m_push_date'];

			$this->_view('/member/notice', $data);
		}
		else {
            $this->common->alert('회원 정보가 없습니다.');
			$this->common->locationhref('/');
			exit;
		}
    }

	public function notice_setup($col='', $val='') {
	
        if($this->input->is_ajax_request() === FALSE) {
            $this->common->alert('잘못된 접근입니다.');
			$this->common->locationhref('/');
			exit;
		}

        if($this->session->userdata('is_login')) {
		
			if($col == '' || $val == '') {
				$result = array('error' => '알림 설정이 실패했습니다.[1]', 'res' => '2');
				exit(json_encode($result));
			}

			$this->output->set_content_type('application/json');

			$this->load->model(DBNAME.'/member_tb_model');
			$update_params = array(
				$col => $val,
				'm_push_date' => date('Y-m-d H:i:s')
			);

			if($this->member_tb_model->doUpdate($this->session->userdata('user_id'), $update_params)->isSuccess()) {
				$success = TRUE;
				$result = array('success' => $success, 'res' => date('Y. m/d'));
				exit(json_encode($result));
			}
			else {
				//업데이트 실패시
				$result = array('error' => '알림 설정이 실패했습니다.[2]', 'res' => '2');
				exit(json_encode($result));
			}
		}
		else {
			$result = array('error' => '회원정보가 없습니다.', 'res' => '1');
			exit(json_encode($result));
		}
	}

	//이메일 보내기 test
	public function sendmail() {

		$this->load->library('email');
        // 전송할 데이터가 html 문서임을 옵션으로 설정
        $this->email->initialize(array('mailtype'=>'html'));

		// 송신자의 이메일과 이름 정보
		$this->email->from('high@datahero.co.kr', 'master');            
		// 이메이 제목
		$this->email->subject('이메일 전송 test 입니다.');
		// 이메일 본문
		$this->email->message('<a href="https:www.choicestock.co.kr">Click!!</a>');
		// 이메일 수신자.
		//$this->email->to($user->email);
		//$user_email = 'cantatoure@naver.com';
		$user_email = 'cantatoure@daum.net';
		$this->email->to($user_email);
		// 이메일 발송
		if($this->email->send()) {
			echo 'ok ['.date('Y-m-d H:i:s').']';
		}
		else {
			echo 'error ['.date('Y-m-d H:i:s').']';
		}
	}

	//유료회원 리스트
	public function payuser() {
        
		$this->loginCheck();

		if($this->session->userdata('user_level') != '9') {

			$this->common->locationhref('/');
			exit;
		}
		$this->load->model(DBNAME.'/pay_tb_model');
		$params = array();
		$params['=']['p_status'] = 'P';
		$params['>=']['p_end_date'] = date('Ymd');
		$params['=']['m_level'] = '0';
        $params['join']['member_tb'] = 'p_user_id = m_id';

		$extra = array(
			'fields' => 'p_user_name, m_phone, p_end_date, m_pt_code, m_reg_date, p_pt_code, p_coupon, p_at_count, p_type, p_card_no, p_at_billkey, p_at_day, p_cancel_date, p_at_eventprice, p_pay, p_card_code',
			'slavedb' => true,
			'order_by' => 'p_end_date asc'
		);

        $payuser_list = $this->pay_tb_model->getList($params, $extra)->getData();

//echo '<pre>'; print_r($payuser_list); exit;

		$this->load->model(DBNAME.'/freepay_tb_model');

		$free_params = array();
		$free_params['=']['fp_code'] = SRV_CODE;
		$free_params['>=']['fp_end_date'] = date('Ymd');
		$free_params['=']['m_paid'] = 'N';
		//$free_params['!=']['m_phone'] = '';
		$free_params['join']['member_tb'] = 'fp_user_id = m_id';

		$free_extra = array(
			'fields' => 'm_name, m_phone, m_reg_date, fp_end_date, m_pt_code',
			'slavedb' => true,
			'order_by' => 'fp_end_date asc'
		);

		$freepay_list = array();
		$freepay_list = $this->freepay_tb_model->getList($free_params, $free_extra)->getData();

		$this->load->model(DBNAME.'/member_tb_model');
		/* 일반회원 */
		$user_params = array();
		$user_params['>=']['m_reg_date'] = date('Y-m-d 00:00:00', strtotime('-3 days'));
		$user_params['=']['m_paid'] = 'N';

		$user_extra = array(
			'fields' => 'm_id, m_name, m_phone, m_reg_date, m_pt_code',
			'slavedb' => true,
			'order_by' => 'm_reg_date desc'
		);

		$user_3day_list = array();
		$user_3day_list = $this->member_tb_model->getList($user_params, $user_extra)->getData();

		$user_list = array();
		foreach($user_3day_list as $key=>$val) {
			if(!$this->freepay_tb_model->get(array('fp_user_id ' => $val['m_id']))->isSuccess()) {
				$user_list[] = $val;
			}
		}

		//echo '<pre>'; print_r($user_list); exit;

		echo date('Y년 m월 d일').', 총 유료회원 : '.number_format(count($payuser_list)+count($freepay_list)).'명 (3일무료 : '.count($freepay_list).' 명)<br><br>';
		
		echo '<table><tr><td><table border=1>';

		foreach($payuser_list as $key => $val) {
			if($val['p_user_name']=='') $val['p_user_name'] = '&nbsp;';
			echo '<tr><td>'.$val['p_user_name'].'</td><td>'.substr($val['m_reg_date'], 0, 10).'</td></tr>';
		}

		foreach($freepay_list as $key => $val) {
			if($val['m_name']=='') $val['m_name'] = '&nbsp;';
			echo '<tr><td bgcolor="#DEDEDE">'.$val['m_name'].'</td><td bgcolor="#DEDEDE">'.substr($val['m_reg_date'], 0, 10).'</td></tr>';
		}


		foreach($user_list as $key => $val) {
			if($val['m_name']=='') $val['m_name'] = '&nbsp;';
			echo '<tr><td bgcolor="#DEDEFF">'.$val['m_name'].'</td><td bgcolor="#DEDEFF">'.substr($val['m_reg_date'], 0, 10).'</td></tr>';
		}
		
		echo '</table></td><td><table border=1>';

		foreach($payuser_list as $key => $val) {
			if($val['m_phone']=='') $val['m_phone'] = 'N/A';
			echo '<tr><td>'.$val['m_phone'].'</td></tr>';
		}

		foreach($freepay_list as $key => $val) {
			if($val['m_phone']=='') $val['m_phone'] = 'N/A';
			echo '<tr><td bgcolor="#DEDEDE">'.$val['m_phone'].'</td></tr>';
		}

		foreach($user_list as $key => $val) {
			if($val['m_phone']=='') $val['m_phone'] = 'N/A';
			echo '<tr><td bgcolor="#DEDEFF">'.$val['m_phone'].'</td></tr>';
		}

		echo '</table></td><td><table border=1>';

		foreach($payuser_list as $key => $val) {
			if($val['m_pt_code'] !='' && ($val['m_pt_code'] == $val['m_pt_code'])) {
				$m_pt_code = $val['m_pt_code'];
				if($m_pt_code=='CSPART015') $m_pt_code = '미주미';
			}
			else {
				$m_pt_code = '데이터히어로';
			}

			if(substr($val['p_coupon'],0,3) == 'WDZ') {
				$p_coupon = '와디즈';			
			}
			/*
			else if(substr($val['p_coupon'],0,3) == 'STO') {
				$p_coupon = '증권사';			
			}
			else if(substr($val['p_coupon'],0,3) == 'CSP') {
				$p_coupon = '제휴사';			
			}
			else if(substr($val['p_coupon'],0,2) == 'C2') {
				$p_coupon = '프로모션';			
			}
			else if(substr($val['p_coupon'],0,3) == 'CHO') {
				$p_coupon = '홍보용';			
			}
			else if(substr($val['p_coupon'],0,3) == 'UPS') {
				$p_coupon = '외부업체';			
			}
			*/
			else {
				$p_coupon = $val['p_coupon'];			
			}

			$twoweek = '';
			$event = '';
			$term = '';
			if($val['p_type']=='A' && $val['p_card_no'] != '' && $val['p_at_billkey'] != '' && $val['p_at_day'] != '' && $val['p_at_count'] < 1) {
				$twoweek = '2주체험';
			}

			if($val['p_type']=='A' && $val['p_card_no'] != '' && $val['p_at_billkey'] != '' && $val['p_at_eventprice'] == '990') {
				$event = '900원이벤트';
			}

			if($val['p_card_code']=='PA') {
				$event = '해외(페이팔)';
			}

			if($val['p_type']=='G') {
				$term = ($val['p_pay']=='2') ? '3개월 ':'6개월 ';
			}

			$cancel = '';
			if($val['p_at_day'] == '' && $val['p_cancel_date'] != '') $cancel = '(취소)';

			echo '<tr><td>'.date('Y-m-d', strtotime($val['p_end_date'])).$cancel.'</td><td>'.$m_pt_code.'</td><td>'.$term.$event.$twoweek.$p_coupon.'</td><td width=80 align=center>'.$val['p_at_count'].'</td></tr>';
		}

		foreach($freepay_list as $key => $val) {
			if($val['m_pt_code']=='CSPART015') $val['m_pt_code'] = '미주미';

			echo '<tr bgcolor="#DEDEDE"><td>'.date('Y-m-d', strtotime($val['fp_end_date'])).'</td><td>'.(($val['m_pt_code']=='')?'데이터히어로':$val['m_pt_code']).'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
		}

		foreach($user_list as $key => $val) {
			if($val['m_pt_code']=='CSPART015') $val['m_pt_code'] = '미주미';

			echo '<tr bgcolor="#DEDEFF"><td>&nbsp;</td><td>'.(($val['m_pt_code']=='')?'데이터히어로':$val['m_pt_code']).'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
		}

		echo '</table></td></tr></table>';
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */