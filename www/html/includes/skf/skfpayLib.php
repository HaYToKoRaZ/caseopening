<?php

define("SKFPAY_LIB_VERSION", "php1.0");

class CApiHelper
{
	function __construct($oauth)
	{
		$this->oauth_ = $oauth;
	}


	public function createGetRequestTokenRequest()
	{
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n"
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "getrequesttoken", false, $context);

		return $file;
	}

	public function createPaymentCreateRequest($payment_args)
	{
		$post_data = http_build_query($payment_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "paymentcreate", false, $context);

		return $file;
	}

	public function createPaymentConfirmRequest($payment_args)
	{
		$post_data = http_build_query($payment_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "paymentconfirm", false, $context);

		return $file;
	}

	public function createPaymentCancelRequest($payment_args)
	{
		$post_data = http_build_query($payment_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "paymentcancel", false, $context);

		return $file;
	}

	public function createPaymentAuthRequest($payment_args)
	{
		$post_data = http_build_query($payment_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "paymentauth", false, $context);

		return $file;
	}


	public function createGetPaymentRequest($payment_id)
	{
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n"
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "paymentget?paymentId=" . $payment_id, false, $context);

		return $file;
	}

	public function createTransferCreateRequest($transfer_args)
	{
		$post_data = http_build_query($transfer_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "transfercreate", false, $context);

		return $file;
	}

	public function createTransferConfirmRequest($transfer_args)
	{
		$post_data = http_build_query($transfer_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "transferconfirm", false, $context);

		return $file;
	}

	public function createTransferCancelRequest($transfer_args)
	{
		$post_data = http_build_query($transfer_args);
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n",
				"content"=>$post_data
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "transfercancel", false, $context);

		return $file;
	}

	public function createGetTransferRequest($transfer_id)
	{
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: OAuth " . $this->oauth_ . "\r\n" . "Skfpay-Lib-Version: " . SKFPAY_LIB_VERSION ."\r\n"
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents($this->api_uri_ . "transferget?transferId=" . $transfer_id, false, $context);

		return $file;
	}


	private $oauth_ = "";
	private $api_uri_ = "https://api.skfpay.com:8443/cApi/";
}


abstract class PaymentStatus
{
	const AWAITING_AUTH = 0;
	const AUTHED = 1;
	const CHARGED = 2;
	const CANCELED_BY_RECEIVER = 3;
	const CANCELED_BY_SENDER = 4;
}

class CPayment
{
	function __construct($payment_arr, $api_helper = null)
	{
		$this->raw_arr_ = $payment_arr["payment"];
		$this->api_helper_ = $api_helper;
	}


	public function setHelper($api_helper)
	{
		$this->api_helper_ = $api_helper;
	}

	public function getStatus()
	{
		return $this->raw_arr_["paymentStatus"];
	}

	public function toRawArr()
	{
		return $this->raw_arr_;
	}

	public function confirm()
	{
		$payment_args = array(
			"requestToken"=>$this->getRequestToken(),
			"amountChecksum"=>$this->raw_arr_["amount"],
			"senderChecksum"=>$this->raw_arr_["sender"],
			"paymentId"=>$this->raw_arr_["paymentId"]
		);
		$payment_args["requestToken"] = $this->getRequestToken();
		$payment_response = $payment_args["requestToken"] ? json_decode($this->api_helper_->createPaymentConfirmRequest($payment_args), true) : null;

		return ($payment_response && $payment_response["success"] ? true : false);
	}

	public function cancel()
	{
		$payment_args = array(
			"requestToken"=>$this->getRequestToken(),
			"paymentId"=>$this->raw_arr_["paymentId"]
		);
		$payment_args["requestToken"] = $this->getRequestToken();
		$payment_response = $payment_args["requestToken"] ? json_decode($this->api_helper_->createPaymentCancelRequest($payment_args), true) : null;

		return ($payment_response && $payment_response["success"] ? true : false);
	}

	public function auth()
	{
		$payment_args = array(
			"requestToken"=>$this->getRequestToken(),
			"paymentId"=>$this->raw_arr_["paymentId"]
		);
		$payment_args["requestToken"] = $this->getRequestToken();
		$payment_response = $payment_args["requestToken"] ? json_decode($this->api_helper_->createPaymentAuthRequest($payment_args), true) : null;
		if($payment_response && $payment_response["success"] ? true : false)
		{
			$this->refreshPayment();

			return true;
		}

		return false;
	}

	private function getRequestToken()
	{
		$token_response = json_decode($this->api_helper_->createGetRequestTokenRequest(), true);

		return ($token_response && $token_response["success"] ? $token_response["response"] : null);
	}

	private function refreshPayment()
	{
		$payment_response = json_decode($this->api_helper_->createGetPaymentRequest($this->raw_arr_["paymentId"]), true);
		$payment_arr = ($payment_response && $payment_response["success"] && $payment_response["response"] ? $payment_response["response"] : null);
		if($payment_arr)
		{
			$this->raw_arr_ = $payment_arr["payment"];

			return true;
		}

		return false;
	}


	private $raw_arr_ = null;
	private $api_helper_ = null;
}


abstract class TransferStatus
{
	const AWAITING_CONFIRMATION = 0;
	const CONFIRMED = 1;
	const CANCELED = 2;
}

class CTransfer
{
	function __construct($transfer_arr, $api_helper = null)
	{
		$this->raw_arr_ = $transfer_arr["transaction"];
		$this->api_helper_ = $api_helper;
	}


	public function setHelper($api_helper)
	{
		$this->api_helper_ = $api_helper;
	}

	public function getStatus()
	{
		return $this->raw_arr_["transferStatus"];
	}

	public function toRawArr()
	{
		return $this->raw_arr_;
	}

	public function confirm()
	{
		$transfer_args = array(
			"requestToken"=>$this->getRequestToken(),
			"amountChecksum"=>$this->raw_arr_["amount"],
			"destinationChecksum"=>$this->raw_arr_["receiver"],
			"transferId"=>$this->raw_arr_["transferId"]
		);
		$transfer_args["requestToken"] = $this->getRequestToken();
		$transfer_response = $transfer_args["requestToken"] ? json_decode($this->api_helper_->createTransferConfirmRequest($transfer_args), true) : null;

		return ($transfer_response && $transfer_response["success"] ? true : false);
	}

	public function cancel()
	{
		$transfer_args = array(
			"requestToken"=>$this->getRequestToken(),
			"transferId"=>$this->raw_arr_["transferId"]
		);
		$transfer_args["requestToken"] = $this->getRequestToken();
		$transfer_response = $transfer_args["requestToken"] ? json_decode($this->api_helper_->createTransferCancelRequest($transfer_args), true) : null;

		return ($transfer_response && $transfer_response["success"] ? true : false);
	}

	private function getRequestToken()
	{
		$token_response = json_decode($this->api_helper_->createGetRequestTokenRequest(), true);

		return ($token_response && $token_response["success"] ? $token_response["response"] : null);
	}

	private function refreshTransfer()
	{
		$transfer_response = json_decode($this->api_helper_->createGetTransferRequest($this->raw_arr_["transferId"]), true);
		$transfer_arr = ($transfer_response && $transfer_response["success"] && $transfer_response["response"] ? $transfer_response["response"] : null);
		if($transfer_arr)
		{
			$this->raw_arr_ = $transfer_arr["transfer"];

			return true;
		}

		return false;
	}


	private $raw_arr_ = null;
	private $api_helper_ = null;
}


class CSkfpay
{
	function __construct($oauth)
	{
		$this->api_helper_ = new CApiHelper($oauth);
	}


	public function createPayment($payment_args)
	{
		$payment_args["requestToken"] = $this->getRequestToken();
		$payment_response = $payment_args["requestToken"] ? json_decode($this->api_helper_->createPaymentCreateRequest($payment_args), true) : null;

		return ($payment_response && $payment_response["success"] && $payment_response["payment_id"] ? $payment_response["payment_id"] : null);
	}

	public function createTransfer($transfer_args)
	{
		$transfer_args["requestToken"] = $this->getRequestToken();
		$transfer_response = $transfer_args["requestToken"] ? json_decode($this->api_helper_->createTransferCreateRequest($transfer_args), true) : null;

		return ($transfer_response && $transfer_response["success"] && $transfer_response["transfer_id"] ? $transfer_response["transfer_id"] : null);
	}

	public function getPayment($payment_id)
	{
		$payment_response = json_decode($this->api_helper_->createGetPaymentRequest($payment_id), true);
		$payment_arr = ($payment_response && $payment_response["success"] && $payment_response["response"] ? $payment_response["response"] : null);

		return $payment_arr ? new CPayment($payment_arr, $this->api_helper_) : null;
	}

	public function getTransfer($transfer_id)
	{
		$transfer_response = json_decode($this->api_helper_->createGetTransferRequest($transfer_id), true);
		$transfer_arr = ($transfer_response && $transfer_response["success"] && $transfer_response["response"] ? $transfer_response["response"] : null);

		return $transfer_arr ? new CTransfer($transfer_arr, $this->api_helper_) : null;
	}

	private function getRequestToken()
	{
		$token_response = json_decode($this->api_helper_->createGetRequestTokenRequest(), true);

		return ($token_response && $token_response["success"] ? $token_response["response"] : null);
	}


	private $api_helper_ = null;
}
