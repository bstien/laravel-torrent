<?php
namespace Stien\Torrent\Facades;

use Illuminate\Support\Facades\Facade;

class Torrent extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'bstien.torrent.scraper';
	}

}