<?php

namespace Stien\Torrent;

use GuzzleHttp\Client;

trait HttpClientTrait {

	protected $httpClient;

	public function setHttpClient(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function getHttpClient()
	{
		return $this->httpClient;
	}
}