<?php
/**
 * Price
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Price {

	public static function init() {
	    add_action( 'init', array( __CLASS__, 'process_currency' ) );
	}
	/**
	 * Formats price
	 *
	 * @access public
	 * @param $price
	 * @return bool|string
	 */
	public static function format_price( $price, $show_null = false, $without_rate_exchange = false ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			if ( !$show_null ) {
				return false;
			}
			$price = 0;
		}
		$decimals = false;
		$money_decimals = wp_listings_directory_get_option('money_decimals');

		if ( wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			$current_currency = self::get_current_currency();
			$multi_currencies = self::get_currencies_settings();

			if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
				$currency_args = $multi_currencies[$current_currency];
			}

			if ( !empty($currency_args) ) {
				if ( !empty($currency_args['custom_symbol']) ) {
					$symbol = $currency_args['custom_symbol'];
				} else {
					$currency = $currency_args['currency'];
					$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
				}
				$currency_position = !empty($currency_args['currency_position']) ? $currency_args['currency_position'] : 'before';
				$rate_exchange_fee = !empty($currency_args['rate_exchange_fee']) ? $currency_args['rate_exchange_fee'] : 1;
				$decimals = true;
				$money_decimals = !empty($currency_args['money_decimals']) ? $currency_args['money_decimals'] : 1;
				if ( !$without_rate_exchange ) {
					$price = $price*$rate_exchange_fee;
				}
			} else {
				$symbol = wp_listings_directory_get_option('custom_symbol', '$');
				if ( empty($symbol) ) {
					$currency = wp_listings_directory_get_option('currency', 'USD');
					$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
				}
				$currency_position = wp_listings_directory_get_option('currency_position', 'before');
			}
		} else {
			$symbol = wp_listings_directory_get_option('custom_symbol', '$');
			if ( empty($symbol) ) {
				$currency = wp_listings_directory_get_option('currency', 'USD');
				$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
			}
			$currency_position = wp_listings_directory_get_option('currency_position', 'before');
		}

		$currency_symbol = ! empty( $symbol ) ? '<span class="suffix">'.$symbol.'</span>' : '<span class="suffix">$</span>';

		if ( wp_listings_directory_get_option('enable_shorten_long_number', 'no') === 'yes' ) {
			$price = self::number_shorten( $price, $decimals, $money_decimals );
		} else {
			$price = WP_Listings_Directory_Mixes::format_number( $price, $decimals, $money_decimals );
		}

		if ( ! empty( $currency_symbol ) ) {
			switch ($currency_position) {
				case 'before':
					$price = $currency_symbol . '<span class="price-text">'.$price.'</span>';
					break;
				case 'after':
					$price = '<span class="price-text">'.$price.'</span>' . $currency_symbol;
					break;
				case 'before_space':
					$price = $currency_symbol . ' <span class="price-text">'.$price.'</span>';
					break;
				case 'after_space':
					$price = '<span class="price-text">'.$price.'</span> ' . $currency_symbol;
					break;
			}
		}

		return $price;
	}

	public static function format_price_without_html( $price, $show_null = false, $without_rate_exchange = false ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			if ( !$show_null ) {
				return false;
			}
			$price = 0;
		}
		$decimals = false;
		$money_decimals = wp_listings_directory_get_option('money_decimals');

		if ( wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			$current_currency = self::get_current_currency();
			$multi_currencies = self::get_currencies_settings();

			if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
				$currency_args = $multi_currencies[$current_currency];
			}

			if ( !empty($currency_args) ) {
				if ( !empty($currency_args['custom_symbol']) ) {
					$symbol = $currency_args['custom_symbol'];
				} else {
					$currency = $currency_args['currency'];
					$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
				}
				$currency_position = !empty($currency_args['currency_position']) ? $currency_args['currency_position'] : 'before';
				$rate_exchange_fee = !empty($currency_args['rate_exchange_fee']) ? $currency_args['rate_exchange_fee'] : 1;
				$decimals = true;
				$money_decimals = !empty($currency_args['money_decimals']) ? $currency_args['money_decimals'] : 1;
				if ( !$without_rate_exchange ) {
					$price = $price*$rate_exchange_fee;
				}
			} else {
				$symbol = wp_listings_directory_get_option('custom_symbol', '$');
				if ( empty($symbol) ) {
					$currency = wp_listings_directory_get_option('currency', 'USD');
					$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
				}
				$currency_position = wp_listings_directory_get_option('currency_position', 'before');
			}
		} else {
			$symbol = wp_listings_directory_get_option('custom_symbol', '$');
			if ( empty($symbol) ) {
				$currency = wp_listings_directory_get_option('currency', 'USD');
				$symbol = WP_Listings_Directory_Price::currency_symbol($currency);
			}
			$currency_position = wp_listings_directory_get_option('currency_position', 'before');
		}

		$currency_symbol = ! empty( $symbol ) ? $symbol : '$';

		if ( wp_listings_directory_get_option('enable_shorten_long_number', 'no') === 'yes' ) {
			$price = self::number_shorten( $price, $decimals, $money_decimals );
		} else {
			$price = WP_Listings_Directory_Mixes::format_number( $price, $decimals, $money_decimals );
		}

		if ( ! empty( $currency_symbol ) ) {
			switch ($currency_position) {
				case 'before':
					$price = $currency_symbol . $price;
					break;
				case 'after':
					$price = $price . $currency_symbol;
					break;
				case 'before_space':
					$price = $currency_symbol .' '. $price;
					break;
				case 'after_space':
					$price = $price .' '. $currency_symbol;
					break;
			}
		}

		return $price;
	}

	public static function convert_price_exchange( $price ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			$price = 0;
		}
		if ( wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			$current_currency = self::get_current_currency();
			$multi_currencies = self::get_currencies_settings();

			if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
				$currency_args = $multi_currencies[$current_currency];
			}

			if ( !empty($currency_args) ) {
				$rate_exchange_fee = !empty($currency_args['rate_exchange_fee']) ? $currency_args['rate_exchange_fee'] : 1;
				$price = $price*$rate_exchange_fee;
			}
		}

		return $price;
	}

	public static function convert_current_currency_to_default( $price ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			$price = 0;
		}
		if ( wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			$current_currency = self::get_current_currency();
			$multi_currencies = self::get_currencies_settings();

			if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
				$currency_args = $multi_currencies[$current_currency];
			}

			if ( !empty($currency_args) ) {
				$rate_exchange_fee = !empty($currency_args['rate_exchange_fee']) ? $currency_args['rate_exchange_fee'] : 1;

				$price = $price*(1/$rate_exchange_fee);
			}
		}

		return $price;
	}

	public static function get_current_currency() {
		if ( wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			$current_currency = !empty($_COOKIE['wp_listings_directory_currency']) ? $_COOKIE['wp_listings_directory_currency'] : wp_listings_directory_get_option('currency', 'USD');
		} else {
			$current_currency = wp_listings_directory_get_option('currency', 'USD');
		}
		return $current_currency;
	}
	/**
	 * Get full list of currency codes.
	 *
	 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
	 *
	 * @return array
	 */
	public static function get_currencies() {
		$currencies = array_unique(
			apply_filters(
				'wp-listings-directory-currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'wp-listings-directory' ),
					'AFN' => __( 'Afghan afghani', 'wp-listings-directory' ),
					'ALL' => __( 'Albanian lek', 'wp-listings-directory' ),
					'AMD' => __( 'Armenian dram', 'wp-listings-directory' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'wp-listings-directory' ),
					'AOA' => __( 'Angolan kwanza', 'wp-listings-directory' ),
					'ARS' => __( 'Argentine peso', 'wp-listings-directory' ),
					'AUD' => __( 'Australian dollar', 'wp-listings-directory' ),
					'AWG' => __( 'Aruban florin', 'wp-listings-directory' ),
					'AZN' => __( 'Azerbaijani manat', 'wp-listings-directory' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'wp-listings-directory' ),
					'BBD' => __( 'Barbadian dollar', 'wp-listings-directory' ),
					'BDT' => __( 'Bangladeshi taka', 'wp-listings-directory' ),
					'BGN' => __( 'Bulgarian lev', 'wp-listings-directory' ),
					'BHD' => __( 'Bahraini dinar', 'wp-listings-directory' ),
					'BIF' => __( 'Burundian franc', 'wp-listings-directory' ),
					'BMD' => __( 'Bermudian dollar', 'wp-listings-directory' ),
					'BND' => __( 'Brunei dollar', 'wp-listings-directory' ),
					'BOB' => __( 'Bolivian boliviano', 'wp-listings-directory' ),
					'BRL' => __( 'Brazilian real', 'wp-listings-directory' ),
					'BSD' => __( 'Bahamian dollar', 'wp-listings-directory' ),
					'BTC' => __( 'Bitcoin', 'wp-listings-directory' ),
					'BTN' => __( 'Bhutanese ngultrum', 'wp-listings-directory' ),
					'BWP' => __( 'Botswana pula', 'wp-listings-directory' ),
					'BYR' => __( 'Belarusian ruble (old)', 'wp-listings-directory' ),
					'BYN' => __( 'Belarusian ruble', 'wp-listings-directory' ),
					'BZD' => __( 'Belize dollar', 'wp-listings-directory' ),
					'CAD' => __( 'Canadian dollar', 'wp-listings-directory' ),
					'CDF' => __( 'Congolese franc', 'wp-listings-directory' ),
					'CHF' => __( 'Swiss franc', 'wp-listings-directory' ),
					'CLP' => __( 'Chilean peso', 'wp-listings-directory' ),
					'CNY' => __( 'Chinese yuan', 'wp-listings-directory' ),
					'COP' => __( 'Colombian peso', 'wp-listings-directory' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'wp-listings-directory' ),
					'CUC' => __( 'Cuban convertible peso', 'wp-listings-directory' ),
					'CUP' => __( 'Cuban peso', 'wp-listings-directory' ),
					'CVE' => __( 'Cape Verdean escudo', 'wp-listings-directory' ),
					'CZK' => __( 'Czech koruna', 'wp-listings-directory' ),
					'DJF' => __( 'Djiboutian franc', 'wp-listings-directory' ),
					'DKK' => __( 'Danish krone', 'wp-listings-directory' ),
					'DOP' => __( 'Dominican peso', 'wp-listings-directory' ),
					'DZD' => __( 'Algerian dinar', 'wp-listings-directory' ),
					'EGP' => __( 'Egyptian pound', 'wp-listings-directory' ),
					'ERN' => __( 'Eritrean nakfa', 'wp-listings-directory' ),
					'ETB' => __( 'Ethiopian birr', 'wp-listings-directory' ),
					'EUR' => __( 'Euro', 'wp-listings-directory' ),
					'FJD' => __( 'Fijian dollar', 'wp-listings-directory' ),
					'FKP' => __( 'Falkland Islands pound', 'wp-listings-directory' ),
					'GBP' => __( 'Pound sterling', 'wp-listings-directory' ),
					'GEL' => __( 'Georgian lari', 'wp-listings-directory' ),
					'GGP' => __( 'Guernsey pound', 'wp-listings-directory' ),
					'GHS' => __( 'Ghana cedi', 'wp-listings-directory' ),
					'GIP' => __( 'Gibraltar pound', 'wp-listings-directory' ),
					'GMD' => __( 'Gambian dalasi', 'wp-listings-directory' ),
					'GNF' => __( 'Guinean franc', 'wp-listings-directory' ),
					'GTQ' => __( 'Guatemalan quetzal', 'wp-listings-directory' ),
					'GYD' => __( 'Guyanese dollar', 'wp-listings-directory' ),
					'HKD' => __( 'Hong Kong dollar', 'wp-listings-directory' ),
					'HNL' => __( 'Honduran lempira', 'wp-listings-directory' ),
					'HRK' => __( 'Croatian kuna', 'wp-listings-directory' ),
					'HTG' => __( 'Haitian gourde', 'wp-listings-directory' ),
					'HUF' => __( 'Hungarian forint', 'wp-listings-directory' ),
					'IDR' => __( 'Indonesian rupiah', 'wp-listings-directory' ),
					'ILS' => __( 'Israeli new shekel', 'wp-listings-directory' ),
					'IMP' => __( 'Manx pound', 'wp-listings-directory' ),
					'INR' => __( 'Indian rupee', 'wp-listings-directory' ),
					'IQD' => __( 'Iraqi dinar', 'wp-listings-directory' ),
					'IRR' => __( 'Iranian rial', 'wp-listings-directory' ),
					'IRT' => __( 'Iranian toman', 'wp-listings-directory' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'wp-listings-directory' ),
					'JEP' => __( 'Jersey pound', 'wp-listings-directory' ),
					'JMD' => __( 'Jamaican dollar', 'wp-listings-directory' ),
					'JOD' => __( 'Jordanian dinar', 'wp-listings-directory' ),
					'JPY' => __( 'Japanese yen', 'wp-listings-directory' ),
					'KES' => __( 'Kenyan shilling', 'wp-listings-directory' ),
					'KGS' => __( 'Kyrgyzstani som', 'wp-listings-directory' ),
					'KHR' => __( 'Cambodian riel', 'wp-listings-directory' ),
					'KMF' => __( 'Comorian franc', 'wp-listings-directory' ),
					'KPW' => __( 'North Korean won', 'wp-listings-directory' ),
					'KRW' => __( 'South Korean won', 'wp-listings-directory' ),
					'KWD' => __( 'Kuwaiti dinar', 'wp-listings-directory' ),
					'KYD' => __( 'Cayman Islands dollar', 'wp-listings-directory' ),
					'KZT' => __( 'Kazakhstani tenge', 'wp-listings-directory' ),
					'LAK' => __( 'Lao kip', 'wp-listings-directory' ),
					'LBP' => __( 'Lebanese pound', 'wp-listings-directory' ),
					'LKR' => __( 'Sri Lankan rupee', 'wp-listings-directory' ),
					'LRD' => __( 'Liberian dollar', 'wp-listings-directory' ),
					'LSL' => __( 'Lesotho loti', 'wp-listings-directory' ),
					'LYD' => __( 'Libyan dinar', 'wp-listings-directory' ),
					'MAD' => __( 'Moroccan dirham', 'wp-listings-directory' ),
					'MDL' => __( 'Moldovan leu', 'wp-listings-directory' ),
					'MGA' => __( 'Malagasy ariary', 'wp-listings-directory' ),
					'MKD' => __( 'Macedonian denar', 'wp-listings-directory' ),
					'MMK' => __( 'Burmese kyat', 'wp-listings-directory' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'wp-listings-directory' ),
					'MOP' => __( 'Macanese pataca', 'wp-listings-directory' ),
					'MRU' => __( 'Mauritanian ouguiya', 'wp-listings-directory' ),
					'MUR' => __( 'Mauritian rupee', 'wp-listings-directory' ),
					'MVR' => __( 'Maldivian rufiyaa', 'wp-listings-directory' ),
					'MWK' => __( 'Malawian kwacha', 'wp-listings-directory' ),
					'MXN' => __( 'Mexican peso', 'wp-listings-directory' ),
					'MYR' => __( 'Malaysian ringgit', 'wp-listings-directory' ),
					'MZN' => __( 'Mozambican metical', 'wp-listings-directory' ),
					'NAD' => __( 'Namibian dollar', 'wp-listings-directory' ),
					'NGN' => __( 'Nigerian naira', 'wp-listings-directory' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'wp-listings-directory' ),
					'NOK' => __( 'Norwegian krone', 'wp-listings-directory' ),
					'NPR' => __( 'Nepalese rupee', 'wp-listings-directory' ),
					'NZD' => __( 'New Zealand dollar', 'wp-listings-directory' ),
					'OMR' => __( 'Omani rial', 'wp-listings-directory' ),
					'PAB' => __( 'Panamanian balboa', 'wp-listings-directory' ),
					'PEN' => __( 'Sol', 'wp-listings-directory' ),
					'PGK' => __( 'Papua New Guinean kina', 'wp-listings-directory' ),
					'PHP' => __( 'Philippine peso', 'wp-listings-directory' ),
					'PKR' => __( 'Pakistani rupee', 'wp-listings-directory' ),
					'PLN' => __( 'Polish z&#x142;oty', 'wp-listings-directory' ),
					'PRB' => __( 'Transnistrian ruble', 'wp-listings-directory' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'wp-listings-directory' ),
					'QAR' => __( 'Qatari riyal', 'wp-listings-directory' ),
					'RON' => __( 'Romanian leu', 'wp-listings-directory' ),
					'RSD' => __( 'Serbian dinar', 'wp-listings-directory' ),
					'RUB' => __( 'Russian ruble', 'wp-listings-directory' ),
					'RWF' => __( 'Rwandan franc', 'wp-listings-directory' ),
					'SAR' => __( 'Saudi riyal', 'wp-listings-directory' ),
					'SBD' => __( 'Solomon Islands dollar', 'wp-listings-directory' ),
					'SCR' => __( 'Seychellois rupee', 'wp-listings-directory' ),
					'SDG' => __( 'Sudanese pound', 'wp-listings-directory' ),
					'SEK' => __( 'Swedish krona', 'wp-listings-directory' ),
					'SGD' => __( 'Singapore dollar', 'wp-listings-directory' ),
					'SHP' => __( 'Saint Helena pound', 'wp-listings-directory' ),
					'SLL' => __( 'Sierra Leonean leone', 'wp-listings-directory' ),
					'SOS' => __( 'Somali shilling', 'wp-listings-directory' ),
					'SRD' => __( 'Surinamese dollar', 'wp-listings-directory' ),
					'SSP' => __( 'South Sudanese pound', 'wp-listings-directory' ),
					'STN' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'wp-listings-directory' ),
					'SYP' => __( 'Syrian pound', 'wp-listings-directory' ),
					'SZL' => __( 'Swazi lilangeni', 'wp-listings-directory' ),
					'THB' => __( 'Thai baht', 'wp-listings-directory' ),
					'TJS' => __( 'Tajikistani somoni', 'wp-listings-directory' ),
					'TMT' => __( 'Turkmenistan manat', 'wp-listings-directory' ),
					'TND' => __( 'Tunisian dinar', 'wp-listings-directory' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'wp-listings-directory' ),
					'TRY' => __( 'Turkish lira', 'wp-listings-directory' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'wp-listings-directory' ),
					'TWD' => __( 'New Taiwan dollar', 'wp-listings-directory' ),
					'TZS' => __( 'Tanzanian shilling', 'wp-listings-directory' ),
					'UAH' => __( 'Ukrainian hryvnia', 'wp-listings-directory' ),
					'UGX' => __( 'Ugandan shilling', 'wp-listings-directory' ),
					'USD' => __( 'United States (US) dollar', 'wp-listings-directory' ),
					'UYU' => __( 'Uruguayan peso', 'wp-listings-directory' ),
					'UZS' => __( 'Uzbekistani som', 'wp-listings-directory' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'wp-listings-directory' ),
					'VES' => __( 'Bol&iacute;var soberano', 'wp-listings-directory' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'wp-listings-directory' ),
					'VUV' => __( 'Vanuatu vatu', 'wp-listings-directory' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'wp-listings-directory' ),
					'XAF' => __( 'Central African CFA franc', 'wp-listings-directory' ),
					'XCD' => __( 'East Caribbean dollar', 'wp-listings-directory' ),
					'XOF' => __( 'West African CFA franc', 'wp-listings-directory' ),
					'XPF' => __( 'CFP franc', 'wp-listings-directory' ),
					'YER' => __( 'Yemeni rial', 'wp-listings-directory' ),
					'ZAR' => __( 'South African rand', 'wp-listings-directory' ),
					'ZMW' => __( 'Zambian kwacha', 'wp-listings-directory' ),
				)
			)
		);

		return $currencies;
	}

	/**
	 * Get all available Currency symbols.
	 *
	 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
	 *
	 * @since 4.1.0
	 * @return array
	 */
	public static function get_currency_symbols() {

		$symbols = apply_filters(
			'wp-listings-directory-currency-symbols',
			array(
				'AED' => '&#x62f;.&#x625;',
				'AFN' => '&#x60b;',
				'ALL' => 'L',
				'AMD' => 'AMD',
				'ANG' => '&fnof;',
				'AOA' => 'Kz',
				'ARS' => '&#36;',
				'AUD' => '&#36;',
				'AWG' => 'Afl.',
				'AZN' => 'AZN',
				'BAM' => 'KM',
				'BBD' => '&#36;',
				'BDT' => '&#2547;&nbsp;',
				'BGN' => '&#1083;&#1074;.',
				'BHD' => '.&#x62f;.&#x628;',
				'BIF' => 'Fr',
				'BMD' => '&#36;',
				'BND' => '&#36;',
				'BOB' => 'Bs.',
				'BRL' => '&#82;&#36;',
				'BSD' => '&#36;',
				'BTC' => '&#8383;',
				'BTN' => 'Nu.',
				'BWP' => 'P',
				'BYR' => 'Br',
				'BYN' => 'Br',
				'BZD' => '&#36;',
				'CAD' => '&#36;',
				'CDF' => 'Fr',
				'CHF' => '&#67;&#72;&#70;',
				'CLP' => '&#36;',
				'CNY' => '&yen;',
				'COP' => '&#36;',
				'CRC' => '&#x20a1;',
				'CUC' => '&#36;',
				'CUP' => '&#36;',
				'CVE' => '&#36;',
				'CZK' => '&#75;&#269;',
				'DJF' => 'Fr',
				'DKK' => 'DKK',
				'DOP' => 'RD&#36;',
				'DZD' => '&#x62f;.&#x62c;',
				'EGP' => 'EGP',
				'ERN' => 'Nfk',
				'ETB' => 'Br',
				'EUR' => '&euro;',
				'FJD' => '&#36;',
				'FKP' => '&pound;',
				'GBP' => '&pound;',
				'GEL' => '&#x20be;',
				'GGP' => '&pound;',
				'GHS' => '&#x20b5;',
				'GIP' => '&pound;',
				'GMD' => 'D',
				'GNF' => 'Fr',
				'GTQ' => 'Q',
				'GYD' => '&#36;',
				'HKD' => '&#36;',
				'HNL' => 'L',
				'HRK' => 'kn',
				'HTG' => 'G',
				'HUF' => '&#70;&#116;',
				'IDR' => 'Rp',
				'ILS' => '&#8362;',
				'IMP' => '&pound;',
				'INR' => '&#8377;',
				'IQD' => '&#x639;.&#x62f;',
				'IRR' => '&#xfdfc;',
				'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
				'ISK' => 'kr.',
				'JEP' => '&pound;',
				'JMD' => '&#36;',
				'JOD' => '&#x62f;.&#x627;',
				'JPY' => '&yen;',
				'KES' => 'KSh',
				'KGS' => '&#x441;&#x43e;&#x43c;',
				'KHR' => '&#x17db;',
				'KMF' => 'Fr',
				'KPW' => '&#x20a9;',
				'KRW' => '&#8361;',
				'KWD' => '&#x62f;.&#x643;',
				'KYD' => '&#36;',
				'KZT' => '&#8376;',
				'LAK' => '&#8365;',
				'LBP' => '&#x644;.&#x644;',
				'LKR' => '&#xdbb;&#xdd4;',
				'LRD' => '&#36;',
				'LSL' => 'L',
				'LYD' => '&#x644;.&#x62f;',
				'MAD' => '&#x62f;.&#x645;.',
				'MDL' => 'MDL',
				'MGA' => 'Ar',
				'MKD' => '&#x434;&#x435;&#x43d;',
				'MMK' => 'Ks',
				'MNT' => '&#x20ae;',
				'MOP' => 'P',
				'MRU' => 'UM',
				'MUR' => '&#x20a8;',
				'MVR' => '.&#x783;',
				'MWK' => 'MK',
				'MXN' => '&#36;',
				'MYR' => '&#82;&#77;',
				'MZN' => 'MT',
				'NAD' => 'N&#36;',
				'NGN' => '&#8358;',
				'NIO' => 'C&#36;',
				'NOK' => '&#107;&#114;',
				'NPR' => '&#8360;',
				'NZD' => '&#36;',
				'OMR' => '&#x631;.&#x639;.',
				'PAB' => 'B/.',
				'PEN' => 'S/',
				'PGK' => 'K',
				'PHP' => '&#8369;',
				'PKR' => '&#8360;',
				'PLN' => '&#122;&#322;',
				'PRB' => '&#x440;.',
				'PYG' => '&#8370;',
				'QAR' => '&#x631;.&#x642;',
				'RMB' => '&yen;',
				'RON' => 'lei',
				'RSD' => '&#1088;&#1089;&#1076;',
				'RUB' => '&#8381;',
				'RWF' => 'Fr',
				'SAR' => '&#x631;.&#x633;',
				'SBD' => '&#36;',
				'SCR' => '&#x20a8;',
				'SDG' => '&#x62c;.&#x633;.',
				'SEK' => '&#107;&#114;',
				'SGD' => '&#36;',
				'SHP' => '&pound;',
				'SLL' => 'Le',
				'SOS' => 'Sh',
				'SRD' => '&#36;',
				'SSP' => '&pound;',
				'STN' => 'Db',
				'SYP' => '&#x644;.&#x633;',
				'SZL' => 'L',
				'THB' => '&#3647;',
				'TJS' => '&#x405;&#x41c;',
				'TMT' => 'm',
				'TND' => '&#x62f;.&#x62a;',
				'TOP' => 'T&#36;',
				'TRY' => '&#8378;',
				'TTD' => '&#36;',
				'TWD' => '&#78;&#84;&#36;',
				'TZS' => 'Sh',
				'UAH' => '&#8372;',
				'UGX' => 'UGX',
				'USD' => '&#36;',
				'UYU' => '&#36;',
				'UZS' => 'UZS',
				'VEF' => 'Bs F',
				'VES' => 'Bs.S',
				'VND' => '&#8363;',
				'VUV' => 'Vt',
				'WST' => 'T',
				'XAF' => 'CFA',
				'XCD' => '&#36;',
				'XOF' => 'CFA',
				'XPF' => 'Fr',
				'YER' => '&#xfdfc;',
				'ZAR' => '&#82;',
				'ZMW' => 'ZK',
			)
		);

		return $symbols;
	}

	/**
	 * Get Currency symbol.
	 *
	 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
	 *
	 * @param string $currency Currency. (default: '').
	 * @return string
	 */
	public static function currency_symbol( $currency = '' ) {
		
		$symbols = self::get_currency_symbols();

		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

		return apply_filters( 'wp-listings-directory-currency-symbol', $currency_symbol, $currency );
	}

	public static function get_currencies_settings() {
		$currency = wp_listings_directory_get_option('currency', 'USD');
		$return = array(
			$currency => array(
				'currency' => $currency,
				'currency_position' => wp_listings_directory_get_option('currency_position', 'before'),
				'money_decimals' => wp_listings_directory_get_option('money_decimals', ''),
				'rate_exchange_fee' => 1,
				'custom_symbol' => wp_listings_directory_get_option('custom_symbol', ''),
			)
		);
		$multi_currencies = wp_listings_directory_get_option('multi_currencies');
		if ( !empty($multi_currencies) ) {
			foreach ($multi_currencies as $multi_currency) {
				if ( !empty($multi_currency['currency']) && $multi_currency['currency'] != $currency) {
					$return[$multi_currency['currency']] = $multi_currency;
				}
			}
		}

		return $return;
	}

	public static function number_shorten($number, $decimals = false, $money_decimals = 0 ) {

		if ( empty( $number ) || ! is_numeric( $number ) ) {
			return 0;
		}

        $divisors = self::get_shorten_divisors();

	    // Loop through each $divisor and find the
	    // lowest amount that matches
	    foreach ($divisors as $key => $value) {
	        if (abs($number) < ($value['divisor'] * 1000)) {
	            $number = $number / $value['divisor'];
	            return WP_Listings_Directory_Mixes::format_number($number, $decimals, $money_decimals) . $value['key'];
	            break;
	        }
	    }

	    return WP_Listings_Directory_Mixes::format_number($number, $decimals, $money_decimals);
	}

	public static function get_shorten_divisors() {

        $divisors = array(
            '' => [
	            	'divisor' => pow(1000, 0),
	            	'key' => ''
	            ], // 1000^0 == 1
        );

        $shorten = wp_listings_directory_get_option('shorten_thousand');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('K', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['thousand'] = [
            	'divisor' => pow(1000, 1),
            	'key' => $key
            ];
        }

        $shorten = wp_listings_directory_get_option('shorten_million');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('M', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['million'] = [
            	'divisor' => pow(1000, 2),
            	'key' => $key
            ];
        }

        $shorten = wp_listings_directory_get_option('shorten_billion');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('B', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['billion'] = [
            	'divisor' => pow(1000, 3),
            	'key' => $key
            ];
        }

        $shorten = wp_listings_directory_get_option('shorten_trillion');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('T', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['trillion'] = [
            	'divisor' => pow(1000, 4),
            	'key' => $key
            ];
        }

        $shorten = wp_listings_directory_get_option('shorten_quadrillion');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('Qa', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['quadrillion'] = [
            	'divisor' => pow(1000, 5),
            	'key' => $key
            ];
        }

        $shorten = wp_listings_directory_get_option('shorten_quintillion');
        if ( !empty($shorten['enable']) && $shorten['enable'] == 'on' ) {
        	$key = __('Qi', 'wp-listings-directory');
        	if ( !empty($shorten['key']) ) {
        		$key = $shorten['key'];
        	}
        	$divisors['quintillion'] = [
            	'divisor' => pow(1000, 6),
            	'key' => $key
            ];
        }

	    return apply_filters('wp_listings_directory_get_shorten_divisors', $divisors);
	}

	public static function process_currency() {
		if ( !empty($_GET['currency']) && wp_listings_directory_get_option('enable_multi_currencies') === 'yes' ) {
			setcookie('wp_listings_directory_currency', sanitize_text_field($_GET['currency']), time() + (86400 * 30), "/" );
			$_COOKIE['wp_listings_directory_currency'] = sanitize_text_field($_GET['currency']);
		}		
	}
}

WP_Listings_Directory_Price::init();