<?php
/**
 * Setting company
 */

return [
    'company_key' => [
        [
            'key' => 'name',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'required',
                'input_attr' => ['class'=>'form-control input-sm','required' => 'required','id' => 'name'],
                'label_attr' => ['class' => 'col-md-3 control-label label-required']
            ]
        ],
        [
            'key' => 'logo',
            'type' => 'file',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'logo'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'birthday',
            'type' => 'date',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => '',
                'input_attr' => ['class'=>'form-control input-sm datepicker','id' => 'birthday'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'address',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => '',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'address'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'phone',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'phone'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'fax',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'fax'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'email',
            'type' => 'email',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'email|nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'email'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'website',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'website'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'facebook',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'facebook'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'google',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'google'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
        [
            'key' => 'twitter',
            'type' => 'text',//text,textarea,email,date,file,image,number
            'data' => [
                'validate' => 'nullable',
                'input_attr' => ['class'=>'form-control input-sm','id' => 'twitter'],
                'label_attr' => ['class' => 'col-md-3 control-label']
            ]
        ],
    ],
    'key_type' => [
        'type_infor' => ['address', 'phone', 'fax', 'email', 'website'],
        'type_follow' => ['facebook', 'google', 'twitter']
    ]
];

?>