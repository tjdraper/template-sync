<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class SyncPartials
{
	/**
	 * Sync partials
	 *
	 * @param string $model
	 * @param array $data
	 * @return array
	 */
	public function run($model, $data)
	{
		$name = $model === 'Snippet' ? 'snippet_name' : 'variable_name';
		$content = $model === 'Snippet' ? 'snippet_contents' : 'variable_data';

		// Get existing partials
		$partials = ee('Model')->get($model)->all();

		// Update partials
		$sync = false;

		// Loop through existing partials
		foreach ($partials as $key => $val) {
			// If the partial is not in the file system delete it
			if (! isset($data[$val->{$name}])) {
				$partials[$key]->delete();
			} else {
				// If the contents do not match, sync from file system
				if ($partials[$key]->{$content} !== $data[$val->{$name}]) {
					$partials[$key]->{$content} = $data[$val->{$name}];

					$sync = true;
				}

				// Remove the item from the data array
				unset($data[$val->{$name}]);
			}
		}

		// Save the model if needed
		if ($sync) {
			$partials->save();
		}

		// Anything remaining in te data array needs to be added
		foreach ($data as $key => $val) {
			$partial = ee('Model')->make($model);

			$partial->{$name} = $key;

			$partial->{$content} = $val;

			$partial->site_id = ee()->config->item('site_id');

			$partial->save();
		}
	}
}