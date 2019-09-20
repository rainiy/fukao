<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MeeduErrorResponseJsonException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // JSON下自定义数据返回格式
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new MeeduErrorResponseJsonException($validator->errors()->all()[0]);
        }
        parent::failedValidation($validator);
    }
}
