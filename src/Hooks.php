<?php

namespace MediaWiki\Extension\TimezoneConverter;

use DateTime;
use DateTimeZone;
use Html;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\MediaWikiServices;
use Message;
use Parser;

class Hooks implements ParserFirstCallInitHook {

	/**
	 * @link https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * @param Parser $parser
	 */
	public function onParserFirstCallInit( $parser ) {
		$parser->setFunctionHook( 'timezoneconverter', [ $this, 'render' ] );
	}

	/**
	 * @param Parser $parser
	 * @return mixed[]
	 */
	public function render( Parser $parser ) {
		$parser->getOutput()->addModules( 'ext.TimezoneConverter' );
		$config = MediaWikiServices::getInstance()->getMainConfig();

		$args = [
			'datetime' => '',
			'format' => 'exact',
		];
		foreach ( array_slice( func_get_args(), 1 ) as $arg ) {
			$eqPos = strpos( $arg, '=' );
			$argName = trim( substr( $arg, 0, $eqPos ) );
			$args[ $argName ] = trim( substr( $arg, $eqPos + 1 ) );
		}
		$formats = $config->get( 'TimezoneConverterFormats' );
		return $this->getOutput( $args, $formats );
	}

	/**
	 * @param string[] $args
	 * @param string[] $formats
	 * @return mixed
	 */
	public function getOutput( $args, $formats ) {
		$datetime = new DateTime( $args['datetime'], new DateTimeZone( 'Z' ) );
		$datetime->setTimezone( new DateTimeZone( 'Z' ) );
		if ( !isset( $formats[ $args['format'] ] ) ) {
			$formatList = Message::listParam( array_keys( $formats ), 'comma' );
			$msg = wfMessage( 'timezoneconverter-invalid-format', $args['format'], $formatList );
			return [
				0 => Html::element( 'span', [ 'class' => 'error' ], $msg ),
				'isHTML' => true,
			];
		}
		$format = $formats[ $args['format'] ];
		$attrs = [
			// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/time#valid_datetime_values
			'datetime' => $datetime->format( 'Y-m-d\TH:i:sT' ),
			'class' => 'ext-timezoneconverter',
			'data-date-options' => json_encode( $format['js'] ),
		];
		if ( isset( $args[ 'itemProp' ] ) ) {
			$attrs['itemProp'] = $args['itemProp'];
		}
		$out = Html::element( 'time', $attrs, $datetime->format( $format['php'] ) );
		return [
			0 => $out,
			'isHTML' => true,
		];
	}
}
