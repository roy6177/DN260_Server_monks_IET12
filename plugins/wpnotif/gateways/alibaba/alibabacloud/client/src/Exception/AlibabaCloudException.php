<?php

namespace AlibabaCloud\Client\Exception;

/**
 * Class AlibabaCloudException
 *
 * @package   AlibabaCloud\Client\Exception
 */
abstract class AlibabaCloudException extends \Exception {

	/**
	 * @var string
	 */
	protected $errorCode;

	/**
	 * @var string
	 */
	protected $errorMessage;

	/**
	 * @return string
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @param $errorCode
	 *
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function setErrorCode( $errorCode ) {
		$this->errorCode = $errorCode;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @param $errorMessage
	 *
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function setErrorMessage( $errorMessage ) {
		$this->errorMessage = $errorMessage;
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @return             void
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function setErrorType() {
	}
}
