<?php

if (! function_exists('render_template')) {
	function render_template($template, $data)
	{
		if (! is_file($template)) {
			throw new Exception('Unable to find template file: ' . $template);
		}

		ob_start();
		require $template;
		$content = ob_get_clean();

		foreach ($data as $key => $field) {
			$content = str_replace('{' . $key . '}', $field, $content);
		}

		return $content;
	}
}