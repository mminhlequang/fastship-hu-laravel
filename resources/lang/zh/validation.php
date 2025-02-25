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
    'There was an error on row' => '第 :row 行出现错误',
    'accepted' => '必须接受 :attribute 字段。',
    'active_url' => ':attribute 字段不是有效的 URL。',
    'after' => ':attribute 字段必须晚于 :date 一天。',
    'after_or_equal' => ':attribute 字段必须是 :date 之后的时间。',
    'alpha' => ':attribute 字段只能包含字母。',
    'alpha_dash' => ':attribute 字段只能包含字母、数字和破折号。',
    'alpha_num' => ':attribute 字段只能包含字母和数字。',
    'array' => ':attribute 字段必须是一个数组。',
    'before' => ':attribute 字段必须早于 :date 一天。',
    'before_or_equal' => ':attribute 字段必须是在 :date 之前或等于 :date 的日期。',
	'between'              => [
        'numeric' => ':attribute 字段必须在 :min - :max 范围内。',
        'file' => ':attribute 字段中的文件大小必须介于 :min - :max kB 之间。',
        'string' => ':attribute 字段必须介于 :min - :max 个字符之间。',
        'array' => ':attribute 字段必须包含 :min - :max 个元素。',
	],
    'boolean' => ':attribute 字段必须为 true 或 false。',
    'confirmed' => ':attribute 字段中的确认值不匹配。',
    'date' => ':attribute 字段不是日期格式。',
    'date_format' => ':attribute 字段与 :format 不匹配。',
    'different' => ':attribute 和 :other 字段必须不同。',
    'digits' => ':attribute 字段的长度必须由 :digits 位数字组成。',
    'digits_between' => ':attribute 字段的长度必须介于 :min 和 :max 位数字之间。',
    'dimensions' => ':attribute 字段的尺寸无效。',
    'distinct' => ':attribute 字段具有重复值。',
    'email' => ':attribute 字段必须是不存在的电子邮件地址。',
    'exists' => ':attribute 字段中选定的值不存在。',
    'file' => ':attribute 字段必须是文件。',
    'filled' => ':attribute 字段不能为空。',
    'image' => ':attribute 字段必须是图像类型。',
    'in' => ':attribute 字段中选择的值无效。',
    'in_array' => ':attribute 字段必须在允许集合中：:other。',
    'integer' => ':attribute 字段必须是整数。',
    'ip' => ':attribute 字段必须是 IP 地址。',
    'ipv4' => ':attribute 字段必须是 IPv4 地址。',
    'ipv6' => ':attribute 字段必须是 IPv6 地址。',
    'json' => ':attribute 字段必须是 JSON 字符串。',
	'max'                  => [
        'numeric' => ':attribute 字段不能大于 :max。',
        'file' => ':attribute 字段中的文件大小不能大于 :max kB。',
        'string' => ':attribute 字段不能大于 :max 个字符。',
        'array' => ':attribute 字段不能大于 :max 元素。',
	],
    'mimes' => ':attribute 字段必须是具有以下格式的文件：:values。',
    'mimetypes' => ':attribute 字段必须是以下格式的文件：:values。',
	'min'                  => [
        'numeric' => ':attribute 字段必须至少为 :min。',
        'file' => ':attribute 字段中的文件大小必须至少为 :min kB。',
        'string' => ':attribute 字段必须至少有 :min 个字符。',
        'array' => ':attribute 字段必须至少包含 :min 个元素。',
	],
    'not_in' => ':attribute 字段中选择的值无效。',
    'numeric' => ':attribute 字段必须是数字。',
    'present' => '必须提供 :attribute 字段。',
    'regex' => '无效：属性字段格式。',
    'required' => ':attribute 字段不能留空。',
    'required_if' => '当 :other 字段为 :value 时 :attribute 字段不能为空。',
    'required_unless' => ':attribute 字段不能为空，除非 :other 是 :values。',
    'required_with' => '当 :values 之一有值时 :attribute 字段不能为空。',
    'required_with_all' => '当所有 :values 都有值时 :attribute 字段不能为空。',
    'required_without' => '当 :values 之一为空时，:attribute 字段不能留空。',
    'required_without_all' => '当:values全部为空时,:attribute字段不能为空。',
    'same' => ':attribute 和 :other 字段必须相同。',
	'size'                 => [
        'numeric' => ':attribute 字段必须等于 :size。',
        'file' => ':attribute 字段中的文件大小必须等于 :size kB。',
        'string' => ':attribute 字段必须包含 :size 个字符。',
        'array' => ':attribute 字段必须包含 :size 元素。',
	],
    'string' => ':attribute 字段必须是字符串。',
    'timezone' => ':attribute 字段必须是有效的时区。',
    'unique' => ':attribute 字段已存在。',
    'uploaded' => ':attribute 字段上传失败。',
    'url' => ':attribute 字段与 URL 格式不匹配。',
    'lte' => ':attribute 字段必须小于或等于 :value。',
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
        'profile.birthday' => "出生日期",
        'name' => '姓名',
        'unit' => '单位',
        'position' => '位置',
        'date_report' => '报告日期',
        'quantity' => '数量',
        'code' => '代码',
        '客户' => '客户',
        'customer_id' => '客户',
        'picture' => '图片',
        'sort' => '排序',
        '时间' => '时间',
        'priceAgent.price' => '代理商价格',
        'agent_id' => "代理人",
        'roles.0' => '权限',
        'customer.name' => '客户姓名',
        'customer.phone' => '电话号码',
	],

];
