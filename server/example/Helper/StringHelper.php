<?php
namespace Helper;

class StringHelper {
	/**
	 * Memotong string dengan panjang tertentu
	 *
	 * @param string $string
	 * @param int $width
	 * @return string
	 */
  public static function truncate($string, $width = 10) {
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
		$parts_count = count($parts);

		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen($parts[$last_part]);
			if ($length > $width) { break; }
		}
		$join = implode(array_slice($parts, 0, $last_part));
		$join = preg_replace('/[^a-z0-9]+$/i', '', $join);
		return $join;
  }

	/**
	 * Mengubah string menjadi search engine friendly title
	 *
	 * @param string $string
	 * @param string $separator
	 * @return string
	 */
  public static function sluggify($string, $separator = '-') {
    $title = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		$title = str_replace(' ', $separator, strtolower($title));
		$title = preg_replace('/[^' . preg_quote($separator) . 'a-z0-9]/i', '', $title);
		$title = preg_replace('/[' . preg_quote($separator) . ']{2,}/', $separator, $title);
		return $title;
  }
}