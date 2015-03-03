<?php
namespace Stien\Torrent;

class TorrentScraper {

	protected $adapters = [];

	public function addAdapter(TorrentAdapterInterface $adapter)
	{
		$this->adapters[] = $adapter;
	}

	public function search($query, $category = null)
	{
		$results = [];

		// Default to Categories::ALL if none specified.
		if ( ! is_int($category) || $category == null )
		{
			$category = Categories::ALL;
		}

		foreach ($this->adapters as $adapter)
		{
			$results[] = $adapter->search($query, $category);
		}
		$results = array_flatten($results);

		return $this->sortResults($results);
	}

	protected function sortResults($results)
	{
		// TODO: Do actual sorting.
		// For now, sort it by seeders.
		usort($results, function ($a, $b)
		{
			return $a->getSeeders() <= $b->getSeeders();
		});

		return $results;
	}
}