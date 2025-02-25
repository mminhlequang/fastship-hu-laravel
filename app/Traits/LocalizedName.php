<?php

namespace App\Traits;

trait LocalizedName
{
    /**
     * Lấy tên theo ngôn ngữ hiện tại.
     *
     * @return string
     */
    public function getName()
    {
        // Lấy ngôn ngữ hiện tại
        $locale = app()->getLocale();

        // Xây dựng tên thuộc tính tương ứng với ngôn ngữ
        $nameField = 'name_' . $locale;

        // Kiểm tra xem thuộc tính có tồn tại không
        if (property_exists($this, $nameField)) {
            return $this->{$nameField};
        }

        // Nếu không có tên cho ngôn ngữ này, trả về tên mặc định (có thể sửa theo ý muốn)
        return $this->name_vi; // Hoặc giá trị mặc định khác
    }
}
