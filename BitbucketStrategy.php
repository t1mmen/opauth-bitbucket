<?php
/**
 * Bitbucket strategy for Opauth
 *
 * Based on work by U-Zyn Chua (http://uzyn.com)
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright    Copyright © 2015 Timm Stokke (http://timm.stokke.me)
 * @link         http://opauth.org
 * @package      Opauth.BitbucketStrategy
 * @license      MIT License
 */


/**
 * Bitbucket strategy for Opauth
 *
 * @package			Opauth.Bitbucket
 */
class BitbucketStrategy extends OpauthStrategy {

	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = array('client_id', 'client_secret');

	/**
	 * Optional config keys, without predefining any default values.
	 */
	public $optionals = array();

	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 * eg. array('scope' => 'post');
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}oauth2callback'
	);

	/**
	 * Auth request
	 */
	public function request() {
		$url = 'https://bitbucket.org/site/oauth2/authorize';
		$params = array(
			'response_type' => 'code',
			'client_id' => $this->strategy['client_id'],
		);

		foreach ($this->optionals as $key) {
			if (!empty($this->strategy[$key])) $params[$key] = $this->strategy[$key];
		}

		$this->clientGet($url, $params);
	}

	/**
	 * Internal callback, after OAuth
	 */
	public function oauth2callback() {
		if (array_key_exists('code', $_GET) && !empty($_GET['code'])) {
			$code = $_GET['code'];
			$url = 'https://bitbucket.org/site/oauth2/access_token';

			$cred = base64_encode($this->strategy['client_id'].':'.$this->strategy['client_secret']);

			$params = array(
				'code' => $code,
				'client_id' => $this->strategy['client_id'],
				'client_secret' => $this->strategy['client_secret'],
				'grant_type' => 'authorization_code',
			);

			$options['http'] = array(
				'header' => "Authorization: Basic ".$cred."\r\nContent-type: application/x-www-form-urlencoded",
				'method' => 'POST',
				'content' => http_build_query($params, '', '&')
				);

			$response = $this->httpRequest($url, $options);
			$results = json_decode($response,true);

			if (!empty($results) && !empty($results['access_token'])) {

				$user = $this->user($results['access_token']);

				$this->auth = array(
					'uid' => $user['uuid'],
					'info' => array(
						'name' => $user['display_name'],
						'nickname' => $user['username'],
						'urls' => array(
							'website' => $user['website'],
						),
					),
					'credentials' => array(
						'token' => $results['access_token'],
						'refresh_token' => $results['refresh_token'],
					),
					'raw' => $user
				);


				$this->callback();
			}
			else {
				$error = array(
					'code' => 'access_token_error',
					'message' => 'Failed when attempting to obtain access token',
					'raw' => array(
						'response' => $response,
						'headers' => $headers
					)
				);

				$this->errorCallback($error);
			}
		}
		else {
			$error = array(
				'code' => 'oauth2callback_error',
				'raw' => $_GET
			);

			$this->errorCallback($error);
		}
	}

	/**
	 * Queries Bitbucket API for user info
	 *
	 * @param string $access_token
	 * @return array Parsed JSON results
	 */

	private function user($access_token) {

		$options['http']['header'] = "Content-Type: application/json";
		$options['http']['header'] .= "\r\nAccept: application/json";
		$options['http']['header'] .= "\r\nAuthorization: Bearer ".$access_token;

		$accountDetails = $this->serverGet('https://api.bitbucket.org/2.0/user', array(), $options);

		if (!empty($accountDetails)) {
			return $this->recursiveGetObjectVars(json_decode($accountDetails,true));
		}
		else {
			$error = array(
				'code' => 'userinfo_error',
				'message' => 'Failed when attempting to query Bitbucket API for user information',
				'raw' => array(
					'response' => $user,
					'headers' => $headers
				)
			);

			$this->errorCallback($error);
		}
	}

}
