<?php

namespace MediaWiki\Extension\TimezoneConverter\Tests;

use MediaWiki\Extension\TimezoneConverter\Hooks;
use MediaWikiUnitTestCase;

/**
 */
class HooksTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Extension\TimezoneConverter\Hooks::getOutput()
	 * @dataProvider provideParserFunctionOutput()
	 */
	public function testParserFunctionOutput( $datetime, $format, $html ) {
		$hooks = new Hooks();
		$args = [
			'datetime' => $datetime,
			'format' => $format,
		];

		$formats = [
			"time" => [
				"php" => "g:iA",
				"js" => [ "timeZoneName" => "short", "hour" => "numeric", "minute" => "numeric" ],
			],
			"exact" => [
				"php" => "Y F j (l), g:iA",
				"js" => [ "timeZoneName"=> "short", "year"=> "numeric", "month"=> "numeric", "day"=> "numeric", "hour"=> "numeric", "minute"=> "numeric" ],
			],
			"day" => [
				"php" => "Y F j (l)",
				"js" => [ "day" => "numeric" ],
			],
		];
		$this->assertSame( $html, $hooks->getOutput( $args, $formats )[0] );
	}

	public function provideParserFunctionOutput() {
		return [
			[
				'2021-01-02 12:34:56',
				'day',
				'<time datetime="2021-01-02T12:34:56Z" class="ext-timezoneconverter"'
				. ' data-date-options="{&quot;day&quot;:&quot;numeric&quot;}">2021 January 2 (Saturday)</time>'
			]
		];
	}
}
