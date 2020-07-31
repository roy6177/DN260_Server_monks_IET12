<?php

namespace AlibabaCloud\Client\Request\Traits;

use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Request\Request;

/**
 * @package    AlibabaCloud\Client\Request\Traits
 *
 * @mixin Request
 */
trait DeprecatedTrait {
	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0, Use $this->options['body'] instead.
	 *
	 */
	public function getContent() {
		return isset( $this->options['body'] )
			? $this->options['body']
			: null;
	}

	/**
	 * @param $content
	 *
	 * @return $this
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use body() instead.
	 *
	 */
	public function setContent( $content ) {
		return $this->body( $content );
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0, Use method instead.
	 *
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * @param string $method
	 *
	 * @return $this
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use method() instead.
	 *
	 */
	public function setMethod( $method ) {
		return $this->method( $method );
	}

	/**
	 * @return             string
	 * @deprecated deprecated since version 2.0, Use uri->getScheme() instead.
	 *
	 */
	public function getProtocol() {
		return $this->uri->getScheme();
	}

	/**
	 * @param string $scheme
	 *
	 * @return $this
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use scheme() instead.
	 *
	 */
	public function setProtocol( $scheme ) {
		return $this->scheme( $scheme );
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0, Use uri->getScheme() instead.
	 *
	 */
	public function getProtocolType() {
		return $this->uri->getScheme();
	}

	/**
	 * @param string $scheme
	 *
	 * @return $this
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use scheme() instead.
	 *
	 */
	public function setProtocolType( $scheme ) {
		return $this->scheme( $scheme );
	}

	/**
	 * @return     array
	 * @deprecated deprecated since version 2.0, Use $this->options['headers'] instead.
	 *
	 */
	public function getHeaders() {
		return isset( $this->options['headers'] )
			? $this->options['headers']
			: [];
	}

	/**
	 * @param string $headerKey
	 * @param string $headerValue
	 *
	 * @return $this
	 * @deprecated deprecated since version 2.0, Use $this->options['headers'] instead.
	 *
	 */
	public function addHeader( $headerKey, $headerValue ) {
		$this->options['headers'][ $headerKey ] = $headerValue;

		return $this;
	}

	/**
	 * @return     array
	 * @deprecated deprecated since version 2.0.
	 */
	public function getQueryParameters() {
		return isset( $this->options['query'] )
			? $this->options['query']
			: [];
	}

	/**
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return $this
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function setQueryParameters( $name, $value ) {
		$this->options['query'][ $name ] = $value;

		return $this;
	}

	/**
	 * @return     array
	 * @deprecated deprecated since version 2.0.
	 */
	public function getDomainParameter() {
		return isset( $this->options['form_params'] )
			? $this->options['form_params']
			: [];
	}

	/**
	 * @param string $name
	 * @param string $value
	 *
	 * @return $this
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function putDomainParameters( $name, $value ) {
		$this->options['form_params'][ $name ] = $value;

		return $this;
	}

	/**
	 * @param $actionName
	 *
	 * @return self
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use action() instead.
	 *
	 */
	public function setActionName( $actionName ) {
		return $this->action( $actionName );
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0.
	 */
	public function getActionName() {
		return $this->action;
	}

	/**
	 * @param string $format
	 *
	 * @return self
	 * @throws ClientException
	 * @deprecated deprecated since version 2.0, Use format() instead.
	 *
	 */
	public function setAcceptFormat( $format ) {
		return $this->format( $format );
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0.
	 */
	public function getAcceptFormat() {
		return $this->format;
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0.
	 */
	public function getLocationEndpointType() {
		return $this->endpointType;
	}

	/**
	 * @return     string
	 * @deprecated deprecated since version 2.0.
	 *
	 */
	public function getLocationServiceCode() {
		return $this->serviceCode;
	}
}
