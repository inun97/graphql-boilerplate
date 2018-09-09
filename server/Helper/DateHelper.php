<?php
namespace Helper;

use \go2hi\go2hi as go2hi;

class DateHelper {
  private static $go2hi;

	/**
	 * Format tanggal ke bahasa Indonesia
	 *
	 * @param string $f
	 * @param integer $t
	 * @param boolean $h
	 * @return string
	 */
  public static function formatDate($f, $t = 0, $h = false) {
    if (\is_null(self::$go2hi)) self::$go2hi = new go2hi;
    
    if ( ! \is_numeric($t)) $t = time();
    if (empty($f)) $f = 'd/m/Y H:i:s';
    return self::$go2hi->date($f, ($h === false ? go2hi::GO2HI_GREG : go2hi::GO2HI_HIJRI), $t, 1);
  }

	/**
	 * Mengubah tanggal dari format DB ke bahasa Indonesia
	 *
	 * @param string $d
	 * @param string $f
	 * @param boolean $h
	 * @return string
	 */
  public static function dateDB2Tanggal($d, $f, $h = false) {
    if (\is_null(self::$go2hi)) self::$go2hi = new go2hi;

    if ( ! preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})/', $d)) return '';
		// bagian date
		$t = strtotime($d);
		if ($t === -1) $t = time();
		// bagian format
		if (empty($f)) {
			$f = 'd/m/Y';
			if (strpos($d, ':') !== false) $f .= ' H:i:s';
		}
		// return formatDate
		return self::formatDate($f, $t, $h);
  }

	/**
	 * Mendapatkan hari-hari dalam satu minggu
	 *
	 * @param string $date
	 * @param boolean $from_monday
	 * @return array
	 */
  public static function daysInWeek($date, $from_monday = false) {
    $sd = 'sunday';
		$sn = 0;
		if ($from_monday) {
			$sd = 'monday';
			$sn = 1;
		}

		$days  	= array();
		$ts 		= strtotime($date); // tanggal request
		$start  = (date('w', $ts) == $sn ? $ts : strtotime('last ' . $sd, $ts));
		$days[] = date('Y-m-d', $start);
		for ($i = 1; $i < 7; $i++) {
			$days[] = date('Y-m-d', strtotime('+' . $i . ' day', $start));
		}
		return $days;
  }
}