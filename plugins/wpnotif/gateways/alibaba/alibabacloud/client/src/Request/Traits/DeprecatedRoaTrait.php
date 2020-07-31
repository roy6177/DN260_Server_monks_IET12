<?php

namespace AlibabaCloud\Client\Request\Traits;

/**
 * @package    AlibabaCloud\Client\Request\Traits
 */
trait DeprecatedRoaTrait {

	/**
	 * @param string $pathPattern
	 *
	 * @return $this
	 * @deprecated deprecated since version 2.0, Use pathPattern() instead.
	 *
	 */
	public function setUriPattern( $pathPattern ) {
		return $this->pathPattern( $pathPattern );
	}

	/**
	 * @return string
	 * @deprecated deprecated since version 2.0, Use pathPattern instead.
	 *
	 */
	public function getUriPattern() {
		return $this->pathPattern;
	}

	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @return $this
	 * @deprecated deprecated since version 2.0, Use pathParameter() instead.
	 *
	 */
	public function putPathParameter( $name, $value ) {
		return $this->pathParameter( $name, $value );
	}

	/**
	 * @return array
	 * @deprecated deprecated since version 2.0, Use pathParameters instead.
	 *
	 */
	public function getPathParameters() {
		return $this->pathParameters;
	}
}
