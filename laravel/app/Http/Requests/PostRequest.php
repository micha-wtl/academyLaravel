<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 07.02.19
 * Time: 16:19
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required'
        ];
    }

}