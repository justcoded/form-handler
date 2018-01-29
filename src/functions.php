<?php

if (! function_exists('render_template')) {
	/**
	 * Render template
	 *
	 * @param string $template Path to template file
	 * @param array $data Array with form fields
	 *
	 * @return mixed|string
	 *
	 * @throws Exception Exception.
	 */
	function render_template(string $template, array $data)
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
