<?php
namespace  App\Bundle\Common\Constants;

final class MessageConst
{
    public const REQUIRE_DATA = [
        'title' => 'require_data',
        'message' => '必須項目です'
    ];

    public const NO_RECORD = [
        'title' => 'no_record',
        'message' => 'データがなし',
    ];

    public const NOT_FOUND = [
        'title' => 'not_found',
        'message' => 'Not found!',
    ];

    public const EXISTING_EMAIL = [
        'title' => 'existing_email',
        'message' => 'Existing email!',
    ];

    public const EXISTING_PRODUCT_CODE = [
        'title' => 'existing_product_code',
        'message' => 'Mã sản phẩm đã tồn tại!',
    ];
}
