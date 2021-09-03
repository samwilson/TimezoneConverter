TimezoneConverter extension
===========================

See https://www.mediawiki.org/wiki/Extension:TimezoneConverter for all information.

## Usage

Call the function like this:

    {{#timezoneconverter: datetime = 2020-01-01 18:34:56 +0800 | format = day }}

for an output such as:

    <time datetime="2020-01-01T18:34:56Z">2020 January 2, 6:34PM</time>

which will also be translated to the user's timezone on display (when viewed in a browser).

The parameters are as follows:

* `datetime` — the time to display, given in UTC.
* `timezone` — the timezone to display the time in. This can be a IANA name such as `Australia/Perth` or a numeric offset in hours.
* `format` – the display format to use. Can be one of the shortcuts (see below).

## Configuration

The default formats are defined in $wgTimezoneConverterFormats and can be extended or changed by a wiki system administrator.

    $wgTimezoneConverterFormats = [
        'time' => 'g:iA',
        'exact' => 'Y F j (l), g:iA',
        'day' => 'Y F j (l)',
        'month' => 'Y F',
        'year' => 'Y',
        'circa' => '\\c. Y',
    ];
