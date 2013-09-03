<?php namespace Fitztrev\LaravelHtmlMinify;

use Illuminate\View\Compilers\BladeCompiler;

class LaravelHtmlMinifyCompiler extends BladeCompiler {

	public function __construct($files, $cachePath) {
		parent::__construct($files, $cachePath);

		// Add Minify to the list of compilers
		$this->compilers[] = 'Minify';
	}

	/**
	* Compress the HTML output before saving it
	*
	* @param  string  $value
	* @return string
	*/
	protected function compileMinify($value)
	{
		// First, check to make sure there are no <pre> or <textarea> blocks inside the view.
		// If there are, we won't bother to compress this view.
		if ( !preg_match('/<(pre|textarea)/', $value) ) {
			// Remove whitespace characters
			$value = str_replace(array("//<![CDATA[", "//]]>"), "", $value);
			
			$replace = array(
				"/\s{2,}/" => ' ',  // Two or more whitespace characters into one,
				"/>\s</" => '><',  // Remove whitespace between html tags
				"/<!--[^\[](.*?)[^\]]-->/" => '', // HTML comments (except IE conditional comments)
			);
			$value = preg_replace(array_keys($replace), array_values($replace), $value);
		}
		return $value;
	}

}
