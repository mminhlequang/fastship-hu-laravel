<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/
    'There was an error on row :row' => 'Hiba történt a :row soron',
    'accepted' => 'Az :attribute mezőt el kell fogadni.',
    'active_url' => 'Az :attribute mező nem érvényes URL.',
    'after' => 'Az :attribute mezőnek egy nappal a :date után kell lennie.',
    'after_or_equal' => 'Az :attribute mezőnek :date után kezdődő időpontnak kell lennie.',
    'alpha' => 'Az :attribute mező csak betűket tartalmazhat.',
    'alpha_dash' => 'Az :attribute mező csak betűket, számokat és kötőjeleket tartalmazhat.',
    'alpha_num' => 'Az :attribute mező csak betűket és számokat tartalmazhat.',
    'array' => 'Az :attribute mezőnek tömbnek kell lennie.',
    'before' => 'Az :attribute mezőnek egy nappal a :date előtt kell lennie.',
    'before_or_equal' => 'Az :attribute mezőnek olyan dátumnak kell lennie, amely a :date előtt kezdődik, vagy egyenlő azzal.',
	'between'              => [
        'numeric' => 'Az :attribute mezőnek a :min - :max tartományban kell lennie.',
        'file' => 'A fájl méretének az :attribute mezőben :min - :max kB-nak kell lennie.',
        'string' => 'Az :attribute mezőnek :min - :max karakterek között kell lennie.',
        'array' => 'Az :attribute mezőnek :min - :max elemek között kell lennie.',
	],
    'boolean' => 'Az :attribute mezőnek igaznak vagy hamisnak kell lennie.',
    'confirmed' => 'Az :attribute mezőben lévő megerősített érték nem egyezik.',
    'date' => 'Az :attribute mező nem dátum formátumú.',
    'date_format' => 'Az :attribute mező nem egyezik a :formattal.',
    'different' => 'Az :attribute és :other mezőknek különbözniük kell.',
    'digits' => 'Az :attribute mező hosszának :digits számjegyekből kell állnia.',
    'digits_between' => 'Az :attribute mező hosszának :min és :max számjegy között kell lennie.',
    'dimensions' => 'Az :attribute mező méretei érvénytelenek.',
    'distinct' => 'Az :attribute mező értéke duplikált.',
    'email' => 'Az :attribute mezőnek nem létező e-mail címnek kell lennie.',
    'exists' => 'Az :attribute mezőben kiválasztott érték nem létezik.',
    'file' => 'Az :attribute mezőnek fájlnak kell lennie.',
    'filled' => 'Az :attribute mező nem maradhat üresen.',
    'image' => 'Az :attribute mezőnek képtípusúnak kell lennie.',
    'in' => 'Az :attribute mezőben kiválasztott érték nem érvényes.',
    'in_array' => 'Az :attribute mezőnek az engedélyezett készletben kell lennie: :other.',
    'integer' => 'Az :attribute mezőnek egész számnak kell lennie.',
    'ip' => 'Az :attribute mezőnek IP-címnek kell lennie.',
    'ipv4' => 'Az :attribute mezőnek IPv4-címnek kell lennie.',
    'ipv6' => 'Az :attribute mezőnek IPv6-címnek kell lennie.',
    'json' => 'Az :attribute mezőnek JSON karakterláncnak kell lennie.',
	'max'                  => [
        'numeric' => 'Az :attribute mező nem lehet nagyobb, mint :max.',
        'file' => 'A fájl mérete az :attribute mezőben nem lehet nagyobb, mint :max kB.',
        'string' => 'Az :attribute mező nem lehet nagyobb :max karakternél.',
        'array' => 'Az :attribute mező nem lehet nagyobb, mint a :max elem.',
	],
    'mimes' => 'Az :attribute mezőnek a következő formátumú fájlnak kell lennie: :values.',
    'mimetypes' => 'Az :attribute mezőnek a következő formátumú fájlnak kell lennie: :values.',
	'min'                  => [
        'numeric' => 'Az :attribute mezőnek legalább :min értékűnek kell lennie.',
        'file' => 'A fájl méretének az :attribute mezőben legalább :min kB-nak kell lennie.',
        'string' => 'Az :attribute mezőnek legalább :min karaktert kell tartalmaznia.',
        'array' => 'Az :attribute mezőnek legalább :min elemet kell tartalmaznia.',
	],
    'not_in' => 'Az :attribute mezőben kiválasztott érték nem érvényes.',
    'numeric' => 'Az :attribute mezőnek számnak kell lennie.',
    'present' => 'Az :attribute mezőt meg kell adni.',
    'regex' => 'Érvénytelen :attribute mezőformátum.',
    'required' => 'Az :attribute mező nem maradhat üresen.',
    'required_if' => 'Az :attribute mező nem lehet üres, ha az :other mező értéke :value.',
    'required_unless' => 'Az :attribute mező csak akkor lehet üres, ha az :other a :values.',
    'required_with' => 'A :attribute mező nem lehet üres, ha az egyik :values-nek van értéke.',
    'required_with_all' => 'Az :attribute mező nem lehet üres, ha minden :values-nek van értéke.',
    'required_without' => 'Az :attribute mező nem maradhat üresen, ha a :values ​​egyike üres.',
    'required_without_all' => 'Az :attribute mező nem maradhat üresen, ha minden :values ​​üres.',
    'same' => 'Az :attribute és :other mezőknek meg kell egyeznie.',
	'size'                 => [
        'numeric' => 'Az :attribute mezőnek egyenlőnek kell lennie a :size értékkel.',
        'file' => 'A fájl méretének az :attribute mezőben :size kB-nak kell lennie.',
        'string' => 'Az :attribute mezőnek :size karaktereket kell tartalmaznia.',
        'array' => 'Az :attribute mezőnek tartalmaznia kell a :size elemet.',
	],
    'string' => 'Az :attribute mezőnek karakterláncnak kell lennie.',
    'timezone' => 'Az :attribute mezőnek érvényes időzónának kell lennie.',
    'unique' => 'A :attribute mező már létezik.',
    'uploaded' => 'Az :attribute mező feltöltése sikertelen.',
    'url' => 'Az :attribute mező nem egyezik az URL formátumával.',
    'lte' => 'Az :attribute mezőnek kisebbnek vagy egyenlőnek kell lennie, mint :value.',
	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
        'profile.birthday' => "Születési dátum",
        'name' => 'Név',
        'unit' => 'Unit',
        'position' => 'Pozíció',
        'date_report' => 'Jelentés dátuma',
        'quantity' => 'Mennyiség',
        'code' => 'Kód',
        'customer' => 'Ügyfél',
        'customer_id' => 'Ügyfél',
        'picture' => 'Kép',
        'sort' => 'Rendelés',
        'time' => 'Time',
        'priceAgent.price' => 'Ár ügynökök számára',
        'agent_id' => "Ügynök",
        'roles.0' => 'Engedélyek',
        'customer.name' => 'Ügyfél neve',
        'customer.phone' => 'Telefonszám',
	],

];
